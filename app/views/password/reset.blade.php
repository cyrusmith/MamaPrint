@extends('layouts.master')

@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">

        <h2>Новый пароль</h2>

        <form action="{{ action('RemindersController@postReset') }}" method="post">

            <div class="form-group">
                <label for="email">Ваш email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>

            <div class="form-group">
                <label for="password">Новый пароль</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Повторить новый пароль</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Отправить</button>

            <input type="hidden" name="token" value="{{ $token }}">

        </form>
    </div>
@stop
