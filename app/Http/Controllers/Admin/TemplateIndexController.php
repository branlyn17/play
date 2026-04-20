<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TemplateIndexController extends Controller
{
    public function __invoke(Request $request): View
    {
        $locale = app()->getLocale();
        $search = trim((string) $request->string('search'));

        $templates = Template::query()
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'category.translations' => fn ($query) => $query->where('locale', $locale),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('code', 'like', "%{$search}%")
                        ->orWhereHas('translations', function ($translationQuery) use ($search) {
                            $translationQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%")
                                ->orWhere('teaser', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.templates.index', [
            'templates' => $templates,
            'search' => $search,
            'title' => trans('admin.templates.title').' | Invita Plus',
        ]);
    }
}
