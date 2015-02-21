@define $sessionData = Session::pull('data')

@if(!empty($sessionData))
    @define $data = $sessionData
@endif

@extends('layouts.admin')

@section('pagetitle')
    Шаблоны/Редактировать
@stop

@section('content')

    <form role="form" action="{{URL::action('Admin\CustomTemplatesController@postTemplate')}}" method="post"
          enctype="multipart/form-data">

        <div class="form-group {{$errors->has('name')?'has-error':''}}">
            <label for="name" class="control-label">Название</label>
            <input type="text" class="form-control" id="name"
                   value="{{$data['name'] or ''}}"
                   name="name">
            @if($errors->has('name'))
                <p class="text-danger">{{$errors->first('name')}}</p>
            @endif
        </div>

        <div class="form-group {{$errors->has('colors')?'has-error':''}}">
            <label for="colors" class="control-label">Цвета</label>
            <input type="text" class="form-control" id="colors"
                   value="{{$data['colors'] or ''}}"
                   name="colors">
            @if($errors->has('colors'))
                <p class="text-danger">{{$errors->first('colors')}}</p>
            @endif
            <p class="help-block">Например: #550099, #345234, #FF00DD</p>
        </div>

        @if(!empty($data['id']))
            <img src="{{action('Admin\CustomTemplatesController@getImage', ['id'=>$data['id']])}}"/>
        @endif

        <div class="form-group">
            <label for="background">Изображение</label>
            <input type="file" id="background" name="background">

            <p class="help-block">Изображение JPG,PNG,GIF</p>
        </div>

        <input type="hidden" name="id" value="{{$data['id'] or ''}}"/>

    </form>

@stop