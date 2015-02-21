@extends('layouts.admin')

@section('pagetitle')
    Каталог
@stop

@section('content')

    <table class="table">
        <tr>
            <th>Картинка</th>
            <th>Название</th>
            <th>Цвета</th>
        </tr>
        @foreach($templates as $template)
            <tr>
                <td>
                    <a href="{{action('Admin\CustomTemplatesController@getImage', ['id'=>$template->id])}}"><img src="{{action('Admin\CustomTemplatesController@getImage', ['id'=>$template->id, 'width'=>50])}}"/></a>
                </td>
                <td><a href="{{URL::action('Admin\CustomTemplatesController@getTemplate', [
                        'id' => $template->id
                    ])}}">{{$template->name}}</a></td>
                <th>
                    @foreach(explode(",", $template->colors) as $color)
                        <span class="label label-default" style="background-color: {{$color}}">{{$color}}</span>
                    @endforeach
                </th>
            </tr>
        @endforeach
    </table>
    <div class="text-center">
        {{$templates->links()}}
    </div>

@stop
