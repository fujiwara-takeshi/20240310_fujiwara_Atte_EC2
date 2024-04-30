<?php
namespace App\Services;

use App\Repositories\AttendanceRepository;
use App\Services\BreakTimeService;
use Carbon\Carbon;

class AttendanceService
{
    private $attendanceRepository;
    private $breakTimeService;

    public function __construct(
        AttendanceRepository $attendanceRepository,
        BreakTimeService $breakTimeService
    ) {
        $this->attendanceRepository = $attendanceRepository;
        $this->breakTimeService = $breakTimeService;
    }

    //ログイン中のユーザー情報を引数に取り、ユーザーの現在の勤務情報を返す
    public function getCurrentAttendance($user_id)
    {
        return $this->attendanceRepository->getLatestAttendanceByUser($user_id);
    }

    //打刻ボタンの活性非活性管理用の、勤務中でない場合にTRUEを返すメソッド
    public function checkNotWorking($attendance)
    {
        return ($attendance && $attendance->end_time) || !$attendance;
    }

    //ログイン中のユーザー情報を引数に取り、勤務開始処理を実行する
    public function start($user_id)
    {
        $current_datetime = Carbon::now();
        $data = [
            'user_id' => $user_id,
            'date' => $current_datetime->toDateString(),
            'start_time' => $current_datetime,
        ];
        $this->attendanceRepository->createAttendance($data);
    }

    //ログイン中のユーザー情報と現在の勤務情報を引数に取り、勤務終了処理を実行する
    public function end($attendance, $user_id)
    {
        $current_datetime = Carbon::now();
        $start_datetime = Carbon::parse($attendance->start_time)->startOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        if ($diff_in_days === 0) { /* 勤務が１日のみの場合の勤務終了処理 */
            $this->recordSingleDayAttendance($current_datetime, $attendance);
        } else { /* 勤務が複数日の場合の勤務終了処理 */
            $this->recordMultiDayAttendance($current_datetime, $attendance, $diff_in_days, $start_datetime, $user_id);
        }
    }

    //勤務が１日のみの場合の勤務終了処理を実行する
    private function recordSingleDayAttendance($current_datetime, $attendance)
    {
        $data = ['end_time' => $current_datetime];
        $this->attendanceRepository->updateAttendance($attendance, $data);
    }

    //勤務が複数日の場合の各日の勤務終了処理、２日目以降分の休憩情報の紐づけ処理を実行する
    private function recordMultiDayAttendance($current_datetime, $attendance, $diff_in_days, $start_datetime, $user_id)
    {
        $attendance_id = $attendance->id;
        $end_datetime = Carbon::parse($attendance->start_time)->endOfDay();
        for ($i = 0; $i <= $diff_in_days; $i++) {
            if ($i === 0) { /* 初日分の勤務終了処理 */
                $this->recordFirstDayAttendance($end_datetime, $attendance);
            } elseif ($i === $diff_in_days) { /* 最終日分の勤務終了処理、休憩の紐づけ処理 */
                $attendance = $this->recordLastDayAttendance($user_id, $current_datetime);
                $this->breakTimeService->updateBreaksByAttendance($attendance_id, $attendance);
            } else { /* 上記以外の日分の勤務終了処理、休憩の紐づけ処理 */
                $attendance = $this->recordOtherDayAttendance($user_id, $start_datetime, $end_datetime, $i);
                $this->breakTimeService->updateBreaksByAttendance($attendance_id, $attendance);
            }
        }
    }

    //複数日勤務の初日分の勤務情報更新を行う
    private function recordFirstDayAttendance($end_datetime, $attendance)
    {
        $data = ['end_time' => $end_datetime];
        $this->attendanceRepository->updateAttendance($attendance, $data);
    }

    //複数日勤務の最終日分の勤務情報作成を行う
    private function recordLastDayAttendance($user_id, $current_datetime)
    {
        $data = [
            'user_id' => $user_id,
            'date' => $current_datetime->toDateString(),
            'start_time' => $current_datetime->copy()->startOfDay(),
            'end_time' => $current_datetime,
        ];
        return $this->attendanceRepository->createAttendance($data);
    }

    //複数日勤務の初日・最終日以外の日分の勤務情報作成を行う
    private function recordOtherDayAttendance($user_id, $start_datetime, $end_datetime, $i)
    {
        $day_start = $start_datetime->copy()->addDays($i);
        $day_end = $end_datetime->copy()->addDays($i);
        $data = [
            'user_id' => $user_id,
            'date' => $day_start->toDateString(),
            'start_time' => $day_start,
            'end_time' => $day_end,
        ];
        return $this->attendanceRepository->createAttendance($data);
    }

    //dateビュー表示のための、重複しない日付の配列を返す
    public function getDistinctDates()
    {
        return $this->attendanceRepository->getDistinctDatesList();
    }

    //dateビューで表示する日付分の勤務情報のページネーションインスタンスを返す
    public function getAttendancesByDate($date)
    {
        return $this->attendanceRepository->paginateAttendancesByDate($date);
    }

    //Userビューで表示するユーザー分の勤務情報のページネーションインスタンスを返す
    public function getAttendancesByUser($user_id)
    {
        return $this->attendanceRepository->paginateAttendancesByUser($user_id);
    }

    //フォームリクエストのattendance_idと現在の勤務情報を照合する
    public function verifyAttendance($attendance_id, $attendance)
    {
        if ($attendance === null || $attendance_id != $attendance->id) {
            return ['error' => '正しい勤務記録を参照できませんでした'];
        }
        return ['success' => true];
    }

    //重複しない日付の配列に対し、$date_keyが有効な整数かを検証する
    public function verifyDateKey($date_key, $dates)
    {
        if (!ctype_digit($date_key) || !isset($dates[$date_key])) {
            return ['error' => '指定された日付が無効です'];
        }
        return ['success' => true];
    }
}