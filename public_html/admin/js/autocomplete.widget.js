define([
    '$',
    'gallery/gallery.list.view',
    'gallery/gallery.image.model'
], function ($, GalleryListView, Image) {

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

        var url = $el.attr('data-url');
        $el.data('ids', []);

        $el.find('input.form-control').autocomplete({
            minLength: 0,
            source: function (request, response) {
                $.getJSON(url, {
                    search: extractLast(request.term)
                }, response);
            },
            search: function () {
                console.log(this.value);
                var term = extractLast(this.value);
                if (term.length < 2) {
                    return false;
                }
            },
            focus: function () {
                return false;
            },
            select: function (event, ui) {
                var terms = split(this.value);
                terms.pop();
                terms.push(ui.item.title);
                terms.push("");
                this.value = terms.join(", ");

                var ids = $el.data('ids');
                if(ids.indexOf(+ui.item.id)===-1) {
                    ids.push(+ui.item.id);
                    $el.data('ids', ids);
                }
                $el.find("input[type=hidden]").val(ids.join(","));
                return false;
            }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .append( "<a>" + item.title + "<br>" + item.short_description.substring(100) + "</a>" )
                .appendTo( ul );
        };;
    }

});