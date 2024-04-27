<?php

namespace App\Repositories;

use App\Models\BreakTime;

class BreakTimeRepository
{
    //Breakレコードの作成
    public function createBreak($data)
    {
        BreakTime::create($data);
    }

    //Breakレコードの更新
    public function updateBreak($break, $data)
    {
        $break->update($data);
    }

    //特定の勤務情報のidで検索し、該当する休憩情報を返す
    public function getBreaksByAttendance($attendance_id)
    {
        return BreakTime::where('attendance_id', $attendance_id)->get();
    }
}