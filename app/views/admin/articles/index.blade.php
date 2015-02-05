<?php
use Illuminate\Support\Facades\Lang;

?>

@extends('layouts.admin')

@section('pagetitle')
    {{Lang::get('static.admin.articles')}}
@stop

@section('content')


    <form action="{{action('Admin\AdminArticlesController@getArticles')}}" class="form-inline">
        <div class="form-group ">
            <label class="control-label">Фильтр</label>
            <input type="text" class="form-control" name="search" value="{{$search}}">
        </div>
        <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span></button>
    </form>
    <table class="table">
        @foreach($articles as $article)
            <tr>
                <td>
                    # {{$article->id}}
                </td>
                <td><a href="{{URL::action('Admin\AdminArticlesController@getArticle', [
                        'id' => $article->id
                    ])}}">{{$article->title}}</a></td>
                <td>{{$article->description}}</td>
            </tr>
        @endforeach
    </table>
    <div class="text-center">
        {{$articles->links()}}
    </div>

@stop
