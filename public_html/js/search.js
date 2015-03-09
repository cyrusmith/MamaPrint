define(['jquery'], function ($) {

    'use strict';

    if ($('.searchform').length) {
        $(".searchform input[name='search']").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "/catalog?search=" + request.term,
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                location.href = "/catalog/" + ui.item.slug;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            var desc = item.short_description;
            if (desc) {
                desc = desc.split(/\s+/);
                var ellipsis = desc.length > 0 ? " ..." : "";
                desc = desc.slice(0, 5);
                desc = desc.join(" ") + ellipsis;
            }
            return $("<li>")
                .append("<a>" + item.title + "<br><small>" + desc + "</small></a>")
                .appendTo(ul);
        };

        $('.searchform button').click(function () {
            var tags = [],
                ages = [],
                goals = [];
            $('.searchform input[data-tag]').each(function () {
                if ($(this).is(':checked'))
                    tags.push($(this).attr('data-tag'));
            });
            $('.searchform input[data-age]').each(function () {
                if ($(this).is(':checked'))
                    ages.push($(this).attr('data-age'));
            });

            $('.searchform input[data-goal]').each(function () {
                if ($(this).is(':checked'))
                    goals.push($(this).attr('data-goal'));
            });
            $('.searchform input[name=tags]').val(tags.join(','));
            $('.searchform input[name=ages]').val(ages.join(','));
            $('.searchform input[name=goals]').val(goals.join(','));

            if (tags.length || ages.length || goals.length || $('.searchform input[name=search]').val()) {
                $('.searchform form').submit();
            }

        });

    }

});