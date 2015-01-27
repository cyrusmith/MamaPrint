define([
    '$'
], function ($) {

    'use strict';

    return {
        init: init,
        name: "gallery"
    };

    function init(el) {
        el.find('.gallery-image').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    }

});