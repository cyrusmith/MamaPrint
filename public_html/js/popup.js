define(['promise'], function (promise) {

    return {

        showLoginPrompt: function() {
            $.magnificPopup.open({
                items: {
                    src: '#auth-prompt-popup'
                },
                type: 'inline'
            }, 0);
        }

    }

});