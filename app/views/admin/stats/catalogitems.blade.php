@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <form action="{{action('Admin\AdminStatsController@getCatalogitems')}}" class="form-inline panel panel-default">
        <div class="panel-body">
            <label>Дата от</label>
            <input type="text" name="from" value="{{$from or ''}}" data-datepicker>
            &nbsp;<label>Дата до</label>
            <input type="text" name="to" value="{{$to or ''}}" data-datepicker>

            &nbsp;<label>Тэг:</label>
            <input type="text" name="searchtag" value="{{$searchtag or ''}}" >

            &nbsp;&nbsp;
            <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span>
                Применить
            </button>
        </div>
    </form>

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