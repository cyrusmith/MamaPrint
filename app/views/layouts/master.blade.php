<html>

<head>

    <title>Mama-print @yield('page_title')</title>

    <meta name='yandex-verification' content='51cf46d6ef645bbd'/>
    <link rel="stylesheet" type="text/css" href="styles.css">

    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

</head>

<body>

<div class="container">

    <div class="row header">

        <div class="col-sm-6 logo">
            <a href="/">
                <img src="img/logobig.png"/>
            </a>
        </div>

        <div class="col-sm-6 text-right contacts hidden-xs">
            <p>
                <a href="mailto:info@mamaprint.ru" class="email">info@mama-print.ru</a>
            </p>
            <p>
                <span class="phone">+7(908)052-81-87 </span>
            </p>

            <a href="https://vk.com/mama_print" target="_blank" class="vk"></a>

        </div>

    </div>

    <div class="page row">
        @yield('content')
    </div>

    <div class="footer row">
        <div class="col-xs-6">mama-print.ru</div>
        <div class="col-xs-6 text-right">&copy; 2014</div>
    </div>

</div>

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