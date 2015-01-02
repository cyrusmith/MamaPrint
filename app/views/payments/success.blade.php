@extends('layouts.master')

@section('content')

    <div class="col-sm-offset-2 col-sm-8">
        <h3>Спасибо за оплату покупки!</h3>
        @if ($order->status === \Order\Order::STATUS_COMPLETE)
            <h4>Вы можете скачать следующие материалы:</h4>
            <table class="table table-hover">
                <tr>
                    <th>Название:</th>
                    <th>&nbsp;</th>
                </tr>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{$item->catalogItem->title}}</td>
                        <th><a class="btn btn-success" href="/orders/{{$order->id}}/download">Скачать</a></th>
                    </tr>
                @endforeach
            </table>
        @else
            <p>Ожижание ответа от платежной системы....</p>
        @endif

        <p>Остались вопросы? Напишите нам на <a href="mailto:info@mama-print.ru">info@mama-print.ru</a></p>

    </div>

@stop