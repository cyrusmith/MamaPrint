require([
    '$',
    'tinymce',
    'editform',
    'twitterbootstrap',
    'datetimepicker'], function ($,
                                 tinymce,
                                 editform) {

    'use strict';

    require([
        'attachments/attachments.widget',
        'gallery/gallery.widget'
    ], function () {
        var args = Array.prototype.slice.call(arguments, 0);

        for (var i = 0; i < args.length; i++) {
            if (typeof args[i].init === "function" && !!args[i].name) {
                var parentNode = $('[data-widget="' + args[i].name + '"]');
                if (parentNode.length > 0) {
                    args[i].init(parentNode);
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

        tinyMCE.baseURL = 'http://' + location.hostname + '/admin/lib/tinymce/js/tinymce';

        tinymce.init({
            selector: 'textarea.wysiwyg',
            plugins: ['advlist autolink link image lists charmap print preview code pagebreak',
                'responsivefilemanager'],
            external_filemanager_path: "/admin/lib/responsivefilemanager/filemanager/",
            filemanager_title: "Responsive Filemanager",
            external_plugins: {"filemanager": "/admin/lib/responsivefilemanager/filemanager/plugin.min.js"}
        });

    });

});