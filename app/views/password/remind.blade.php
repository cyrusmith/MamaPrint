@extends('layouts.master')

@section('content')
    <div class="col-sm-6 col-sm-offset-3 register">

        <h2>Напоминание пароля</h2>

        <form action="{{ action('RemindersController@postRemind') }}" method="post">
            <div class="form-group">
                <label for="email">Ваш email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="">
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>

    </div>
@stop