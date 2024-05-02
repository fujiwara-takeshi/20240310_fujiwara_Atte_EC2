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
    ) {
        $this->attendanceService = $attendanceService;
        $this->userService = $userService;
    }

    //検索キーワードを引数として受け取り、該当するユーザー情報を送り、ユーザー一覧ページを表示する
    public function users(Request $request)
    {
        if ($request->has('keyword')) { /* リクエストに検索キーワードがある場合、ユーザー検索を行う */
            $users = $this->userService->searchUsers($request->keyword);
            $users = $users->appends($request->input());
            return view('users', compact('users'));
        }
        $users = $this->userService->getUsers(); /* 検索キーワードがない場合、すべてのユーザー情報を送る */
        return view('users', compact('users'));
    }

    //特定のユーザーidを引数に取り、そのユーザーの勤務情報を表示する
    public function user($user_id)
    {
        $selected_user = $this->userService->getUser($user_id);
        $verification_user = $this->userService->verifySelectedUser($user_id, $selected_user);
        if (!isset($verification_user['success'])) {
            return redirect()->route('users.show')->withErrors($verification_user['error']);
        }
        $attendances = $this->attendanceService->getAttendancesByUser($user_id);
        return view('user', compact('selected_user', 'attendances'));
    }
}
