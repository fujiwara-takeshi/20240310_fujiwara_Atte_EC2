<nav>
    <ul class="pagination_date">
        {{-- Previous Page Link --}}
        @if ($date_key == 0)
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ route('attendance.date.show', ['date_key' => $date_key - 1]) }}"><</a>
            </li>
        @endif

        <li class="selected_date">{{ $selected_date }}</li>

        {{-- Next Page Link --}}
        @if ($date_key == $dates_count - 1)
            <li class="page-item disabled"  aria-disabled="true">
                <span class="page-link">></span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ route('attendance.date.show', ['date_key' => $date_key + 1]) }}">></a>
            </li>
        @endif
    </ul>
</nav>
