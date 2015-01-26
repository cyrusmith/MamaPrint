@extends('layouts.master')

@section('content')


    <div class="col-sm-10 col-sm-offset-1">
        <div class="row catalog-item">
            <div class="col-sm-6">
                <div class="gallery">
                    <div class="mainimage">
                        @if(count($images) === 0)
                            <img src="/assets/noimage.png"/>
                        @endif
                    </div>
                    <div class="thumbs">
                        <a href=""></a>
                    </div>

                </div>
            </div>
            <div class="col-sm-6">
                <h1>{{$item->title}}</h1>
                @if(!empty($item->info_age))
                    <p><b>Возраст:</b> {{$item->info_age}}</p>
                @endif
                @if(!empty($item->info_level))
                    <p><b>Уровень сложности:</b> {{$item->info_level}}</p>
                @endif
                @if(!empty($item->info_targets))
                    <p><b>Что развиваем:</b> {{$item->info_targets}}</p>
                @endif
            </div>
        </div>

    </div>


@stop