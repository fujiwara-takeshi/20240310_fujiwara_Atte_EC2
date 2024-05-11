<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceService;
use App\Services\BreakTimeService;
use App\Services\UserService;

class BreakTimeController extends Controller
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

    //現在の勤務情報を引数に取り、休憩開始処理を実行する
    public function start(Request $request)
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
        $this->breakTimeService->start($attendance_id);
        return redirect()->route('attendance.index')->with('success', '休憩開始処理が完了しました');
    }

    //現在の勤務情報と休憩情報を引数に取り、休憩終了処理を実行する
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
        $break_id = $request->break_id;
        $break = $this->breakTimeService->getCurrentBreak($attendance);
        $verification_break = $this->breakTimeService->verifyBreak($break_id, $break); /* 休憩記録の整合性を検証 */
        if (!isset($verification_break['success'])) {
            return redirect()->route('attendance.index')->withErrors(['message' => $verification_break['error']]);
        }
        $this->breakTimeService->end($break, $attendance_id);
        return redirect()->route('attendance.index')->with('success', '休憩終了処理が完了しました');
    }
}
