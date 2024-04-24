<nav class="header-nav">
    <ul class="header-nav__list">
        <li class="header-nav__item">
            <a href="{{ route('attendance.index') }}">ホーム</a>
        </li>
        <li class="header-nav__item">
            <a href="{{ route('attendance.date.show') }}">日付一覧</a>
        </li>
        <li class="header-nav__item">
            <a href="{{ route('users.show') }}">社員一覧</a>
        </li>
        <li class="header-nav__item">
            <form action="/logout" method="post">
                @csrf
                <button class="header-nav__logout-button">ログアウト</button>
            </form>
        </li>
    </ul>
</nav>