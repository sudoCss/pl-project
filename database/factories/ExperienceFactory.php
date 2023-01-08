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
        // $expertId = User::where('role_id',  Role::where('name', 'Expert')->first()->id)->get()->random()->id;
        // $expertAndSpeciality = fake()->unique()->regexify("/^$expertId-[1-6]{1}");
        // $specialityId = explode('-', $expertAndSpeciality)[1];

        $expertIds =  User::select('id')->where('role_id',  Role::where('name', 'Expert')->first()->id)->get();
        $expertId = fake()->unique()->randomElement($expertIds);
        $specialityId = Speciality::all()->random()->id;

        // $expertAndSpeciality = fake()->unique()->regexify("/^$specialityId-[1-20]{1}");


        return [
            'details' => fake()->text($maxNbChars = 100),
            'speciality_id' => $specialityId, //Speciality::all()->random()->id,
            'user_id' => $expertId, //User::where('role_id',  Role::where('name', 'Expert')->first()->id)->get()->random()->id,  //all()->random()->id
        ];
    }
}
