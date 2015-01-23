define(function () {

    'use strict';

    return new AuthService;

    function AuthService() {

        this.setUser = setUser;
        this.getUser = getUser;

        var _user = null;

        function setUser(user) {
            _user = user;
        }

        function getUser() {
            return _user;
        }

    }

});
