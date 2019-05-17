<?php

namespace Tests\Unit;

use App\User;
use App\BankAccount;
use Illuminate\Http\Response;
use Tests\TestCase;
use Carbon\Carbon;

class BankAccountTest extends TestCase
{
    private $bankAccount;
    private $owner;

    /** @test */
    public function testBankAccountIsValid() {

        $this->assertTrue($this->owner->isValid());
        $this->assertObjectHasAttribute('amountCredited', $this->bankAccount->credit(300));
        $this->assertObjectHasAttribute('balanceAfterCredit', $this->bankAccount->credit(300));
    }

    /** @test */
    public function testBankAccountIsNotValidBecauseOwnerNotValid() {

        $this->owner->setEmail('no valid email');

        $this->assertFalse($this->owner->isValid());
        $this->assertEquals($this->bankAccount->credit(300)->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function testBankAccountIsNotValidBecauseAmountIsNegative() {
        
        $this->assertTrue($this->owner->isValid());
        $this->assertEquals($this->bankAccount->credit(-50)->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }
    
    protected function setUp(): void
    {
        $this->owner = new User("jhondoe@test.com", "Jhon", "Doe", 23);
        $this->bankAccount = new BankAccount('500', $this->owner);
        
        // $dbConnection = $this->createMock(\App\DBConnection::class);
        // $dbConnection->expects($this->any())->method("saveExchange")->willReturn(true);

        $emailSender = $this->createMock(\App\EmailSender::class);
        $emailSender->expects($this->any())->method("sendEmail")->willReturn(true);
        
        parent::setUp();
    }
}