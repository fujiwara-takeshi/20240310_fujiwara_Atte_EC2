@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login">
    <div class="login__inner">
        <div class="login__wrapper">
            <div class="login__heading">
                <h2 class="heading__title">ログイン</h2>
            </div>
            <div class="login__form">
                <form class="form" action="" method="">
                    @csrf
                    <div class="form__item">
                        <input class="form__item-input" type="text" name="email" placeholder="メールアドレス">
                        <div class="form__item-error">

                        </div>
                    </div>
                    <div class="form__item">
                        <input class="form__item-input" type="text" name="password" placeholder="パスワード">
                        <div class="form__item-error">

                        </div>
                    </div>
                    <div class="form__item">
                        <button class="form__item-button" type="submit">ログイン</button>
                    </div>
                </form>
            </div>
            <div class="login__link">
                <p class="link__information-text">アカウントをお持ちでない方はこちらから</p>
                <a class="link__register" href="/register">会員登録</a>
            </div>
        </div>
    </div>
</div>
@endsection