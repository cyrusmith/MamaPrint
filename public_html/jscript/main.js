(function ($) {

    $(function () {

        $('a[rel*="leanModal"]').leanModal({
            closeButton: ".close"
        });

        $('.call_back_responce_back_button').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            history.back();
            return false;
        });

        var forms = [
            {
                name: "get1",
                valid: [
                    {
                        name: "email",
                        message: "Пожалуйста, введите email"
                    }
                ]
            },
            {
                name: "get2",
                valid: [
                    {
                        name: "email",
                        message: "Пожалуйста, введите email"
                    }
                ]
            },
            {
                name: "get3",
                valid: [
                    {
                        name: "email",
                        message: "Пожалуйста, введите email"
                    }
                ]
            },
            {
                name: "callback",
                valid: [
                    {
                        name: "phone",
                        message: "Пожалуйста, введите телефон"
                    }
                ]
            }
        ];

        for (var i = 0; i < forms.length; i++) {
            $('form[name="' + forms[i].name + '"]').data('form-validators', forms[i].valid);

            $('form[name="' + forms[i].name + '"]').submit(function (e) {

                var valids = $(this).data('form-validators');

                var errors = [];
                for (var j = 0; j < valids.length; j++) {
                    var fieldVal = $(this).find('input[name="'+valids[j].name+'"]').val();
                    if (!fieldVal || fieldVal.trim().length == 0) {
                        errors.push(valids[j].message);
                    }
                }
                if(errors.length) {
                    alert(errors.join("\n"));
                    return false;
                }
                return true;
            });

        }

    });
})(jQuery);
