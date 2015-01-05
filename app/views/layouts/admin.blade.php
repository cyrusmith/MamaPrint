<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="/admin/styles.css">
</head>
<body>

<div class="container">

    <div class="col-xs-3">

        <ul class="menu">
            <li><a href="/admin/catalog">Каталог</a></li>
            <li><a href="/admin/users">Пользователи</a></li>
            <li><a href="/admin/stats">Статистика</a></li>
            <li><a href="/admin/operations">Операции</a></li>
        </ul>

    </div>

    <div class="col-xs-9">

        @yield('content')

    </div>

</div>

</body>
</html>