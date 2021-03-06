@extends('layouts.admin')

@section('pagetitle')
    Статьи
@stop

@section('content')
    <form role="form" action="{{action('Admin\AdminArticlesController@postArticle')}}" method="post" enctype="multipart/form-data">

        @if(!empty($id))
            <a href="{{action('ArticlesController@getArticle', ['path'=>$urlpath])}}" class="btn btn-default"
               target="_blank"><span
                        class="glyphicon glyphicon-eye-open"></span> {{Lang::get('static.admin.article.view')}}</a>
        @endif

        <div class="checkbox">
            <label>
                <input type="checkbox"
                       @if(!isset($isblog) || $isblog))
                       checked="checked"
                       @endif value="1" name="isblog"> {{Lang::get('static.admin.article.isblog')}}
            </label>
        </div>

            <div class="checkbox">
            <label>
                <input type="checkbox"
                       @if(!isset($active) || $active))
                       checked="checked"
                       @endif value="1" name="active"> {{Lang::get('static.admin.article.published')}}
            </label>
        </div>

        <div class="row">
            <div class="col-sm-10">

                <div class="form-group {{$errors->has('title')?'has-error':''}}">
                    <label for="title" class="control-label">{{Lang::get('static.admin.article.title')}}</label>
                    <input type="text" class="form-control" id="title"
                           placeholder="" value="{{$title or ''}}"
                           name="title">
                    @if($errors->has('title'))
                        <p class="text-danger">{{$errors->first('title')}}</p>
                    @endif
                </div>


                <div class="form-group {{$errors->has('description')?'has-error':''}}">
                    <label for="description"
                           class="control-label">{{Lang::get('static.admin.article.description')}}</label>
                    <input type="text" class="form-control" id="description"
                           placeholder="" value="{{$description or ''}}"
                           name="description">
                    @if($errors->has('description'))
                        <p class="text-danger">{{$errors->first('description')}}</p>
                    @endif
                </div>

                <div class="form-group {{$errors->has('urlpath')?'has-error':''}}">
                    <label for="description" class="control-label">{{Lang::get('static.admin.article.urlpath')}}</label>
                    <input type="text" class="form-control" id="urlpath"
                           placeholder="" value="{{$urlpath or ''}}"
                           name="urlpath">

                    <p class="text-info">{{Lang::get('static.admin.article.urlpath.help')}}</p>
                    @if($errors->has('urlpath'))
                        <p class="text-danger">{{$errors->first('urlpath')}}</p>
                    @endif
                </div>

                <div class="form-group {{$errors->has('seo_title')?'has-error':''}}">
                    <label for="seo_title" class="control-label">{{Lang::get('static.admin.article.seo_title')}}</label>
                    <input type="text" class="form-control" id="title"
                           placeholder="" value="{{$seo_title or ''}}"
                           name="seo_title">
                    @if($errors->has('seo_title'))
                        <p class="text-danger">{{$errors->first('seo_title')}}</p>
                    @endif
                </div>

                <div class="form-group">
                    @if(!empty($image))
                        <img src="{{$image}}" style="max-width:300px;"/><br/>
                    @endif
                    <label for="image">Картинка для статьи (для pinterest)</label>
                    <input type="file" id="image" name="image">
                    <p class="help-block">png, jpg, gif</p>
                </div>

                <div class="form-group {{$errors->has('publish_date')?'has-error':''}}">
                    <label for="publish_date"
                           class="control-label">{{Lang::get('static.admin.article.publish_date')}}</label>

                    <div class="input-group">
                        <input type="text" class="form-control" name="publish_date" placeholder=""
                               value="{{$publish_date or ''}}" data-datetimepicker>
                          <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><span
                                            class="glyphicon glyphicon-calendar"></span></button>
                            </span>
                    </div>
                </div>

                <div class="form-group {{$errors->has('seo_description')?'has-error':''}}">
                    <label for="seo_description"
                           class="control-label">{{Lang::get('static.admin.article.seo_description')}}</label>
                    <input type="text" class="form-control" id="seo_description"
                           placeholder="" value="{{$seo_description or ''}}"
                           name="seo_description">
                    @if($errors->has('description'))
                        <p class="text-danger">{{$errors->first('seo_description')}}</p>
                    @endif
                </div>

            </div>
            <div class="col-sm-2">
                {{--
    <div class="panel panel-default categories-widget">
        <div class="panel-heading">{{Lang::get('static.articles.categories')}}<a class="btn btn-right btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span></a></div>
        <div class="panel-body">
            <ul>
                <li class="categ" data-id="1"><label><input type="checkbox"> Раздел 1</label></li>
                <li class="categ" data-id="1"><label><input type="checkbox"> Раздел 2</label></li>
                <li class="categ" data-id="1"><label><input type="checkbox"> Раздел 3</label></li>
                <li class="categ" data-id="1"><label><input type="checkbox"> Раздел 4</label></li>
            </ul>


        </div>
    </div>
    --}}
            </div>
        </div>

        <div class="form-group {{$errors->has('content')?'has-error':''}}">
            <label for="content" class="control-label">{{Lang::get('static.admin.article.content')}}</label>
            <textarea class="wysiwyg" name="content"
                      class="form-control" rows="40">{{$content or ''}}</textarea>
            @if($errors->has('content'))
                <p class="text-danger">{{$errors->first('content')}}</p>
            @endif
        </div>

        <input type="hidden" name="id" value="{{$id or ''}}"/>

    </form>
@stop