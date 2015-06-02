define(['jquery'], function ($) {

    'use strict';

    return new RegisterForm;

    function RegisterForm(config) {

        var TIMEOUT = 30000;

        this.init = init;

        function init(isAuthorized) {

            var page = $('body').attr('data-page');

            if (isAuthorized) {
                setShown(365);
                return;
            }

            if (isShown()) return;

            if (page == 'login' || page == 'register' || page == 'register.confirm') {
                resetTs();
                setShown(2);
                return;
            }

            var $popup = $('#registerModal');
            if ($popup.length == 0) return;

            $popup.on('hide.bs.modal', function (e) {
                resetTs();
                setShown(2);
            });

            var ts = getTs();
            var delay = TIMEOUT;
            if (!ts) {
                setTs(Date.now());
            }
            else {
                delay = TIMEOUT - Date.now() + ts;
                if (delay <= 1000) {
                    delay = 1000;
                }
            }

            setTimeout(function () {
                $popup.modal('show');
                $.cookie('rfts', 0);
            }, delay);

        }

        function setShown(expInDays) {
            $.cookie('rfshown', true, {
                path: '/',
                expires: expInDays || 365
            });
        }

        function isShown() {
            return !!$.cookie('rfshown');
        }

        function setTs(ts) {
            $.cookie('rfts', ts, {
                path: '/'
            });
        }

        function getTs() {
            var ts = parseInt($.cookie('rfts'));
            return ts || 0;
        }

        function resetTs() {
            $.cookie('rfts', 0, {
                path: '/'
            });
        }

    };


});
