<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Role;
use App\Models\Day;
use App\Models\UserDay;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $expert;
        $expertDays;
        do{
            $expert =  User::where('role_id',  Role::where('name', 'Expert')->first()->id)->get()->random();
            $expertDay = UserDay::where([
                'user_id' => $expert->id,
                'day_id' => $expert->days[fake()->unique()->numberBetween($min = 0, $max = count($expert->days) > 0 ? count($expert->days) - 1 : 0)]->id
            ])->first();
        } while(empty($expertDay));

        $startTime = fake()->numberBetween($min = $expertDay->startTime, $max = $expertDay->endTime - 1);

        return [
            'user_id' =>  User::all()->random()->id,
            'expert' => $expert->id,
            'day_id' => $expertDay->day_id,
            'startTime' => $startTime,
            'endTime' => fake()->numberBetween($min = $startTime + 1, $max = $expertDay->endTime)
        ];
    }
}
