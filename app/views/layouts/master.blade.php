<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mama-print @yield('page_title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name='yandex-verification' content='51cf46d6ef645bbd'/>
    <link rel="stylesheet" type="text/css" href="/styles.css">

</head>

<body>

<div class="topbar">
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <div class="container">
                <ul class="menu col-sm-8">
                    <li><a href="/howto" class="{{Request::is('howto')?'active':''}}">Как оформить заказ</a></li>
                    <li><a href="/public_offer" class="{{Request::is('public_offer')?'active':''}}">Публичная оферта</a></li>
                    <li><a href="/contacts" class="{{Request::is('contacts')?'active':''}}">Контакты</a></li>
                </ul>
                <div class="cart col-sm-4" data-widget="cartlink">
                    <a class="btn btn-info" href="{{URL::action('CartController@userCart')}}"><span class="glyphicon glyphicon-shopping-cart"></span> Корзина (<span class="title">Нет товаров</span>)</a>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="container">

    <div class="row header">

        <a href="/" class="logo">
            <img src="/img/logobig.png"/>
        </a>

        <div class="authcontrols">
            @if(Auth::check())
                <a class="btn btn-sm btn-primary" href="/user" title="{{Auth::user()->name}}">Личный кабинет</a>
                <a class="btn btn-warning btn-sm" href="/logout">Выйти</a>
            @else
                <a class="btn btn-primary btn-sm" href="/login">Войти</a> или <a class="btn btn-link register"
                                                                                 href="/register">Зарегистрироваться</a>
                <br>
            @endif
        </div>

    </div>

    <div class="page row">
        <div class="mainmenu">
            <a href="/workbook" class="font-hanwritten {{Request::is('workbook')?'active':''}}">Зимняя-тетрадка</a>
            <span>/</span>
            <a href="/about" class="{{Request::is('about')?'active':''}}">О нас</a>
            <span>/</span>
            <a href="/contacts" class="{{Request::is('contacts')?'active':''}}">Контакты</a>
            <span>/</span>
            <a href="/contacts" class="{{Request::is('contacts')?'active':''}}"><span class="glyphicon glyphicon-shopping-cart"></span>Корзина</a>
        </div>

        @if(Session::get('message'))
            @define $msgType = 'success'
            @if(Session::get('message_type'))
                @define $msgType = Session::get('message_type')
            @endif

            <div class="alert alert-{{$msgType}} alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                {{Session::get('message')}}
            </div>
        @endif


        @yield('content')
    </div>

    <div class="footer row">
        <div class="col-xs-3 contacts">info@mama-print.ru<br>
            +7(908)052-81-87
        </div>
        <div class="col-xs-6 text-center">
            <div class="paygateways-links">
                <a href="http://webmoney.ru"><img src="/img/onpay-logo.png" width="80"/></a>
            </div>
        </div>
        <div class="col-xs-3 text-right">&copy; 2014</div>
    </div>

</div>

<script>
    (function (scope) {
        var user = <?php echo Auth::user()?Auth::user()->toJson():'null'?>, token = '<?php echo csrf_token(); ?>';
        scope.mamaprint = {
            user: user,
            token: token
        }
    })(this);
</script>

@if (Config::get('app.debug'))
    <script src="/bower_components/requirejs/require.js" data-main="/js/main.js"></script>
@else
    <script src="/all.js"></script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    w.yaCounter27971118 = new Ya.Metrika({
                        id: 27971118,
                        webvisor: true,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true
                    });
                } catch (e) {
                }
            });

            var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () {
                        n.parentNode.insertBefore(s, n);
                    };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript>
        <div><img src="//mc.yandex.ru/watch/27971118" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->

@endif

</body>

</html>