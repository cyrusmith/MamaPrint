@extends('layouts.master')

@section('content')

    <div class="col-sm-8 col-sm-offset-2">
        <h3><span class="glyphicon glyphicon-shopping-cart text-primary"></span> Корзина</h3>
        @if(count($items) > 0)
            <table class="table table-hover">
                @foreach($items as $item)
                    <tr>
                        <td>
                            <a href="{{URL::action('CatalogController@item', ['path'=>$item['slug']])}}">{{$item['title']}}</a>
                        </td>
                        <td>
                            {{$item['price']/100}} P
                        </td>
                        <th class="text-right"><a class="btn btn-danger btn-sm" href="javascript:void(0);"><span
                                        class="glyphicon glyphicon-trash"></span> Удалить</a></th>
                    </tr>
                @endforeach

                <tr>
                    <td class="text-right">
                        Итого:
                    </td>
                    <td>
                        {{$total/100}} P
                    </td>
                    <th class="text-right"><a class="btn btn-success" href="javascript:void(0);">Оплатить <span
                                    class="glyphicon glyphicon-chevron-right"></span></a></th>
                </tr>
            </table>
        @else
            <p>Корзина пуста. <a href="/">Продолжить покупки</a></p>
        @endif

    </div>


@stop