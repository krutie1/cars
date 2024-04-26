@extends('layout.layout')

@section('title', 'Вход')

@section('content')
    <section class="login">
        <form class="form-signin" method="POST" action="{{ route('auth.authenticate') }}">
            <img class="bi logo-img mb-3" src="{{ asset('assets/imgs/logo.jpg') }}" alt="Dido Cars">
            <h3 class="form-signin-heading">Вход в приложение</h3>
            <hr>
            <input type="tel" id="inputPhone" name="phone_number" class="form-control" placeholder="Номер телефона"
                   required autofocus pattern="8[0-9]{10}">
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Пароль"
                   required>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <button class="btn btn-md btn-primary btn-block" type="submit">Войти</button>
        </form>

    </section>
@endsection
