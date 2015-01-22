@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2">
        <h3>Item</h3>
        {{$item->toJson()}}
    </div>


@stop