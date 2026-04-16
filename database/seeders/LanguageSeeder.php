<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'code' => 'es',
                'label' => 'ES',
                'name' => 'Spanish',
                'native_name' => 'Espanol',
                'flag' => "\u{1F1EA}\u{1F1F8}",
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'en',
                'label' => 'EN',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => "\u{1F1FA}\u{1F1F8}",
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language,
            );
        }
    }
}
