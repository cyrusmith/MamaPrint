@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2 payconfirm">
        <h2>Подтверждение покупки</h2>

        <h3>Вы заказали:</h3>

        <table class="table">
            @foreach($order->items as $item)
                <tr>
                    <td><a href="/workbook" target="_blank">{{$item->catalog_item->title}}</a></td>
                    <td>{{$item->catalog_item->short_description}}</td>
                </tr>
            @endforeach
        </table>

        <div class="text-center confirmcontrols">

            <a class="btn btn-success"
               href="https://secure.onpay.ru/pay/mamaprint_ru?price_final=true&ticker=RUR&pay_mode=fix&price={{(float)($order->total/100.0)}}&pay_for={{$order->id}}&user_email={{Auth::check()?Auth::user()->email:''}}&url_success={{URL::to('/pay/success/'.$order->id)}}&url_fail={{URL::to('/pay/fail')}}&ln=ru">Перейти
                к оплате</a>
        </div>

    </div>

@stop