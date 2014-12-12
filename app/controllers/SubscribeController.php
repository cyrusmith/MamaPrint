<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 12.12.2014
 * Time: 7:57
 */
class SubscribeController extends BaseController
{

    public function getCards()
    {

        $name = Input::get('name');
        $email = Input::get('email');

        $validator = Validator::make(
            array(
                'name' => $name,
                'email' => $email
            ),
            array('name' => 'required', 'email' => 'email')
        );

        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->messages()), 400);
        }

        Mail::send('emails.subscribe.subscribe_admin', array(
            'name' => $name,
            'email' => $email
        ), function ($message) {
            $message->to(Config::get('mamaprint.adminemail'), 'MamaPrint')->subject('Get cards request');
        });

        return Response::json();

    }

} 