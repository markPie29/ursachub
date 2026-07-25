<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courses;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Products;
use App\Models\News;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Courses
        $bsit = Courses::create(['name' => 'BS Information Technology']);
        $bscs = Courses::create(['name' => 'BS Computer Science']);
        $bsis = Courses::create(['name' => 'BS Information Systems']);
        $bsce = Courses::create(['name' => 'BS Civil Engineering']);

        // 2. Seed Admins
        $adminCcs = Admin::create([
            'name' => 'CCS Student Council',
            'org' => 'CCS',
            'password' => 'admin123',
            'gcash_name' => 'CCS Treasurer',
            'gcash_number' => '09123456789',
        ]);

        $adminCea = Admin::create([
            'name' => 'Engineering Student Council',
            'org' => 'CEA',
            'password' => 'admin123',
            'gcash_name' => 'CEA Treasurer',
            'gcash_number' => '09987654321',
        ]);

        // 3. Seed Demo Students
        $student1 = Student::create([
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'middle_name' => 'Santos',
            'student_id' => '2024-00001',
            'course_id' => $bsit->id,
            'password' => 'student123',
        ]);

        $student2 = Student::create([
            'first_name' => 'Maria',
            'last_name' => 'Clara',
            'middle_name' => 'Rizal',
            'student_id' => '2024-00002',
            'course_id' => $bsce->id,
            'password' => 'student123',
        ]);

        // 4. Seed Products
        $product1 = Products::create([
            'org' => 'CCS',
            'name' => 'CCS Official Lanyard',
            'small' => 50,
            'medium' => 50,
            'large' => 50,
            'extralarge' => 0,
            'double_extralarge' => 0,
            'price' => 150.00,
            'photos' => json_encode([]),
            'logo' => null,
        ]);
        $product1->courses()->attach([$bsit->id, $bscs->id, $bsis->id]);

        $product2 = Products::create([
            'org' => 'CCS',
            'name' => 'CCS Tech Shirt 2024',
            'small' => 20,
            'medium' => 30,
            'large' => 25,
            'extralarge' => 10,
            'double_extralarge' => 5,
            'price' => 350.00,
            'photos' => json_encode([]),
            'logo' => null,
        ]);
        $product2->courses()->attach([$bsit->id, $bscs->id, $bsis->id]);

        $product3 = Products::create([
            'org' => 'CEA',
            'name' => 'CEA Engineering Hoodie',
            'small' => 15,
            'medium' => 25,
            'large' => 20,
            'extralarge' => 10,
            'double_extralarge' => 5,
            'price' => 650.00,
            'photos' => json_encode([]),
            'logo' => null,
        ]);
        $product3->courses()->attach([$bsce->id]);

        // 5. Seed News
        News::create([
            'org' => 'CCS',
            'headline' => 'Welcome to URSAC Hub Live Demo!',
            'content' => 'URSAC Hub is now deployed as a public demo environment. Feel free to log in as a student or admin using the demo credentials.',
            'photos' => json_encode([]),
            'logo' => null,
        ]);

        News::create([
            'org' => 'CEA',
            'headline' => 'Annual Engineering Week Announced',
            'content' => 'Join us next month for workshops, competitions, and guest speaker events during Engineering Week.',
            'photos' => json_encode([]),
            'logo' => null,
        ]);

        // 6. Seed Events
        Event::create([
            'name' => 'CCS Hackathon 2024',
            'date' => now()->addDays(14),
            'venue' => 'College Building Rm 301',
            'org' => 'CCS',
        ]);

        Event::create([
            'name' => 'CEA General Assembly',
            'date' => now()->addDays(7),
            'venue' => 'Main Gymnasium',
            'org' => 'CEA',
        ]);
    }
}

