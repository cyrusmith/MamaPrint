@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <form role="form" action="{{URL::action('Admin\AdminSettingsController@edit')}}" method="post">

        <div class="form-group {{$errors->has('title')?'has-error':''}}">
            <label for="{{SiteConfig::MIN_ORDER_PRICE}}"
                   class="control-label">{{Lang::get('static.admin.settings.min_order_price')}}</label>
            <input type="text" class="form-control" id="{{SiteConfig::MIN_ORDER_PRICE}}"
                   placeholder="{{Lang::get('static.admin.settings.min_order_price.help')}}"
                   value="{{$config->getMinOrderPrice()}}"
                   name="{{SiteConfig::MIN_ORDER_PRICE}}">
            @if($errors->has('title'))
                <p class="text-danger">{{$errors->first('title')}}</p>
            @endif
        </div>

    </form>

@stop