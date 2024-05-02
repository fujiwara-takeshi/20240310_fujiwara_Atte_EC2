@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content__inner">
        <div class="content__wrapper">
            <div class="content__heading">
                <h2 class="heading__title">認証メールが送信されました！</h2>
                <p class="heading__text">メールのリンクを開いて認証を完了してください</p>
            </div>
            <div class="content__retransmission-form">
                <form class="form" action="/email/verification-notification" method="post">
                    @csrf
                    <p class="form__information">認証メールの再送信はこちらから</p>
                    <button class="form__item-button" type="submit">送信</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection