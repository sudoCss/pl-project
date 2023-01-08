<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DaySeeder::class);
        //  \App\Models\Day::factory(7)->create();
        $this->call(SpecialitySeeder::class);
        //  \App\Models\Speciality::factory(5)->create();
        //   \App\Models\Role::factory(3)->create();
        $this->call(RoleSeeder::class);
          \App\Models\User::factory(50)->create();
          \App\Models\Experience::factory(10)->create();
          \App\Models\Rating::factory(20)->create();
          \App\Models\Favourite::factory(20)->create();
        //   \App\Models\UserDay::factory(20)->create();
        //   \App\Models\Appointment::factory(20)->create();
        $this->call(AppointmentSeeder::class);
        $this->call(UserDaySeeder::class);
        //   \App\Models\Wallet::factory(20)->create();
        //   \App\Models\User::factory(20)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
