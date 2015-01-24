define(['$', 'promise'], function ($, promise) {

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
            return new promise.Promise(function (resolve, reject) {
                $.magnificPopup.open({
                    items: {
                        src: '#cart-prompt-popup'
                    },
                    type: 'inline',
                    mainClass: 'mfp-zoom-in',
                    removalDelay: 500
                }, 0);
                resolve(true);
            });
        }

    }

});