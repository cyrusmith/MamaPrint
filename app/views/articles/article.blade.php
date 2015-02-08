@extends('layouts.master')

@section('page_title'){{$article->seo_title or $article->title}}@stop

@section('description'){{$article->seo_description or $article->description}}@stop

@section('content')

    <div class="row article-page">
        <div class="col-sm-8 col-sm-offset-2">

            <article>
                <h1>{{$article->title}}</h1>

                <p class="description">
                    {{$article->description}}
                </p>
                {{$article->content}}
            </article>

        </div>
    </div>

@stop