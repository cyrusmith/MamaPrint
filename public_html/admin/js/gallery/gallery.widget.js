define([
    '$',
    'gallery/gallery.list.view',
    'gallery/gallery.image.model'
], function ($, GalleryListView, Image) {

    'use strict';

    return {
        init: init,
        name: "gallery"
    };

    function init(el) {

        var ImagesCollection = Backbone.Collection.extend({
            model: Image
        });

        var images = new ImagesCollection;

        var galleryView = new GalleryListView({
            el: el,
            model: images
        });

        /*
        var modelsJson = $('#attachment-item-models-json').text();

        var modelsData = JSON.parse(modelsJson);

        if (_.isArray(modelsData)) {
            for (var i = 0; i < modelsData.length; i++) {
                images.add(modelsData[i]);
            }
        }
        */

    }

});
