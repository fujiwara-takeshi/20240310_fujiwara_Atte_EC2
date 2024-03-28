@extends('layouts.app')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('header-nav')
<nav class="header-nav">
    <ul class="header-nav__list">
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
<div class="content">
    <div class="content__inner">
        <div class="content__wrapper">
            <div class="content__heading-pagination">
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
                            <li class="page-item disabled"  aria-disabled="true">
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
            <div class="content__attendance-records">
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
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->start_time->format('H:i:s') }}</td>
                            <td>{{ $attendance->end_time ? $attendance->end_time->format('H:i:s') : '' }}</td>
                            <td>{{ $attendance->break_time }}</td>
                            <td>{{ $attendance->working_time }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="content__bottom-pagination">
                <div class="pagination-attendances">
                    {{ $attendances->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection