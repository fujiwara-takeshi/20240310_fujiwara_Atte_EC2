<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceService;
use App\Services\BreakTimeService;
use App\Services\UserService;

class AttendanceController extends Controller
{
    private $attendanceService;
    private $breakTimeService;
    private $userService;

    public function __construct(
        AttendanceService $attendanceService,
        BreakTimeService $breakTimeService,
        UserService $userService
    ) {
        $this->attendanceService = $attendanceService;
        $this->breakTimeService = $breakTimeService;
        $this->userService = $userService;
    }

    //ユーザー情報・勤務情報・勤務状態を送り、indexビューを表示する
    public function index()
    {
        $user = Auth::user();
        $attendance = $this->attendanceService->getCurrentAttendance($user->id);
        $break = $this->breakTimeService->getCurrentBreak($attendance);
        $attendance_status = $this->attendanceService->checkNotWorking($attendance);
        $break_status = $this->breakTimeService->checkBreaking($break);
        return view('index', compact('user', 'attendance', 'break', 'attendance_status', 'break_status'));
    }

    //勤務開始処理を実行する
    public function start()
    {
        $user_id = Auth::id();
        $verification_user = $this->userService->verifyUser($user_id); /*有効なユーザーのログインを検証*/
        if (!isset($verification_user['success'])) {
            return redirect()->route('attendance.index')->withErrors(['message' => $verification_user['error']]);
        }
        $this->attendanceService->start($user_id);
        return redirect()->route('attendance.index')->with('success', '勤務開始処理が完了しました');
    }

    //現在の勤務情報を引数に取り、勤務終了処理を実行する
    public function end(Request $request)
    {
        $user_id = Auth::id();
        $verification_user = $this->userService->verifyUser($user_id); /*有効なユーザーのログインを検証*/
        if (!isset($verification_user['success'])) {
            return redirect()->route('attendance.index')->withErrors(['message' => $verification_user['error']]);
        }
        $attendance_id = $request->attendance_id;
        $attendance = $this->attendanceService->getCurrentAttendance($user_id);
        $verification_attendance = $this->attendanceService->verifyAttendance($attendance_id, $attendance); /* 勤務記録の整合性を検証 */
        if (!isset($verification_attendance['success'])) {
            return redirect()->route('attendance.index')->withErrors(['message' => $verification_attendance['error']]);
        }
        $this->attendanceService->end($attendance, $user_id);
        return redirect()->route('attendance.index')->with('success', '勤務終了処理が完了しました');
    }

    //整数のkeyを引数に取り、指定の日付の勤怠記録を表示する
    public function date($date_key = "0")
    {
        $dates = $this->attendanceService->getDistinctDates();
        $verification_date_key = $this->attendanceService->verifyDateKey($date_key, $dates);
        if (!isset($verification_date_key['success'])) { /* $datesに対し$date_keyが有効な整数かを検証 */
            return redirect()->back()->withErrors(['message' => $verification_date_key['error']]);
        }
        $selected_date = $dates[$date_key];
        $dates_count = count($dates);
        $attendances = $this->attendanceService->getAttendancesByDate($selected_date);
        return view('date', compact('date_key', 'selected_date', 'dates_count', 'attendances'));
    }
}
