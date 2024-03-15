<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;

class AuthController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function date()
    {
        return view('date');
    }
}
