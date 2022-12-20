<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Appointment::query()->insert([
            [
                'user_id' => 1,
                'expert' => 2,
                'day_id' => 1,
                'startTime' => 9,
                'endTime' => 10,
            ],
            [
                'user_id' => 3,
                'expert' => 2,
                'day_id' => 1,
                'startTime' => 11,
                'endTime' => 12,
            ],
        ]);
    }
}
