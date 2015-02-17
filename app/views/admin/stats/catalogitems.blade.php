@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <table class="table table-condensed">

        <tr>
            <th>Название</th>
            <th>Цена</th>
            <th>Кол-во покупок</th>
            <th>Сумма</th>
        </tr>

        @foreach($items as $item)

            <tr>
                <td>
                    <a href="{{action('Admin\AdminCatalogController@getItem', ['id'=>$item->id])}}">{{$item->title}}</a>
                </td>
                <td>
                    {{$item->price/100}}
                </td>
                <td>
                    {{$item->count}}
                </td>
                <td>
                    {{$item->sum/100}}
                </td>
            </tr>

        @endforeach

    </table>

    <div class="text-center">
        {{$items->links()}}
    </div>

@stop