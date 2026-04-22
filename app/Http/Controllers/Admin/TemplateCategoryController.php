<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvitationCategory;
use App\Models\InvitationCategoryTranslation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TemplateCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        $search = trim((string) $request->string('search'));

        $categories = InvitationCategory::query()
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
            ])
            ->withCount('templates')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('key', 'like', "%{$search}%")
                        ->orWhereHas('translations', function ($translationQuery) use ($search) {
                            $translationQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%")
                                ->orWhere('description', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.template-categories.index', [
            'categories' => $categories,
            'search' => $search,
            'title' => trans('admin.template_categories.title').' | Invita Plus',
        ]);
    }

    public function create(): View
    {
        return view('admin.template-categories.create', [
            'category' => new InvitationCategory([
                'is_active' => true,
                'sort_order' => ((int) InvitationCategory::query()->max('sort_order')) + 1,
            ]),
            'translations' => $this->blankTranslations(),
            'locales' => config('locales.supported', []),
            'title' => trans('admin.template_categories.create.title').' | Invita Plus',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        $category = DB::transaction(function () use ($validated) {
            $category = InvitationCategory::create([
                'key' => $validated['key'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => (bool) ($validated['is_active'] ?? false),
            ]);

            $this->syncTranslations($category, $validated['translations']);

            return $category;
        });

        return redirect()
            ->route('admin.template-categories.index', $this->routeQuery())
            ->with('status', trans('admin.template_categories.flash.created', ['name' => $category->key]));
    }

    public function edit(InvitationCategory $templateCategory): View
    {
        $templateCategory->load('translations');

        return view('admin.template-categories.edit', [
            'category' => $templateCategory,
            'translations' => $this->translationsForForm($templateCategory),
            'locales' => config('locales.supported', []),
            'title' => trans('admin.template_categories.edit.title').' | Invita Plus',
        ]);
    }

    public function update(Request $request, InvitationCategory $templateCategory): RedirectResponse
    {
        $validated = $this->validatedData($request, $templateCategory);

        DB::transaction(function () use ($templateCategory, $validated) {
            $templateCategory->update([
                'key' => $validated['key'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => (bool) ($validated['is_active'] ?? false),
            ]);

            $this->syncTranslations($templateCategory, $validated['translations']);
        });

        return redirect()
            ->route('admin.template-categories.index', $this->routeQuery())
            ->with('status', trans('admin.template_categories.flash.updated', ['name' => $templateCategory->key]));
    }

    public function destroy(InvitationCategory $templateCategory): RedirectResponse
    {
        $name = $templateCategory->key;

        $templateCategory->delete();

        return redirect()
            ->route('admin.template-categories.index', $this->routeQuery())
            ->with('status', trans('admin.template_categories.flash.deleted', ['name' => $name]));
    }

    private function validatedData(Request $request, ?InvitationCategory $category = null): array
    {
        $input = $this->normalizedInput($request);
        $locales = array_keys(config('locales.supported', []));
        $rules = [
            'key' => [
                'required',
                'alpha_dash',
                'max:80',
                Rule::unique('invitation_categories', 'key')->ignore($category?->id),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
        ];

        foreach ($locales as $locale) {
            $translationId = $category
                ? $category->translations()->where('locale', $locale)->value('id')
                : null;

            $rules["translations.{$locale}"] = ['required', 'array'];
            $rules["translations.{$locale}.name"] = ['required', 'string', 'max:120'];
            $rules["translations.{$locale}.slug"] = [
                'required',
                'alpha_dash',
                'max:160',
                Rule::unique('invitation_category_translations', 'slug')
                    ->where(fn ($query) => $query->where('locale', $locale))
                    ->ignore($translationId),
            ];
            $rules["translations.{$locale}.description"] = ['nullable', 'string', 'max:1000'];
            $rules["translations.{$locale}.seo_title"] = ['nullable', 'string', 'max:255'];
            $rules["translations.{$locale}.seo_description"] = ['nullable', 'string', 'max:255'];
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function normalizedInput(Request $request): array
    {
        $input = $request->all();

        if (isset($input['key'])) {
            $input['key'] = Str::slug((string) $input['key']);
        }

        foreach (array_keys(config('locales.supported', [])) as $locale) {
            $name = $input['translations'][$locale]['name'] ?? '';
            $slug = $input['translations'][$locale]['slug'] ?? '';

            if ($slug === '' && $name !== '') {
                $input['translations'][$locale]['slug'] = Str::slug($name);
            } elseif ($slug !== '') {
                $input['translations'][$locale]['slug'] = Str::slug($slug);
            }
        }

        return $input;
    }

    private function syncTranslations(InvitationCategory $category, array $translations): void
    {
        foreach (array_keys(config('locales.supported', [])) as $locale) {
            $payload = $translations[$locale];

            InvitationCategoryTranslation::updateOrCreate(
                [
                    'invitation_category_id' => $category->id,
                    'locale' => $locale,
                ],
                [
                    'name' => $payload['name'],
                    'slug' => $payload['slug'],
                    'description' => $payload['description'] ?? null,
                    'seo_title' => $payload['seo_title'] ?? null,
                    'seo_description' => $payload['seo_description'] ?? null,
                ],
            );
        }
    }

    private function translationsForForm(InvitationCategory $category): array
    {
        $translations = $this->blankTranslations();

        foreach ($category->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'slug' => $translation->slug,
                'description' => $translation->description,
                'seo_title' => $translation->seo_title,
                'seo_description' => $translation->seo_description,
            ];
        }

        return $translations;
    }

    private function blankTranslations(): array
    {
        return collect(config('locales.supported', []))
            ->mapWithKeys(fn ($meta, string $locale) => [
                $locale => [
                    'name' => '',
                    'slug' => '',
                    'description' => '',
                    'seo_title' => '',
                    'seo_description' => '',
                ],
            ])
            ->all();
    }

    private function routeQuery(): array
    {
        return request()->has('lang') ? ['lang' => request()->query('lang')] : [];
    }
}
