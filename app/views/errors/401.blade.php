@extends('layouts.master')

@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">
        <h2 class="text-warning">Ошибка доступа</h2>

        <p>{{$error or ''}}</p>
    </div>
@stop