@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <table class="table table-condensed">

        <tr>
            <th>Название</th>
            <th>Тэги</th>
            <th>Цена</th>
            <th>Цена для зарегиных</th>
            <th>Кол-во покупок</th>
            <th>Сумма по товару</th>
        </tr>

        @foreach($items as $item)

            <tr>
                <td>
                    <a href="{{action('Admin\AdminCatalogController@getItem', ['id'=>$item->id])}}">{{$item->title}}</a>
                </td>
                <td width="100"><small>{{implode(", ",explode(",", $item->tags))}}</small></td>
                <td>
                    {{$item->price/100}}
                </td>
                <td>
                    {{$item->registered_price/100}}
                </td>
                <td>
                    {{$item->number_bought}}
                </td>
                <td>
                    {{$item->total/100}}
                </td>
            </tr>

        @endforeach

    </table>

    <div class="text-center">
        {{$items->links()}}
    </div>

@stop