<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Speciality;
use App\Models\User;
use App\Models\Role;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'details' => fake()->text($maxNbChars = 100),
            'speciality_id' => Speciality::all()->random()->id,
            'user_id' => User::where('role_id',  Role::where('name', 'Expert')->first()->id)->get()->random()->id,  //all()->random()->id
        ];
    }
}
