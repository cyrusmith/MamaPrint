<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Покупка сайте на mama-print.ru</h2>

<div>
    <p>Спасибо за покупку на сайте mama-print.ru!</p>

    <p>Чтобы скачать ваш заказ, пройдите по ссылке <a
                href="{{URL::to('/downloads/'.$token)}}">{{URL::to('/downloads/'.$token)}}</a></p>

    <p><strong style="font-weight: bold;">Внимание!</strong> Ссылка действует в
        течение {{Config::get('mamaprint.download_link_timeout')}} мин.</p>

    @if(Auth::check())
        <p>Также, оплаченные материалы доступны в вашем личном кабинете {{URL::to('/user')}}.</p>
    @else
        <p>Чтобы иметь постоянный доступ к приобретеным материалам, необходимо <a href="{{URL::to('/register/')}}">зарегистрироваться
                на сайте</a>.</p>
    @endif

    <p>С уважением, команда Mama-Print.ru.<br><i>Скачайте и распечатайте качественные
            материалы для занятий с ребенком 0+</i></p>

    <p><a href="http://mama-print.ru">http://mama-print.ru</a><br>
        <a href="https://vk.com/mamaprint">https://vk.com/mamaprint</a></p>

</div>
</body>
</html>