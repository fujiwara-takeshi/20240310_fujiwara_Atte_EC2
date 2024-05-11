@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content__inner">
        <div class="content__wrapper">
            <div class="content__heading">
                <h2 class="heading__title">会員登録</h2>
            </div>
            <div class="content__register-form">
                <form class="form" action="/register" method="post">
                    @csrf
                    <div class="form__item">
                        <input class="form__item-input" type="text" name="name" value="{{ old('name') }}" placeholder="名前">
                        <div class="form__item-error">
                            @error('name')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
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
                        <input class="form__item-input" type="text" name="password_confirmation" placeholder="確認用パスワード">
                        <div class="form__item-error">
                            @error('password_confirmation')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form__item">
                        <button class="form__item-button" type="submit">会員登録</button>
                    </div>
                </form>
            </div>
            <div class="content__link">
                <p class="link__information">アカウントをお持ちの方はこちらから</p>
                <a class="link__login" href="/login">ログイン</a>
            </div>
        </div>
    </div>
</div>
@endsection