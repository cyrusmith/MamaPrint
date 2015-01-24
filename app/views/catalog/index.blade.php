@extends('layouts.master')

@section('content')
        {{$test1 or "No test :((("}}
    <div class="container catalog-items">

        @for($i=0; $i < $items->count(); $i++)

            @define $item = $items[$i]
            @define $image = $items[$i]->galleries()->first()->images()->first()

            @if($i % 3 === 0)
                <div class="row">
                    @endif

                    <div class="col-sm-4">
                        <div class="catalog-item" data-widget="catalogitem" data-id="{{$item->id}}">

                            <a href="/catalog/items/{{$item->slug}}" class="img">
                                @if(empty($image))
                                    <img src="/assets/noimage.png" title="" alt=""/>
                                @else
                                    <img src="/images/{{$image->id}}?width=220&height=220&crop=1" title="" alt=""
                                         class="img-rounded"/>
                                @endif
                            </a>

                            <div class="info">

                                <div class="title">
                                    <h3><a href="/catalog/items/{{$item->slug}}">{{$item->title}}</a></h3>

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

                                <div class="addtocart">
                                    <div class="price">
                                        @if(!empty($item->old_price))
                                            <span class="old">{{floatval($item->old_price/100)}} Р.</span>
                                        @else
                                            <span class="old empty">&nbsp;</span>
                                        @endif
                                        <span class="new"><span class="label label-primary">{{floatval($item->price/100)}}
                                                Р.</span></span>
                                    </div>
                                    <div class="button">

                                        <a class="btn btn-success btn-sm catalogitem-addtocart progress-parent progress-hidden"@if(in_array($item->id, $cart_ids)) style="display:none;"@endif><span
                                                    class="glyphicon glyphicon-shopping-cart"></span> В корзину <span class="progress-left"></span></a>

                                        <a class="btn btn-xs catalogitem-removefromcart progress-parent progress-hidden"@if(!in_array($item->id, $cart_ids)) style="display:none;"@endif>Удалить<br>из
                                            корзины <span class="progress-left"></span></a>

                                    </div>
                                </div>


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

    <script type="x-tpl" id="cart-json">
        {{json_encode($cart)}}
    </script>

@stop