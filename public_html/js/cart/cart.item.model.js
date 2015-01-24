define(['backbone'], function (Backbone) {
    return Backbone.Model.extend({
        defaults: {
            title: null,
            price: 0
        }
    });
});