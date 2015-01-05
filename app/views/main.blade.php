<html>

<head>

    <title>Mama-print - Материалы для занятий с ребенком</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

    <meta name='yandex-verification' content='51cf46d6ef645bbd'/>

</head>

<body class="page-main">

<div class="fixedtop">

    <div class="contents">

        <div class="mainmenu">
            <a href="/workbook" class="font-hanwritten {{Request::is('workbook')?'active':''}}">Зимняя-тетрадка</a>
            <span>/</span>
            <a href="/about" class="{{Request::is('about')?'active':''}}">О нас</a>
            <span>/</span>
            <a href="/public_offer" class="{{Request::is('public_offer')?'active':''}}">Публичная оферта</a>
            <span>/</span>
            <a href="/howto" class="{{Request::is('howto')?'active':''}}">Как оформить заказ</a>
            <span>/</span>
            <a href="/contacts" class="{{Request::is('contacts')?'active':''}}">Контакты</a>
        </div>

        <!--<div class="curlytitle">
            <a href="/workbook">
                <img src="/img/winterbook-title.png" width="200"/>
            </a>
            <a href="/workbook" class="btn btn-primary">Как получить бесплатно?</a>
        </div>-->
    </div>
</div>

<div id="workbook-popup" class="white-popup mfp-hide">
    <div class="curlytitle">
        <a href="/workbook"><img src="/img/winterbook-title.png"/></a>
    </div>
    <div class="title">
        <h1>
            <a href="/workbook">Тридцать творческих уроков для малышей на тему «Новый год и зима»</a>
        </h1>
        <h4>Вы будете счастливы видеть, как ваш ребенок становится настоящим художником!</h4>
    </div>
    <div class="row text-center">
        <p>Как получить бесплатно?</p>
        <a class="btn btn-link" href="/workbook">Подробнее</a>
    </div>
</div>

<div id="main">


    <div id="logo">
        <img src="i/logo.png" id="logo"/>
    </div>
    <div id="contact">
        <p><span class="email"><a href="mailto:info@mama-print.ru">info@mama-print.ru</a></span><br>
            <span style="font:bold 24px aAlbionicTitulInflBold, arial;">+7(908)052-81-87</span></p>


    </div>
    <div id="descriptor">
        <p style="padding-top:50px; font: bold 32px Tahoma;">
            <span style="color:#cd2447; font: bold 48px Tahoma;">Скачайте и распечатайте</span> качественные<br>материалы
            для занятий с ребенком <span style="color:#cd2447; font: bold 36px Tahoma;">0+</span></p>

        <p>
            <span style="font: bold 24px Tahoma; ">Полноценное занятие </span>
            <span style="color:#cd2447; font: bold 24px Tahoma;">всего за 5 минут</span></p>
    </div>
    <div id="p10000"><img src="i/10000.png" id="p10000"/></div>
    <form action="{{URL::action('SubscribeController@getCards')}}" name="get1" method="post" id="fform1">
        Получите комплект<br>
        познавательных карточек<br>
        прямо сейчас!<br>
        <input type="text" class="field" placeholder="Введите имя: *" name="name"/><br>
        <input type="text" class="field" placeholder="Введите e-mail: *" name="email"/><br>
        <input type="submit" value="ПОЛУЧИТЬ ФАЙЛЫ!">
    </form>
    <br>

    <div id="trigers">
        <div id="trig1">Самая большая<br>база знаний</div>
        <div id="trig2">БЕСПЛАТНЫЕ материалы<br>для скачивания</div>
        <div id="trig3">Простейший способ<br>подготовить занятие</div>
        <div id="trig4">Готовые интересные<br>занятия с ребенком</div>
        <div id="trig5">Новые виды игр<br>без подготовки</div>
        <div id="trig6">Самые прогрессивные<br>методики</div>
    </div>
    <div id="end1">ВСЁ, ЧТО НУЖНО, ДЛЯ МАМЫ, ВОСПИТАТЕЛЯ И ПЕДАГОГА ЗДЕСЬ!</div>

    <div id="catalog" style="color:#cd2447;">
        Изучаем&Играем<br>
        <img src="i/cat1.jpg"/><br><br><br>
        Поделки<br>
        <img src="i/cat2.jpg"/><br><br><br>
        Праздники<br>
        <img src="i/cat3.jpg"/><br><br><br><br><br><br><br><br><br><br><br><br><br>

        <form action="{{URL::action('SubscribeController@getCards')}}" name="get2" method="post" id="fform2">
            Получите комплект<br>
            познавательных карточек<br>
            прямо сейчас!<br>
            <input type="text" class="field" placeholder="Введите имя: *" name="name"/><br>
            <input type="text" class="field" placeholder="Введите e-mail: *" name="email"/><br>
            <input type="submit" value="ПОЛУЧИТЬ ФАЙЛЫ!">
        </form>
        Вечеринки&Дни рождения<br>
        <img src="i/cat4.jpg"/><br><br><br>
        Декор для детской и дома<br>
        <img src="i/cat5.jpg"/><br><br><br>
        Идеи для дома<br>
        <img src="i/cat6.jpg"/><br><br><br><br><br><br><br><br><br><br><br>

        <form action="{{URL::action('SubscribeController@getCards')}}" name="get3" method="post" id="fform3">
            Получите комплект<br>
            познавательных карточек<br>
            прямо сейчас!<br>
            <input type="text" class="field" placeholder="Введите имя: *" name="name"/><br>
            <input type="text" class="field" placeholder="Введите e-mail: *" name="email"/><br>
            <input type="submit" value="ПОЛУЧИТЬ ФАЙЛЫ!">
        </form>
        <img src="i/social1.jpg"/>
        <img src="i/social2.jpg"/>
        <img src="i/social3.jpg"/>
        <img src="i/social4.jpg"/>

    </div>

    <div id="footer">
        <img src="i/logo.png" style="vertical-align:top;top:40px;position: relative"/>
        <img src="i/soon.png"/>

        <div id="fcontact">
            <p><span style="font: 19px  aAlbionicTitulInflBold, arial;">info@mama-print.ru</span><br>
                <span style="font: 24px  aAlbionicTitulInflBold, arial;">8-908-052-81-87</span><br>
                <a class="button" href="#callback_popup" rel="leanModal">Заказать звонок</a></p>
        </div>
    </div>
</div>

<div id="callback_popup">
    <a href="#" class="close">X</a>

    <form class="{{URL::action('SubscribeController@getCards')}}" action="callback.php" name="callback" method="post">
        <div class="input text">
            <input placeholder="Ваше имя" name="name"/>
        </div>
        <div class="input text">
            <input placeholder="Ваш телефон" name="phone"/>
        </div>
        <div class="input submit">
            <button class="button" onclick="" type="submit">Заказать звонок</button>
        </div>
    </form>
</div>

<script src='jscript/lib/jquery-1.9.1.min.js'></script>
<script src='jscript/lib/jquery.leanModal.min.js'></script>
<script src='jscript/main.js'></script>

@if (Config::get('app.debug'))
    <script src="bower_components/requirejs/require.js" data-main="js/main.js"></script>
@else
    <script src="all.js"></script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">(function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    w.yaCounter22980460 = new Ya.Metrika({
                        id: 22980460,
                        webvisor: true,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true
                    });
                } catch (e) {
                }
            });
            var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () {
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
        })(document, window, "yandex_metrika_callbacks");</script>
    <noscript>
        <div><img src="//mc.yandex.ru/watch/22980460" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript><!-- /Yandex.Metrika counter -->

@endif

</body>

</html>