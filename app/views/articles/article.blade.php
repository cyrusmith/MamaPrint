@extends('layouts.master')

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

            @include('disqus')

        </div>
    </div>

@stop