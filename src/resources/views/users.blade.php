@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/bootstrap-4.css') }}">
<link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endsection

@section('header-nav')
<nav class="header-nav">
    <ul class="header-nav__list">
        <li class="header-nav__item"><a href="/">ホーム</a></li>
        <li class="header-nav__item"><a href="/attendance">日付一覧</a></li>
        <li class="header-nav__item"><a href="/users">社員一覧</a></li>
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
            <div class="content__heading-search">
                <form class="search-form" action="/users/search" method="get">
                    @csrf
                    <input class="search-form__item-input" type="text" name="keyword" placeholder="名前・メールアドレスで検索">
                    <button class="search-form__item-button" type="submit">検索</button>
                </form>
            </div>
            <div class="content__user-list">
                <table class="users__table">
                    <tr class="table__row">
                        <th>名前</th>
                        <th>メールアドレス</th>
                    </tr>
                    @foreach($users as $user)
                        <tr class="table__row">
                            <td><a href="/users/{{$user->id}}">{{ $user->name }}</a></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="content__bottom-pagination">
                <div class="pagination-users">
                    {{ $users->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection