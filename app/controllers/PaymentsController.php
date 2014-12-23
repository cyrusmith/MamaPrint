<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 20:31
 */
class PaymentsController extends BaseController
{

    public function pay() {

    }

    public function onpayApi()
    {
        $request = Request::instance();
        $content = $request->getContent();
        if (empty($content)) {
            return Response::json(array(
                'error' => 'Нет параметров'
            ), 400);
        }
        return $content;
    }

}