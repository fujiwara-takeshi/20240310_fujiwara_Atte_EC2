<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time'
    ];

    protected $dates = ['start_time', 'end_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }

    //ビューでattendanceインスタンスのbreak_timeプロパティを呼び出して表示するためのアクセサ
    public function getBreakTimeAttribute()
    {
        $break_seconds = $this->calculateBreakTimeSeconds();
        return gmdate('H:i:s', $break_seconds);
    }

    //attendanceインスタンスの休憩時間を計算する
    private function calculateBreakTimeSeconds()
    {
        $break_seconds = 0;
        foreach ($this->breakTimes as $break) {
            if ($break->end_time && $this->end_time) {
                $break_seconds += $break->end_time->diffInSeconds($break->start_time);
            }
        }
        return $break_seconds;
    }

    //ビューでattendanceインスタンスのworking_timeプロパティを呼び出して表示するためのアクセサ
    public function getWorkingTimeAttribute()
    {
        if ($this->end_time) {
            $total_seconds = $this->calculateWorkingTimeSeconds();
            return gmdate('H:i:s', $total_seconds);
        }
        return '';
    }

    //attendanceインスタンスの休憩時間を除いた勤務時間を計算する
    private function calculateWorkingTimeSeconds()
    {
        $break_seconds = Carbon::parse($this->break_time)->secondsSinceMidnight();
        $total_seconds = $this->end_time->diffInSeconds($this->start_time) - $break_seconds;
        return $total_seconds;
    }
}
