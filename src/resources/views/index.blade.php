@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
<div class="index">
    <div class="index__inner">
        <div class="index__wrapper">
            <div class="index__heading">
                <h2 class="heading__text">藤原健さんお疲れ様です！</h2>
            </div>
            <div class="index__stamps">
                <div class="stamps__block">
                    <div class="stamp__box">
                        <form class="stamp__form" action="" method="">
                            @csrf
                            <input type="hidden" name="">
                            <button class="stamp__button">勤務開始</button>
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="" method="">
                            @csrf
                            <input type="hidden" name="">
                            <button class="stamp__button">勤務終了</button>
                        </form>
                    </div>
                </div>
                <div class="stamps__block">
                    <div class="stamp__box">
                        <form class="stamp__form" action="" method="">
                            @csrf
                            <input type="hidden" name="">
                            <button class="stamp__button">休憩開始</button>
                        </form>
                    </div>
                    <div class="stamp__box">
                        <form class="stamp__form" action="" method="">
                            @csrf
                            <input type="hidden" name="">
                            <button class="stamp__button">休憩終了</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection