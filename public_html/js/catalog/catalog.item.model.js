define(['backbone'], function (Backbone) {
    return Backbone.Model.extend({
        defaults: {
            title: null,
            inCart: false,
            price: 0
        }
    });
});