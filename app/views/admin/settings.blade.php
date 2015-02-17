@extends('layouts.admin')

@section('pagetitle')

@stop

@section('content')

    <form role="form" action="{{URL::action('Admin\AdminSettingsController@edit')}}" method="post">

        <div class="form-group {{$errors->has(SiteConfig::DESCRIPTOR)?'has-error':''}}">
            <label for="{{SiteConfig::DESCRIPTOR}}"
                   class="control-label">{{Lang::get('static.admin.settings.descriptor')}}</label>
            <input type="text" class="form-control" id="{{SiteConfig::DESCRIPTOR}}"
                   placeholder="{{Lang::get('static.admin.settings.descriptor.help')}}"
                   value="{{$config->getDescriptor()}}"
                   name="{{SiteConfig::DESCRIPTOR}}">
            @if($errors->has('title'))
                <p class="text-danger">{{$errors->first(SiteConfig::DESCRIPTOR)}}</p>
            @endif
        </div>

        <div class="form-group {{$errors->has(SiteConfig::SEO_DESCRIPTION)?'has-error':''}}">
            <label for="{{SiteConfig::SEO_DESCRIPTION}}"
                   class="control-label">{{Lang::get('static.admin.settings.seo_description')}}</label>
            <input type="text" class="form-control" id="{{SiteConfig::SEO_DESCRIPTION}}"
                   value="{{$config->getSeoDescription()}}"
                   name="{{SiteConfig::SEO_DESCRIPTION}}">
            @if($errors->has('title'))
                <p class="text-danger">{{$errors->first(SiteConfig::SEO_DESCRIPTION)}}</p>
            @endif
        </div>

        <div class="form-group {{$errors->has(SiteConfig::MIN_ORDER_PRICE)?'has-error':''}}">
            <label for="{{SiteConfig::MIN_ORDER_PRICE}}"
                   class="control-label">{{Lang::get('static.admin.settings.min_order_price')}}</label>
            <input type="text" class="form-control" id="{{SiteConfig::MIN_ORDER_PRICE}}"
                   placeholder="{{Lang::get('static.admin.settings.min_order_price.help')}}"
                   value="{{$config->getMinOrderPrice()}}"
                   name="{{SiteConfig::MIN_ORDER_PRICE}}">
            @if($errors->has('title'))
                <p class="text-danger">{{$errors->first(SiteConfig::MIN_ORDER_PRICE)}}</p>
            @endif
        </div>

    </form>

@stop