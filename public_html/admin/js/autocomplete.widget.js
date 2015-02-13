define([
    '$',
    'backbone',
    'gallery/gallery.list.view',
    'gallery/gallery.image.model'
], function ($, Backbone, GalleryListView, Image) {

    'use strict';

    return {
        init: init,
        name: "autocomplete"
    };

    function extractLast(term) {
        return split(term).pop();
    }

    function split(val) {
        return val.split(/,\s*/);
    }

    function init($el) {

        var url = $el.attr('data-url'),
            $input = $el.find('input.form-control'),
            titleModelMap = {},
            exclude = $el.attr('data-exclude');

        var ids = new Backbone.Collection();

        try {
            var json = JSON.parse($('#related-json').text());
            for (var i = 0; i < json.length; i++) {
                titleModelMap[json[i].title.toLowerCase()] = ids.add(json[i]);
            }

        }
        catch (e) {
            throw e;
        }

        ids.on("add remove change", refreshInput);

        function refreshInput() {
            var hiddenValue = ids.map(function (item) {
                return item.get('id')
            }).join(",");
            $el.find("input[type='hidden']").val(hiddenValue);
        }

        $input.autocomplete({
            minLength: 0,
            source: function (request, response) {
                $.getJSON(url, {
                    search: extractLast(request.term),
                    exclude: exclude
                }, response);
            },
            search: function () {
                var term = extractLast(this.value);
                var newTitleModelMap = {};
                var items = split(this.value);

                for (var i = 0; i < items.length; i++) {
                    var title = items[i].toLowerCase();
                    if (titleModelMap.hasOwnProperty(title)) {
                        newTitleModelMap[title] = titleModelMap[title];
                        delete titleModelMap[title];
                    }
                }

                for (var title in titleModelMap) {
                    ids.remove(titleModelMap[title]);
                }

                titleModelMap = newTitleModelMap;

                if (term.length < 2) {
                    return false;
                }
            },
            focus: function () {
                return false;
            },
            select: function (event, ui) {
                titleModelMap[ui.item.title.toLowerCase()] = ids.add(ui.item);

                var string = ids.map(function (item) {
                    return item.get('title')
                }).join(", ");
                if (string.length > 0) {
                    string = string + ", ";
                }
                $input.val(string);

                refreshInput();
                return false;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a><span class='title'>" + item.title + "</span><br><span class='description'>" + item.short_description + "</span></a>")
                .appendTo(ul);
        };
        ;
    }

});