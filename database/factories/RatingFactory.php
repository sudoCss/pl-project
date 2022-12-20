<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'stars' => fake()->randomElement($array=array('1','2', '3', '4', '5')),
            'rated' => User::where('role_id',  Role::where('name', 'Expert')->first()->id)->get()->random()->id,
            'rater' => User::all()->random()->id,
        ];
    }
}
