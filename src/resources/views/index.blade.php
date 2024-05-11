@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('header-nav')
    @include('components.header-nav')
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
                        <form class="stamp__form" action="{{ route('attendance.start') }}" method="post">
                            @csrf
                            @if($attendance_status)
                                <button class="stamp__button">勤務開始</button>
                            @else
                                <button class="stamp__button" disabled>勤務開始</button>
                            @endif
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="{{ route('attendance.end') }}" method="post">
                            @csrf
                            @method('patch')
                            @if($attendance_status || $break_status)
                                <button class="stamp__button" disabled>勤務終了</button>
                            @else
                                <input type="hidden" name="attendance_id" value="{{ $attendance['id'] }}">
                                <button class="stamp__button">勤務終了</button>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="stamps__block">
                    <div class="stamp__box">
                        <form class="stamp__form" action="{{ route('break.start') }}" method="post">
                            @csrf
                            @if($attendance_status || $break_status)
                                <button class="stamp__button" disabled>休憩開始</button>
                            @else
                                <input type="hidden" name="attendance_id" value="{{ $attendance['id'] }}">
                                <button class="stamp__button">休憩開始</button>
                            @endif
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="{{ route('break.end') }}" method="post">
                            @csrf
                            @method('patch')
                            @if($break_status)
                                <input type="hidden" name="break_id" value="{{ $break['id'] }}">
                                <input type="hidden" name="attendance_id" value="{{ $attendance['id'] }}">
                                <button class="stamp__button">休憩終了</button>
                            @else
                                <button class="stamp__button" disabled>休憩終了</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="content__alert">
                @if (session('success'))
                    <div class="alert alert--success">
                        {{ session('success') }}
                    </div>
                @elseif ($errors->any())
                    <div class="alert alert--danger">
                        {{ $errors->first() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection