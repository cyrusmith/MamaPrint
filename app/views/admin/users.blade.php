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

    <table class="table table-condensed">

        <tr>
            <th>#</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Заказы</th>
        </tr>

        @foreach($users as $user)
            <tr>
                <td>
                    <a href="/admin/users/{{$user->id}}" class="btn btn-primary btn-xs">{{$user->id}}</a>
                </td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td><a href="/admin/users/{{$user->id}}/orders" class="btn btn-info btn-xs">{{$user->orders->count()}}</a>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="text-center">
        {{$users->links()}}
    </div>

@stop