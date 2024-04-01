@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('header-nav')
<nav class="header-nav">
    <ul class="header-nav__list">
        <li class="header-nav__item"><a href="/">ホーム</a></li>
        <li class="header-nav__item"><a href="/attendance">日付一覧</a></li>
        <li class="header-nav__item"><a href="/users">社員一覧</a></li>
        <li class="header-nav__item">
            <form action="/logout" method="post">
                @csrf
                <button class="header-nav__logout-button">ログアウト</button>
            </form>
        </li>
    </ul>
</nav>
@endsection

@section('content')
<div class="content">
    <div class="content__inner">
        <div class="content__wrapper">
            <div class="content__heading">
                <h2 class="heading__text">{{ $user['name'] }}さんお疲れ様です！</h2>
            </div>
            <div class="content__stamps">
                <div class="stamps__block">
                    <div class="stamp__box">
                        <form class="stamp__form" action="/start" method="post">
                            @csrf
                            @if($attendanceStatus)
                                <button class="stamp__button">勤務開始</button>
                            @else
                                <button class="stamp__button" disabled>勤務開始</button>
                            @endif
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="/end" method="post">
                            @csrf
                            @method('put')
                            @if($attendanceStatus || $breakStatus)
                                <button class="stamp__button" disabled>勤務終了</button>
                            @else
                                <input type="hidden" name="id" value="{{ $attendance['id'] }}">
                                <button class="stamp__button">勤務終了</button>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="stamps__block">
                    <div class="stamp__box">
                        <form class="stamp__form" action="/break-start" method="post">
                            @csrf
                            @if($attendanceStatus || $breakStatus)
                                <button class="stamp__button" disabled>休憩開始</button>
                            @else
                                <input type="hidden" name="attendance_id" value="{{ $attendance['id'] }}">
                                <button class="stamp__button">休憩開始</button>
                            @endif
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="/break-end" method="post">
                            @csrf
                            @method('put')
                            @if($breakStatus)
                                <input type="hidden" name="id" value="{{ $break['id'] }}">
                                <input type="hidden" name="attendance_id" value="{{ $attendance['id'] }}">
                                <button class="stamp__button">休憩終了</button>
                            @else
                                <button class="stamp__button" disabled>休憩終了</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection