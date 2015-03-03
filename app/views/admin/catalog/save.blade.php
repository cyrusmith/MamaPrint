@define $sessionData = Session::pull('data')

@if(!empty($sessionData))
    @define $data = $sessionData
@endif

@extends('layouts.admin')

@section('pagetitle')
    Каталог | Новый товар
@stop

@section('content')

    <form role="form" action="{{URL::action('Admin\AdminCatalogController@postItem')}}" method="post"
          enctype="multipart/form-data">

        @if(!empty($data['id']))
            <a href="/catalog/{{$data['slug']}}" class="btn btn-default" target="_blank"><span
                        class="glyphicon glyphicon-eye-open"></span> Просмотр</a>
        @endif

        <div class="row">
            <div class="col-sm-3 checkbox">
                <label>
                    <input type="checkbox"
                           @if(!isset($data['active']) || $data['active']))
                           checked="checked"
                           @endif value="1" name="active"> Активен
                </label>
            </div>
            <div class="col-sm-3">
                <label>Вес:</label>
                <input class="form-control" value="{{$data['weight'] or 0}}" name="weight"/>
            </div>
        </div>
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

        <fieldset class="attachments">
            <legend><span class="glyphicon glyphicon-file"></span> Файлы</legend>

            <div class="attachmentslist" data-widget="attachments">

                <ul class="list" data-container="attachments">
                </ul>

                <a class="btn btn-default btn-primary btn-sm" data-control="addfile"><span
                            class="glyphicon glyphicon-plus"></span> {{Lang::get('static.admin.addattachment')}}</a>

            </div>

        </fieldset>

        <fieldset class="gallery">
            <legend><span class="glyphicon glyphicon-file"></span> {{Lang::get('static.admin.catitem.gallery')}}
            </legend>

            <div class="galleryimages" data-widget="gallery">

                <ul class="list" data-container="images">
                </ul>

                <a class="btn btn-default btn-primary btn-sm" data-control="addimage"><span
                            class="glyphicon glyphicon-plus"></span> {{Lang::get('static.admin.catitem.addimage')}}</a>

            </div>

        </fieldset>

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
                   name="tags"
                   placeholder="{{Lang::get('static.admin.catitem.tags.help')}}" value="{{$data['tags'] or ''}}">
            @if($errors->has('tags'))
                <p class="text-danger">{{$errors->first('tags')}}</p>
            @endif
        </div>

        <fieldset>
            <legend><span class="glyphicon glyphicon-list"></span> {{Lang::get('static.admin.catalogitem.related')}}
            </legend>
            <div class="form-group {{$errors->has('info_age')?'has-error':''}}" data-widget="autocomplete"
                 data-url="{{action('Admin\AdminCatalogController@index')}}" data-exclude="{{$data['id'] or ''}}">
                <input type="text" class="form-control"
                       value="{{$data['relatedtitles']}}"/>
                <input type="hidden" name="related" value="{{$data['relatedids']}}"/>

                <p>
                    <small>Начните вводить название материала</small>
                </p>
            </div>
        </fieldset>

        <fieldset>
            <legend><span class="glyphicon glyphicon-info-sign"></span> Доп. информация</legend>
            <div class="form-group {{$errors->has('info_age')?'has-error':''}}">
                <label for="catitemage" class="control-label">{{Lang::get('static.admin.catitem.age')}}</label>
                <select class="form-control" id="catitemage"
                        name="info_age"
                        value="{{$data['info_age'] or ''}}">
                    <option value="любой" @if(!empty($data['info_age']) && $data['info_age']=='любой') selected="selected" @endif>любой</option>
                    <option value="малыш" @if(!empty($data['info_age']) && $data['info_age']=='малыш') selected="selected" @endif>малыш</option>
                    <option value="детский сад" @if(!empty($data['info_age'])) && $data['info_age']=='детский сад') selected="selected" @endif>детский
                        сад
                    </option>
                    <option value="дошкольник" @if(!empty($data['info_age']) && $data['info_age']=='дошкольник') selected="selected" @endif>
                        дошкольник
                    </option>
                    <option value="1 класс" @if(!empty($data['info_age']) && $data['info_age']=='1 класс') selected="selected" @endif>1 класс
                    </option>
                    <option value="2 класс" @if(!empty($data['info_age'])&& $data['info_age']=='2 класс') selected="selected" @endif>2 класс
                    </option>
                    <option value="3 класс" @if(!empty($data['info_age']) && $data['info_age']=='3 класс') selected="selected" @endif>3 класс
                    </option>
                    <option value="4 класс" @if(!empty($data['info_age']) && $data['info_age']=='4 класс') selected="selected" @endif>4 класс
                    </option>
                </select>
                @if($errors->has('info_age'))
                    <p class="text-danger">{{$errors->first('info_age')}}</p>
                @endif
            </div>

            <div class="form-group {{$errors->has('info_targets')?'has-error':''}}">
                <label for="catitemtargets" class="control-label">{{Lang::get('static.admin.catitem.targets')}}</label>
                <input type="text" class="form-control" id="catitemtargets"
                       placeholder="{{Lang::get('static.admin.catitem.targets.help')}}"
                       name="info_targets"
                       value="{{$data['info_targets'] or ''}}">
                @if($errors->has('info_targets'))
                    <p class="text-danger">{{$errors->first('info_targets')}}</p>
                @endif
            </div>

            <div class="form-group {{$errors->has('info_level')?'has-error':''}}">
                <label for="catitemlevel" class="control-label">{{Lang::get('static.admin.catitem.level')}}</label>
                <input type="text" class="form-control" id="catitemlevel"
                       name="info_level"
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


    <script type="x-tpl" id="attachment-item-models-json">
        {{$attachments or '[]'}}







    </script>

    <script type="x-tpl" id="gallery-images-json">
        {{$images or '[]'}}







    </script>

    <script type="x-tpl" id="related-json">
        {{$data['related'] or '[]'}}



    </script>

    <script type="x-tpl" id="gallery-item-tpl">
        <li>
            <a href="/images/<%= id %>"
               class="img-rounded"
               style="background:url(/images/<%= id %>?width=200&height=220&crop=1) no-repeat 50% 50%;background-size: auto 100%;"
               target="_blank">
               <% if(!id) { %>

               <div class="text-center file">
                    <input type="file" name="gallery_image[]">
               </div>

               <% } %>
                <span class="control-delete btn btn-xs btn-danger glyphicon glyphicon-trash"></span>
            </a>
        </li>







    </script>
    <script type="x-tpl" id="attachment-item-tpl">
        <li class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4>{{Lang::get('static.admin.catitem.attachment')}}</h4>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a class="btn btn-default btn-danger btn-xs" data-control="removefile"><span
                                                class="glyphicon glyphicon-trash"></span> {{Lang::get('static.admin.removeattachment')}}
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="attachment<%= id %>title"
                                       class="control-label">{{Lang::get('static.admin.catitem.attachmenttitle')}}</label>
                                <input data-field="title" type="text" class="form-control" id="attachment<%= id %>
        title" name="attachment_title[]" value="<%= title %>">
                            </div>
                            <div class="form-group">
                                <label for="attachment<%= id %>description"
                                       class="control-label">{{Lang::get('static.admin.catitem.attachmentdescription')}}</label>
                                <textarea data-field="description" id="attachment<%= id %>description"
                                          class="form-control" name="attachment_description[]"><%= description %></textarea>
                            </div>
                            <% if (!id) { %>
                            <div class="form-group">
                                <label for="attachment1"
                                       class="control-label">{{Lang::get('static.admin.catitem.choosefile')}}</label>
                                <input type="file" name="attachment[]" id="attachment<%= id %>" required>
                            </div>
                            <% } else { %>
                                <div class="downloadlink">
                                    <span class="glyphicon glyphicon-download"></span><a href="/admin/attachments/<%= id %>/download">Скачать</a>
                                </div>
                            <% }  %>
                            <dl>
                                <dl>
                                    <dt>{{Lang::get('static.admin.catitem.attachment.extension')}}</dt>
                                    <dd><%= extension %></dd>
                                    <dt>{{Lang::get('static.admin.catitem.attachment.mime')}}</dt>
                                    <dd><%= mime %></dd>
                                    <dt>{{Lang::get('static.admin.catitem.attachment.size')}}</dt>
                                    <dd><%= size %></dd>
                                </dl>
                            </dl>
                        </div>
                         <% if (!!id) { %>
                        <div class="savecontrols text-right">
                                        <a class="btn btn-primary btn-xs" data-control="savefile"><span
                                                    class="glyphicon glyphicon-check"></span> {{Lang::get('static.admin.saveattachment')}}
                                        </a>
                               <div class="progress">
                                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    <span class="sr-only">In progress</span>
                                  </div>
                               </div>
                        </div>
                         <% }  %>
                    </li>





























    </script>

@stop
