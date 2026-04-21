<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvitationCategory;
use App\Models\Template;
use App\Models\TemplateTranslation;
use App\Support\Templates\TemplateFieldCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TemplateCreateController extends Controller
{
    public function create(): View
    {
        return view('admin.templates.create', [
            'title' => trans('admin.templates.create.title').' | Invita Plus',
            'locales' => config('locales.supported', []),
            'categories' => InvitationCategory::query()
                ->with(['translations' => fn ($query) => $query->where('locale', app()->getLocale())])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'colorTokens' => TemplateFieldCatalog::availableColorTokens(),
            'requiredPlaceholders' => TemplateFieldCatalog::requiredPlaceholders(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $locales = array_keys(config('locales.supported', []));

        $rules = [
            'code' => ['required', 'alpha_dash', 'max:80', 'unique:templates,code'],
            'category_key' => ['required', 'exists:invitation_categories,key'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'catalog_accent' => ['required', Rule::in(TemplateFieldCatalog::availableColorTokens())],
            'catalog_background' => ['required', 'string'],
            'source_html' => ['required', 'file'],
            'source_payload' => ['required', 'file'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_premium' => ['nullable', 'boolean'],
        ];

        $validated = $request->validate($rules);
        $extension = Str::lower((string) $request->file('source_html')->getClientOriginalExtension());
        $payloadExtension = Str::lower((string) $request->file('source_payload')->getClientOriginalExtension());

        if (! in_array($extension, ['html', 'htm'], true)) {
            throw ValidationException::withMessages([
                'source_html' => trans('admin.templates.create.validation.extension'),
            ]);
        }

        if ($payloadExtension !== 'json') {
            throw ValidationException::withMessages([
                'source_payload' => trans('admin.templates.create.validation.json_extension'),
            ]);
        }

        $uploadedHtml = $request->file('source_html')->get();
        $missingPlaceholders = collect(TemplateFieldCatalog::requiredPlaceholders())
            ->reject(fn (string $placeholder) => Str::contains($uploadedHtml, $placeholder))
            ->values()
            ->all();

        if ($missingPlaceholders !== []) {
            throw ValidationException::withMessages([
                'source_html' => trans('admin.templates.create.validation.placeholders', [
                    'placeholders' => implode(', ', $missingPlaceholders),
                ]),
            ]);
        }

        $payload = $this->validatedPayload($request, $locales);

        $storedHtmlPath = $request->file('source_html')->storeAs(
            'templates/'.$validated['code'],
            'index.html',
        );

        $request->file('source_payload')->storeAs(
            'templates/'.$validated['code'],
            'template.json',
        );

        $template = DB::transaction(function () use ($validated, $payload, $locales, $storedHtmlPath) {
            $template = Template::create([
                'invitation_category_id' => InvitationCategory::query()->where('key', $validated['category_key'])->value('id'),
                'code' => $validated['code'],
                'default_locale' => config('locales.default', 'es'),
                'preview_image_path' => null,
                'thumbnail_image_path' => null,
                'source_html_path' => $storedHtmlPath,
                'source_css_path' => null,
                'source_js_path' => null,
                'editor_schema' => TemplateFieldCatalog::editorSchema(),
                'default_content' => [
                    'shared' => [
                        'style' => $payload['style'],
                    ],
                    'locales' => collect($locales)->mapWithKeys(function (string $locale) use ($payload) {
                        return [
                            $locale => [
                                'content' => $payload['locales'][$locale]['content'],
                                'dictionary' => [
                                    'labels' => $payload['locales'][$locale]['dictionary'],
                                ],
                            ],
                        ];
                    })->all(),
                ],
                'design_tokens' => [
                    'accent' => $validated['catalog_accent'],
                    'catalog_background' => $validated['catalog_background'],
                ],
                'available_fonts' => TemplateFieldCatalog::availableFonts(),
                'available_colors' => TemplateFieldCatalog::availableColorTokens(),
                'is_active' => (bool) ($validated['is_active'] ?? false),
                'is_featured' => (bool) ($validated['is_featured'] ?? false),
                'is_premium' => (bool) ($validated['is_premium'] ?? false),
                'sort_order' => $validated['sort_order'] ?? ((int) Template::query()->max('sort_order') + 1),
                'view_count' => 0,
                'download_count' => 0,
                'use_count' => 0,
                'published_at' => ($validated['is_active'] ?? false) ? now() : null,
            ]);

            foreach ($locales as $locale) {
                TemplateTranslation::create([
                    'template_id' => $template->id,
                    'locale' => $locale,
                    'name' => $payload['locales'][$locale]['catalog']['name'],
                    'slug' => $payload['locales'][$locale]['catalog']['slug'],
                    'teaser' => $payload['locales'][$locale]['catalog']['teaser'],
                    'description' => $payload['locales'][$locale]['catalog']['description'],
                    'seo_title' => $payload['locales'][$locale]['catalog']['seo_title'] ?? null,
                    'seo_description' => $payload['locales'][$locale]['catalog']['seo_description'] ?? null,
                ]);
            }

            return $template;
        });

        return redirect()
            ->route('admin.templates.index', request()->has('lang') ? ['lang' => request()->query('lang')] : [])
            ->with('status', trans('admin.templates.create.flash.created', ['name' => $template->code]));
    }

    private function validatedPayload(Request $request, array $locales): array
    {
        $payload = json_decode($request->file('source_payload')->get(), true);

        if (! is_array($payload)) {
            throw ValidationException::withMessages([
                'source_payload' => trans('admin.templates.create.validation.json_invalid'),
            ]);
        }

        $unsupportedLocales = array_diff(array_keys($payload['locales'] ?? []), $locales);

        if ($unsupportedLocales !== []) {
            throw ValidationException::withMessages([
                'source_payload' => trans('admin.templates.create.validation.unsupported_locales', [
                    'locales' => implode(', ', $unsupportedLocales),
                ]),
            ]);
        }

        $rules = [
            'version' => ['required', 'integer', 'min:1'],
            'style.accentColor' => ['required', 'string', 'max:32'],
            'style.backgroundColor' => ['required', 'string', 'max:32'],
            'style.surfaceColor' => ['required', 'string', 'max:32'],
            'style.textColor' => ['required', 'string', 'max:32'],
            'style.fontFamily' => ['required', Rule::in(TemplateFieldCatalog::availableFonts())],
            'locales' => ['required', 'array'],
        ];

        foreach ($locales as $locale) {
            $rules["locales.{$locale}"] = ['required', 'array'];
            $rules["locales.{$locale}.catalog.name"] = ['required', 'string', 'max:120'];
            $rules["locales.{$locale}.catalog.slug"] = [
                'required',
                'alpha_dash',
                'max:160',
                Rule::unique('template_translations', 'slug')->where(fn ($query) => $query->where('locale', $locale)),
            ];
            $rules["locales.{$locale}.catalog.teaser"] = ['required', 'string', 'max:255'];
            $rules["locales.{$locale}.catalog.description"] = ['required', 'string'];
            $rules["locales.{$locale}.catalog.seo_title"] = ['nullable', 'string', 'max:255'];
            $rules["locales.{$locale}.catalog.seo_description"] = ['nullable', 'string', 'max:255'];

            foreach (TemplateFieldCatalog::contentFields() as $field) {
                $rules["locales.{$locale}.content.{$field['key']}"] = ['required', 'string'];
            }

            foreach (TemplateFieldCatalog::dictionaryFields() as $field) {
                $rules["locales.{$locale}.dictionary.{$field['key']}"] = ['required', 'string', 'max:120'];
            }
        }

        return Validator::make($payload, $rules)->validate();
    }
}
