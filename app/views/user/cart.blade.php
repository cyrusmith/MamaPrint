@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2">
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
                        </td>
                        <th class="text-right"><a class="btn btn-danger btn-sm progress-parent progress-hidden"
                                                  href="javascript:void(0);"
                                                  data-cart-item-remove><span
                                        class="glyphicon glyphicon-trash"></span> Удалить<span
                                        class="progress-left"></span></a></th>
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
                        <form action="{{URL::action('OrdersController@createOrder')}}" method="post">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <button type="submit" class="btn btn-success progress-parent progress-hidden"
                                    href="javascript:void(0);">
                                Оплатить <span class="glyphicon glyphicon-chevron-right"></span><span
                                        class="progress-left"></span></button>
                        </form>
                    </th>
                </tr>
            </table>

        @endif

        <p class="emptycart-message" @if(count($items) > 0) style="display:none;" @endif>Корзина пуста. <a href="/">Продолжить
                покупки</a></p>

    </div>


@stop