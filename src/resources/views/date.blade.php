@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/date.css') }}">
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
<div class="date">
    <div class="date__inner">
        <div class="date__wrapper">
            <div class="date__heading">
                <div class="heading__paginate-date">2021-11-01</div>
            </div>
            <div class="date__attendance-records">
                <table class="attendance__table">
                    <tr class="table__row">
                        <th>名前</th>
                        <th>勤務開始</th>
                        <th>勤務終了</th>
                        <th>休憩時間</th>
                        <th>勤務時間</th>
                    </tr>
                    <tr class="table__row">
                        @foreach($attendances as $attendance)
                            <td>{{ $attendance['user']['name'] }}</td>
                            <td>{{ $attendance['start_time']->format('H:i:s') }}</td>
                            @if($attendance['end_time'])
                                <td>{{ $attendance['end_time']->format('H:i:s') }}</td>
                            @else
                                <td></td>
                            @endif
                            <td>
                                @php
                                    $break_time = Carbon::createFromTime(0, 0, 0);
                                    foreach ($attendance['breakTimes'] as $break) {
                                        if ($break['end_time']) {
                                            $seconds = $break['end_time']->diffInSeconds($break['start_time']);
                                            $break_time->addSeconds($seconds);
                                        }
                                    }
                                    echo $break_time->format('H:i:s');
                                @endphp
                            </td>
                            @if($attendance['end_time'])
                                @php
                                    $total_seconds = $attendance['end_time']->diffInSeconds($attendance['start_time']) - $break_time->seconds;
                                    $formatted_time = Carbon::createFromTime(0, 0, 0)->addSeconds($total_seconds)->format('H:i:s');
                                @endphp
                                <td>{{ $formatted_time }}</td>
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                </table>
            </div>
            <div class="date__bottom">
                <div class="bottom__paginate-attendance">
                    1 2 3 4 5 6 7 8 9
                </div>
            </div>
        </div>
    </div>
</div>
@endsection