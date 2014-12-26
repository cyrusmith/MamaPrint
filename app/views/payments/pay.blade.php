@extends('layouts.master')

@section('content')

    {{$items->first()->catalog_item}}

@stop