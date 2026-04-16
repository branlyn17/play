<?php

namespace Database\Seeders;

use App\Models\InvitationCategory;
use App\Models\Template;
use App\Models\TemplateTranslation;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $categories = InvitationCategory::query()->pluck('id', 'key');

        $templates = [
            [
                'code' => 'aura',
                'category_key' => 'wedding',
                'is_featured' => true,
                'is_premium' => true,
                'sort_order' => 1,
                'view_count' => 1420,
                'download_count' => 312,
                'use_count' => 198,
                'design_tokens' => [
                    'accent' => 'sky',
                    'catalog_background' => 'linear-gradient(160deg, rgba(255,255,255,0.15), rgba(255,255,255,0.03)), radial-gradient(circle at top left, rgba(191,219,254,0.55), transparent 32%), radial-gradient(circle at bottom right, rgba(129,140,248,0.28), transparent 30%), linear-gradient(135deg, #eff6ff, #dbeafe, #c7d2fe)',
                ],
                'translations' => [
                    'es' => ['name' => 'Aura', 'slug' => 'aura', 'teaser' => 'Elegancia suave en azul claro.', 'description' => 'Una plantilla refinada para bodas con tipografia limpia, secciones romanticas y una presencia luminosa.'],
                    'en' => ['name' => 'Aura', 'slug' => 'aura', 'teaser' => 'Soft elegance in light blue.', 'description' => 'A refined wedding template with clean typography, romantic sections and a luminous presence.'],
                ],
            ],
            [
                'code' => 'luna',
                'category_key' => 'quince',
                'is_featured' => true,
                'is_premium' => true,
                'sort_order' => 2,
                'view_count' => 1288,
                'download_count' => 276,
                'use_count' => 164,
                'design_tokens' => [
                    'accent' => 'indigo',
                    'catalog_background' => 'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.24), transparent 28%), radial-gradient(circle at bottom left, rgba(244,114,182,0.18), transparent 24%), linear-gradient(135deg, #1e1b4b, #172554, #0f172a)',
                ],
                'translations' => [
                    'es' => ['name' => 'Luna', 'slug' => 'luna', 'teaser' => 'Brillo nocturno para unos XV inolvidables.', 'description' => 'Escena premium con contraste fuerte, ideal para celebraciones glamorosas y llenas de energia.'],
                    'en' => ['name' => 'Luna', 'slug' => 'luna', 'teaser' => 'Night glow for unforgettable sweet fifteen events.', 'description' => 'Premium scene with bold contrast, ideal for glamorous and high-energy celebrations.'],
                ],
            ],
            [
                'code' => 'brisa',
                'category_key' => 'birthday',
                'is_featured' => false,
                'is_premium' => false,
                'sort_order' => 3,
                'view_count' => 864,
                'download_count' => 154,
                'use_count' => 122,
                'design_tokens' => [
                    'accent' => 'cyan',
                    'catalog_background' => 'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)), radial-gradient(circle at top left, rgba(224,242,254,0.8), transparent 34%), radial-gradient(circle at bottom right, rgba(147,197,253,0.26), transparent 26%), linear-gradient(135deg, #ffffff, #eff6ff, #dbeafe)',
                ],
                'translations' => [
                    'es' => ['name' => 'Brisa', 'slug' => 'brisa', 'teaser' => 'Frescura visual para celebrar a cualquier edad.', 'description' => 'Una plantilla ligera, alegre y muy adaptable para fiestas familiares, juveniles o tematicas.'],
                    'en' => ['name' => 'Brisa', 'slug' => 'brisa', 'teaser' => 'A fresh visual tone for celebrations at any age.', 'description' => 'A light, joyful and highly adaptable template for family, youth or themed birthday parties.'],
                ],
            ],
            [
                'code' => 'nido',
                'category_key' => 'baby-shower',
                'is_featured' => false,
                'is_premium' => false,
                'sort_order' => 4,
                'view_count' => 742,
                'download_count' => 121,
                'use_count' => 97,
                'design_tokens' => [
                    'accent' => 'rose',
                    'catalog_background' => 'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.02)), radial-gradient(circle at top left, rgba(251,207,232,0.55), transparent 32%), radial-gradient(circle at bottom right, rgba(191,219,254,0.26), transparent 28%), linear-gradient(135deg, #fff1f2, #fdf2f8, #eff6ff)',
                ],
                'translations' => [
                    'es' => ['name' => 'Nido', 'slug' => 'nido', 'teaser' => 'Dulzura moderna para un baby shower memorable.', 'description' => 'Colores amables, composicion limpia y bloques flexibles para celebrar una nueva llegada con estilo.'],
                    'en' => ['name' => 'Nest', 'slug' => 'nest', 'teaser' => 'Modern sweetness for a memorable baby shower.', 'description' => 'Gentle colors, clean composition and flexible content blocks to celebrate a new arrival with style.'],
                ],
            ],
            [
                'code' => 'cielo',
                'category_key' => 'baptism',
                'is_featured' => false,
                'is_premium' => true,
                'sort_order' => 5,
                'view_count' => 653,
                'download_count' => 118,
                'use_count' => 84,
                'design_tokens' => [
                    'accent' => 'sky',
                    'catalog_background' => 'linear-gradient(160deg, rgba(255,255,255,0.12), rgba(255,255,255,0.03)), radial-gradient(circle at top, rgba(186,230,253,0.5), transparent 30%), radial-gradient(circle at bottom right, rgba(125,211,252,0.22), transparent 24%), linear-gradient(135deg, #f0f9ff, #dbeafe, #e0e7ff)',
                ],
                'translations' => [
                    'es' => ['name' => 'Cielo', 'slug' => 'cielo', 'teaser' => 'Un tono sereno para bautizos y encuentros familiares.', 'description' => 'Plantilla delicada y luminosa pensada para ceremonias intimas, con un lenguaje visual sobrio y elegante.'],
                    'en' => ['name' => 'Sky Grace', 'slug' => 'sky-grace', 'teaser' => 'A serene tone for baptisms and family gatherings.', 'description' => 'Delicate and bright template built for intimate ceremonies, with a sober and elegant visual language.'],
                ],
            ],
            [
                'code' => 'pulse',
                'category_key' => 'corporate',
                'is_featured' => true,
                'is_premium' => true,
                'sort_order' => 6,
                'view_count' => 1112,
                'download_count' => 231,
                'use_count' => 176,
                'design_tokens' => [
                    'accent' => 'slate',
                    'catalog_background' => 'linear-gradient(160deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02)), radial-gradient(circle at top right, rgba(96,165,250,0.18), transparent 28%), radial-gradient(circle at bottom left, rgba(59,130,246,0.16), transparent 22%), linear-gradient(135deg, #020617, #0f172a, #1e293b)',
                ],
                'translations' => [
                    'es' => ['name' => 'Pulse', 'slug' => 'pulse', 'teaser' => 'Formato premium para experiencias corporativas y de marca.', 'description' => 'Pensada para lanzamientos, networking y eventos profesionales con un acabado moderno y de alto impacto.'],
                    'en' => ['name' => 'Pulse', 'slug' => 'pulse', 'teaser' => 'Premium format for corporate and branded experiences.', 'description' => 'Built for launches, networking and professional events with a modern, high-impact finish.'],
                ],
            ],
        ];

        foreach ($templates as $data) {
            $template = Template::updateOrCreate(
                ['code' => $data['code']],
                [
                    'invitation_category_id' => $categories[$data['category_key']] ?? null,
                    'default_locale' => 'es',
                    'preview_image_path' => null,
                    'thumbnail_image_path' => null,
                    'source_html_path' => "templates/{$data['code']}/index.html",
                    'source_css_path' => "templates/{$data['code']}/style.css",
                    'source_js_path' => null,
                    'editor_schema' => [
                        'sections' => ['hero', 'event', 'countdown', 'gallery', 'rsvp'],
                        'supports' => ['colors', 'fonts', 'spacing', 'backgrounds'],
                    ],
                    'default_content' => [
                        'sections' => ['hero', 'event', 'countdown', 'gallery', 'rsvp'],
                    ],
                    'design_tokens' => $data['design_tokens'],
                    'available_fonts' => ['Sora', 'Manrope', 'Cormorant Garamond'],
                    'available_colors' => ['sky', 'indigo', 'rose', 'emerald', 'slate'],
                    'is_active' => true,
                    'is_featured' => $data['is_featured'],
                    'is_premium' => $data['is_premium'],
                    'sort_order' => $data['sort_order'],
                    'view_count' => $data['view_count'],
                    'download_count' => $data['download_count'],
                    'use_count' => $data['use_count'],
                    'published_at' => now(),
                ],
            );

            foreach ($data['translations'] as $locale => $translation) {
                TemplateTranslation::updateOrCreate(
                    [
                        'template_id' => $template->id,
                        'locale' => $locale,
                    ],
                    $translation,
                );
            }
        }
    }
}
