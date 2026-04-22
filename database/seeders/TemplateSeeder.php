<?php

namespace Database\Seeders;

use App\Models\InvitationCategory;
use App\Models\Template;
use App\Models\TemplateTranslation;
use App\Support\Templates\TemplateFieldCatalog;
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
                'defaults' => [
                    'style' => [
                        'accentColor' => '#38bdf8',
                        'backgroundColor' => '#eef6ff',
                        'surfaceColor' => '#ffffff',
                        'textColor' => '#0f172a',
                        'fontFamily' => 'Cormorant Garamond',
                    ],
                    'locales' => [
                        'es' => [
                            'content' => [
                                'eventLabel' => 'Boda elegante',
                                'headline' => 'Aura para una celebracion inolvidable',
                                'subheadline' => 'Personaliza una invitacion delicada, romantica y lista para compartir.',
                                'guestName' => 'Familia Perez',
                                'eventType' => 'Boda',
                                'eventName' => 'Boda de Valeria y Daniel',
                                'hosts' => 'Valeria y Daniel',
                                'dateLabel' => '2026-10-18',
                                'timeLabel' => '19:30',
                                'timezoneLabel' => 'America/La_Paz',
                                'venueLabel' => 'Jardines del Lago, Cochabamba',
                                'venueName' => 'Jardines del Lago',
                                'venueAddress' => 'Av. Costanera del Lago 245, Cochabamba, Bolivia',
                                'googleMapsUrl' => 'https://maps.google.com/?q=Jardines+del+Lago+Cochabamba+Bolivia',
                                'appleMapsUrl' => 'https://maps.apple.com/?q=Jardines+del+Lago+Cochabamba+Bolivia',
                                'mapButtonLabel' => 'Haz click aqui',
                                'dressCode' => 'Elegante formal',
                                'rsvpDeadline' => '2026-10-01',
                                'message' => 'Acompananos en una noche especial llena de musica, detalles suaves y una experiencia pensada para emocionar.',
                                'closing' => 'Confirma tu asistencia y guarda esta fecha para celebrar con nosotros.',
                                'buttonLabel' => 'Confirmar asistencia',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Anfitriones',
                                    'date' => 'Fecha',
                                    'time' => 'Hora',
                                    'venue' => 'Lugar',
                                ],
                            ],
                        ],
                        'en' => [
                            'content' => [
                                'eventLabel' => 'Elegant wedding',
                                'headline' => 'Aura for an unforgettable celebration',
                                'subheadline' => 'Customize a delicate, romantic invitation that is ready to share.',
                                'guestName' => 'Perez Family',
                                'eventType' => 'Wedding',
                                'eventName' => 'Valeria and Daniel Wedding',
                                'hosts' => 'Valeria and Daniel',
                                'dateLabel' => '2026-10-18',
                                'timeLabel' => '19:30',
                                'timezoneLabel' => 'America/La_Paz',
                                'venueLabel' => 'Lake Gardens, Cochabamba',
                                'venueName' => 'Lake Gardens',
                                'venueAddress' => 'Lake Coast Avenue 245, Cochabamba, Bolivia',
                                'googleMapsUrl' => 'https://maps.google.com/?q=Jardines+del+Lago+Cochabamba+Bolivia',
                                'appleMapsUrl' => 'https://maps.apple.com/?q=Jardines+del+Lago+Cochabamba+Bolivia',
                                'mapButtonLabel' => 'Click here',
                                'dressCode' => 'Formal elegant',
                                'rsvpDeadline' => '2026-10-01',
                                'message' => 'Join us for a special evening shaped by music, soft details and a refined atmosphere.',
                                'closing' => 'Confirm your attendance and save the date to celebrate with us.',
                                'buttonLabel' => 'Confirm attendance',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Hosts',
                                    'date' => 'Date',
                                    'time' => 'Time',
                                    'venue' => 'Venue',
                                ],
                            ],
                        ],
                    ],
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
                'defaults' => [
                    'style' => [
                        'accentColor' => '#6366f1',
                        'backgroundColor' => '#e0e7ff',
                        'surfaceColor' => '#ffffff',
                        'textColor' => '#1f2937',
                        'fontFamily' => 'Manrope',
                    ],
                    'locales' => [
                        'es' => [
                            'content' => [
                                'eventLabel' => 'XV de gala',
                                'headline' => 'Luna para una noche que todos recordaran',
                                'subheadline' => 'Un estilo brillante para celebrar tus quince con energia, color y personalidad.',
                                'hosts' => 'Familia Torres',
                                'dateLabel' => 'Sabado 12 de septiembre de 2026',
                                'timeLabel' => '08:00 PM',
                                'venueLabel' => 'Salon Aurora, Santa Cruz',
                                'message' => 'Te esperamos para vivir una velada llena de luces, baile y momentos inolvidables.',
                                'closing' => 'Reserva tu lugar y preparate para una noche espectacular.',
                                'buttonLabel' => 'Reservar lugar',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Anfitriones',
                                    'date' => 'Fecha',
                                    'time' => 'Hora',
                                    'venue' => 'Lugar',
                                ],
                            ],
                        ],
                        'en' => [
                            'content' => [
                                'eventLabel' => 'Sweet fifteen gala',
                                'headline' => 'Luna for a night everyone will remember',
                                'subheadline' => 'A glowing style to celebrate your sweet fifteen with energy, color and personality.',
                                'hosts' => 'Torres Family',
                                'dateLabel' => 'Saturday, September 12, 2026',
                                'timeLabel' => '08:00 PM',
                                'venueLabel' => 'Aurora Hall, Santa Cruz',
                                'message' => 'Join us for a night of lights, dancing and unforgettable moments.',
                                'closing' => 'Save your seat and get ready for a spectacular evening.',
                                'buttonLabel' => 'Reserve your seat',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Hosts',
                                    'date' => 'Date',
                                    'time' => 'Time',
                                    'venue' => 'Venue',
                                ],
                            ],
                        ],
                    ],
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
                'defaults' => [
                    'style' => [
                        'accentColor' => '#06b6d4',
                        'backgroundColor' => '#f0f9ff',
                        'surfaceColor' => '#ffffff',
                        'textColor' => '#0f172a',
                        'fontFamily' => 'Sora',
                    ],
                    'locales' => [
                        'es' => [
                            'content' => [
                                'eventLabel' => 'Cumpleanos',
                                'headline' => 'Brisa para celebrar a tu manera',
                                'subheadline' => 'Una invitacion ligera y alegre para reuniones familiares o fiestas llenas de color.',
                                'hosts' => 'Camila celebra',
                                'dateLabel' => 'Viernes 24 de julio de 2026',
                                'timeLabel' => '06:00 PM',
                                'venueLabel' => 'Terraza Central, La Paz',
                                'message' => 'Trae tu mejor energia y acompananos a compartir musica, comida y momentos inolvidables.',
                                'closing' => 'Confirma tu asistencia y celebra con nosotros.',
                                'buttonLabel' => 'Quiero asistir',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Organiza',
                                    'date' => 'Fecha',
                                    'time' => 'Hora',
                                    'venue' => 'Lugar',
                                ],
                            ],
                        ],
                        'en' => [
                            'content' => [
                                'eventLabel' => 'Birthday',
                                'headline' => 'Brisa to celebrate your way',
                                'subheadline' => 'A light and joyful invitation for family gatherings or color-filled parties.',
                                'hosts' => 'Camila celebrates',
                                'dateLabel' => 'Friday, July 24, 2026',
                                'timeLabel' => '06:00 PM',
                                'venueLabel' => 'Central Terrace, La Paz',
                                'message' => 'Bring your best energy and join us for music, food and unforgettable moments.',
                                'closing' => 'Confirm your attendance and celebrate with us.',
                                'buttonLabel' => 'I want to attend',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Hosted by',
                                    'date' => 'Date',
                                    'time' => 'Time',
                                    'venue' => 'Venue',
                                ],
                            ],
                        ],
                    ],
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
                'defaults' => [
                    'style' => [
                        'accentColor' => '#f43f5e',
                        'backgroundColor' => '#fff1f2',
                        'surfaceColor' => '#ffffff',
                        'textColor' => '#1f2937',
                        'fontFamily' => 'Manrope',
                    ],
                    'locales' => [
                        'es' => [
                            'content' => [
                                'eventLabel' => 'Baby shower',
                                'headline' => 'Nido para recibir una nueva historia',
                                'subheadline' => 'Una invitacion suave y moderna para compartir la alegria de una nueva llegada.',
                                'hosts' => 'Familia Mendoza',
                                'dateLabel' => 'Domingo 15 de noviembre de 2026',
                                'timeLabel' => '04:30 PM',
                                'venueLabel' => 'Casa Jardin, Sucre',
                                'message' => 'Celebremos juntos esta nueva etapa con detalles tiernos, juegos y una tarde especial.',
                                'closing' => 'Confirma tu asistencia y acompananos en este momento tan esperado.',
                                'buttonLabel' => 'Confirmar presencia',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Organiza',
                                    'date' => 'Fecha',
                                    'time' => 'Hora',
                                    'venue' => 'Lugar',
                                ],
                            ],
                        ],
                        'en' => [
                            'content' => [
                                'eventLabel' => 'Baby shower',
                                'headline' => 'Nest to welcome a new story',
                                'subheadline' => 'A soft, modern invitation to share the joy of a new arrival.',
                                'hosts' => 'Mendoza Family',
                                'dateLabel' => 'Sunday, November 15, 2026',
                                'timeLabel' => '04:30 PM',
                                'venueLabel' => 'Garden House, Sucre',
                                'message' => 'Let us celebrate this new chapter with gentle details, games and a special afternoon.',
                                'closing' => 'Confirm your attendance and join us for this long-awaited moment.',
                                'buttonLabel' => 'Confirm presence',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Hosted by',
                                    'date' => 'Date',
                                    'time' => 'Time',
                                    'venue' => 'Venue',
                                ],
                            ],
                        ],
                    ],
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
                'defaults' => [
                    'style' => [
                        'accentColor' => '#38bdf8',
                        'backgroundColor' => '#f0f9ff',
                        'surfaceColor' => '#ffffff',
                        'textColor' => '#0f172a',
                        'fontFamily' => 'Cormorant Garamond',
                    ],
                    'locales' => [
                        'es' => [
                            'content' => [
                                'eventLabel' => 'Bautizo',
                                'headline' => 'Cielo para una ceremonia serena',
                                'subheadline' => 'Un diseno luminoso y delicado para celebrar un momento intimo en familia.',
                                'hosts' => 'Familia Alvarez',
                                'dateLabel' => 'Domingo 06 de diciembre de 2026',
                                'timeLabel' => '11:00 AM',
                                'venueLabel' => 'Capilla San Rafael, Tarija',
                                'message' => 'Nos encantara compartir contigo una ceremonia especial y un almuerzo de celebracion.',
                                'closing' => 'Agradecemos tu presencia en este dia tan importante para nuestra familia.',
                                'buttonLabel' => 'Ver ubicacion',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Familia',
                                    'date' => 'Fecha',
                                    'time' => 'Hora',
                                    'venue' => 'Lugar',
                                ],
                            ],
                        ],
                        'en' => [
                            'content' => [
                                'eventLabel' => 'Baptism',
                                'headline' => 'Sky Grace for a serene ceremony',
                                'subheadline' => 'A bright and delicate design to celebrate an intimate family moment.',
                                'hosts' => 'Alvarez Family',
                                'dateLabel' => 'Sunday, December 6, 2026',
                                'timeLabel' => '11:00 AM',
                                'venueLabel' => 'San Rafael Chapel, Tarija',
                                'message' => 'We would love to share this special ceremony and a celebration lunch with you.',
                                'closing' => 'Thank you for joining us on this meaningful day for our family.',
                                'buttonLabel' => 'View location',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Family',
                                    'date' => 'Date',
                                    'time' => 'Time',
                                    'venue' => 'Venue',
                                ],
                            ],
                        ],
                    ],
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
                'defaults' => [
                    'style' => [
                        'accentColor' => '#334155',
                        'backgroundColor' => '#e2e8f0',
                        'surfaceColor' => '#ffffff',
                        'textColor' => '#0f172a',
                        'fontFamily' => 'Sora',
                    ],
                    'locales' => [
                        'es' => [
                            'content' => [
                                'eventLabel' => 'Evento corporativo',
                                'headline' => 'Pulse para experiencias de marca con impacto',
                                'subheadline' => 'Una invitacion moderna para lanzamientos, networking y eventos profesionales.',
                                'hosts' => 'Equipo Invita Plus',
                                'dateLabel' => 'Jueves 20 de agosto de 2026',
                                'timeLabel' => '07:00 PM',
                                'venueLabel' => 'Centro Empresarial Norte, Santa Cruz',
                                'message' => 'Acompananos a una noche de conexiones, contenido de valor y una experiencia disenada para impresionar.',
                                'closing' => 'Confirma tu asistencia para reservar tu acceso a esta experiencia.',
                                'buttonLabel' => 'Confirmar acceso',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Organiza',
                                    'date' => 'Fecha',
                                    'time' => 'Hora',
                                    'venue' => 'Lugar',
                                ],
                            ],
                        ],
                        'en' => [
                            'content' => [
                                'eventLabel' => 'Corporate event',
                                'headline' => 'Pulse for branded experiences with impact',
                                'subheadline' => 'A modern invitation for launches, networking and professional events.',
                                'hosts' => 'Invita Plus Team',
                                'dateLabel' => 'Thursday, August 20, 2026',
                                'timeLabel' => '07:00 PM',
                                'venueLabel' => 'North Business Center, Santa Cruz',
                                'message' => 'Join us for an evening of connections, valuable content and a carefully crafted branded experience.',
                                'closing' => 'Confirm your attendance to reserve your access to this experience.',
                                'buttonLabel' => 'Confirm access',
                            ],
                            'dictionary' => [
                                'labels' => [
                                    'hosts' => 'Hosted by',
                                    'date' => 'Date',
                                    'time' => 'Time',
                                    'venue' => 'Venue',
                                ],
                            ],
                        ],
                    ],
                ],
                'translations' => [
                    'es' => ['name' => 'Pulse', 'slug' => 'pulse', 'teaser' => 'Formato premium para experiencias corporativas y de marca.', 'description' => 'Pensada para lanzamientos, networking y eventos profesionales con un acabado moderno y de alto impacto.'],
                    'en' => ['name' => 'Pulse', 'slug' => 'pulse', 'teaser' => 'Premium format for corporate and branded experiences.', 'description' => 'Built for launches, networking and professional events with a modern, high-impact finish.'],
                ],
            ],
        ];

        foreach ($templates as $data) {
            $normalizedLocales = collect($data['defaults']['locales'])
                ->map(function (array $payload) {
                    $contentDefaults = collect(TemplateFieldCatalog::contentFields())
                        ->pluck('key')
                        ->mapWithKeys(fn (string $key) => [$key => ''])
                        ->all();

                    $payload['content'] = array_merge($contentDefaults, $payload['content'] ?? []);
                    $payload['media'] = array_replace_recursive(TemplateFieldCatalog::defaultMedia(), $payload['media'] ?? []);

                    return $payload;
                })
                ->all();

            $existingTemplate = Template::query()->where('code', $data['code'])->first();

            $template = Template::updateOrCreate(
                ['code' => $data['code']],
                [
                    'invitation_category_id' => $categories[$data['category_key']] ?? null,
                    'default_locale' => 'es',
                    'preview_image_path' => $existingTemplate?->preview_image_path,
                    'thumbnail_image_path' => $existingTemplate?->thumbnail_image_path,
                    'source_html_path' => "templates/{$data['code']}/index.html",
                    'source_css_path' => "templates/{$data['code']}/style.css",
                    'source_js_path' => null,
                    'editor_schema' => TemplateFieldCatalog::editorSchema(),
                    'default_content' => [
                        'shared' => [
                            'style' => $data['defaults']['style'],
                            'visibility' => TemplateFieldCatalog::defaultVisibility(),
                        ],
                        'locales' => $normalizedLocales,
                    ],
                    'design_tokens' => $data['design_tokens'],
                    'available_fonts' => TemplateFieldCatalog::availableFonts(),
                    'available_colors' => TemplateFieldCatalog::availableColorTokens(),
                    'is_active' => true,
                    'is_featured' => $data['is_featured'],
                    'is_premium' => $data['is_premium'],
                    'sort_order' => $data['sort_order'],
                    'view_count' => $existingTemplate?->view_count ?? 0,
                    'download_count' => $existingTemplate?->download_count ?? 0,
                    'use_count' => $existingTemplate?->use_count ?? 0,
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
