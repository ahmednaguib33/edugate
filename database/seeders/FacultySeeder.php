<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->faculties() as $data) {
            Faculty::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name_en' => $data['name_en'],
                    'name_ar' => $data['name_ar'],
                    'icon' => $data['icon'],
                    'description_en' => 'Faculty of '.$data['name_en'].' at accredited Egyptian universities.',
                    'description_ar' => 'كلية '.$data['name_ar'].' في الجامعات المصرية المعتمدة.',
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * @return array<int, array{name_en: string, name_ar: string, slug: string, icon: string}>
     */
    private function faculties(): array
    {
        return [
            ['name_en' => 'Medicine', 'name_ar' => 'الطب البشري', 'slug' => 'medicine', 'icon' => 'stethoscope'],
            ['name_en' => 'Dentistry', 'name_ar' => 'طب الفم والأسنان', 'slug' => 'dentistry', 'icon' => 'tooth'],
            ['name_en' => 'Engineering', 'name_ar' => 'الهندسة', 'slug' => 'engineering', 'icon' => 'cog'],
            ['name_en' => 'Petroleum Engineering', 'name_ar' => 'هندسة البترول', 'slug' => 'petroleum-engineering', 'icon' => 'oil-can'],
            ['name_en' => 'Energy Engineering', 'name_ar' => 'هندسة الطاقة', 'slug' => 'energy-engineering', 'icon' => 'bolt'],
            ['name_en' => 'Computers & Information', 'name_ar' => 'الحاسبات والمعلومات', 'slug' => 'computers-information', 'icon' => 'laptop-code'],
            ['name_en' => 'Science', 'name_ar' => 'العلوم', 'slug' => 'science', 'icon' => 'flask'],
            ['name_en' => 'Agriculture', 'name_ar' => 'الزراعة', 'slug' => 'agriculture', 'icon' => 'seedling'],
            ['name_en' => 'Veterinary Medicine', 'name_ar' => 'الطب البيطري', 'slug' => 'veterinary-medicine', 'icon' => 'paw'],
            ['name_en' => 'Nursing', 'name_ar' => 'التمريض', 'slug' => 'nursing', 'icon' => 'user-nurse'],
            ['name_en' => 'Physical Therapy', 'name_ar' => 'العلاج الطبيعي', 'slug' => 'physical-therapy', 'icon' => 'hand-holding-medical'],
            ['name_en' => 'Law', 'name_ar' => 'الحقوق', 'slug' => 'law', 'icon' => 'scale-balanced'],
            ['name_en' => 'Al-Alsun (Languages)', 'name_ar' => 'الألسن', 'slug' => 'al-alsun', 'icon' => 'language'],
            ['name_en' => 'Archaeology', 'name_ar' => 'الآثار', 'slug' => 'archaeology', 'icon' => 'landmark'],
            ['name_en' => 'Mass Communication', 'name_ar' => 'الإعلام', 'slug' => 'mass-communication', 'icon' => 'tower-broadcast'],
            ['name_en' => 'Business Administration', 'name_ar' => 'إدارة الأعمال', 'slug' => 'business-administration', 'icon' => 'briefcase'],
            ['name_en' => 'Economics & Political Science', 'name_ar' => 'الاقتصاد والعلوم السياسية', 'slug' => 'economics-political-science', 'icon' => 'chart-line'],
            ['name_en' => 'Education', 'name_ar' => 'التربية', 'slug' => 'education', 'icon' => 'chalkboard-user'],
            ['name_en' => 'Physical Education', 'name_ar' => 'التربية الرياضية', 'slug' => 'physical-education', 'icon' => 'dumbbell'],
            ['name_en' => 'Fine Arts', 'name_ar' => 'الفنون الجميلة', 'slug' => 'fine-arts', 'icon' => 'palette'],
            ['name_en' => 'Applied Arts', 'name_ar' => 'الفنون التطبيقية', 'slug' => 'applied-arts', 'icon' => 'pen-ruler'],
            ['name_en' => 'Dar Al-Ulum (Arabic Studies)', 'name_ar' => 'دار العلوم', 'slug' => 'dar-al-ulum', 'icon' => 'book-quran'],
        ];
    }
}
