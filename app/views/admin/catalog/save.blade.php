@extends('layouts.admin')

@section('pagetitle')
    Каталог|Новый товар
@stop

@section('content')
    <form action="" method="post">
        <div class="form-group">
            <label for="catitemtitle">{{Lang::get('static.admin.catitem.title')}}</label>
            <input type="text" class="form-control" id="catitemtitle"
                   placeholder="{{Lang::get('static.admin.catitem.title.help')}}">
        </div>
        <div class="form-group">
            <label for="catitemslug">{{Lang::get('static.admin.catitem.slug')}}</label>
            <input type="text" class="form-control" id="catitemslug"
                   placeholder="{{Lang::get('static.admin.catitem.slug.help')}}">
        </div>
        <div class="form-group">
            <label for="catitembreaf">{{Lang::get('static.admin.catitem.breaf')}}</label>
            <input type="text" class="form-control" id="catitembreaf"
                   placeholder="{{Lang::get('static.admin.catitem.breaf.help')}}">
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="catitempriсe">{{Lang::get('static.admin.catitem.priсe')}}</label>
                    <input type="text" class="form-control" id="catitempriсe"
                           placeholder="{{Lang::get('static.admin.catitem.priсe.help')}}">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="catitempriceold">{{Lang::get('static.admin.catitem.priceold')}}</label>
                    <input type="text" class="form-control" id="catitempriceold"
                           placeholder="{{Lang::get('static.admin.catitem.priceold.help')}}">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="catitempriceregistered">{{Lang::get('static.admin.catitem.priceregistered')}}</label>
                    <input type="text" class="form-control" id="catitempriceregistered"
                           placeholder="{{Lang::get('static.admin.catitem.priceregistered.help')}}">
                </div>
            </div>

        </div>

        <div class="form-group">
            <label for="article">{{Lang::get('static.admin.catitem.descr')}}</label>
            <textarea class="wysiwyg"></textarea>
        </div>
        <div class="form-group">
            <label for="catitemtags">{{Lang::get('static.admin.catitem.tags')}}</label>
            <input type="text" class="form-control" id="catitemtags"
                   placeholder="{{Lang::get('static.admin.catitem.tags.help')}}">
        </div>

        <fieldset>
            <legend>Доп. информация</legend>
            <div class="form-group">
                <label for="catitemage">{{Lang::get('static.admin.catitem.age')}}</label>
                <input type="text" class="form-control" id="catitemage"
                       placeholder="{{Lang::get('static.admin.catitem.age.help')}}">
            </div>

            <div class="form-group">
                <label for="catitemtargets">{{Lang::get('static.admin.catitem.targets')}}</label>
                <input type="text" class="form-control" id="catitemtargets"
                       placeholder="{{Lang::get('static.admin.catitem.targets.help')}}">
            </div>

            <div class="form-group">
                <label for="catitemlevel">{{Lang::get('static.admin.catitem.level')}}</label>
                <input type="text" class="form-control" id="catitemlevel"
                       placeholder="{{Lang::get('static.admin.catitem.level.help')}}">
            </div>

        </fieldset>
        <button type="submit" class="btn btn-default">{{Lang::get('static.admin.save')}}</button>
    </form>
@stop
