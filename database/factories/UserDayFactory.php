<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;
use App\Models\User;
use App\Models\Day;
use App\Models\UserDay;





/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDay>
 */
class UserDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        $userId =  User::where('role_id', Role::where('name', 'Expert')->first()->id)->get()->random()->id;

        $userAndDay = fake()->unique()->regexify("/^$userId-[1-7]{1}");
        // $dayId = Day::all()->random()->id;
        $dayId = explode('-', $userAndDay)[1];

        $startTime = fake()->numberBetween($min = 0, $max = 23);

        return [
            'user_id' => $userId,
            'day_id' => $dayId,//Day::all()->random()->id,
            'startTime' => $startTime,
            'endTime' => fake()->numberBetween($min = $startTime + 1, $max = 23)
        ];
    }
}
