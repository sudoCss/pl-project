<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\UserDay;

class UserDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserDay::query()->insert([
            [
                'user_id' => 2,
                'day_id' => 1,
                'startTime' => 8,
                'endTime' => 1
            ]
        ]);
    }
}
