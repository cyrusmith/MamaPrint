<?php
use \Illuminate\Support\Facades\App;

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$page_title or $site_config->getDescriptor()}}</title>
    <meta name="description" content="{{$page_description or $site_config->getSeoDescription()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name='yandex-verification' content='51cf46d6ef645bbd'/>

    <link rel="image_src" href="{{$page_image or '/img/logobig.png'}}"/>

    <meta property="og:type" content="{{$og_type or 'article'}}">
    <meta property="og:url" content="{{Request::url()}}">
    <meta property="og:title" content="{{$page_title or $site_config->getDescriptor()}}">
    <meta property="og:description" content="{{$page_description or $site_config->getSeoDescription()}}">
    <meta property="og:image" content="{{$page_image or '/img/logobig.png'}}"/>
    <meta name="twitter:card" content="{{$page_title or $site_config->getDescriptor()}}">
    <meta name="twitter:site" content="@mamaprint">
    <meta name="twitter:creator" content="@mamaprint">

    <link rel="stylesheet" type="text/css" href="/styles.css?v={{Config::get('mamaprint.version')}}">
    <style type="text/css">
        #sitepreloader {
            opacity: 0.7;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 1000;
            background: #FFF url(/img/preloader.gif) no-repeat 50% 50%;
        }
    </style>
</head>

<body data-page="{{implode('.', Request::segments())}}">
<div id="sitepreloader"></div>
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
                    <li><a href="/public_offer" class="{{Request::is('public_offer')?'active':''}}">Публичная оферта</a>
                    </li>
                    <li><a href="/contacts" class="{{Request::is('contacts')?'active':''}}">Контакты</a></li>
                </ul>
                <div class="cart col-sm-4" data-widget="cartlink">
                    <a class="btn btn-info" href="{{URL::action('CartController@userCart')}}"><span
                                class="glyphicon glyphicon-shopping-cart"></span> Корзина (<span
                                class="title">@if(empty($cart)){{'Нет товаров'}}@else{{count($cart)}} ед.@endif</span>)</a>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="container">

    <div class="row header">

        <!--
        <a href="/" class="logo">
            <img src="/img/logobig.png"/>
        </a>
        -->
        <div class="topbanner" style="position: relative;">
            <img src="/img/bannertop.gif" width="100%"
                 alt="{{$page_description or $site_config->getSeoDescription()}}"/>
            <a href="/" style="position: absolute; left: 0;top: 0;width:18%;height: 100%; text-decoration: none;"
               title="{{$page_description or $site_config->getSeoDescription()}}">&nbsp;</a>
            <a href="http://mama-print.ru/catalog?search=&tags=93&ages=&goals="
               style="position: absolute; left: 18%;top: 0;width:38%;height: 100%; text-decoration: none;"
               title="Тематические комплекты для занятий">&nbsp;</a>
            <a href="http://mama-print.ru/blog/5_35"
               style="position: absolute; left: 56%;top: 0;width:18%;height: 100%; text-decoration: none;"
               title="Самое интересное от 5 до 35 рублей">&nbsp;</a>
            <a href="http://mama-print.ru/catalog?search=&tags=5&ages=&goals="
               style="position: absolute; left: 74%;top: 0;width:26%;height: 100%; text-decoration: none;"
               title="Научим считать до 10и">&nbsp;</a>
        </div>

        @if(mb_strlen(trim($site_config->getDescriptor())) > 10)
            <h3 class="descriptor text-center" style="padding-top: 1em;">{{$site_config->getDescriptor()}}</h3>
        @endif

        <div class="authcontrols">
            @if(Auth::check())
                <a class="btn btn-sm btn-primary" href="/user" title="{{Auth::user()->name}}"><span
                            class="glyphicon glyphicon-user"></span> Личный кабинет</a>
                <a class="btn btn-warning btn-sm" href="/logout">Выйти <span class="glyphicon glyphicon-log-out"></span></a>
            @else
                <a class="btn btn-primary btn-sm" href="/login"><span class="glyphicon glyphicon-log-in"></span>
                    Войти</a> или <a class="btn btn-link register"
                                     href="/register">Зарегистрироваться</a>
                <br>
            @endif
        </div>

    </div>

    <div class="page row">
        <div class="mainmenu">
            <a href="/" class="{{Request::is('/')?'active':''}}">Все материалы</a>
            <span>/</span>
            <a href="/free" class="{{Request::is('free')?'active':''}}">Бесплатно</a>
            <span>/</span>
            <a href="{{action('ArticlesController@getArticles')}}" class="{{Request::is('blog')?'active':''}}">Блог</a>
            <span>/</span>
            <a href="/about" class="{{Request::is('about')?'active':''}}">О нас</a>
            <span>/</span>
            <a href="/contacts" class="{{Request::is('contacts')?'active':''}}" rel="nofollow">Контакты</a>
        </div>

        @define $msg = null
        @if(Session::get('success'))
            @define $msg = Session::get('success')
            @define $msgType = 'success'
        @elseif(Session::get('error'))
            @define $msg = Session::get('error')
            @define $msgType = 'danger'
        @endif

        @if(!empty($msg))
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="alert alert-{{$msgType}} alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        {{$msg}}
                    </div>
                </div>
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

    <div id="cart-prompt-popup" class="white-popup mfp-with-anim mfp-hide">
        <div class="popup-content">
            <a href="/" class="btn btn-default btn-sm">Продолжить покупки</a>
            <a href="/cart" class="btn btn-success btn-sm">Оформить заказ <span
                        class="glyphicon glyphicon-chevron-right"></span></a>
        </div>

    </div>

    <div id="auth-prompt-popup" class="white-popup mfp-with-anim mfp-hide">
        <a href="{{URL::to('/login')}}">Войдите</a> или <a href="{{URL::to('/register')}}">зарегистрируйтесь</a>
    </div>

    <div id="cookies-warning-popup" class="white-popup mfp-with-anim mfp-hide">
        <h3 class="text-danger">Отключены cookies</h3>

        <p>Для корректной работы сайта необходимо <a href="https://www.google.ru/search?q=Как+включить+cookies"
                                                     target="_blank">включить cookies в вашем браузере</a></p>
    </div>

</div>

<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2>Войти и скачать материалы бесплатно</h2>
                <p class="text-center">
                    <a href="javascript:void(0);" class="btn btn-social btn-sm btn-vk" id="loginVk"><i class="fa fa-vk"></i> Войти через ВКонтакте</a>
                </p>
                <h4>С помощью email</h4>
                <form role="form" action="{{URL::action('AuthController@login')}}" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="control-label">Емейл</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                               placeholder="example@mail.ru" value="{{$email or ''}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="control-label">Пароль</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                               placeholder="Ваш пароль" value="{{$form['password'] or ''}}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Войти</button>
                        <a href="/register" class="btn btn-link">Регистрация</a>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Спасибо, позже</button>
            </div>

        </div>
    </div>
</div>
</div>

<script type="javascript/template" id="cart-json">
    {{json_encode($cart)}}
</script>

@define $user = App::make("UserService")->getUser()

<script type="javascript/template" id="appconfig">
    { "siteBaseUrl": "{{URL::to('/')}}",
    "user": @if(empty($user)) null @else {{$user->toJson()}} @endif,
    "siteConfig": {{$site_config->toJSON()}},
    "token": "{{csrf_token()}}",
    "vkId": "{{Config::get('services.vk.id')}}"
    }
</script>

@if (Config::get('app.debug'))
    <script src="/bower_components/requirejs/require.js" data-main="/js/main.js"></script>
@else
    <script src="/all.js?v={{Config::get('mamaprint.version')}}"></script>
    <script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=alsG0C6qV9mhaLfYW4qmnwqWcCkWxS5bsDfnVzoBMhtoTJV*UgIJ316PcGZYUXSBVCjzrDk7l64XZWhc*0fMqJxI2aJWOHZrvnJI0BHr40d339ocg1kzj2hGnWKtpfXSAY7vCx0pO22p5NZwYqqD2hPZ7OwYK1EpZRimu9BDVY8-';</script>
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