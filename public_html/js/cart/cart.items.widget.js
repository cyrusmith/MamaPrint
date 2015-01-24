define([
    '$',
    './carttable.view'
], function ($, CartTableView) {

    'use strict';

    return {
        init: init,
        name: "cartitems"
    };

    function init(el) {
        new CartTableView({
            el: el
        });
    }

});