@extends('layouts.master')

@section('content')

    <div class="container catalog-items">

        @for($i=0; $i < $items->count(); $i++)

            @define $item = $items[$i]
            @define $image = $items[$i]->galleries()->first()->images()->first()

            @if($i % 3 === 0)
                <div class="row">
                    @endif

                    <div class="col-sm-4">
                        <div class="catalog-item">

                            <a href="/catalog/items/{{$item->slug}}" class="img">
                                @if(empty($image))
                                    <img src="/assets/noimage.png" title="" alt=""/>
                                @else
                                    <img src="/images/{{$image->id}}?width=220&height=220&crop=1" title="" alt=""/>
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
                                        <span class="old">99 Р.</span>
                                        <span class="new"><span class="label label-primary">39 Р.</span></span>
                                    </div>
                                    <div class="button">
                                        <a class="btn btn-success btn-sm"><span
                                                    class="glyphicon glyphicon-shopping-cart"></span> В корзину</a>
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

@stop