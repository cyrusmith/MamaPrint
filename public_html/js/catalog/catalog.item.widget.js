define([
    'jquery',
    './catalog.item.view',
    './catalog.item.model'
], function ($, CatalogItemView, CatalogItem) {

    'use strict';

    return {
        init: init,
        name: "catalogitem"
    };

    function init(el) {

        var itemModel = new CatalogItem({
            id: el.attr('data-id'),
            title: el.find('.info .title h3 a').text(),
            inCart: false,
            price: parseFloat(el.find('.new .label').text())*100
        });

        var cartLinkView = new CatalogItemView({
            el: el,
            model: itemModel
        });

    }

});
