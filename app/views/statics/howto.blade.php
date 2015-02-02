@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2 payconfirm">
        <h2>Как оформить заказ</h2>


        @if(!Auth::check())
            <p>Мы рекомендуем вам <a href="/register">зарегистрироваться</a> или <a href="/login">войти</a>, прежде чем
                начать делать покупки.</p>
            <ul>
                <li>цены для зарегистрированных пользователей ниже</li>
                <li>купленные материалы будут всегда доступны из вашего личного кабинета</li>
                <li>возможность участвовать в акциях и мероприятиях</li>
            </ul>
            <p>Однако, вы можете совершать покупки, и не регистрируясь на сайте.</p>
        @endif

        <p>Добавьте товары в корзину и перейдите на <a href="http://mamaprint127.ru/cart">страницу редактирования</a>.
            Вы можете удалить некоторые позиции из корзины.</p>

        <p>Вы также можете воспользоваться кнопкой "купить в один клик" на странице товара. Это значит, что будет создан
            заказ с одним единственным материалом, без предварительного добавления его в корзину.</p>

        <p>Нажмите на кнопку "Оплатить". Бы будете перенаправлены на страницу оплаты платежного агрегатора onpay.ru</p>

        @if(!Auth::check())
            <p>Если вы не зарегистрированы на сайте mama-print.ru, то тут ван нужно ввести ваш емейл. Мы не сохраняем
                ваш
                емейл, он нужет только для того, чтобы отправить вам временную ссылку для скачивания материала.</p>
            <p>Если вы зарегистрированы, то материалы
                становятся доступным в личном кабинете.</p>
        @else
            <p>Вам также придет письмо с временной ссылкой. Дополнительно, материалы
                становятся доступным в личном кабинете.</p>
        @endif

        <p>Выберите способ оплаты и нажмите кнопку "Продолжить". Вы будете перенаправлены на страницу сервиса, который
            отвечает за оплату выбранным вами способом.</p>

        <p>После оплаты автоматически перейдете обратно на сайт, а на ваш email придет письмо со ссылкой для
            скачивания.</p>

        <p>Если что-то пошло не так или остались вопросы по работе сайта, то напишите нам на info@mama-print.ru или
            позвоните по телефону +7-908-052-81-87.</p>

        <p><b>Желаем вам приятных и полезных минут с вашим ребенком!</b></p>

    </div>

@stop