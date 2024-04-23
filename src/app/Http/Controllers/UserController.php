<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceService;
use App\Services\UserService;

class UserController extends Controller
{
    private $attendanceService;
    private $userService;

    public function __construct(
        AttendanceService $attendanceService,
        UserService $userService
    ){
        $this->attendanceService = $attendanceService;
        $this->userService = $userService;
    }

    public function users(Request $request)
    {
        if ($request->has('keyword')) {
            $users = $this->userService->searchUsers($request->keyword);
            $users = $users->appends($request->input());
            return view('users', compact('users'));
        }
        $users = $this->userService->getUsers();
        return view('users', compact('users'));
    }

        public function user($user_id)
    {
        $attendances = $this->attendanceService->getAttendancesByUser($user_id);
        $user_name = $this->userService->getUserName($user_id);
        return view('user', compact('attendances', 'user_name'));
    }
}
