<?php

namespace App\Services;

use App\Repositories\BreakTimeRepository;
use Carbon\Carbon;

class BreakTimeService
{
    private $breakTimeRepository;

    public function __construct(
        BreakTimeRepository $breakTimeRepository
    ) {
        $this->breakTimeRepository = $breakTimeRepository;
    }

    //勤務情報を引数に取り、紐づけられた最新の休憩情報を返す
    public function getCurrentBreak($attendance)
    {
        return $attendance ? $attendance->breakTimes()->latest()->first() : null;
    }

    //打刻ボタンの活性非活性管理用の、休憩中である場合にTRUEを返すメソッド
    public function checkBreaking($break)
    {
        return $break && $break->start_time && !$break->end_time;
    }

    //勤務情報のidを引数に取り、紐づいた休憩の開始処理を実行する
    public function start($attendance_id)
    {
        // $current_datetime = Carbon::now();
        $current_datetime = Carbon::parse('2024-05-03 15:00:00');
        $data = [
            'attendance_id' => $attendance_id,
            'start_time' => $current_datetime,
        ];
        $this->breakTimeRepository->createBreak($data);
    }

    //休憩情報と勤務情報を引数に取り、休憩終了処理を実行する
    public function end($break, $attendance_id)
    {
        $current_datetime = Carbon::now();
        $current_datetime = Carbon::parse('2024-05-05 12:00:00');
        $start_datetime = Carbon::parse($break->start_time)->startOfDay();
        $diff_in_days = $current_datetime->copy()->startOfDay()->diffInDays($start_datetime);
        if ($diff_in_days === 0) { /* 休憩が１日内に収まる場合の休憩終了処理 */
            $this->recordSingleDayBreak($current_datetime, $break);
        } else { /* 休憩が複数日にまたがる場合の休憩終了処理 */
            $this->recordMultiDayBreak($current_datetime, $break, $attendance_id, $diff_in_days, $start_datetime);
        }
    }

    //休憩が１日内に収まる場合の休憩終了処理を実行する
    private function recordSingleDayBreak($current_datetime, $break)
    {
        $data = ['end_time' => $current_datetime];
        $this->breakTimeRepository->updateBreak($break, $data);
    }

    //休憩が複数日にまたがる場合の各日の休憩終了処理を実行する
    private function recordMultiDayBreak($current_datetime, $break, $attendance_id, $diff_in_days, $start_datetime)
    {
        $end_datetime = Carbon::parse($break->start_time)->endOfDay();
        for ($i = 0; $i <= $diff_in_days; $i++) {
            if ($i === 0) { /* 初日分の休憩終了処理 */
                $this->recordFirstDayBreak($end_datetime, $break);
            } elseif ($i === $diff_in_days) { /* 最終日分の休憩終了処理 */
                $this->recordLastDayBreak($attendance_id, $current_datetime);
            } else { /* 上記以外の日分の休憩終了処理 */
                $this->recordOtherDayBreak($attendance_id, $start_datetime, $end_datetime, $i);
            }
        }
    }

    //複数日にまたがる休憩の、初日分の休憩情報更新を行う
    private function recordFirstDayBreak($end_datetime, $break)
    {
        $data = ['end_time' => $end_datetime];
        $this->breakTimeRepository->updateBreak($break, $data);
    }

    //複数日にまたがる休憩の、最終日分の休憩情報作成を行う
    private function recordLastDayBreak($attendance_id, $current_datetime)
    {
        $data = [
            'attendance_id' => $attendance_id,
            'start_time' => $current_datetime->copy()->startOfDay(),
            'end_time' => $current_datetime,
        ];
        $this->breakTimeRepository->createBreak($data);
    }

    //複数日にまたがる休憩の、初日・最終日以外の日分の休憩情報作成を行う
    private function recordOtherDayBreak($attendance_id, $start_datetime, $end_datetime, $i)
    {
        $day_start = $start_datetime->copy()->addDays($i);
        $day_end = $end_datetime->copy()->addDays($i);
        $data = [
            'attendance_id' => $attendance_id,
            'start_time' => $day_start,
            'end_time' => $day_end,
        ];
        $this->breakTimeRepository->createBreak($data);
    }

    //複数日の勤務の２日目以降分の休憩を各日の勤務情報と紐づける処理
    public function updateBreaksByAttendance($attendance_id, $attendance)
    {
        $breaks = $this->breakTimeRepository->getBreaksByAttendance($attendance_id);
        foreach ($breaks as $break) {
            if (Carbon::parse($break->start_time)->toDateString() === $attendance->date) {
                $data = ['attendance_id' => $attendance->id];
                $this->breakTimeRepository->updateBreak($break, $data);
            }
        }
    }

    //フォームリクエストのbreak_idと現在の休憩情報を照合する
    public function verifyBreak($break_id, $break)
    {
        if ($break === null || $break_id != $break->id) {
            return ['error' => '正しい休憩記録を参照できませんでした'];
        }
        return ['success' => true];
    }
}

