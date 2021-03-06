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

        <form action="{{action('Admin\AdminStatsController@getOrders')}}" class="form-inline panel panel-default">
            <div class="panel-body">
                <label>Дата от</label>
                <input type="text" name="from" value="{{$from or ''}}" data-datepicker>
                &nbsp;<label>Дата до</label>
                <input type="text" name="to" value="{{$to or ''}}" data-datepicker>
                &nbsp;&nbsp;<label>
                    <input type="checkbox" name="complete" @if($complete) checked="checked" @endif> Только оплаченные
                </label>

                &nbsp;&nbsp;
                <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span>
                    Применить
                </button>
            </div>
        </form>

        @foreach($orders as $order)

            <tr @if(\Order\Order::STATUS_COMPLETE == $order->status) class="success" @endif>
                <td>
                    {{$order->id}}
                </td>
                <td><a href="/admin/users/{{$order->user['id']}}" class="btn btn-link">{{$order->user['name']}}</a></td>
                <td>{{toMoscowTZ($order->created_at)}}</td>

                <td>
                    @if($order->status == \Order\Order::STATUS_COMPLETE)
                        {{toMoscowTZ($order->updated_at)}}
                    @else
                        -
                    @endif
                </td>
                <td>
                    {{$order->total/100}}
                </td>
                <td>
                    <span class="label label-default">{{$order->items->count()}}</span><a
                            href="/admin/stats/orders/{{$order->id}}" class="btn btn-link btn-xs">Просмотр</a>
                </td>
            </tr>

        @endforeach

    </table>

    <div class="text-center">
        {{$orders->links()}}
    </div>

@stop