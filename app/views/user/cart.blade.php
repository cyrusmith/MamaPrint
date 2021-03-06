@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2" xmlns="http://www.w3.org/1999/html">
        <h3><span class="glyphicon glyphicon-shopping-cart text-primary"></span> Корзина</h3>
        @if(count($items) > 0)
            <table class="table table-hover" data-widget="cartitems">
                @foreach($items as $item)
                    <tr data-cart-item data-cart-item-id="{{$item['id']}}">
                        <td>
                            <a href="{{URL::action('CatalogController@item', ['path'=>$item['slug']])}}"
                               data-cart-item-title>{{$item['title']}}</a>
                        </td>
                        <td>
                            <span data-cart-item-price>{{$item['price']/100}}</span> P
                            @if($item['registered_price'] ==0)
                                <a href="/login" class="btn btn-xs btn-default">Войти</a> и скачать бесплатно
                            @endif
                        </td>
                        <th class="text-right"><a class="btn btn-danger btn-sm"
                                                  href="javascript:void(0);"
                                                  data-cart-item-remove><span
                                        class="glyphicon glyphicon-trash"></span> Удалить</a></th>
                    </tr>
                @endforeach

                <tr data-summary-row>
                    <td class="text-right">
                        Итого:
                    </td>
                    <td>
                        <span data-cart-total>{{$total/100}}</span> P
                    </td>
                    <th class="text-right">

                        <form class="payform" action="{{URL::action('OrdersController@createOrder')}}"
                              method="post" @if($total < ($site_config->getMinOrderPrice()*100))
                              style="display: none;" @endif target="_blank">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <button type="submit" class="btn btn-success"
                                    onclick="yaCounter{{Config::get('mamaprint.yandex_counter')}}.reachGoal('to_pay'); return true;"
                                    href="javascript:void(0);">
                                Оплатить <span class="glyphicon glyphicon-chevron-right"></span></button>
                        </form>

                    </th>
                </tr>

                @if($total >= ($site_config->getMinOrderPrice()*100))
                    <tr data-summary-row>

                        <td colspan="3">
                            <p class="text-warning text-right"><strong>Внимание!</strong> Если вы оплачиваете через SMS, то
                                убедитесь, что после оплаты на балансе телефона останется не менее 50 рублей,
                            <nobr>
                                иначе платеж не пройдет.
                            </nobr>
                            </p>
                        </td>

                    </tr>
                @endif
            </table>

            <p class="text-center payments-icons" @if($total < ($site_config->getMinOrderPrice()*100))
               style="display:none;" @endif><img src="/img/ic-payments.gif"/></p>

            <p class="panel text-danger insufficientprice-message" @if($total >= ($site_config->getMinOrderPrice()*100))
               style="display:none;" @endif>
                Минимальная сумма заказа - {{$site_config->getMinOrderPrice()}} Р.
                <a href="/">Продолжить
                    покупки</a>
            </p>


        @endif

        <p class="emptycart-message" @if(count($items) > 0) style="display:none;" @endif>Корзина пуста. <a href="/">Продолжить
                покупки</a></p>

        @if(!empty($text))
            <article>
                {{$text}}
            </article>
        @endif

    </div>


@stop