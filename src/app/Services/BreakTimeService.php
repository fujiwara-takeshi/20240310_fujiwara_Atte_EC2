<?php

namespace App\Services;

use App\Repositories\BreakTimeRepository;
use Carbon\Carbon;

class BreakTimeService
{
    private $breakTimeRepository;

    public function __construct(
        BreakTimeRepository $breakTimeRepository
    ){
        $this->breakTimeRepository = $breakTimeRepository;
    }

    public function getCurrentBreak($attendance)
    {
        return $attendance ? $attendance->breakTimes()->latest()->first() : null;
    }

    public function checkBreakStatus($break)
    {
        return $break && $break->start_time && !$break->end_time;
    }

    public function start($attendance_id)
    {
        $current_datetime = Carbon::now();
        $data = [
            'attendance_id' => $attendance_id,
            'start_time' => $current_datetime,
        ];
        $this->breakTimeRepository->createBreak($data);
    }

    public function end($break_id, $attendance_id)
    {
        $current_datetime = Carbon::now();
        $break = $this->breakTimeRepository->findBreak($break_id);
        $start_datetime = Carbon::parse($break->start_time)->startOfDay();
        $end_datetime = Carbon::parse($break->start_time)->endOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        for ($i = 0; $i <= $diff_in_days; $i++) {
            if ($i === 0 && $i === $diff_in_days) {
                $data = ['end_time' => $current_datetime];
                $this->breakTimeRepository->updateBreak($break, $data);
                break;
            } elseif ($i === 0) {
                $data = ['end_time' => $end_datetime];
                $this->breakTimeRepository->updateBreak($break, $data);
            } elseif ($i === $diff_in_days) {
                $data = [
                    'attendance_id' => $attendance_id,
                    'start_time' => $current_datetime->copy()->startOfDay(),
                    'end_time' => $current_datetime,
                ];
                $this->breakTimeRepository->createBreak($data);
            } else {
                $day_start = $start_datetime->copy()->addDays($i);
                $day_end = $end_datetime->copy()->addDays($i);
                $data = [
                    'attendance_id' => $attendance_id,
                    'start_time' => $day_start,
                    'end_time' => $day_end,
                ];
                $this->breakTimeRepository->createBreak($data);
            }
        }
    }
}