<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
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
        $current_datetime = Carbon::now();
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $current_datetime->toDateString(),
            'start_time' => $current_datetime,
        ]);
        return redirect('/');
    }

    public function end(Request $request)
    {
        $current_datetime = Carbon::now();
        $attendance = Attendance::find($request->id);
        $start_datetime = Carbon::parse($attendance->start_time)->startOfDay();
        $end_datetime = Carbon::parse($attendance->start_time)->endOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        for ($i = 0; $i <= $diff_in_days; $i++) {
            if ($i === 0 && $i === $diff_in_days) {
                $attendance->update(['end_time' => $current_datetime]);
                return redirect('/');
            } elseif ($i === 0) {
                $attendance->update(['end_time' => $end_datetime]);
                continue;
            } elseif ($i === $diff_in_days) {
                $attendance = new Attendance;
                $attendance->fill([
                    'user_id' => Auth::id(),
                    'date' => $current_datetime->toDateString(),
                    'start_time' => $current_datetime->copy()->startOfDay(),
                    'end_time' => $current_datetime,
                ]);
            } else {
                $day_start = $start_datetime->copy()->addDays($i);
                $day_end = $end_datetime->copy()->addDays($i);
                $attendance = new Attendance;
                $attendance->fill([
                    'user_id' => Auth::id(),
                    'date' => $day_start->toDateString(),
                    'start_time' => $day_start,
                    'end_time' => $day_end,
                ]);
            }
            $attendance->save();
            $breaks = BreakTime::where('attendance_id', $request->id)->get();
            foreach ($breaks as $break) {
                if (Carbon::parse($break->start_time)->toDateString() === $attendance->date) {
                    $break->update(['attendance_id' => $attendance->id]);
                }
            }
        }
        return redirect('/');
    }

    public function breakStart(Request $request)
    {
        BreakTime::create([
            'attendance_id' => $request->attendance_id,
            'start_time' => Carbon::now(),
        ]);
        return redirect('/');
    }

    public function breakEnd(Request $request)
    {
        $current_datetime = Carbon::now();
        $break = BreakTime::find($request->id);
        $start_datetime = Carbon::parse($break->start_time)->startOfDay();
        $end_datetime = Carbon::parse($break->start_time)->endOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        for ($i = 0; $i <= $diff_in_days; $i++) {
            if ($i === 0 && $i === $diff_in_days) {
                $break->update(['end_time' => $current_datetime]);
                return redirect('/');
            } elseif ($i === 0) {
                $break->update(['end_time' => $end_datetime]);
                continue;
            } elseif ($i === $diff_in_days) {
                $break = new BreakTime;
                $break->fill([
                    'attendance_id' => $request->attendance_id,
                    'start_time' => $current_datetime->copy()->startOfDay(),
                    'end_time' => $current_datetime,
                ]);
            } else {
                $day_start = $start_datetime->copy()->addDays($i);
                $day_end = $end_datetime->copy()->addDays($i);
                $break = new BreakTime;
                $break->fill([
                    'attendance_id' => $request->attendance_id,
                    'start_time' => $day_start,
                    'end_time' => $day_end,
                ]);
            }
            $break->save();
        }
        return redirect('/');
    }

    public function date($key = "0")
    {
        $dates = Attendance::distinct()->select('date')->orderBy('date','desc')->pluck('date');
        $dates_count = count($dates);
        $selected_date = $dates[$key];
        $attendances = Attendance::where('date', $selected_date)->with('user', 'breakTimes')->orderBy('start_time', 'desc')->paginate(5);
        return view('date', compact('key', 'dates_count', 'selected_date', 'attendances'));
    }

    public function user($id)
    {
        $attendances = Attendance::where('user_id', $id)->with('breakTimes')->orderBy('start_time', 'desc')->paginate(5);
        $user_name = User::find($id)->name;
        return view('user', compact('attendances', 'user_name'));
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
