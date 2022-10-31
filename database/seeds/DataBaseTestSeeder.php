<?php

use Illuminate\Database\Seeder;

class DataBaseTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $route =database_path('database_stradata_person_public.sql');
        DB::unprepared(file_get_contents($route));
    }
}
