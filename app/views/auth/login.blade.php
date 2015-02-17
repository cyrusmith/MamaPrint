<?php
$data = Session::get('data');
$email = $data['email'];
$error = $data['error'];
?>
@extends('layouts.master')

@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">
        <h2>Войти</h2>
        @if(!empty($error))

            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                {{$error}}
            </div>
        @endif
        <form role="form" action="{{URL::action('AuthController@login')}}" method="post">
            <div class="form-group">
                <label for="exampleInputEmail1" class="control-label">Емейл</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                       placeholder="example@mail.ru" value="{{$email or ''}}">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1" class="control-label">Пароль</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                       placeholder="Ваш пароль" value="{{$form['password'] or ''}}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Отправить</button>
            </div>
            <p>Еще нет аккаунта? <a href="/register">Зарегистрироваться</a></p>

            <p>Забыли пароль? <a href="/remindpassword">Восстановить пароль</a></p>
        </form>

    </div>
@stop