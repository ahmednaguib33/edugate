<?php

namespace Database\Seeders;

use App\Enums\DegreeLevel;
use App\Enums\ProgramLanguage;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Bilingual selling points taken from the EduGate faculty designs.
     *
     * @var array<int, string>
     */
    private array $highlights = [
        'Locally & internationally accredited certificates',
        'Hands-on practical training with the latest technology',
        'Diverse and safe campus life',
        'High-quality education aligned with international standards',
    ];

    public function run(): void
    {
        $faculties = Faculty::all()->keyBy('slug');
        $universityIds = University::active()->pluck('id')->all();

        $degreeMeta = [
            'bachelor' => ['label_en' => 'Bachelor', 'label_ar' => 'بكالوريوس', 'enum' => DegreeLevel::Bachelor, 'duration' => null, 'tuition_add' => 0],
            'master' => ['label_en' => 'Master', 'label_ar' => 'ماجستير', 'enum' => DegreeLevel::Master, 'duration' => 2, 'tuition_add' => 1000],
            'phd' => ['label_en' => 'PhD', 'label_ar' => 'دكتوراه', 'enum' => DegreeLevel::Phd, 'duration' => 4, 'tuition_add' => 2000],
        ];

        foreach ($this->config() as $slug => $c) {
            $faculty = $faculties->get($slug);

            if ($faculty === null) {
                continue;
            }

            foreach ($c['levels'] as $level) {
                $meta = $degreeMeta[$level];

                $titleEn = $level === 'phd'
                    ? 'PhD in '.$faculty->name_en
                    : $meta['label_en'].' of '.$faculty->name_en;

                $program = Program::updateOrCreate(
                    ['slug' => $level.'-'.$slug],
                    [
                        'faculty_id' => $faculty->id,
                        'degree_level' => $meta['enum'],
                        'title_en' => $titleEn,
                        'title_ar' => $meta['label_ar'].' '.$faculty->name_ar,
                        'description_en' => 'Study a '.$meta['label_en'].' in '.$faculty->name_en.' at accredited Egyptian universities — designed for Arab and Gulf students.',
                        'description_ar' => 'دراسة '.$meta['label_ar'].' '.$faculty->name_ar.' في الجامعات المصرية المعتمدة للطلاب العرب وأبناء الخليج.',
                        'tuition_min' => $c['tuition'][0] + $meta['tuition_add'],
                        'tuition_max' => $c['tuition'][1] + $meta['tuition_add'],
                        'currency' => 'USD',
                        'min_admission_rate' => $level === 'bachelor' ? $c['rate'] : null,
                        'duration_years' => $level === 'bachelor' ? $c['duration'] : $meta['duration'],
                        'language' => ProgramLanguage::Both,
                        'highlights' => $this->highlights,
                        'is_featured' => $level === 'bachelor' && ($c['featured'] ?? false),
                        'is_active' => true,
                    ]
                );

                $program->universities()->sync($universityIds);
            }
        }
    }

    /**
     * Per-faculty tuition (USD), minimum admission rate (%), bachelor duration (years),
     * and the degree levels offered — derived from the EduGate design cards.
     *
     * @return array<string, array{tuition: array{int, int}, rate: int, duration: int, levels: array<int, string>, featured?: bool}>
     */
    private function config(): array
    {
        return [
            'medicine' => ['tuition' => [6000, 8000], 'rate' => 70, 'duration' => 6, 'levels' => ['bachelor', 'master', 'phd'], 'featured' => true],
            'dentistry' => ['tuition' => [6000, 8000], 'rate' => 70, 'duration' => 5, 'levels' => ['bachelor', 'master', 'phd'], 'featured' => true],
            'engineering' => ['tuition' => [4000, 6000], 'rate' => 65, 'duration' => 5, 'levels' => ['bachelor', 'master', 'phd'], 'featured' => true],
            'petroleum-engineering' => ['tuition' => [5000, 7000], 'rate' => 70, 'duration' => 5, 'levels' => ['bachelor', 'master', 'phd']],
            'energy-engineering' => ['tuition' => [5000, 7000], 'rate' => 70, 'duration' => 5, 'levels' => ['master', 'phd']],
            'computers-information' => ['tuition' => [4000, 6000], 'rate' => 60, 'duration' => 4, 'levels' => ['bachelor', 'master', 'phd']],
            'science' => ['tuition' => [3000, 5000], 'rate' => 55, 'duration' => 4, 'levels' => ['bachelor', 'master', 'phd']],
            'agriculture' => ['tuition' => [3000, 4500], 'rate' => 50, 'duration' => 4, 'levels' => ['bachelor', 'master', 'phd']],
            'veterinary-medicine' => ['tuition' => [4000, 6000], 'rate' => 65, 'duration' => 5, 'levels' => ['bachelor', 'master', 'phd']],
            'nursing' => ['tuition' => [3500, 5000], 'rate' => 60, 'duration' => 4, 'levels' => ['bachelor', 'master', 'phd']],
            'physical-therapy' => ['tuition' => [4000, 6000], 'rate' => 65, 'duration' => 5, 'levels' => ['bachelor', 'master', 'phd']],
            'law' => ['tuition' => [3000, 4500], 'rate' => 50, 'duration' => 4, 'levels' => ['bachelor']],
            'al-alsun' => ['tuition' => [3500, 5000], 'rate' => 60, 'duration' => 4, 'levels' => ['bachelor']],
            'archaeology' => ['tuition' => [3000, 4500], 'rate' => 50, 'duration' => 4, 'levels' => ['bachelor']],
            'mass-communication' => ['tuition' => [3500, 5000], 'rate' => 55, 'duration' => 4, 'levels' => ['bachelor']],
            'business-administration' => ['tuition' => [3500, 5500], 'rate' => 55, 'duration' => 4, 'levels' => ['bachelor']],
            'economics-political-science' => ['tuition' => [3500, 5500], 'rate' => 55, 'duration' => 4, 'levels' => ['bachelor']],
            'education' => ['tuition' => [3000, 4500], 'rate' => 50, 'duration' => 4, 'levels' => ['bachelor']],
            'physical-education' => ['tuition' => [3000, 4500], 'rate' => 50, 'duration' => 4, 'levels' => ['bachelor']],
            'fine-arts' => ['tuition' => [3500, 5000], 'rate' => 55, 'duration' => 5, 'levels' => ['bachelor']],
            'applied-arts' => ['tuition' => [3500, 5000], 'rate' => 55, 'duration' => 5, 'levels' => ['bachelor']],
            'dar-al-ulum' => ['tuition' => [3000, 4500], 'rate' => 50, 'duration' => 4, 'levels' => ['bachelor']],
        ];
    }
}
