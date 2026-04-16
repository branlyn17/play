<?php

namespace App\Http\Controllers;

use App\Support\Catalog\InvitationDraftService;
use App\Support\Catalog\PublicTemplateEditorData;
use App\Support\Localization\PublicPage;
use App\Support\Localization\PublicViewData;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicTemplateEditorController extends Controller
{
    public function __invoke(Request $request, string $slug): View|RedirectResponse
    {
        $locale = app()->getLocale();
        $editToken = $request->query('edit');

        if ($editToken) {
            $invitation = PublicTemplateEditorData::findInvitationByEditToken($editToken);

            abort_unless($invitation && $invitation->template, 404);

            $invitationLocale = $invitation->locale ?: $locale;
            $canonicalSlug = $invitation->template->translations->firstWhere('locale', $invitationLocale)?->slug;

            if ($invitationLocale !== $locale || ($canonicalSlug && $canonicalSlug !== $slug)) {
                return redirect()->route(PublicPage::routeName('catalog.show', $invitationLocale), [
                    'slug' => $canonicalSlug,
                    'edit' => $invitation->edit_token,
                ]);
            }

            $editorData = PublicTemplateEditorData::present($invitation->template, $locale, $invitation);
        } else {
            $template = PublicTemplateEditorData::findTemplateBySlug($locale, $slug);

            abort_unless($template, 404);

            $invitation = InvitationDraftService::createFromTemplate($template, $locale);
            $localizedSlug = $template->translations->firstWhere('locale', $locale)?->slug ?? $slug;

            return redirect()->route(PublicPage::routeName('catalog.show', $locale), [
                'slug' => $localizedSlug,
                'edit' => $invitation->edit_token,
            ]);
        }

        abort_unless($editorData, 404);

        $viewData = PublicViewData::make('template_editor', [
            'locales' => $editorData['locales'],
            'navigation' => PublicPage::navigation($locale, 'catalog'),
            'template' => $editorData['template'],
            'invitation' => [
                'editToken' => $invitation->edit_token,
                'publicToken' => $invitation->public_token,
            ],
            'saveUrl' => route(PublicPage::routeName('catalog.save', $locale), ['slug' => $slug]),
        ]);

        $viewData['title'] = $editorData['template']['name'].' | '.config('app.name');
        $viewData['metaDescription'] = $editorData['template']['teaser'] ?: trans('public.template_editor.meta_description');

        return view('public.template-editor', $viewData);
    }
}
