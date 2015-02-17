@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <form action="{{action('Admin\AdminUsersController@getUsers')}}" class="form-inline">
        <div class="form-group ">
            <label class="control-label">Фильтр</label>
            <input type="text" class="form-control" name="search" value="{{$search}}">
        </div>
        <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span></button>
    </form>

    <p style="padding-top: 1em;"><label>Всего:</label> {{$count}}</p>

    <table class="table table-condensed">

        <tr>
            <th>Имя</th>
            <th>Email</th>
            <th>Дата регистрации</th>
            <th>Заказы</th>
        </tr>

        @foreach($users as $user)
            <tr>
                <td><a href="/admin/users/{{$user->id}}" class="btn btn-primary btn-xs">{{$user->name}}</a></td>
                <td>{{$user->email}}</td>
                <td>{{$user->created_at}}</td>
                <td>
                    @if($user->orders->count() == 0)
                        -
                    @else
                        <span class="label label-default">{{$user->orders->count()}}</span><a
                                href="/admin/users/{{$user->id}}/orders" class="btn btn-link btn-xs">Просмотр</a>
                    @endif
                </td>
            </tr>
        @endforeach

    </table>

    <div class="text-center">
        {{$users->links()}}
    </div>

@stop