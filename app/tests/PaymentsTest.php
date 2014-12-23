<?php

class PaymentsTest extends TestCase
{

    public function testOnpayEmptyRequest()
    {
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay');

        $this->assertTrue($this->client->getResponse()->isClientError());
    }

    public function testOnpayCheck()
    {
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay', array(), array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "55446",
            "amount" => 500.0,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => "82f67760dbc5331963b7e00bc6df77f1"
        )));
        echo get_class($this->client->getResponse());
        $this->assertTrue($this->client->getResponse()->isOk());
    }

}
