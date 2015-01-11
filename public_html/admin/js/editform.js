define(['$'], function ($) {

    'use strict';

    return new EditForm;

    function EditForm() {

        this.init = init;

        function init() {
            $(function () {

                $('.admin-action-post').click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).attr('disabled', 'disabled');
                    $('form:eq(0)').submit();
                });

            });
        }

    }

});