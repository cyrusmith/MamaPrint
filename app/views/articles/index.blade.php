@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2">

        <div class="blog">
            @if($articles->isEmpty())
                <p class="panel text-error text-center">Пока нет статей</p>
            @endif

            @foreach($articles as $article)
                <div class="blog-item">
                    <h2>
                        <span class="publishdate">{{iconv("Windows-1251", "UTF-8", $article->publish_date->formatLocalized('%d %b %Y'))}}</span>

                        <a href="{{action('ArticlesController@getArticle', ['path'=>$article->urlpath])}}#articlecut">{{$article->title}}</a>
                    </h2>

                    <p class="description">
                        {{$article->description}}
                    </p>

                    <div class="cut">
                        {{$article->getPagebreak()}}
                    </div>

                </div>
            @endforeach
        </div>

        <div class="text-center">
            {{$articles->links()}}
        </div>


    </div>

@stop