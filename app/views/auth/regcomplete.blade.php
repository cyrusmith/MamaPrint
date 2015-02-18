@define $emailLink = Session::get('emailLink')
@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <h2>Регистрация почти завершена</h2>

            <p class="alert alert-warning">
                @if(!empty($emailLink))
                    <a href="{{$emailLink}}" target="_blank">
                        @endif
                        Проверьте ваш email
                        @if(!empty($emailLink))
                    </a>
                @endif и перейдите по ссылке в письме для подтверждения
                регистрации.</p>
        </div>
    </div>
@stop