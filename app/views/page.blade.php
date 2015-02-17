@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2 payconfirm">
        {{$article->content or ''}}
    </div>

@stop