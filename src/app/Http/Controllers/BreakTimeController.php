<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BreakTimeService;

class BreakTimeController extends Controller
{
    private $breakTimeService;

    public function __construct(
        BreakTimeService $breakTimeService
    ){
        $this->breakTimeService = $breakTimeService;
    }

    public function start(Request $request)
    {
        $attendance_id = $request->attendance_id;
        $this->breakTimeService->start($attendance_id);
        return redirect()->route('attendance.index');
    }

    public function end(Request $request)
    {
        $break_id = $request->break_id;
        $attendance_id = $request->attendance_id;
        $this->breakTimeService->end($break_id, $attendance_id);
        return redirect()->route('attendance.index');
    }
}
