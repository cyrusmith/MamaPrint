@extends('layouts.master')

@section('page_title'){{$item->title}}@stop

@section('description'){{$item->short_description}}@stop

@section('content')

    <div class="col-lg-10 col-lg-offset-1 catalog-item-page">
        <div class="row">
            <div class="col-sm-6">
                <div class="gallery" data-widget="gallery">
                    <div class="mainimage">
                        @if(count($images) === 0)
                            <img src="/assets/noimage.png"/>
                        @else
                            <a href="/images/{{$images[0]->id}}" class="gallery-image">
                                <img src="/images/{{$images[0]->id}}?width=400&height=400&crop=1"/>
                            </a>
                        @endif
                    </div>
                    @if(count($images) > 0)
                        <div class="thumbs">
                            @define $len = min(count($images), 5)
                            @for($i=1; $i < $len; $i++)
                                <a href="/images/{{$images[$i]->id}}" class="gallery-image @if($i==4) {{'last'}}@endif">
                                    <img src="/images/{{$images[$i]->id}}?width=80&height=80&crop=1"
                                         class="img-rounded"/>
                                </a>
                            @endfor
                        </div>
                    @endif

                </div>
            </div>
            <div class="col-sm-6">

                <h1>{{$item->title}}</h1>

                <p>{{$item->short_description}}</p>

                <p class="price">

                    @if(Auth::check())
                        @if(!empty($item->old_price))
                            <span class="oldprice">{{$item->old_price/100}} руб.</span><br/>
                        @endif
                        <span class="price text-primary">{{$item->getOrderPrice()/100}} руб.</span>
                    @else
                        <span class="price">{{$item->getOrderPrice()/100}} руб.</span>
                        @if(!empty($item->registered_price))
                            <span class="registeredprice"><span class="pricesum">{{$item->registered_price/100}}
                                    руб.</span> - <span class="title">{{Lang::get('static.catalogitem.registeredprice')}}</span>
                               </span>
                        @endif
                    @endif

                </p>

                @if(!empty($item->info_age))
                    <p><b>Возраст:</b> {{$item->info_age}}</p>
                @endif
                @if(!empty($item->info_level))
                    <p><b>Уровень сложности:</b> {{$item->info_level}}</p>
                @endif
                @if(!empty($item->info_targets))
                    <p><b>Что развиваем:</b> {{$item->info_targets}}</p>
                @endif

                <div class="buttons" data-widget="itemcartbuttons" data-id="{{$item->id}}">
                    @if(in_array($item->id, $user_item_ids))
                        <a class="btn btn-inverse downloadlink" href="/catalog/{{$item->slug}}/download"><span
                                    class="glyphicon glyphicon-download"></span> Скачать</a>
                    @else
                        @if($item->canBuyInOneClick())
                            <form class="oneclickorder" action="{{URL::action('OrdersController@buyitem', [
                        'itemId' => $item->id
                    ])}}" method="post">
                                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                <button type="submit" class="btn btn-default btn-sm">Купить в один клик</button>
                            </form>
                        @endif
                        <a data-addtocart="data-addtocart"
                           class="btn btn-success" @if(in_array($item->id, $cart_ids))
                           style="display:none;"@endif data><span class="glyphicon glyphicon-shopping-cart"></span>
                            Добавить
                            в корзину</a>
                        <a data-gotocart="data-gotocart" href="{{URL::to('/cart')}}"
                           class="btn btn-success" @if(!in_array($item->id, $cart_ids))
                           style="display:none;"@endif><span class="glyphicon glyphicon-ok"></span> Оформить заказ</a>
                    @endif
                </div>

            </div>
        </div>

        @if(!empty($item->long_description))
            <div class="row">
                <div class="col-lg-10 col-lg-offset-2">
                    {{$item->long_description}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-2">
                    <div class="buttons text-center" data-widget="itemcartbuttons" data-id="{{$item->id}}">
                        @if(in_array($item->id, $user_item_ids))
                            <a class="btn btn-inverse" href="/catalog/{{$item->slug}}/download"><span
                                        class="glyphicon glyphicon-download"></span> Скачать</a>
                        @else
                            @if($item->canBuyInOneClick())
                                <form class="oneclickorder" action="{{URL::action('OrdersController@buyitem', [
                        'itemId' => $item->id
                    ])}}" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                    <button type="submit" class="btn btn-default btn-sm">Купить в один клик</button>
                                </form>
                            @endif
                            <a data-addtocart
                               class="btn btn-success" @if(in_array($item->id, $cart_ids))
                               style="display:none;"@endif data><span class="glyphicon glyphicon-shopping-cart"></span>
                                Добавить в корзину</a>
                            <a data-gotocart="data-gotocart" href="{{URL::to('/cart')}}"
                               class="btn btn-success" @if(!in_array($item->id, $cart_ids))
                               style="display:none;"@endif><span class="glyphicon glyphicon-ok"></span> Оформить
                                заказ</a>

                        @endif
                    </div>
                </div>
            </div>

        @endif

    </div>


@stop