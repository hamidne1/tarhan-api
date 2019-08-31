<?php

use Illuminate\Console\Command;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * @param String $file
     */
    public function dumpSqlFile(String $file): void
    {
        $path = "database/seeds/database/{$file}.sql";
        DB::unprepared(file_get_contents($path));
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $dumps = [
            'admins',
        ];

        foreach ($dumps as $dump) {
            $this->dumpSqlFile($dump);
        }
    }
}
