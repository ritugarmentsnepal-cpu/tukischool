<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Exam;
use App\Models\Syllabus;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        // Get Lok Sewa Section Officer exam
        $lokSewa = Exam::where('slug', 'lok-sewa-section-officer')->first();
        if (!$lokSewa) return;

        // Create pre-loaded syllabus
        $syllabus = Syllabus::create([
            'user_id' => null,
            'exam_id' => $lokSewa->id,
            'title' => 'Lok Sewa Section Officer — Core Syllabus',
            'source' => 'pre_loaded',
            'status' => 'completed',
            'chapter_count' => 8,
        ]);

        $chapters = [
            [
                'title' => 'Constitution of Nepal 2072',
                'title_nepali' => 'नेपालको संविधान २०७२',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Governance System & Public Administration',
                'title_nepali' => 'शासन प्रणाली र लोक प्रशासन',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Nepal\'s Foreign Policy & International Relations',
                'title_nepali' => 'नेपालको विदेश नीति र अन्तर्राष्ट्रिय सम्बन्ध',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Economic Development & Planning',
                'title_nepali' => 'आर्थिक विकास र योजना',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Civil Service Act & Rules',
                'title_nepali' => 'निजामती सेवा ऐन र नियमावली',
                'credits_to_unlock' => 3,
            ],
            [
                'title' => 'Good Governance & Anti-Corruption',
                'title_nepali' => 'सुशासन र भ्रष्टाचार निवारण',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Federalism & Local Governance',
                'title_nepali' => 'संघीयता र स्थानीय शासन',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Current Affairs & National Issues',
                'title_nepali' => 'समसामयिक घटना र राष्ट्रिय मुद्दाहरू',
                'credits_to_unlock' => 3,
            ],
        ];

        foreach ($chapters as $i => $ch) {
            Chapter::create([
                'syllabus_id' => $syllabus->id,
                'exam_id' => $lokSewa->id,
                'order' => $i + 1,
                'title' => $ch['title'],
                'title_nepali' => $ch['title_nepali'],
                'credits_to_unlock' => $ch['credits_to_unlock'],
                'status' => 'locked',
            ]);
        }

        // Also seed for NRB Assistant
        $nrb = Exam::where('slug', 'nrb-assistant')->first();
        if (!$nrb) return;

        $nrbSyllabus = Syllabus::create([
            'user_id' => null,
            'exam_id' => $nrb->id,
            'title' => 'NRB Assistant — Core Syllabus',
            'source' => 'pre_loaded',
            'status' => 'completed',
            'chapter_count' => 6,
        ]);

        $nrbChapters = [
            [
                'title' => 'Central Banking & Monetary Policy',
                'title_nepali' => 'केन्द्रीय बैंकिङ र मौद्रिक नीति',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Nepal Rastra Bank Act 2058',
                'title_nepali' => 'नेपाल राष्ट्र बैंक ऐन २०५८',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Banking & Financial Institutions',
                'title_nepali' => 'बैंकिङ तथा वित्तीय संस्थाहरू',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'Nepal\'s Economic Indicators',
                'title_nepali' => 'नेपालको आर्थिक सूचकहरू',
                'credits_to_unlock' => 3,
            ],
            [
                'title' => 'Financial Market & Instruments',
                'title_nepali' => 'वित्तीय बजार र उपकरणहरू',
                'credits_to_unlock' => 5,
            ],
            [
                'title' => 'International Financial Organizations',
                'title_nepali' => 'अन्तर्राष्ट्रिय वित्तीय संगठनहरू',
                'credits_to_unlock' => 3,
            ],
        ];

        foreach ($nrbChapters as $i => $ch) {
            Chapter::create([
                'syllabus_id' => $nrbSyllabus->id,
                'exam_id' => $nrb->id,
                'order' => $i + 1,
                'title' => $ch['title'],
                'title_nepali' => $ch['title_nepali'],
                'credits_to_unlock' => $ch['credits_to_unlock'],
                'status' => 'locked',
            ]);
        }
    }
}
