<?php

namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use App\Models\Attendance;

class AttendanceRepository
{
    //Attendanceレコードの作成
    public function createAttendance($data)
    {
        return Attendance::create($data);
    }

    //Attendanceレコードの更新
    public function updateAttendance($attendance, $data)
    {
        $attendance->update($data);
    }

    //ログイン中のユーザーidを引数に取り、そのユーザーの最新のAttendanceレコードを取得
    public function getLatestAttendanceByUser($user_id)
    {
        return Attendance::where('user_id', $user_id)->latest()->first();
    }

    //重複しないdateの値を取得し配列で返す
    public function getDistinctDatesList()
    {
        return Attendance::distinct()->select('date')->orderBy('date', 'desc')->pluck('date');
    }

    //特定のdateの値のAttendanceレコードを取得しページネーションインスタンスとして返す
    public function paginateAttendancesByDate($date)
    {
        return Attendance::where('date', $date)->with('user', 'breakTimes')->orderBy('start_time', 'desc')->paginate(5);
    }

    //特定のユーザーのidで検索し、該当するAttendanceレコードを取得しページネーションインスタンスとして返す
    public function paginateAttendancesByUser($user_id)
    {
        return Attendance::where('user_id', $user_id)->with('breakTimes')->orderBy('start_time', 'desc')->paginate(5);
    }
}