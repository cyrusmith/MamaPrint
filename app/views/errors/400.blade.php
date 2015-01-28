@extends('layouts.master')

@section('page_title')
    | Ошибка {{$error or ''}}
@stop

@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">
        <h2 class="text-warning">Ошибка при выполнении запроса</h2>

        <p>{{$error or ''}}</p>
    </div>
@stop