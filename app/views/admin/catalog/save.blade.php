@define $sessionData = Session::pull('data')

@if(!empty($sessionData))
    @define $data = $sessionData
@endif

@extends('layouts.admin')

@section('pagetitle')
    Каталог | Новый товар
@stop

@section('content')
    <form role="form" action="{{URL::action('Admin\AdminCatalogController@save')}}" method="post">
        <div class="form-group {{$errors->has('title')?'has-error':''}}">
            <label for="catitemtitle" class="control-label">{{Lang::get('static.admin.catitem.title')}}</label>
            <input type="text" class="form-control" id="catitemtitle"
                   placeholder="{{Lang::get('static.admin.catitem.title.help')}}" value="{{$data['title'] or ''}}"
                   name="title">
            @if($errors->has('title'))
                <p class="text-danger">{{$errors->first('title')}}</p>
            @endif
        </div>
        <div class="form-group {{$errors->has('slug')?'has-error':''}}">
            <label for="catitemslug" class="control-label">{{Lang::get('static.admin.catitem.slug')}}</label>
            <input type="text" class="form-control" id="catitemslug"
                   placeholder="{{Lang::get('static.admin.catitem.slug.help')}}" value="{{$data['slug'] or ''}}"
                   name="slug">
            @if($errors->has('slug'))
                <p class="text-danger">{{$errors->first('slug')}}</p>
            @endif
        </div>
        <div class="form-group {{$errors->has('short_description')?'has-error':''}}">
            <label for="catitembreaf" class="control-label">{{Lang::get('static.admin.catitem.breaf')}}</label>
            <input type="text" class="form-control" id="catitembreaf"
                   placeholder="{{Lang::get('static.admin.catitem.breaf.help')}}"
                   value="{{$data['short_description'] or ''}}"
                   name="short_description">
            @if($errors->has('short_description'))
                <p class="text-danger">{{$errors->first('short_description')}}</p>
            @endif
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="form-group {{$errors->has('price')?'has-error':''}}">
                    <label for="catitempriсe" class="control-label">{{Lang::get('static.admin.catitem.priсe')}}
                        , {{Lang::get('static.admin.currency.rub')}}</label>
                    <input type="text" class="form-control" id="catitempriсe"
                           placeholder="{{Lang::get('static.admin.catitem.priсe.help')}}"
                           value="{{(isset($data['price'])?$data['price']:0)/100}}"
                           name="price">
                    @if($errors->has('price'))
                        <p class="text-danger">{{$errors->first('price')}}</p>
                    @endif
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group {{$errors->has('old_price')?'has-error':''}}">
                    <label for="catitempriceold"
                           class="control-label">{{Lang::get('static.admin.catitem.priceold')}}
                        , {{Lang::get('static.admin.currency.rub')}}</label>
                    <input type="text" class="form-control" id="catitempriceold"
                           placeholder="{{Lang::get('static.admin.catitem.priceold.help')}}"
                           value="{{(isset($data['old_price'])?$data['old_price']:0)/100}}" name="old_price">
                    @if($errors->has('old_price'))
                        <p class="text-danger">{{$errors->first('old_price')}}</p>
                    @endif
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group {{$errors->has('registered_price')?'has-error':''}}">
                    <label for="catitempriceregistered"
                           class="control-label">{{Lang::get('static.admin.catitem.priceregistered')}}
                        , {{Lang::get('static.admin.currency.rub')}}</label>
                    <input type="text" class="form-control" id="catitempriceregistered"
                           placeholder="{{Lang::get('static.admin.catitem.priceregistered.help')}}"
                           value="{{(isset($data['registered_price'])?$data['registered_price']:0)/100}}"
                           name="registered_price">
                    @if($errors->has('registered_price'))
                        <p class="text-danger">{{$errors->first('registered_price')}}</p>
                    @endif
                </div>
            </div>

        </div>

        <div class="form-group {{$errors->has('long_description')?'has-error':''}}">
            <label for="article" class="control-label">{{Lang::get('static.admin.catitem.descr')}}</label>
            <textarea class="wysiwyg" name="long_description"
                      class="form-control">{{$data['long_description'] or ''}}</textarea>
            @if($errors->has('long_description'))
                <p class="text-danger">{{$errors->first('long_description')}}</p>
            @endif
        </div>
        <div class="form-group {{$errors->has('tags')?'has-error':''}}">
            <label for="catitemtags" class="control-label">{{Lang::get('static.admin.catitem.tags')}}</label>
            <input type="text" class="form-control" id="catitemtags"
                   placeholder="{{Lang::get('static.admin.catitem.tags.help')}}" value="{{$data['tags'] or ''}}">
            @if($errors->has('tags'))
                <p class="text-danger">{{$errors->first('tags')}}</p>
            @endif
        </div>

        <fieldset>
            <legend>Файлы</legend>

            <div class="attachments" data-controller="AttachmentsController">

                <ul class="list" data-container="attachments">
                    <li class="panel panel-default">
                        <div class="panel-heading">{{Lang::get('static.admin.catitem.attachment')}}</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="attachment1title"
                                       class="control-label">{{Lang::get('static.admin.catitem.attachmenttitle')}}</label>
                                <input type="text" class="form-control" id="attachment1title">
                            </div>
                            <div class="form-group">
                                <label for="attachment1description"
                                       class="control-label">{{Lang::get('static.admin.catitem.attachmentdescription')}}</label>
                                <textarea id="attachment1description" name="attachment1description"
                                          class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="attachment1"
                                       class="control-label">{{Lang::get('static.admin.catitem.choosefile')}}</label>
                                <input type="file" id="attachment1">
                            </div>
                            <p class="text-right">
                                <a class="btn btn-default btn-danger btn-sm" data-control="addfile"><span
                                            class="glyphicon glyphicon-trash"></span> {{Lang::get('static.admin.removeattachment')}}</a>
                            </p>
                        </div>

                    </li>
                </ul>

                <a class="btn btn-default btn-primary btn-sm" data-control="addfile"><span
                            class="glyphicon glyphicon-plus"></span> {{Lang::get('static.admin.addattachment')}}</a>

            </div>

        </fieldset>

        <fieldset>
            <legend>Доп. информация</legend>
            <div class="form-group {{$errors->has('info_age')?'has-error':''}}">
                <label for="catitemage" class="control-label">{{Lang::get('static.admin.catitem.age')}}</label>
                <input type="text" class="form-control" id="catitemage"
                       placeholder="{{Lang::get('static.admin.catitem.age.help')}}" value="{{$data['info_age'] or ''}}">
                @if($errors->has('info_age'))
                    <p class="text-danger">{{$errors->first('info_age')}}</p>
                @endif
            </div>

            <div class="form-group {{$errors->has('info_targets')?'has-error':''}}">
                <label for="catitemtargets" class="control-label">{{Lang::get('static.admin.catitem.targets')}}</label>
                <input type="text" class="form-control" id="catitemtargets"
                       placeholder="{{Lang::get('static.admin.catitem.targets.help')}}"
                       value="{{$data['info_targets'] or ''}}">
                @if($errors->has('info_targets'))
                    <p class="text-danger">{{$errors->first('info_targets')}}</p>
                @endif
            </div>

            <div class="form-group {{$errors->has('info_level')?'has-error':''}}">
                <label for="catitemlevel" class="control-label">{{Lang::get('static.admin.catitem.level')}}</label>
                <input type="text" class="form-control" id="catitemlevel"
                       placeholder="{{Lang::get('static.admin.catitem.level.help')}}"
                       value="{{$data['info_level'] or ''}}">
                @if($errors->has('info_level'))
                    <p class="text-danger">{{$errors->first('info_level')}}</p>
                @endif
            </div>

        </fieldset>
        <button type="submit" class="btn btn-default">{{Lang::get('static.admin.save')}}</button>
        <input type="hidden" name="id" value="{{$data['id'] or ''}}">
    </form>
@stop
