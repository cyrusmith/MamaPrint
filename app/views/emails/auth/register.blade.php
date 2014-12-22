<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<?php
$url = URL::action("AuthController@confirm", array("hash" => $hash));
?>
<h2>Регистрация на mama-print.ru</h2>

<div>
    <p>Спасибо за регистрацию на сайте mama-print.ru!</p>

    <p>Чтобы подтвердить ваш емейл, пройдите по этой ссылке: <a
                href="{{$url}}">$url</a></p>

    <p>Внимание! Если вы не регистрировались на сайте mama-print.ru, то просто игнорируйте данное письмо.</p>

    <p>С уважением, команда Mama-Print.ru. Скачайте и распечатайте качественные
        материалы для занятий с ребенком 0+</p>

    <p><a href="http://mama-print.ru">http://mama-print.ru</a><br>
        <a href="https://vk.com/mamaprint">https://vk.com/mamaprint</a></p>

</div>
</body>
</html>
