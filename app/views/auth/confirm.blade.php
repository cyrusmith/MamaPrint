@extends('layouts.master')

@section('page_title')
    | Подтверждение регистрации
@stop


@section('content')
    <h2>Подтверждение регистрации</h2>
    @if($error)
        <p class="">{{$error}}</p>
    @endif
@stop