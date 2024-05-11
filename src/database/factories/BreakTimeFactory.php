<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BreakTime;
use App\Models\Attendance;
use Carbon\Carbon;

class BreakTimeFactory extends Factory
{
    protected $model = BreakTime::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $attendance = Attendance::inRandomOrder()->first();
        $start_time = Carbon::parse($attendance->start_time)->addMinutes(rand(0, $attendance->end_time->diffInMinutes($attendance->start_time)));
        $end_time = (clone $start_time)->addMinutes(rand(0, $attendance->end_time->diffInMinutes($start_time)));

        return [
            'attendance_id' => $attendance->id,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ];
    }
}
