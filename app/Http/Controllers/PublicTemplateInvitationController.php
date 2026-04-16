<?php

namespace App\Http\Controllers;

use App\Support\Catalog\InvitationDraftService;
use App\Support\Catalog\PublicTemplateEditorData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicTemplateInvitationController extends Controller
{
    public function store(Request $request, string $slug): JsonResponse
    {
        $validated = $request->validate([
            'edit_token' => ['required', 'string'],
            'editor_state' => ['required', 'array'],
            'html_document' => ['required', 'string'],
            'downloaded' => ['nullable', 'boolean'],
        ]);

        $invitation = PublicTemplateEditorData::findInvitationByEditToken($validated['edit_token']);

        abort_unless($invitation && $invitation->template, 404);
        abort_unless($invitation->locale === app()->getLocale(), 404);

        $translation = $invitation->template->translations()
            ->where('locale', app()->getLocale())
            ->where('slug', $slug)
            ->first();

        abort_unless($translation, 404);

        $invitation = InvitationDraftService::persistEditorState(
            $invitation,
            $validated['editor_state'],
            $validated['html_document'],
            (bool) ($validated['downloaded'] ?? false),
        );

        return response()->json([
            'saved' => true,
            'edit_token' => $invitation->edit_token,
            'public_token' => $invitation->public_token,
            'download_count' => $invitation->download_count,
            'status' => $invitation->status,
        ]);
    }
}
