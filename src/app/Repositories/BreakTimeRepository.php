<?php

namespace App\Repositories;

use App\Models\BreakTime;

class BreakTimeRepository
{
    public function createBreak($data)
    {
        BreakTime::create($data);
    }

    public function updateBreak($break, $data)
    {
        $break->update($data);
    }

    public function findBreak($id)
    {
        return BreakTime::find($id);
    }

    public function getBreaksByAttendance($attendance_id)
    {
        return BreakTime::where('attendance_id', $attendance_id)->get();
    }
}