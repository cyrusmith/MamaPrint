require([
    '$',
    'tinymce',
    'editform',
    'twitterbootstrap',
    'bootstrapTagsinput',
    'datetimepicker',
    'jqueryui'], function ($,
                           tinymce,
                           editform) {

    'use strict';

    require([
        'attachments/attachments.widget',
        'gallery/gallery.widget',
        'tagsinput.widget',
        'relativesinput.widget'
    ], function () {
        var args = Array.prototype.slice.call(arguments, 0);

        for (var i = 0; i < args.length; i++) {
            if (typeof args[i].init === "function" && !!args[i].name) {
                var parentNode = $('[data-widget="' + args[i].name + '"]');

                if (parentNode.length > 0) {
                    parentNode.each(function() {
                        args[i].init($(this));
                    });
                }
            }
        }

    });

    $(function () {

        editform.init();

        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });

        $('[data-datetimepicker]').datetimepicker({
            format: 'Y-m-d H:i',
            lang: 'ru'
        });
        $('[data-datepicker]').datetimepicker({
            format: 'Y-m-d',
            lang: 'ru',
            timepicker: false
        });

        tinyMCE.baseURL = 'http://' + location.hostname + '/admin/lib/tinymce/js/tinymce';

        tinymce.init({
            selector: 'textarea.wysiwyg',
            plugins: ['advlist autolink link image lists charmap print preview code pagebreak',
                'responsivefilemanager'],
            image_advtab: true,
            external_filemanager_path: "/admin/lib/responsivefilemanager/filemanager/",
            filemanager_title: "Responsive Filemanager",
            external_plugins: {"filemanager": "/admin/lib/responsivefilemanager/filemanager/plugin.min.js"}
        });

    });

});