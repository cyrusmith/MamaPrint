<?php

use Order\Order;
use Catalog\CatalogItem;

use Account\Account;
use Account\Operation;
use Account\OperationRefill;
use Account\OperationPurchase;
use Account\AccountException;

class AccountTest extends TestCase
{

    private $user;
    private $account;

    public function setUp()
    {
        parent::setUp();

        $this->user = new User;

        $this->user->name = "John";
        $this->user->email = "john@mail.ru";
        $this->user->password = "123";
        $this->user->save();
        $this->be($this->user);

        $this->account = new Account;
        $this->account->balance = 1000;
        $this->account->currency = "RUR";
        $this->account->user()->associate($this->user);
        $this->account->save();

    }

    public function testAddRefill()
    {
        $refill = new OperationRefill;
        $refill->amount = 666;
        $this->account->addOperation($refill);
        $this->assertEquals($this->account->balance, 1666);
        $op = $this->account->operations()->first();
        $this->assertTrue($op instanceof OperationRefill);
        $this->assertEquals($op->amount, 666);
    }

    public function testAddPurchase()
    {
        $refill = new OperationPurchase();
        $refill->amount = 666;
        $this->account->addOperation($refill);
        $this->assertEquals($this->account->balance, 334);
        $op = $this->account->operations()->first();
        $this->assertTrue($op instanceof OperationPurchase);
        $this->assertEquals($op->amount, 666);
    }

    /**
     * @expectedException Account\AccountException
     */
    public function testAddPurchaseInvalid()
    {
        $refill = new OperationPurchase();
        $refill->amount = 1200;
        $this->account->addOperation($refill);
    }

}
