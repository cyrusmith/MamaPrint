<?php
$form = Session::get('form');
?>
@extends('layouts.master')

@section('page_title')
    | Регистрация на сайте
@stop


@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">
        <h2>Регистрация</h2>
        <form role="form" action="{{URL::action('AuthController@register')}}" method="post">
            <div class="form-group {{$errors->has('email')?'has-error':''}}">
                <label for="exampleInputEmail1" class="control-label">Емейл</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                       placeholder="example@mail.ru" value="{{$form['email'] or ''}}">
                @if($errors->has('email'))
                    <p class="text-danger">{{$errors->first('email')}}</p>
                @endif
            </div>
            <div class="form-group {{$errors->has('name')?'has-error':''}}">
                <label for="exampleInputName" class="control-label">Имя</label>
                <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Ваше имя"
                       value="{{$form['name'] or ''}}">
                @if($errors->has('name'))
                    <p class="text-danger">{{$errors->first('name')}}</p>
                @endif

            </div>
            <div class="form-group {{$errors->has('password')?'has-error':''}}">
                <label for="exampleInputPassword1" class="control-label">Пароль</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                       placeholder="Ваш пароль" value="{{$form['password'] or ''}}">
                @if($errors->has('password'))
                    <p class="text-danger">{{$errors->first('password')}}</p>
                @endif
            </div>
            <div class="form-group {{$errors->has('password2')?'has-error':''}}">
                <label for="exampleInputPassword2" class="control-label">Подтвердите пароль</label>
                <input type="password" name="password2" class="form-control" id="exampleInputPassword2"
                       placeholder="Пароль еще раз" value="{{$form['password2'] or ''}}">
                @if($errors->has('password2'))
                    <p class="text-danger">{{$errors->first('password2')}}</p>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
            <p>Уже зарегистрированы? <a href="/login">Войти</a></p>
        </form>

    </div>
@stop