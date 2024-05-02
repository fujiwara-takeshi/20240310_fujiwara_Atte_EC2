@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content__inner">
        <div class="content__wrapper">
            <div class="content__heading">
                <h2 class="heading__title">ログイン</h2>
            </div>
            <div class="content__login-form">
                <form class="form" action="/login" method="post">
                    @csrf
                    <div class="form__item">
                        <input class="form__item-input" type="text" name="email" value="{{ old('email') }}" placeholder="メールアドレス">
                        <div class="form__item-error">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form__item">
                        <input class="form__item-input" type="text" name="password" placeholder="パスワード">
                        <div class="form__item-error">
                            @error('password')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form__item">
                        <button class="form__item-button" type="submit">ログイン</button>
                    </div>
                </form>
            </div>
            <div class="content__link">
                <p class="link__information">アカウントをお持ちでない方はこちらから</p>
                <a class="link__register" href="/register">会員登録</a>
            </div>
        </div>
    </div>
</div>
@endsection