@extends('layouts.master')

@section('content')

    <div class="col-lg-10 col-lg-offset-1 catalog-item-page">

        <div class="row">
            <div class="col-sm-6">
                <!-- Image -->
                <div class="gallery" data-widget="gallery">
                    <div class="mainimage">
                        @if(count($images) === 0)
                            <img src="/img/noimage.png"/>
                        @else
                            <a href="{{action('GalleryController@view', ['id'=>$images[0]->id, 'ext'=>$images[0]->extension])}}"
                               class="gallery-image">
                                <img src="{{action('GalleryController@view', ['id'=>$images[0]->id, 'ext'=>$images[0]->extension])}}"/> {{--?width=400&height=400&crop=1--}}
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
                <!-- End Image-->

            </div>
            <div class="col-sm-6">

                <h1>{{$item->title}}</h1>

                <p>{{$item->short_description}}</p>

                @if(!$item->tags->isEmpty())
                    <p><span>Тэги: </span>
                        @foreach($item->tags as $tag)
                            <a class="btn btn-xs btn-default" href="/catalog?tags={{$tag->id}}">{{$tag->tag}}</a>
                        @endforeach
                    </p>
                @endif

                <p class="price">

                    @if(Auth::check())
                        @if($item->registered_price == 0)
                            <a class="btn btn-inverse downloadlink" href="/catalog/{{$item->slug}}/download"><span
                                        class="glyphicon glyphicon-download"></span> Скачать</a>
                        @else
                            @if(!empty($item->old_price))
                                <span class="oldprice">{{$item->old_price/100}} руб.</span><br/>
                            @endif
                            <span class="price">{{$item->getOrderPrice()/100}} руб.</span>
                        @endif
                    @else
                        <span class="price">{{$item->getOrderPrice()/100}} руб.</span>
                        @if(empty($item->registered_price))
                            <span class="text-muted">Бесплатно для зарегистрированных.</span>
                            <a class="btn btn-primary btn-xs" href="/login"><span
                                        class="glyphicon glyphicon-log-in"></span> Войти</a>
                        @else
                            <span class="registeredprice"><span class="pricesum">{{$item->registered_price/100}}
                                    руб. <sup>*</sup></span>
                                <small class="title"><span class="text-red">*</span>
                                    - {{Lang::get('static.catalogitem.registeredprice')}}. <a
                                            href="{{action('AuthController@login')}}">{{Lang::get('static.login')}}</a>
                                </small>
                               </span>
                        @endif

                    @endif

                </p>

                @if(!$item->ages->isEmpty())
                    <p><b>Возраст:</b>
                        @foreach($item->ages as $age)
                            <a class="btn btn-xs btn-default" href="/catalog?ages={{$age->id}}">{{$age->tag}}</a>
                        @endforeach
                    </p>
                @endif
                {{--  }}
                @if(!empty($item->info_level))
                    <p><b>Уровень сложности:</b> {{$item->info_level}}</p>
                @endif
                {{  --}}
                @if(!$item->goals->isEmpty())
                    <p><b>Что развиваем:</b>
                        @foreach($item->goals as $goal)
                            <a class="btn btn-xs btn-default" href="/catalog?goals={{$goal->id}}">{{$goal->tag}}</a>
                        @endforeach
                    </p>
                @endif

                @if(!Auth::check() || !$item->registered_price ==0)
                    <div class="buttons" data-widget="itemcartbuttons" data-id="{{$item->id}}">
                        @if(in_array($item->id, $user_item_ids))
                            <a class="btn btn-inverse downloadlink" href="/catalog/{{$item->slug}}/download"><span
                                        class="glyphicon glyphicon-download"></span> Скачать</a>
                        @else
                            @if($orderLimitPolicy->canBuyInOneClick($user, $item))
                                <form class="oneclickorder" action="{{URL::action('OrdersController@buyitem', [
                        'itemId' => $item->id
                    ])}}" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                    <button type="submit" class="btn btn-default btn-sm">Купить в один клик</button>
                                </form>
                            @endif
                            <a data-addtocart="data-addtocart"
                               @if(!Config::get('app.debug')))
                               onclick="yaCounter{{Config::get('mamaprint.yandex_counter')}}.reachGoal('to_basket'); return true;"
                               @endif
                               class="btn btn-success" @if(in_array($item->id, $cart_ids))
                               style="display:none;"@endif data><span class="glyphicon glyphicon-shopping-cart"></span>
                                Добавить
                                в корзину</a>
                            <a data-gotocart="data-gotocart" href="{{URL::to('/cart')}}"
                               class="btn btn-success" @if(!in_array($item->id, $cart_ids))
                               style="display:none;"@endif><span class="glyphicon glyphicon-ok"></span> Оформить
                                заказ</a>
                            <br>
                            <img src="/img/ic-payments.gif"/>
                        @endif
                    </div>
                @endif


                <div class="social-likes social-likes_notext" data-url="{{Request::url()}}">
                    <span class="btn btn-xs">Поделиться:</span>
                    <div class="vkontakte" title="Поделитесь в VK.com">&nbsp;&nbsp;&nbsp;</div>
                    <div class="odnoklassniki" title="Поделитесь в Одноклассниках"></div>
                    <div class="pinterest" title="Поделитесь в Pinterest"></div>
                    <div class="facebook" title="Поделитесь в Facebook"></div>
                    <div class="twitter" title="Поделитесь в Twitter"></div>
                    <div class="plusone" title="Поделитесь в Google+"></div>
                    <div class="mailru" title="Поделитесь в Mailru"></div>
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
                    @if(!Auth::check() || !$item->registered_price ==0)
                        <div class="buttons text-center" data-widget="itemcartbuttons" data-id="{{$item->id}}">
                            @if(in_array($item->id, $user_item_ids))
                                <a class="btn btn-inverse" href="/catalog/{{$item->slug}}/download"><span
                                            class="glyphicon glyphicon-download"></span> Скачать</a>
                            @else
                                @if($orderLimitPolicy->canBuyInOneClick($user, $item))
                                    <form class="oneclickorder" action="{{URL::action('OrdersController@buyitem', [
                        'itemId' => $item->id
                    ])}}" method="post">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                        <button type="submit" class="btn btn-default btn-sm">Купить в один клик</button>
                                    </form>
                                @endif
                                <a data-addtocart
                                   @if(!Config::get('app.debug')))
                                   onclick="yaCounter{{Config::get('mamaprint.yandex_counter')}}.reachGoal('to_basket'); return true;"
                                   @endif
                                   class="btn btn-success" @if(in_array($item->id, $cart_ids))
                                   style="display:none;"@endif data><span
                                            class="glyphicon glyphicon-shopping-cart"></span>
                                    Добавить в корзину</a>
                                <a data-gotocart="data-gotocart" href="{{URL::to('/cart')}}"
                                   class="btn btn-success" @if(!in_array($item->id, $cart_ids))
                                   style="display:none;"@endif><span class="glyphicon glyphicon-ok"></span> Оформить
                                    заказ</a>
                                <br>
                                <img src="/img/ic-payments.gif"/>
                            @endif
                        </div>
                    @endif

                </div>
            </div>

        @endif

        @if(!$item->relatedItems->isEmpty())
            <div class="row related">
                <div class="col-lg-10 col-lg-offset-2">
                    <h3>{{Lang::get('static.catalogitem.related')}}</h3>

                    <div class="related-items">
                        @foreach($item->relatedItems as $relItem)
                            <div class="related-item">
                                <div class="related-item-wrapper">
                                    @define $img = $relItem->galleries()->first()->images()->first()
                                    <a href="{{action('CatalogController@item', ['path'=>$relItem->slug])}}">
                                        @if(empty($img))
                                            <img src="/img/noimage.png"/>
                                        @else
                                            <img src="{{action('GalleryController@view', ['id'=>$img->id, 'ext'=>$img->extension])}}?width=200&height=200&crop=1"/>
                                        @endif
                                    </a>
                                    <h5>
                                        <a href="{{action('CatalogController@item', ['path'=>$relItem->slug])}}">{{$relItem->title}}</a>
                                    </h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @include('disqus')

    </div>


@stop