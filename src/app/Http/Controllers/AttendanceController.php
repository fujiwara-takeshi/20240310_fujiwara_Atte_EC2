<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\BreakTime;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $attendance = session()->get('attendance');
        $break = session()->get('break');
        if ($attendance && $break) {
            return view('index', compact('user', 'attendance', 'break'));
        } elseif ($attendance) {
            return view('index', compact('user', 'attendance'));
        } else {
            return view('index', compact('user'));
        }
    }

    public function start()
    {
        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'date' => Carbon::now()->toDateString(),
            'start_time' => Carbon::now(),
        ]);
        session()->put('attendance', $attendance);
        return redirect('/');
    }

    public function end(Request $request)
    {
        $current_datetime = Carbon::now();
        $current_attendance = Attendance::find($request->id);
        $start_datetime = Carbon::parse($current_attendance->start_time)->startOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        if ($diff_in_days == 0) {
            $end_datetime = $current_datetime;
        } elseif ($diff_in_days == 1) {
            $end_datetime = $start_datetime->copy()->endOfDay();
            Attendance::create([
                'user_id' => Auth::id(),
                'date' => $current_datetime->toDateString(),
                'start_time' => $current_datetime->copy()->startOfDay(),
                'end_time' => $current_datetime,
            ]);
        } else {
            $end_datetime = $start_datetime->copy()->endOfDay();
            for ($i = 1; $i < $diff_in_days; $i++) {
                $datetime_between = $start_datetime->copy()->addDays($i);
                Attendance::create([
                    'user_id' => Auth::id(),
                    'date' => $datetime_between->toDateString(),
                    'start_time' => $datetime_between->copy()->startOfDay(),
                    'end_time' => $datetime_between->endOfDay(),
                ]);
            }
            Attendance::create([
                'user_id' => Auth::id(),
                'date' => $current_datetime->toDateString(),
                'start_time' => $current_datetime->copy()->startOfDay(),
                'end_time' => $current_datetime,
            ]);
        }
        $current_attendance->update(['end_time' => $end_datetime]);
        session()->forget('attendance');
        return redirect('/');
    }

    public function breakStart(Request $request)
    {
        $break = BreakTime::create([
            'attendance_id' => $request->attendance_id,
            'start_time' => Carbon::now(),
        ]);
        session()->put('break', $break);
        return redirect('/');
    }

    public function breakEnd(Request $request)
    {
        $current_datetime = Carbon::now();
        $current_break = BreakTime::find($request->id);
        $start_datetime = Carbon::parse($current_break->start_time)->startOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        if ($diff_in_days == 0) {
            $end_datetime = $current_datetime;
        } elseif ($diff_in_days == 1) {
            $end_datetime = $start_datetime->copy()->endOfDay();
            BreakTime::create([
                'attendance_id' => $request->attendance_id,
                'start_time' => $current_datetime->copy()->startOfDay(),
                'end_time' => $current_datetime,
            ]);
        } else {
            $end_datetime = $start_datetime->copy()->endOfDay();
            for ($i = 1; $i < $diff_in_days; $i++) {
                $datetime_between = $start_datetime->copy()->addDays($i);
                BreakTime::create([
                    'attendance_id' => $request->attendance_id,
                    'start_time' => $datetime_between->copy()->startOfDay(),
                    'end_time' => $datetime_between->endOfDay(),
                ]);
            }
            BreakTime::create([
                'attendance_id' => $request->attendance_id,
                'start_time' => $current_datetime->copy()->startOfDay(),
                'end_time' => $current_datetime,
            ]);
        }
        $current_break->update(['end_time' => $end_datetime]);
        session()->forget('break');
        return redirect('/');
    }

    // public function date()
    // {
    //     return view('date');
    // }
}
