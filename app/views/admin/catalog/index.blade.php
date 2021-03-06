<?php
use Illuminate\Support\Facades\Lang;

?>

@extends('layouts.admin')

@section('pagetitle')
    Каталог
@stop

@section('content')

    <form action="{{action('Admin\AdminCatalogController@index')}}" class="form-inline">
        <div class="form-group ">
            <label class="control-label">Фильтр</label>
            <input type="text" class="form-control" name="search" value="{{$search}}">
        </div>
        <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span></button>
    </form>

    <form action="{{action('Admin\AdminCatalogController@postReorder')}}" method="post">
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Заголовок</th>
                <th>Цена</th>
                <th>Цена для залогиненых</th>
                <th>Возраст</th>
                <th>Активен</th>
                <th>Теги</th>
                <th>Вес <button type="submit" class="btn btn-xs"><span class="glyphicon glyphicon-ok"></span></button></th>
            </tr>
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
                    <td>{{$item->registered_price/100}}
                    </td>
                    <td>{{$item->info_age}}</td>
                    <td>
                        @if($item->active)
                            {{Lang::get('static.admin.catitem.active')}}
                        @else
                            {{Lang::get('static.admin.catitem.inactive')}}
                        @endif

                    </td>
                    <td>{{$item->getTagsAsString()}}</td>
                    <td>
                        <input type="text" size="3" value="{{$item->weight}}" name="weights[{{$item->id}}]"/>
                    </td>
                </tr>
            @endforeach
        </table>
    </form>
    <div class="text-center">
        {{$items->links()}}
    </div>

@stop
