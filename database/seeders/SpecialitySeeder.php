<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speciality::query()->insert([
            [
                'name' => 'Law',
            ],
            [
                'name' => 'Medical',
            ],
            [
                'name' => 'Mental Health',
            ],
            [
                'name' => 'Business',
            ],
            [
                'name' => 'Family',
            ],
            [
                'name' => 'Nutrition',
            ],

        ]);
    }
}
