@extends('layouts.master')

@section('content')

    <div class="row article-page">
        <div class="col-sm-8 col-sm-offset-2">

            <article>
                <h1>{{$article->title}}</h1>

                <p class="description">
                    {{$article->description}}
                </p>
                {{replacePagebreakWithCut($article->content)}}

                <div class="social-likes social-likes_notext" data-url="{{Request::url()}}">
                    <span class="btn btn-xs">Поделиться:</span>

                    <div class="vkontakte" title="Поделитесь в VK.com">&nbsp;&nbsp;&nbsp;</div>
                    <div class="odnoklassniki" title="Поделитесь в Одноклассниках"></div>
                    @define $path = $article->getImagePath()
                    @if(!empty($path))
                        <div class="pinterest"
                             data-media="{{$path}}"
                        title="Поделитесь в Pinterest"></div>
                    @endif
                    <div class="facebook" title="Поделитесь в Facebook"></div>
                    <div class="twitter" title="Поделитесь в Twitter"></div>
                    <div class="plusone" title="Поделитесь в Google+"></div>
                    <div class="mailru" title="Поделитесь в Mailru"></div>
                </div>

            </article>

            @include('disqus')

        </div>
    </div>

@stop