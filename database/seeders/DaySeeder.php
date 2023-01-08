<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Day;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Day::query()->insert([
            [
                'name' => 'Sunday',
            ],
            [
                'name' => 'Monday',
            ],
            [
                'name' => 'Tuesday',
            ],
            [
                'name' => 'Wednesday',
            ],
            [
                'name' => 'Thursday',
            ],
            [
                'name' => 'Friday',
            ],
            [
                'name' => 'Saturday',
            ],

        ]);

    }
}
