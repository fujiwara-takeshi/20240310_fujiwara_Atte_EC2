<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', Auth::id())->latest()->first();
        $break = $attendance ? $attendance->breakTimes()->latest()->first() : null;
        $attendanceStatus = $this->attendanceStatus($attendance);
        $breakStatus = $this->breakStatus($break);
        return view('index', compact('user', 'attendance', 'break', 'attendanceStatus', 'breakStatus'));
    }

    public function start()
    {
        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'date' => Carbon::now()->toDateString(),
            'start_time' => Carbon::now(),
        ]);
        return redirect('/');
    }

    public function end(Request $request)
    {
        $current_datetime = Carbon::now();
        $current_attendance = Attendance::find($request->id);
        $start_datetime = Carbon::parse($current_attendance->start_time)->startOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        for ($i = 0; $i < $diff_in_days + 1; $i++) {
            $start_datetime = Carbon::parse($current_attendance->start_time)->addDays($i)->startOfDay();
            if ($i === 0) {
                $start_datetime = Carbon::parse($current_attendance->start_time);
            }
            $end_datetime = Carbon::parse($current_attendance->start_time)->addDays($i)->endOfDay();
            if ($i === $diff_in_days) {
                $end_datetime = $current_datetime;
            }
            Attendance::updateOrCreate(
                ['id' => $request->id + $i],
                [
                    'user_id' => Auth::id(),
                    'date' => $start_datetime->toDateString(),
                    'start_time' => $start_datetime,
                    'end_time' => $end_datetime
                ]
            );
        }
        return redirect('/');
    }

    public function breakStart(Request $request)
    {
        $break = BreakTime::create([
            'attendance_id' => $request->attendance_id,
            'start_time' => Carbon::now(),
        ]);
        return redirect('/');
    }

    public function breakEnd(Request $request)
    {
        $current_datetime = Carbon::now();
        $current_break = BreakTime::find($request->id);
        $start_datetime = Carbon::parse($current_break->start_time)->startOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        for ($i = 0; $i < $diff_in_days + 1; $i++) {
            $start_datetime = Carbon::parse($current_break->start_time)->addDays($i)->startOfDay();
            if ($i === 0) {
                $start_datetime = Carbon::parse($current_break->start_time);
            }
            $end_datetime = Carbon::parse($current_break->start_time)->addDays($i)->endOfDay();
            if ($i === $diff_in_days) {
                $end_datetime = $current_datetime;
            }
            BreakTime::updateOrCreate(
                ['id' => $request->id + $i],
                [
                    'attendance_id' => $request->attendance_id,
                    'start_time' => $start_datetime,
                    'end_time' => $end_datetime
                ]
            );
        }
        return redirect('/');
    }

    public function date()
    {
        $attendances = Attendance::with('user', 'breakTimes')->get();
        return view('date', compact('attendances'));
    }

    public function attendanceStatus($attendance)
    {
        return ($attendance && $attendance->end_time) || !$attendance;
    }

    public function breakStatus($break)
    {
        return $break && $break->start_time && !$break->end_time;
    }
}
