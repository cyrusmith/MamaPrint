@extends('layouts.master')

@section('content')

    <div class="container catalog-items">

        @if(Auth::user() && Auth::user()->hasRole(Role::getByName(Role::ROLE_ADMIN))):

        <div class="row searchform">
            <form action="{{action('CatalogController@search')}}" class="col-sm-8 col-sm-offset-2" method="get">

                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{$search or ''}}"
                           placeholder="Искать материал..." autocomplete="off">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="button"><span class="glyphicon glyphicon-search"></span>
                            Найти
                        </button>
                    </span>
                </div>
                @if(!$tags->isEmpty() || !$ages->isEmpty())
                    <div class="advsearch">
                        <a href="javascript:void(0)" class="pseudo-link" data-toggle="#advserach">Расширенный поиск</a>

                        <div id="advserach"  @if(!empty($selected_tags)) class="open" @endif>
                            @if(!$tags->isEmpty())
                                <div class="input-group-tags">
                                    <label>Теги</label>
                                    <br>
                                    @foreach($tags as $tag)
                                        <div class="checkbox label label-primary">
                                            <label>
                                                <input type="checkbox" data-tag="{{$tag->id}}"
                                                       @if(!empty($selected_tags) && in_array($tag->id, $selected_tags)))
                                                       checked="checked" @endif/> {{$tag->tag}}
                                            </label>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="tags" value=""/>
                                </div>
                            @endif
                            @if(!$ages->isEmpty())
                                <div class="input-group-ages">
                                    <label>Возраст</label>
                                    <br>
                                    @foreach($ages as $age)
                                        <div class="checkbox label label-success">
                                            <label>
                                                <input type="checkbox" data-age="{{$age->id}}"
                                                       @if(!empty($selected_tags) && in_array($age->id, $selected_tags)))
                                                       checked="checked" @endif /> {{$age->tag}}
                                            </label>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="ages" value=""/>
                                </div>
                            @endif

                            @if(!$goals->isEmpty())
                                <div class="input-group-goals">
                                    <label>Что развиваем</label>
                                    <br>
                                    @foreach($goals as $goal)
                                        <div class="checkbox label label-info">
                                            <label>
                                                <input type="checkbox" data-goal="{{$goal->id}}"
                                                       @if(!empty($selected_tags) && in_array($goal->id, $selected_tags)))
                                                       checked="checked" @endif /> {{$goal->tag}}
                                            </label>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="goals" value=""/>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </form>

        </div>

        @endif

        @if(!empty($selected_tags) || !empty($search))
            <h2>Результаты поиска</h2>
            <hr>
            @if($items->isEmpty())
                <p class="map">По вашему запросу ничего не найдено</p>
            @endif
        @endif

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
                                    <img class="lazy"
                                         data-src="{{action('GalleryController@view', ['id'=>$image->id, 'ext'=>$image->extension])}}"
                                         src="" {{--?width=360&height=360&crop=1--}}
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
                                    <a class="btn btn-link"
                                       href="{{action('CatalogController@item', ['path'=>$item->slug])}}">{{Lang::get('static.catalogitem.more')}}
                                        <span
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

                @if(ceil($i/3) == 2)
                    <a class="hidden-xs" href="http://mama-print.ru/catalog/summerbook1"
                       onclick="yaCounter{{Config::get('mamaprint.yandex_counter')}}.reachGoal('banner2'); return true;"
                       style="display: block; padding: 0 0 3em;">
                        <img src="/img/banner-summer-book.jpg" alt="Большая Летняя книга. Часть первая"
                             title="Большая Летняя книга. Часть первая" style="width: 100%; max-width: 1140px;"/>
                    </a>
                @endif
                @if(ceil($i/3) == 1)
                    <a class="visible-xs-block" href="http://mama-print.ru/catalog/summerbook1"
                       onclick="yaCounter{{Config::get('mamaprint.yandex_counter')}}.reachGoal('banner2'); return true;"
                       style="display: block; padding: 0 0 3em;">
                        <img src="/img/banner-summer-book.jpg" alt="Большая Летняя книга. Часть первая"
                             title="Большая Летняя книга. Часть первая" style="width: 100%; max-width: 1140px;"/>
                    </a>
                @endif
            @endif

        @endfor

    </div>

    <div class="text-center">
        {{$items->links()}}
    </div>

@stop