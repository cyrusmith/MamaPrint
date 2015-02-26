@extends('layouts.admin')

@section('content')

    <ul>
        <li>
            <form action="{{action('Admin\AdminSeedController@postInfos')}}" method="post">
                <button class="btn btn-primary">Infos</button>
            </form>
        </li>

    </ul>

@stop