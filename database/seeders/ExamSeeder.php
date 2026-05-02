<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $exams = [
            [
                'slug' => 'lok-sewa-section-officer',
                'name' => 'Lok Sewa - Section Officer',
                'name_nepali' => 'लोक सेवा - शाखा अधिकृत',
                'description' => 'Section Officer (शाखा अधिकृत) preparation for Lok Sewa Aayog exam.',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'lok-sewa-nayab-subba',
                'name' => 'Lok Sewa - Nayab Subba',
                'name_nepali' => 'लोक सेवा - नायब सुब्बा',
                'description' => 'Nayab Subba (नायब सुब्बा) preparation for Lok Sewa Aayog exam.',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'nrb-assistant',
                'name' => 'NRB Assistant',
                'name_nepali' => 'नेपाल राष्ट्र बैंक - सहायक',
                'description' => 'Nepal Rastra Bank Assistant level exam preparation.',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'bank-exam-common',
                'name' => 'Common Bank Exam',
                'name_nepali' => 'बैंक परीक्षा - साझा',
                'description' => 'Common preparation for NIC Asia, NMB, Nabil, Prabhu, Global IME bank exams.',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'teaching-license-secondary',
                'name' => 'Teaching License - Secondary',
                'name_nepali' => 'शिक्षक सेवा आयोग - माध्यमिक',
                'description' => 'Secondary level Teaching Service Commission license exam preparation.',
                'is_featured' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($exams as $exam) {
            Exam::updateOrCreate(['slug' => $exam['slug']], $exam);
        }
    }
}
