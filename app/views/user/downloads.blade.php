@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2">
        <h3>Личный кабинет</h3>
        @if(!$orders->isEmpty())
            <table class="table table-hover">
                <tr>
                    <th>Название:</th>
                    <th>&nbsp;</th>
                </tr>
                @foreach($orders as $order)
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{$item->catalogItem->title}}</td>
                            <th><a class="btn btn-success" href="/orders/{{$order->id}}/download">Скачать</a></th>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        @else
            <p>У вас пока нет материалов для скачивания</p>
        @endif

    </div>


@stop