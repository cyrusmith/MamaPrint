@extends('layouts.master')

@section('content')

    <div class="col-lg-8 col-lg-offset-2 user-area">
        <h3>Личный кабинет</h3>

        <ul class="nav nav-tabs">
            <li role="presentation" class="{{Request::is('user') ? 'active' : '' }}"><a href="/user"><span class="glyphicon glyphicon-shopping-cart"></span> Ваши покупки</a></li>
            <li role="presentation" class="{{Request::is('user/settings') ? 'active' : '' }}"><a href="/user/settings"><span class="glyphicon glyphicon-user"></span> Настройки</a></li>
        </ul>

        <div class="content">
            @yield("userarea_content")
        </div>

    </div>

@stop