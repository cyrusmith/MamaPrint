@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <table class="table table-condensed">

        <tr>
            <th>#</th>
            <th>Пользователь</th>
            <th>Дата создания</th>
            <th>Дата оплаты</th>
            <th>Сумма</th>
            <th>Позиции</th>
        </tr>

        @foreach($orders as $order)
            <tr @if(\Order\Order::STATUS_COMPLETE == $order->status) class="success" @endif>
                <td>
                    <a href="/admin/stats/orders/{{$order->id}}" class="btn btn-primary btn-xs">{{$order->id}}</a>
                </td>
                <td><a href="/admin/users/{{$order->user->id}}" class="btn btn-link">{{$order->user->name}}</a></td>
                <td>{{$order->created_at}}</td>
                <td>
                    @if($order->status == \Order\Order::STATUS_COMPLETE)
                        {{$order->updated_at}}
                    @else
                        -
                    @endif
                </td>
                <td>
                    {{$order->total/100}}
                </td>
                <td>
                    <a href="/admin/stats/orders/{{$order->id}}"
                       class="btn btn-info btn-xs">({{$order->items->count()}}) Просмотр <span class="glyphicon glyphicon-eye-open"></span></a>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="text-center">
        {{$orders->links()}}
    </div>

@stop