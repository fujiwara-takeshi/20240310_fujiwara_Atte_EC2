<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\User;

class UserController extends Controller
{
    public function users()
    {
        $users = User::paginate(5);
        return view('users', compact('users'));
    }

    public function search(Request $request)
    {
        $users = User::KeywordSearch($request->keyword)->paginate(5)->appends($request->input());
        return view('users', compact('users'));
    }
}
