define([
    '$'
], function ($) {

    'use strict';

    return {
        init: init,
        name: "relativesinput"
    };

    function init($el) {

        var related = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: "/admin/catalog?search=%QUERY",
            prefetch: {
                url: "/admin/catalog"
            }
        });
        related.initialize();

        $el.tagsinput({
            itemValue: 'id',
            itemText: 'title',
            typeaheadjs: {
                name: 'related',
                displayKey: 'title',
                source: related.ttAdapter()
            }
        });

        $el.on('itemAdded itemRemoved', function (evt) {
            console.log($el.tagsinput('items'));
        });


    }

});