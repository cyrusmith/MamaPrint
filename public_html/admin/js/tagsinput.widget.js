define([
    '$'
], function ($) {

    'use strict';

    return {
        init: init,
        name: "tagsinput"
    };

    function init($el) {

        var tags = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: "/api/v1/tags?q=%QUERY",
            prefetch: {
                url: "/api/v1/tags"
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