@extends('user.userarea')

@section('userarea_content')

    @if(!$items->isEmpty())
        <table class="table table-hover">
            @foreach($items as $item)
                <tr>
                    <td>
                        <a href="{{URL::action('CatalogController@item', ['path'=>$item->slug])}}">{{$item->title}}</a>
                    </td>
                    <th class="text-right"><a class="btn btn-success btn-sm" href="/orders/{{$item->id}}/download"><span
                                    class="glyphicon glyphicon-download"></span> Скачать</a></th>
                </tr>
            @endforeach
        </table>
    @else
        <p>У вас пока нет материалов для скачивания</p>
    @endif

@stop