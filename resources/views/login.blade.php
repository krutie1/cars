@extends('layout.layout')

@section('title', 'Вход')

@section('content')
    <section class="login">
        <form class="form-signin">
            <h3 class="form-signin-heading">Вход в приложение</h3>
            <hr>
            <input type="tel" id="inputPhone" name="phone" class="form-control" placeholder="Номер телефона"
                   required="true" autofocus="" pattern="8[0-9]{10}">
            <input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required="true">
            <button class="btn btn-md btn-primary btn-block" type="submit">Войти</button>
        </form>
    </section>
@endsection
