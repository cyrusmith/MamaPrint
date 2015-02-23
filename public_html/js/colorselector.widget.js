define([
    '$',
    'backbone'
], function ($, Backbone) {

    'use strict';

    var ColorSelector = Backbone.View.extend({
        events: {
            'click .catalogitem-addtocart': 'addToCart',
            'click .dropdown-menu li a': 'selectColor'
        },

        initialize: function () {
            this.$colorName = this.$el.find('.colorname');
            this.$colorLabel = this.$el.find('.colorlabel');
            this.$input = this.$el.find('input[type="hidden"]');
        },

        selectColor: function (e) {
            var color = $(e.currentTarget).data('color');
            this.$colorName.text(color);
            this.$input.val(color);
            this.$colorLabel.css('background-color', color);
            this.$colorLabel.text(" ");
        }

    });

    return {
        init: init,
        name: "colorselector"
    };

    function init(el) {
        new ColorSelector({
            el: el
        });
    }

});