<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Сброс пароля</h2>

<p>
    Чтобы переустановить пароль, перейдите по ссылке: {{ URL::to('remindpassword', array($token)) }}.<br/>
    Ссылка работает в течение {{ Config::get('auth.reminder.expire', 60) }} мин.
</p>

<p>
    <strong>Внимание!</strong> Если вы не запрашивали сброс пароля, то просто игнорируйте данное письмо.
    Если подобные письма продолжают приходить, то пожалуйста, сообщите нам об этом на email info@mama-print.ru
</p>

<p>С уважением, команда Mama-Print.ru.<br><i>Скачайте и распечатайте качественные
        материалы для занятий с ребенком 0+</i></p>

<p><a href="http://mama-print.ru">http://mama-print.ru</a><br>
    <a href="https://vk.com/mamaprint">https://vk.com/mamaprint</a></p>

</body>
</html>
