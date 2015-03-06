define(['jquery'], function ($) {

    'use strict';

    if ($('[data-toggle]').length) {
        $("[data-toggle]").each(function () {
            var rel = $($(this).attr('data-toggle'));
            if (rel.length) {
                $(this).click(function () {
                    rel.toggle(400, function() {
                        if(rel.is(':hidden')) {
                            rel.find('input[type=checkbox]').prop('checked', false);
                        }
                    });
                });
            }

        });
    }

});