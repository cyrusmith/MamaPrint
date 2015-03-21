define(['jquery'], function ($) {

    return {

        showLoginPrompt: function () {
            $.magnificPopup.open({
                items: {
                    src: '#auth-prompt-popup'
                },
                type: 'inline'
            }, 0);
        },

        showCartPrompt: function () {

            $.magnificPopup.open({
                items: {
                    src: '#cart-prompt-popup'
                },
                type: 'inline',
                mainClass: 'mfp-zoom-in',
                removalDelay: 500
            }, 0);

            $("#cart-prompt-popup").find('.btn-default').one("click", function (e) {
                e.stopPropagation();
                e.preventDefault();
                $.magnificPopup.close();
            });
        },

        showCookiesWarning: function () {
            $.magnificPopup.open({
                items: {
                    src: '#cookies-warning-popup',
                    type: 'inline',
                    mainClass: 'mfp-zoom-in',
                    removalDelay: 500
                }
            });
        }

    }

});