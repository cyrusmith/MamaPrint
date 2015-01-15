<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>@yield('pagetitle')</title>
    <link rel="stylesheet" type="text/css" href="/admin/styles.css">
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
                <a class="navbar-brand" href="/admin">Mama-print</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="/admin/catalog">Каталог <span class="sr-only">(current)</span></a></li>
                    <li><a href="/admin/users">Пользователи</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Статистика
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Операции</a></li>
                            <li><a href="#">Посещения</a></li>
                            <li><a href="#">Скачивания</a></li>
                        </ul>
                    </li>
                </ul>


                <a class="navbar-right navbar-link navbar-btn btn" href="/logout"
                   data-toggle="tooltip" data-placement="bottom" title="Log out"><span
                            class="glyphicon glyphicon-log-out"></span> Выход</a>

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
    <script data-main="/admin/all.js"></script>
@endif

</body>
</html>