<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
</head>
<body>

<ul class="actions">
    <li><a href="/test/orders/payorder"
           data-method="post"
           data-params='[{"name":"order_id"}]'
                >PayOrder</a></li>

    <li><a href="test/orders/downloadlink"
           data-method="post"
           data-params='[{"name":"order_id"}]'
                >DownloadLink</a></li>

    <li><a href="api/v1/payments/onpay"
           data-method="post"
           data-params='[{"name":"user[email]", "value":"someeamil@mail.ru"},{"name":"type", "value":"pay"},{"name":"pay_for"}, {"name":"order[from_amount]"}, {"name": "balance[amount]"}, {"name": "balance[way]", "value":"RUR"}, {"name":"signature"}, {"name":"payment[id]", "value":"666"}, {"name": "payment[amount]"}, {"name":"payment[way]", "value":"RUR"}]'
                >PayOrder</a></li>
</ul>

<div class="params"></div>

<script>

    (function ($) {


        function Request() {

            this.setUrl = setUrl;
            this.setMethod = setMethod;
            this.setParams = setParams;
            this.openForm = openForm;
            this.send = send;

            var _url = null, _method = null, _params = [];

            function setUrl(url) {
                _url = url;
            }

            function setMethod(method) {
                _method = method ? method.toLowerCase() : null;
            }

            function setParams(params) {
                if (!params || !params.length) return;
                _params = params;
            }

            function openForm(pos) {

                if (!_url) {
                    alert('Url not set');
                    return;
                }

                var $form = $('<form/>');

                $form.css({
                    position: 'absolute',
                    width: 400,
                    padding: '1em',
                    top: pos.top,
                    left: pos.left,
                    border: '1px solid #999',
                    background: '#FFF',
                    boxShadow: '-10px 20px 10px'
                });

                var $inputs = {};

                for (var i = 0; i < _params.length; i++) {
                    $inputs[_params[i].name] = createFormInput($form, _params[i]);
                }

                var $submitButton = $('<input type="submit" value="Отправить">')
                $form.append($submitButton);

                $('body').append($form);

                $form.on('submit', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var query = [];
                    for (var i = 0; i < _params.length; i++) {
                        var name = _params[i].name;
                        if ($inputs.hasOwnProperty(name)) {
                            query.push(name + '=' + encodeURIComponent($inputs[name].val()));
                        }
                    }
                    var options = {
                        url: _url,
                        type: _method,
                        complete: function () {
                            $form.remove();
                        },
                        error: function (xhr) {
                            if (xhr['responseJSON'] && xhr.responseJSON['message'])
                                alert(xhr.responseJSON.message);
                            else {
                                alert("Error!");
                            }
                        }
                    };

                    if (query.length && (_method === 'get' || !_method)) {
                        options.url += (options.url.indexOf('?') === -1 ? '?' : '&') + query.join('&');
                    }
                    else {
                        options.data = query.join('&')
                    }
                    $.ajax(options);
                    return false;
                });

            }

            function createFormInput($form, param) {
                var $input = $('<input class="form-input" type="text" name="' + param.name + '" value="' + (param.value || '') + '">');
                var $div = $('<div class="form-input"/>');
                $div.append($('<label class="form-label">' + param.name + '</label>'));
                $div.append($input);
                $form.append($div);
                return $input;
            }

            function send() {

            }

        }

        $(function () {

            var requestsMap = {};

            $('.actions li a').each(function () {
                var el = $(this);
                var method = el.attr('data-method');
                var params = JSON.parse(el.attr('data-params'));
                var req = new Request();
                req.setUrl(el.attr('href'));
                req.setMethod(method);
                req.setParams(params);
                requestsMap[el.text()] = req;

                el.on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    requestsMap[$(this).text()].openForm($(this).offset());
                });
            });
        });
    })(jQuery)

</script>


</body>
</html>