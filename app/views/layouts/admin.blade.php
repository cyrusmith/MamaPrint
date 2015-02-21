<?php
use \Illuminate\Support\Facades\Lang;
?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('pagetitle')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/admin/styles.css?v={{Config::get('mamaprint.version')}}">
</head>
<body>

<div class="container">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Mama-print</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="/admin/catalog"><span class="glyphicon glyphicon-list"></span> Каталог <span
                                    class="sr-only">(current)</span></a></li>
                    <li><a href="{{action('Admin\AdminArticlesController@getArticles')}}"><span class="glyphicon glyphicon-copyright-mark"></span> {{Lang::get('static.admin.articles')}} <span
                                    class="sr-only">(current)</span></a></li>
                    <li><a href="{{action('Admin\CustomTemplatesController@getTemplates')}}"><span class="glyphicon glyphicon-adjust"></span> Шаблоны</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Статистика
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/admin/users"><span class="glyphicon glyphicon-user"></span>Пользователи</a></li>
                            <li><a href="/admin/stats/orders">Заказы</a></li>
                            <li><a href="/admin/stats/catalogitems">Материалы</a></li>
                        </ul>
                    </li>
                </ul>

                <a class="navbar-right navbar-link navbar-btn btn" href="/logout"
                   data-toggle="tooltip" data-placement="bottom" title="Log out"><span
                            class="glyphicon glyphicon-log-out"></span> Выход</a>
                <a href="/admin/settings" class="navbar-right navbar-link navbar-btn btn"><span class="glyphicon glyphicon-wrench"></span><span
                            class="sr-only">(current)</span></a>
            </div>
        </div>

    </nav>

    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-body row">

                <div class="col-sm-4">
                    <h4>
                        @if(!empty($pagetitle))
                            {{$pagetitle}}
                        @else
                            @yield('title')
                        @endif
                    </h4>
                </div>
                <div class="col-sm-8 text-right">
                    @if(isset($toolbaractions) && !empty($toolbaractions))
                        @foreach($toolbaractions as $action)
                            <a class="btn btn-default admin-action-{{$action['method']}}" type="button"
                               href="/admin/{{$action['url']}}">
                                {{$action['title']}}
                            </a>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>

        @if(Session::get('error'))
            @define $msg = Session::pull('error')
            @define $msgType = 'danger'
        @endif

        @if(Session::get('success'))
            @define $msg = Session::pull('success')
            @define $msgType = 'success'
        @endif

        @if(isset($msg))
            <div class="alert alert-{{$msgType}} alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                {{$msg}}
            </div>
        @endif
        @yield('content')
    </div>
</div>

@if (Config::get('app.debug'))
    <script src="/bower_components/requirejs/require.js" data-main="/admin/js/main.js"></script>
@else
    <script src="/admin/all.js?v={{Config::get('mamaprint.version')}}"></script>
@endif

</body>
</html>