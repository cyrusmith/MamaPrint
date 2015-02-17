@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2 payconfirm">
        <h2>Подтверждение покупки</h2>

        <h3>Вы покупаете:</h3>

        <table class="table">
            @foreach($order->items as $item)
                <tr>
                    <td><a href="{{URL::action('CatalogController@item', ['path'=>$item->catalog_item->slug])}}"
                           target="_blank">{{$item->catalog_item->title}}</a></td>
                    <td>{{$item->catalog_item->short_description}}</td>
                </tr>
            @endforeach
        </table>

        <div class="text-center confirmcontrols">

            <a class="btn btn-success"
               onclick="yaCounter{{Config::get('mamaprint.yandex_counter')}}.reachGoal('to_pay'); return true;"
               href="https://secure.onpay.ru/pay/mamaprint_ru?price_final=true&ticker=RUR&pay_mode=fix&price={{(float)($order->total/100.0)}}&pay_for={{$order->id}}&user_email={{Auth::check()?Auth::user()->email:''}}&url_success={{URL::to('/pay/success/'.$order->id)}}&url_fail={{URL::to('/pay/fail')}}&ln=ru">Перейти
                к оплате</a>

            <p style="padding-top: 1em;"><span style="position: relative;top: 4px;">Оплата с помощью</span> <img
                        src="/img/onpay-logo.png" width="80"></p>
        </div>

    </div>

@stop