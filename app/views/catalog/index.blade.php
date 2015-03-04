@extends('layouts.master')

@section('content')

    <div class="container catalog-items">

        @for($i=0; $i < $items->count(); $i++)

            @define $item = $items[$i]
            @define $image = null
            @if($items[$i]->galleries()->first())
                @define $image = $items[$i]->galleries()->first()->images()->first()
            @endif

            @if($i % 3 === 0)
                <div class="row">
                    @endif

                    <div class="col-sm-4">
                        <div class="catalog-item" data-widget="catalogitem" data-id="{{$item->id}}">

                            <a href="{{action('CatalogController@item', ['path'=>$item->slug])}}" class="img">
                                @if(empty($image))
                                    <img src="/img/noimage.png" title="" alt=""/>
                                @else
                                    <img src="/images/{{$image->id}}" {{--?width=360&height=360&crop=1--}}
                                         title="{{$item->title}}" alt="{{$item->short_description}}"/>
                                @endif
                            </a>

                            <div class="info">

                                <div class="title">
                                    <h3><a href="/catalog/{{$item->slug}}">{{$item->title}}</a></h3>

                                    <div class="addfav">
                                        <a class="btn btn-inverse" href="javascript:void(0)"><span
                                                    class="glyphicon glyphicon-star"></span>
                                                                                <span class="note">
                                            Добавить<br>в избранное
                                        </span>

                                        </a>
                                    </div>
                                </div>

                                <p class="short">
                                    {{$item->short_description}}
                                </p>

                                <p class="text-right">
                                    <a class="btn btn-link" href="{{action('CatalogController@item', ['path'=>$item->slug])}}">{{Lang::get('static.catalogitem.more')}}<span
                                                class="glyphicon glyphicon-menu-right"></span></a>
                                </p>

                                {{--
                                <div class="addtocart">
                                    <div class="price">
                                        @if(!Auth::check() && !empty($item->registered_price))
                                            <span class="registered"><span class="sum label label-default">{{floatval($item->registered_price/100)}}
                                                    Р.</span><span class="title"> - для <a href="/login">зарегистрированных</a></span>
                                                </span>
                                        @elseif(!empty($item->old_price))
                                            <span class="old">{{floatval($item->old_price/100)}} Р.</span>
                                        @else
                                            <span class="old empty">&nbsp;</span>
                                        @endif

                                        <span class="new"><span class="label label-primary">{{floatval($item->getOrderPrice()/100)}}
                                                Р.</span></span>
                                    </div>
                                    <div class="button">

                                        @if(in_array($item->id, $user_item_ids))
                                            <a class="btn btn-inverse" href="/catalog/{{$item->slug}}/download"><span
                                                        class="glyphicon glyphicon-download"></span> Скачать</a>
                                        @else
                                            <a class="btn btn-default btn-sm catalogitem-addtocart"@if(in_array($item->id, $cart_ids))
                                               style="display:none;"@endif><span
                                                        class="glyphicon glyphicon-shopping-cart"></span> В
                                                корзину</a>

                                            <a href="{{URL::to('/cart')}}"
                                               class="btn btn-sm goto-order btn-success"@if(!in_array($item->id, $cart_ids))
                                               style="display:none;"@endif><span class="glyphicon glyphicon-ok"></span>
                                                Оформить заказ</a>

                                        @endif

                                    </div>
                                </div>
                                --}}

                            </div>
                        </div>


                    </div>
                    <div class="col-sm-4 text-center">

                    </div>
                    <div class="col-sm-4 text-center">

                    </div>

                    @if($i % 3 === 2)
                </div>
            @endif

        @endfor

    </div>

    <div class="text-center">
        {{$items->links()}}
    </div>

@stop