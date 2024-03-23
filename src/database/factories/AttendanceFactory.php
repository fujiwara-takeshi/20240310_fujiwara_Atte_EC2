<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start_date = Carbon::create(2023, 1, 1);
        $end_date = Carbon::create(2023, 12, 31);
        $random_date = $start_date->addDays(rand(0, $end_date->diffInDays($start_date)));
        $start_time = $random_date->addHours(rand(0, 23))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));
        $end_time = (clone $start_time)->addHours(rand(0, 23 - $start_time->hour))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));

        return [
            'user_id' => User::factory(),
            'date' => $start_time->toDateString(),
            'start_time' => $start_time,
            'end_time' => $end_time,
        ];
    }
}
