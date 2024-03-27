@extends('layouts.app')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('header__nav')
<nav class="header__nav">
    <ul class="header__nav-list">
        <li class="header-nav__item"><a href="/">ホーム</a></li>
        <li class="header-nav__item"><a href="/attendance">日付一覧</a></li>
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
<div class="date">
    <div class="date__inner">
        <div class="date__wrapper">
            <div class="date__heading-pagination">
                <nav>
                    <ul class="pagination_date">
                        {{-- Previous Page Link --}}
                        @if ($key == 0)
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link">＜</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="/attendance/{{$key - 1}}">＜</a>
                            </li>
                        @endif

                        <li class="selected_date">{{ $selected_date }}</li>

                        {{-- Next Page Link --}}
                        @if ($key == $dates_count - 1)
                            <li class="page-item disabled" disabled aria-disabled="true">
                                <span class="page-link">＞</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="/attendance/{{$key + 1}}">＞</a>
                            </li>
                        @endif
                    </ul>
                </nav>
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
                    @foreach($attendances as $attendance)
                        <tr class="table__row">
                            <td>{{ $attendance['user']['name'] }}</td>
                            <td>{{ $attendance['start_time']->format('H:i:s') }}</td>
                            @if($attendance['end_time'])
                                <td>{{ $attendance['end_time']->format('H:i:s') }}</td>
                            @else
                                <td></td>
                            @endif
                            <td>
                                @php
                                    $break_time = Carbon\Carbon::createFromTime(0, 0, 0);
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
                                    $total_seconds = $attendance['end_time']->diffInSeconds($attendance['start_time']) - $break_time->secondsSinceMidnight();
                                    $formatted_time = Carbon\Carbon::createFromTime(0, 0, 0)->addSeconds($total_seconds)->format('H:i:s');
                                @endphp
                                <td>{{ $formatted_time }}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="date__bottom-pagination">
                <div class="pagination-attendances">
                    {{ $attendances->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection