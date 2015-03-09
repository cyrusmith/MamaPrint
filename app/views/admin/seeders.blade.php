@extends('layouts.admin')

@section('content')

    <ul>
        <li>
            <form action="{{action('Admin\AdminSeedController@postTags')}}" method="post">
                <button class="btn btn-primary">Tags</button>
            </form>
        </li>

    </ul>

@stop