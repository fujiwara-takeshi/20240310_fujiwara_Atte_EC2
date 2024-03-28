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

    public function getBreakTimeAttribute()
    {
        $break_seconds = 0;
        foreach ($this->breakTimes as $break) {
            if ($break->end_time) {
                $break_seconds += $break->end_time->diffInSeconds($break->start_time);
            }
        }
        return gmdate('H:i:s', $break_seconds);
    }

    public function getWorkingTimeAttribute()
    {
        if ($this->end_time) {
            $break_seconds = Carbon::parse($this->break_time)->secondsSinceMidnight();
            $total_seconds = $this->end_time->diffInSeconds($this->start_time) - $break_seconds;
            return gmdate('H:i:s', $total_seconds);
        }
        return '';
    }
}
