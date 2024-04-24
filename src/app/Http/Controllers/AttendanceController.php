<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceService;
use App\Services\BreakTimeService;

class AttendanceController extends Controller
{
    private $attendanceService;
    private $breakTimeService;

    public function __construct(
        AttendanceService $attendanceService,
        BreakTimeService $breakTimeService
    ){
        $this->attendanceService = $attendanceService;
        $this->breakTimeService = $breakTimeService;
    }

    public function index()
    {
        $user = Auth::user();
        $attendance = $this->attendanceService->getCurrentAttendance($user->id);
        $break = $this->breakTimeService->getCurrentBreak($attendance);
        $attendance_status = $this->attendanceService->checkAttendanceStatus($attendance);
        $break_status = $this->breakTimeService->checkBreakStatus($break);
        return view('index', compact('user', 'attendance', 'break', 'attendance_status', 'break_status'));
    }

    public function start()
    {
        $this->attendanceService->start(Auth::id());
        return redirect()->route('attendance.index');
    }

    public function end(Request $request)
    {
        $attendance_id = $request->attendance_id;
        $user_id = Auth::id();
        $this->attendanceService->end($attendance_id, $user_id);
        return redirect()->route('attendance.index');
    }

    public function date($date_key = "0")
    {
        $dates = $this->attendanceService->getDistinctDates();
        $dates_count = count($dates);
        $selected_date = $dates[$date_key];
        $attendances = $this->attendanceService->getAttendancesByDate($selected_date);
        return view('date', compact('date_key', 'dates_count', 'selected_date', 'attendances'));
    }
}
