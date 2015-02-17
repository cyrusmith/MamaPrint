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
                    {{$item->title}}
                </td>
                <td>
                    {{$item->price}}
                </td>
                <td>
                    {{$item->count}}
                </td>
                <td>
                    {{$item->sum}}
                </td>
            </tr>

        @endforeach

    </table>

    <div class="text-center">
        {{$items->links()}}
    </div>

@stop