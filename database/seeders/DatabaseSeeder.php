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
        $this->call([
            SqlDumpSeeder::class,
            MockEventsSeeder::class,
        ]);
    }
}

