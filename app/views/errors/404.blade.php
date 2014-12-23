<?php
$data = Session::get('data');
$email = $data['email'];
$error = $data['error'];
?>
@extends('layouts.master')

@section('page_title')
    | Ошибка {{$error or ''}}
@stop

@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">
        <h2>{{$error or 'Ошибка!'}}}</h2>
    </div>
@stop