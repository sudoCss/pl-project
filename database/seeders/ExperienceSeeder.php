<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Experience;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Experience::query()->insert([
            [
                'user_id' => 2,
                'speciality_id' => 1,
                'details' => 8,
            ],
            [
                'user_id' => 2,
                'speciality_id' => 1,
                'details' => 8,
            ],
            [
                'user_id' => 2,
                'speciality_id' => 1,
                'details' => 8,
            ],
            [
                'user_id' => 2,
                'speciality_id' => 1,
                'details' => 8,
            ],
            [
                'user_id' => 2,
                'speciality_id' => 1,
                'details' => 8,
            ],
        ]);
    }
}
