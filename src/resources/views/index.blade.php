@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('header__nav')
<nav class="header__nav">
    <ul class="header__nav-list">
        <li class="header__nav-item"><a href="/">ホーム</a></li>
        <li class="header__nav-item"><a href="/attendance">日付一覧</a></li>
        <li class="header__nav-item">
            <form action="/logout" method="post">
                @csrf
                <button class="header-nav__logout-button">ログアウト</button>
            </form>
        </li>
    </ul>
</nav>
@endsection

@section('content')
<div class="index">
    <div class="index__inner">
        <div class="index__wrapper">
            <div class="index__heading">
                <h2 class="heading__text">{{ $user['name'] }}さんお疲れ様です！</h2>
            </div>
            <div class="index__stamps">
                <div class="stamps__block">
                    <div class="stamp__box">
                        <form class="stamp__form" action="/start" method="get">
                            @csrf
                            @if(session('attendance'))
                            <button class="stamp__button" disabled style="cursor: not-allowed;">勤務開始</button>
                            @else
                            <button class="stamp__button">勤務開始</button>
                            @endif
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="/end" method="get">
                            @csrf
                            @if(!session('attendance') || session('break'))
                            <button class="stamp__button" disabled style="cursor: not-allowed;">勤務終了</button>
                            @else
                            <input type="hidden" name="id" value="{{ session('attendance.id') }}">
                            <button class="stamp__button">勤務終了</button>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="stamps__block">
                    <div class="stamp__box">
                        <form class="stamp__form" action="/break-start" method="get">
                            @csrf
                            @if(!session('attendance') || session('break'))
                            <button class="stamp__button" disabled style="cursor: not-allowed;">休憩開始</button>
                            @else
                            <input type="hidden" name="attendance_id" value="{{ session('attendance.id') }}">
                            <button class="stamp__button">休憩開始</button>
                            @endif
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="/break-end" method="get">
                            @csrf
                            @if(session('break'))
                            <input type="hidden" name="id" value="{{ session('break.id') }}">
                            <input type="hidden" name="attendance_id" value="{{ session('attendance.id') }}">
                            <button class="stamp__button">休憩終了</button>
                            @else
                            <button class="stamp__button" disabled style="cursor: not-allowed;">休憩終了</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection