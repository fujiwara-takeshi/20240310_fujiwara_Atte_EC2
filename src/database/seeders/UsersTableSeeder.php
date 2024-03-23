<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create()->each(function($user) {
            Attendance::factory(10)->create(['user_id' => $user->id])->each(function ($attendance) {
                BreakTime::factory(rand(0, 2))->create(['attendance_id' => $attendance->id]);
            });
        });
    }
}
