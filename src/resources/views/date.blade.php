@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/bootstrap-4.css') }}">
<link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('header-nav')
    @include('components.header-nav')
@endsection

@section('content')
<div class="content">
    <div class="content__inner">
        <div class="content__wrapper">
            <div class="content__heading-pagination">
                @include('components.custom-pagination', ['date_key' => $date_key, 'selected_date' => $selected_date, 'dates_count' => $dates_count])
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