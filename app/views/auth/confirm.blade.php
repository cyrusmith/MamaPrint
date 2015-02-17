@extends('layouts.master')


@section('content')
    <h2>Подтверждение регистрации</h2>
    @if($error)
        <p class="">{{$error}}</p>
    @endif
@stop