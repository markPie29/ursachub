<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class MockEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [
            [
                'name' => 'Tech Summit 2026: Innovations in AI & Robotics',
                'date' => Carbon::now()->addDays(2)->setTime(9, 0, 0)->format('Y-m-d H:i:s'),
                'venue' => 'Main University Auditorium',
                'org' => 'College of Engineering - Student Council',
            ],
            [
                'name' => 'Pistang Inhinyero: Annual Engineering Week',
                'date' => Carbon::now()->format('Y-m-d H:i:s'), // Event is today!
                'venue' => 'University Gymnasium',
                'org' => 'College of Engineering - Student Council',
            ],
            [
                'name' => 'Cyber Security Workshop & Hackathon',
                'date' => Carbon::now()->addDays(5)->setTime(13, 30, 0)->format('Y-m-d H:i:s'),
                'venue' => 'Computer Lab 3',
                'org' => 'College of Computer Studies',
            ],
            [
                'name' => 'Leadership & Governance Seminar',
                'date' => Carbon::now()->addDays(10)->setTime(10, 0, 0)->format('Y-m-d H:i:s'),
                'venue' => 'AVR Building B',
                'org' => 'University Student Government',
            ]
        ];

        foreach ($events as $eventData) {
            Event::updateOrCreate(
                ['name' => $eventData['name']],
                $eventData
            );
        }
    }
}
