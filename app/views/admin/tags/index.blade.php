@extends('layouts.admin')

@section('content')


    <form action="{{action('Admin\AdminTagsController@getTags')}}" class="form-inline">
        <div class="form-group ">
            <label class="control-label">Тэг</label>
            <input type="text" class="form-control" name="search" value="{{$search}}">
            &nbsp;
            <label class="control-label">Тип</label>
            <select class="form-control" name="type">
                <option value="">Любой</option>
                <option value="{{Tag::TYPE_TAG}}" @if($type == Tag::TYPE_TAG)
                        selected="selected" @endif>{{Tag::TYPE_TAG}}</option>
                <option value="{{Tag::TYPE_AGE}}" @if($type == Tag::TYPE_AGE)
                        selected="selected" @endif>{{Tag::TYPE_AGE}}</option>
                <option value="{{Tag::TYPE_GOAL}}" @if($type == Tag::TYPE_GOAL)
                        selected="selected" @endif>{{Tag::TYPE_GOAL}}</option>
            </select>
        </div>
        <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span></button>
    </form>

    <form action="{{action('Admin\AdminTagsController@postReorder')}}" method="post">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Тип</th>
                <th>Удалить</th>
                <th>Вес
                    <button type="submit" class="btn btn-xs"><span class="glyphicon glyphicon-ok"></span></button>
                </th>
            </tr>
            </thead>
            @foreach($tags as $tag)
                <tr>
                    <td>
                        # {{$tag->id}}
                    </td>
                    <td>
                        {{$tag->tag}}
                    </td>
                    <td>
                        <i>{{$tag->type}}</i>
                    </td>
                    <td>
                        <a href="{{action('Admin\AdminTagsController@deleteTag', ['id'=>$tag->id])}}" type="submit"
                           class="btn btn-danger btn-xs"><span
                                    class="glyphicon glyphicon-trash"></span>
                        </a>
                    </td>
                    <td>
                        <input type="text" size="3" value="{{$tag->weight}}" name="weights[{{$tag->id}}]" autocomplete="off"/>
                    </td>
                </tr>
            @endforeach
        </table>
    </form>
    <div class="text-center">
        {{$tags->links()}}
    </div>

@stop
