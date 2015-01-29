@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <dl>
        <dt>Дата создания</dt>
        <dd>{{$order->created_at}}</dd>
        @if(\Order\Order::STATUS_COMPLETE==$order->status)
            <dt>Дата оплаты</dt>
            <dd>{{$order->updated_at}}</dd>
        @endif
        <dt>Cтатус</dt>
        <dd>{{\Order\Order::STATUS_COMPLETE==$order->status? 'Оплачен': 'В ожидании оплаты'}}</dd>
        <dt>Пользователь</dt>
        <dd><a href="/admin/users/{{$order->user->id}}" class="btn btn-info btn-xs">{{$order->user->name}}</a> <a
                    href="mailto:{{$order->user->email}}">{{$order->user->email}}</a></dd>
    </dl>

    @if(\Order\Order::STATUS_PENDING==$order->status)
        <form action="{{action('Admin\AdminStatsController@postOrder', ['orderId'=>$order->id])}}" method="post">
            <button type="submit" class="btn btn-primary">Завершить заказ</button>
            <input type="hidden" name="status" value="{{\Order\Order::STATUS_COMPLETE}}"/>
            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
        </form>
    @endif

    <h4>Позиции</h4>

    <table class="table">
        @foreach($order->items as $item)
            <tr>
                <td><a href="/admin/catalog/edit/{{$item->catalogItem->id}}">{{$item->catalogItem->title}}</a></td>
                <td>{{$item->catalogItem->short_description}}</td>
                <td>{{$item->price/100}}</td>
            </tr>
        @endforeach
    </table>

@stop