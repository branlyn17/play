<?php

namespace Database\Seeders;

use App\Models\InvitationCategory;
use App\Models\InvitationCategoryTranslation;
use Illuminate\Database\Seeder;

class InvitationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'key' => 'wedding',
                'sort_order' => 1,
                'translations' => [
                    'es' => ['name' => 'Bodas', 'slug' => 'bodas', 'description' => 'Plantillas romanticas y elegantes para ceremonias y recepciones.'],
                    'en' => ['name' => 'Weddings', 'slug' => 'weddings', 'description' => 'Romantic and elegant templates for ceremonies and receptions.'],
                ],
            ],
            [
                'key' => 'quince',
                'sort_order' => 2,
                'translations' => [
                    'es' => ['name' => 'Quinceaneras', 'slug' => 'quinceaneras', 'description' => 'Disenos con brillo, gala y personalidad para tus XV.'],
                    'en' => ['name' => 'Sweet 15', 'slug' => 'sweet-15', 'description' => 'Shiny, gala-inspired designs with personality for sweet fifteen celebrations.'],
                ],
            ],
            [
                'key' => 'birthday',
                'sort_order' => 3,
                'translations' => [
                    'es' => ['name' => 'Cumpleanos', 'slug' => 'cumpleanos', 'description' => 'Invitaciones frescas para fiestas intimas o grandes celebraciones.'],
                    'en' => ['name' => 'Birthdays', 'slug' => 'birthdays', 'description' => 'Fresh invitations for intimate parties or big birthday celebrations.'],
                ],
            ],
            [
                'key' => 'baby-shower',
                'sort_order' => 4,
                'translations' => [
                    'es' => ['name' => 'Baby Shower', 'slug' => 'baby-shower', 'description' => 'Colecciones tiernas y modernas para celebrar nuevas llegadas.'],
                    'en' => ['name' => 'Baby Shower', 'slug' => 'baby-shower', 'description' => 'Gentle and modern collections to celebrate new arrivals.'],
                ],
            ],
            [
                'key' => 'baptism',
                'sort_order' => 5,
                'translations' => [
                    'es' => ['name' => 'Bautizos', 'slug' => 'bautizos', 'description' => 'Estilos delicados para ceremonias familiares y momentos especiales.'],
                    'en' => ['name' => 'Baptisms', 'slug' => 'baptisms', 'description' => 'Delicate styles for family ceremonies and special moments.'],
                ],
            ],
            [
                'key' => 'corporate',
                'sort_order' => 6,
                'translations' => [
                    'es' => ['name' => 'Corporativos', 'slug' => 'corporativos', 'description' => 'Propuestas limpias para lanzamientos, eventos y experiencias de marca.'],
                    'en' => ['name' => 'Corporate', 'slug' => 'corporate', 'description' => 'Clean proposals for launches, events and branded experiences.'],
                ],
            ],
        ];

        foreach ($categories as $data) {
            $category = InvitationCategory::updateOrCreate(
                ['key' => $data['key']],
                [
                    'sort_order' => $data['sort_order'],
                    'is_active' => true,
                ],
            );

            foreach ($data['translations'] as $locale => $translation) {
                InvitationCategoryTranslation::updateOrCreate(
                    [
                        'invitation_category_id' => $category->id,
                        'locale' => $locale,
                    ],
                    $translation,
                );
            }
        }
    }
}
