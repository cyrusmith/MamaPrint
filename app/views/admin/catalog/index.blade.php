<?php
use Illuminate\Support\Facades\Lang;
?>

@extends('layouts.admin')

@section('pagetitle')
    Каталог
@stop

@section('content')


    @if($items->isEmpty())
        <h3>Пока ни одного товара не добавлено</h3>
    @else
        <table class="table">
            @foreach($items as $item)
                <tr>
                    <td>
                        # {{$item->id}}
                    </td>
                    <td><a href="{{URL::action('Admin\AdminCatalogController@getItem', [
                        'id' => $item->id
                    ])}}">{{$item->title}}</a></td>
                    <td>{{$item->price/100}}
                        @if($item->old_price > 0)
                            <strike>{{$item->old_price/100}}</strike>
                        @endif</td>
                    <td>
                        @if($item->active)
                            {{Lang::get('static.admin.catitem.active')}}
                        @else
                            {{Lang::get('static.admin.catitem.inactive')}}
                        @endif

                    </td>
                    <td>{{$item->getTagsAsString()}}</td>
                </tr>
            @endforeach
        </table>
    @endif

@stop
