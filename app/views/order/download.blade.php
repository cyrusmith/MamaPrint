@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2 payconfirm">
        <h2>Временная страница для скачивания материалов</h2>

        <p class="text-center"><a href="/downloads/{{$token}}/download" class="btn btn-primary"><span
                        class="glyphicon glyphicon-download"></span> Скачать материалы</a></p>

        <p class="text-danger"><strong>Внимание!</strong> Это временная страница с доступом всего
            на {{Config::get('mamaprint.download_link_timeout')}} мин.</p>

        <p>Чтобы скачивать оплаченные материалы, <a href="/login">войдите</a> или <a
                    href="/register">зарегистрируйтесь</a> на сайте. Ваши материалы всегда будут доступны в личном кабинете.
        </p>

    </div>

@stop