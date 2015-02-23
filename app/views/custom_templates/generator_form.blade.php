@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2 custom_template_generator">
        <h2 class="text-center">{{$name}}</h2>

        <p class="text-center">
            <img src="{{action('CustomTemplatesController@getImage', ['id'=>$id])}}"/>
        </p>

        <form role="form" action="" method="post">
            <div class="form-group">
                <label for="title" class="control-label">Ваш заголовок</label>
                <input type="text" name="title" class="form-control" id="title">
            </div>

            <div class="form-group">
                <div class="dropdown" data-widget="colorselector">

                    <input type="hidden" name="color"/>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-expanded="false">
                        <span class="colorlabel label label-default"></span> <span class="colorname">Цвет</span> <span
                                class="caret"></span>
                    </button>

                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        @foreach($colors as $color)
                            <li><a href="javascript:void(0)" data-color="{{$color}}"><span class='label label-default'
                                                                   style='background-color:{{$color}}'>&nbsp;</span> {{$color}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Создать</button>
                </div>
            </div>

        </form>


    </div>

@stop