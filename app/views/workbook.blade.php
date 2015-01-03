@extends('layouts.master')

@section('content')
    <div class="curlytitle">
        <h2 Class="font-hanwritten">Зимняя тетрадка</h2>
    </div>
    <div class="title">
        <h1>
            Тридцать творческих уроков на тему: «Новый год и зима» для детей дошкольного возраста
        </h1>
        <h4>Вы будете счастливы видеть, как ваш ребенок становится настоящим художником!</h4>
    </div>

    <div class="preview">
        <div data-configid="0/10552992" style="width:100%; height:400px;" class="issuuembed"></div>
        <script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>
    </div>

    <div class="row">

        <div class="article col-sm-8 col-lg-offset-2">

            <h3>Чем заняться с детьми в канун Нового Года?</h3>

            <p>Вам в помощь «Зимняя тетрадка». Комплект будет интересен для мамы ребенка с года до шести лет. Также,
                эти
                задания могут статья отличным помощником педагогу в детском саду.</p>

            <h3>Что понадобится?</h3>

            <ul>
                <li>Скачайте и распечатайте шаблоны на цветном принтере (Листочки А4)</li>
                <li>Прочтите инструкцию на листочке и приготовьте нужные материалы для творчества. Всё легко найти
                    дома.
                </li>
                <li>Теперь можно творить!</li>
            </ul>

            <div class="c2a">
                @if(Auth::check())
                    <form action="{{URL::action('OrdersController@buyitem', ['itemId' => '1'])}}" method="post">
                        <button class="btn btn-lg btn-danger">Скачать за 39 рублей</button>
                        <input type="hidden" name="_token" value="<?php echo csrf_token();?>"/>
                    </form>
                @else
                    <form action="{{URL::action('OrdersController@buyitem', ['itemId' => '1'])}}" method="post">
                        <button class="btn btn-lg btn-danger">Скачать за 99 рублей</button>
                        <input type="hidden" name="_token" value="<?php echo csrf_token();?>"/>
                    </form>
                    <p>или <a href="/login" class="btn btn-sm btn-default">войти</a> и скачать всего <strong>за 39
                            рублей!</strong></p>
                @endif
            </div>

            <h3>Есть ли в этом какая-то польза?</h3>

            <ol>
                <li>У ребёнка появляются новые представления об окружающем

                    мире, формируется восприятие оттенков, размеров.
                </li>
                <li>Развивается мелкая моторика, координация руки, воображение,

                    интеллект.
                </li>
                <li>Укрепляется стремление к творческой деятельности.
                </li>
            </ol>

            <img src="/img/collage.jpg" class="collage"/>

            <div class="c2a">
                @if(Auth::check())
                    <form action="{{URL::action('OrdersController@buyitem', ['itemId' => '1'])}}" method="post">
                        <button class="btn btn-lg btn-danger">Скачать за 39 рублей</button>
                        <input type="hidden" name="_token" value="<?php echo csrf_token();?>"/>
                    </form>
                @else
                    <form action="{{URL::action('OrdersController@buyitem', ['itemId' => '1'])}}" method="post">
                        <button class="btn btn-lg btn-danger">Скачать за 99 рублей</button>
                        <input type="hidden" name="_token" value="<?php echo csrf_token();?>"/>
                    </form>
                    <p>или <a href="/login" class="btn btn-sm btn-default">войти</a> и скачать всего <strong>за 39
                            рублей!</strong></p>
                @endif
            </div>

        </div>
    </div>

@stop
