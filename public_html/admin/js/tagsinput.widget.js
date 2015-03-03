define([
    '$'
], function ($) {

    'use strict';

    return {
        init: init,
        name: "tagsinput"
    };

    function init($el) {

        var url = $el.attr('data-url');

        var tags = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: url + "?q=%QUERY",
            prefetch: {
                url: url
            }
        });
        tags.initialize();

        $el.tagsinput({
            typeaheadjs: {
                name: 'tags',
                displayKey: 'tag',
                valueKey: 'tag',
                source: tags.ttAdapter()
            }
        });
    }

});