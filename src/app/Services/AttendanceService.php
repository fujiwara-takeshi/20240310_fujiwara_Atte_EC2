<?php
namespace App\Services;

use App\Repositories\AttendanceRepository;
use App\Repositories\BreakTimeRepository;
use Carbon\Carbon;

class AttendanceService
{
    private $attendanceRepository;
    private $breakTimeRepository;

    public function __construct(
        AttendanceRepository $attendanceRepository,
        BreakTimeRepository $breakTimeRepository
    ){
        $this->attendanceRepository = $attendanceRepository;
        $this->breakTimeRepository = $breakTimeRepository;
    }

    public function getCurrentAttendance($user_id)
    {
        return $this->attendanceRepository->getLatestAttendanceByUser($user_id);
    }

    public function checkAttendanceStatus($attendance)
    {
        return ($attendance && $attendance->end_time) || !$attendance;
    }

    public function start($user_id)
    {
        $current_datetime = Carbon::now();
        $data = [
            'user_id' => $user_id,
            'date' => $current_datetime->toDateString(),
            'start_time' => $current_datetime,
        ];
        $this->attendanceRepository->createAttendance($data);
    }

    public function end($attendance_id, $user_id)
    {
        $current_datetime = Carbon::now();
        $attendance = $this->attendanceRepository->findAttendance($attendance_id);
        $start_datetime = Carbon::parse($attendance->start_time)->startOfDay();
        $end_datetime = Carbon::parse($attendance->start_time)->endOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        for ($i = 0; $i <= $diff_in_days; $i++) {
            if ($i === 0 && $i === $diff_in_days) {
                $data = ['end_time' => $current_datetime];
                $this->attendanceRepository->updateAttendance($attendance, $data);
                break;
            } elseif ($i === 0) {
                $data = ['end_time' => $end_datetime];
                $this->attendanceRepository->updateAttendance($attendance, $data);
                continue;
            } elseif ($i === $diff_in_days) {
                $data = [
                    'user_id' => $user_id,
                    'date' => $current_datetime->toDateString(),
                    'start_time' => $current_datetime->copy()->startOfDay(),
                    'end_time' => $current_datetime,
                ];
                $attendance = $this->attendanceRepository->createAttendance($data);
            } else {
                $day_start = $start_datetime->copy()->addDays($i);
                $day_end = $end_datetime->copy()->addDays($i);
                $data = [
                    'user_id' => $user_id,
                    'date' => $day_start->toDateString(),
                    'start_time' => $day_start,
                    'end_time' => $day_end,
                ];
                $attendance = $this->attendanceRepository->createAttendance($data);
            }
            $breaks = $this->breakTimeRepository->getBreaksByAttendance($attendance_id);
            foreach ($breaks as $break) {
                if (Carbon::parse($break->start_time)->toDateString() === $attendance->date) {
                    $data = ['attendance_id' => $attendance->id];
                    $this->breakTimeRepository->updateBreak($break, $data);
                }
            }
        }
    }

    public function getDistinctDates()
    {
        return $this->attendanceRepository->pluckDistinctDates();
    }

    public function getAttendancesByDate($date)
    {
        return $this->attendanceRepository->paginateAttendancesByDate($date);
    }

    public function getAttendancesByUser($user_id)
    {
        return $this->attendanceRepository->paginateAttendancesByUser($user_id);
    }
}