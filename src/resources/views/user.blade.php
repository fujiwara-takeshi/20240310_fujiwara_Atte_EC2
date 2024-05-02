@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/bootstrap-4.css') }}">
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('header-nav')
    @include('components.header-nav')
@endsection

@section('content')
<div class="content">
    <div class="content__inner">
        <div class="content__wrapper">
            <div class="content__heading">
                <h2 class="heading__user-name">
                    {{ $selected_user->name }}
                </h2>
            </div>
            <div class="content__attendance-records">
                <table class="attendance__table">
                    <tr class="table__row">
                        <th>勤務日</th>
                        <th>勤務開始</th>
                        <th>勤務終了</th>
                        <th>休憩時間</th>
                        <th>勤務時間</th>
                    </tr>
                    @foreach($attendances as $attendance)
                        <tr class="table__row">
                            <td>{{ $attendance->date }}</td>
                            <td>{{ $attendance->start_time->format('H:i:s') }}</td>
                            <td>{{ $attendance->end_time ? $attendance->end_time->format('H:i:s') : '' }}</td>
                            <td>{{ $attendance->break_time }}</td>
                            <td>{{ $attendance->working_time }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="content__pagination">
                <div class="pagination-attendances">
                    {{ $attendances->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection