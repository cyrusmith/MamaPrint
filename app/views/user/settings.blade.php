@extends('user.userarea')

@section('userarea_content')

    <div class="row">
        <div class="col-sm-4">
            <form action="{{URL::action('UserController@saveName')}}" method="post">

                <div class="form-group">

                    @define $hasEmail = !empty(Auth::user()->email)
                    @define $fromRedir = Session::get('success')

                    <label for="name">E-mail</label>
                    <input type="text" class="form-control @if(!$hasEmail && empty($fromRedir)) popoveropen @endif" id="name" placeholder="Ваш email" name="email"
                           value="{{Auth::user()->email}}" @if($hasEmail) readonly="true" @elseif(empty($fromRedir)) data-toggle="popover" title="Укажите email" data-content="Подтвердите email и пользуйтесь услугами сайта без ограничений" @endif />
                </div>
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" id="name" placeholder="Ваше имя (ник)" name="name"
                           value="{{Auth::user()->name}}">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>

            </form>

            <br/>
            <form action="{{URL::action('UserController@savePassword')}}" method="post">

                <fieldset>

                    <legend>Изменить пароль</legend>

                    <div class="form-group">
                        <label for="oldpassword">Старый пароль</label>
                        <input type="password" class="form-control" id="oldpassword" name="oldpassword">
                    </div>

                    <div class="form-group">
                        <label for="newpassword">Новый пароль</label>
                        <input type="password" class="form-control" id="newpassword" name="newpassword">
                    </div>

                    <div class="form-group">
                        <label for="newpassword2">Повторить новый пароль</label>
                        <input type="password" class="form-control" id="newpassword2" name="newpassword2">
                    </div>

                </fieldset>
                <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>

            </form>

        </div>

    </div>

@stop