@extends('layouts.admin')

@section('pagetitle')
   Каталог|Новый товар
@stop

@section('content')
    <form action="" method="post">
        <div class="form-group">
            <label for="catitemtitle">{{Lang::get('static.admin.catitem.title')}}</label>
            <input type="text" class="form-control" id="catitemtitle" placeholder="{{Lang::get('static.admin.catitem.title.help')}}">
        </div>
        <div class="form-group">
            <label for="catitembreaf">{{Lang::get('static.admin.catitem.breaf')}}</label>
            <input type="text" class="form-control" id="catitembreaf" placeholder="{{Lang::get('static.admin.catitem.breaf.help')}}">
        </div>
        <div class="form-group">
            <label for="article">{{Lang::get('static.admin.catitem.descr')}}</label>
            <textarea class="wysiwyg"></textarea>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox"> Check me out
            </label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
@stop
