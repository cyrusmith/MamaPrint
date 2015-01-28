define(function () {
    'use strict';

    return new SiteConfig;

    function SiteConfig() {

        this.init = init;
        this.getMinOrderPrice = getMinOrderPrice;

        var _minOrderPrice = 0;

        function init(json) {
            if (json.hasOwnProperty("min_order_price")) {
                _minOrderPrice = +json["min_order_price"];
            }
        }

        function getMinOrderPrice() {
            return _minOrderPrice;
        }
    }

});
