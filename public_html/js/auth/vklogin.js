define(['jquery'], function($) {

    'use strict';

    return new VkLogin;

    function VkLogin() {

        this.init = init;

        function init(appId, redirectUrl) {
            $('#loginVk').on('click', function(e) {
                window.open("https://oauth.vk.com/authorize?client_id={1}&scope=friends&redirect_uri={2}&response_type=code&v=5.33&state=123"
                    .replace("{1}",appId)
                    .replace("{2}", encodeURIComponent(redirectUrl)));
            });

        }

    }


});