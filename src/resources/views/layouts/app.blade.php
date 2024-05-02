<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__inner-left">
                <div class="header__logo">
                    <a class="header__logo-link"href="{{ route('attendance.index') }}">Atte</a>
                </div>
            </div>
            <div class="header__inner-right">
                @yield('header-nav')
            </div>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer__inner">
            <small class="footer__copy">Atte, inc.</small>
        </div>
    </footer>
</body>
</html>