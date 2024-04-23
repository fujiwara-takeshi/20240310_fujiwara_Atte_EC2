<?php

namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use App\Models\Attendance;

class AttendanceRepository
{
    public function createAttendance($data)
    {
        return Attendance::create($data);
    }

    public function updateAttendance($attendance, $data)
    {
        $attendance->update($data);
    }

    public function findAttendance($id)
    {
        return Attendance::find($id);
    }

    public function getLatestAttendanceByUser($user_id)
    {
        return Attendance::where('user_id', $user_id)->latest()->first();
    }

    public function pluckDistinctDates()
    {
        return Attendance::distinct()->select('date')->orderBy('date', 'desc')->pluck('date');
    }

    public function paginateAttendancesByDate($date)
    {
        return Attendance::where('date', $date)->with('user', 'breakTimes')->orderBy('start_time', 'desc')->paginate(5);
    }

    public function paginateAttendancesByUser($user_id)
    {
        return Attendance::where('user_id', $user_id)->with('breakTimes')->orderBy('start_time', 'desc')->paginate(5);
    }
}