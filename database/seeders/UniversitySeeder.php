<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        $universities = [
            [
                'name_en' => 'Cairo University',
                'name_ar' => 'جامعة القاهرة',
                'slug' => 'cairo-university',
                'city' => 'Giza',
                'established_year' => 1908,
                'global_ranking' => 551,
            ],
            [
                'name_en' => 'Ain Shams University',
                'name_ar' => 'جامعة عين شمس',
                'slug' => 'ain-shams-university',
                'city' => 'Cairo',
                'established_year' => 1950,
                'global_ranking' => 801,
            ],
            [
                'name_en' => 'Alexandria University',
                'name_ar' => 'جامعة الإسكندرية',
                'slug' => 'alexandria-university',
                'city' => 'Alexandria',
                'established_year' => 1938,
                'global_ranking' => 701,
            ],
            [
                'name_en' => 'Mansoura University',
                'name_ar' => 'جامعة المنصورة',
                'slug' => 'mansoura-university',
                'city' => 'Mansoura',
                'established_year' => 1972,
                'global_ranking' => 901,
            ],
            [
                'name_en' => 'Helwan University',
                'name_ar' => 'جامعة حلوان',
                'slug' => 'helwan-university',
                'city' => 'Helwan',
                'established_year' => 1975,
                'global_ranking' => 1001,
            ],
            [
                'name_en' => 'Zagazig University',
                'name_ar' => 'جامعة الزقازيق',
                'slug' => 'zagazig-university',
                'city' => 'Zagazig',
                'established_year' => 1974,
                'global_ranking' => 1201,
            ],
        ];

        foreach ($universities as $data) {
            University::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    ...$data,
                    'description_en' => $data['name_en'].' is an accredited Egyptian public university offering internationally recognised degrees.',
                    'description_ar' => $data['name_ar'].' جامعة مصرية حكومية معتمدة تقدم شهادات معترف بها دولياً.',
                    'is_accredited' => true,
                    'is_active' => true,
                ]
            );
        }
    }
}
