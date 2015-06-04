<?php
$data = Session::get('data');
$email = $data['email'];
?>
@extends('layouts.master')

@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">
        <p class="text-left">
            <a href="javascript:void(0);" class="btn btn-social btn-sm btn-vk" id="loginVk"><i class="fa fa-vk"></i> Войти через ВКонтакте</a>
        </p>
        <h3>Войти через email (рекомендуется):</h3>
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
                <button type="submit" class="btn btn-primary">Войти</button> Еще нет аккаунта? <a href="/register">Зарегистрироваться</a>
            </div>

            <p>Забыли пароль? <a href="/remindpassword">Восстановить пароль</a></p>
        </form>

    </div>
@stop