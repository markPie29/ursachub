<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlDumpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sqlPath = base_path('ursachub db/ursachub.sql');

        if (!File::exists($sqlPath)) {
            $this->command->error("SQL file not found at: {$sqlPath}");
            return;
        }

        $this->command->info("Reading SQL dump from {$sqlPath}...");
        $sql = File::get($sqlPath);

        // Turn off foreign keys for SQLite
        DB::statement('PRAGMA foreign_keys = OFF;');

        // Match all INSERT INTO statements (handles multi-line values)
        preg_match_all('/INSERT\s+INTO\s+[^;]+;/i', $sql, $matches);

        if (empty($matches[0])) {
            $this->command->warn("No INSERT INTO statements found in SQL file.");
            return;
        }

        $count = 0;
        foreach ($matches[0] as $insertQuery) {
            // Skip inserting into migrations table as artisan migrate manages it
            if (preg_match('/INSERT\s+INTO\s+[`"]?migrations[`"]?/i', $insertQuery)) {
                continue;
            }

            // Replace MySQL escaped single quotes \' with SQLite standard ''
            $sqliteQuery = str_replace("\\'", "''", $insertQuery);
            
            // Replace MySQL escaped double quotes \" with "
            $sqliteQuery = str_replace('\\"', '"', $sqliteQuery);

            // Replace double backslashes \\ with single backslash \ (for escaped JSON paths)
            $sqliteQuery = str_replace('\\\\', '\\', $sqliteQuery);

            try {
                DB::unprepared($sqliteQuery);
                $count++;
            } catch (\Exception $e) {
                $this->command->error("Failed executing query chunk: " . substr($insertQuery, 0, 100) . "...");
                $this->command->error("Error: " . $e->getMessage());
            }
        }

        // Re-enable foreign keys
        DB::statement('PRAGMA foreign_keys = ON;');

        $this->command->info("Successfully executed {$count} INSERT statements into SQLite!");
    }
}
