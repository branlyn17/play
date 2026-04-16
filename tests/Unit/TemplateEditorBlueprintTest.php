<?php

namespace Tests\Unit;

use App\Models\Template;
use App\Support\Catalog\TemplateEditorBlueprint;
use Tests\TestCase;

class TemplateEditorBlueprintTest extends TestCase
{
    public function test_it_resolves_content_and_dictionary_for_the_requested_locale(): void
    {
        $template = new Template([
            'default_locale' => 'es',
            'editor_schema' => [
                'fields' => [
                    ['key' => 'headline', 'group' => 'content', 'translatable' => true],
                    ['key' => 'accentColor', 'group' => 'style', 'translatable' => false],
                ],
            ],
            'default_content' => [
                'shared' => [
                    'style' => [
                        'accentColor' => '#38bdf8',
                    ],
                ],
                'locales' => [
                    'es' => [
                        'content' => [
                            'headline' => 'Invitacion en espanol',
                        ],
                        'dictionary' => [
                            'labels' => [
                                'date' => 'Fecha',
                            ],
                        ],
                    ],
                    'en' => [
                        'content' => [
                            'headline' => 'Invitation in English',
                        ],
                        'dictionary' => [
                            'labels' => [
                                'date' => 'Date',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $resolved = TemplateEditorBlueprint::resolve($template, 'en');

        $this->assertSame('Invitation in English', $resolved['contentDefaults']['headline']);
        $this->assertSame('#38bdf8', $resolved['styleDefaults']['accentColor']);
        $this->assertSame('Date', $resolved['dictionary']['labels']['date']);
        $this->assertSame('en', $resolved['resolvedLocale']);
    }

    public function test_it_splits_translatable_content_and_style_fields(): void
    {
        $template = new Template([
            'editor_schema' => [
                'fields' => [
                    ['key' => 'headline', 'group' => 'content', 'translatable' => true],
                    ['key' => 'buttonLabel', 'group' => 'content', 'translatable' => true],
                    ['key' => 'accentColor', 'group' => 'style', 'translatable' => false],
                    ['key' => 'fontFamily', 'group' => 'style', 'translatable' => false],
                ],
            ],
        ]);

        $parts = TemplateEditorBlueprint::splitEditorState($template, [
            'headline' => 'Hello world',
            'buttonLabel' => 'Confirm',
            'accentColor' => '#111827',
            'fontFamily' => 'Sora',
        ]);

        $this->assertSame([
            'headline' => 'Hello world',
            'buttonLabel' => 'Confirm',
        ], $parts['content']);

        $this->assertSame([
            'accentColor' => '#111827',
            'fontFamily' => 'Sora',
        ], $parts['style']);
    }
}
