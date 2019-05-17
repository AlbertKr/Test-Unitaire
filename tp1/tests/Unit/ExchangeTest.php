<?php

namespace Tests\Unit;

use App\Product;
use App\User;
use App\Exchange;
use Illuminate\Http\Response;
use Tests\TestCase;
use Carbon\Carbon;

class ExchangeTest extends TestCase
{
    private $product;
    private $user;
    private $receiver;
    private $exchange;

    /** @test */
    public function testExchangeIsValid() {

        $this->assertTrue($this->product->getOwner()->isValid());
        $this->assertTrue($this->product->isValid());
        $this->assertTrue($this->receiver->isValid());
        $this->assertEquals($this->exchange->save()->getStatusCode(), Response::HTTP_CREATED);
    }
    
    /** @test */
    public function testExchangeIsNotValidBecauseBeginDateIsBeforeNow() {

        $this->exchange->setDateStart(2019, 5, 5);

        $this->assertTrue($this->product->getOwner()->isValid());
        $this->assertTrue($this->product->isValid());
        $this->assertTrue($this->receiver->isValid());
        $this->assertEquals($this->exchange->save()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }
    
    /** @test */
    public function testExchangeIsNotValidBecauseEndDateIsBeforeStartDate() {

        $this->exchange->setDateEnd(2019, 5, 9);

        $this->assertTrue($this->product->getOwner()->isValid());
        $this->assertTrue($this->product->isValid());
        $this->assertTrue($this->receiver->isValid());
        $this->assertEquals($this->exchange->save()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }
    
    /** @test */
    public function testExchangeIsNotValidBecauseOwnerIsNotValid() {

        $this->owner->setEmail('no valid email');

        $this->assertFalse($this->product->getOwner()->isValid());
        $this->assertFalse($this->product->isValid());
        $this->assertTrue($this->receiver->isValid());
        $this->assertEquals($this->exchange->save()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }
    
    /** @test */
    public function testExchangeIsNotValidBecauseReceiverIsNotValid() {

        $this->receiver->setAge(10);

        $this->assertTrue($this->product->getOwner()->isValid());
        $this->assertTrue($this->product->isValid());
        $this->assertFalse($this->receiver->isValid());
        $this->assertEquals($this->exchange->save()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }
    
    /** @test */
    public function testExchangeIsNotValidBecauseProductIsNotValid() {

        $this->product->setName('');

        $this->assertTrue($this->product->getOwner()->isValid());
        $this->assertFalse($this->product->isValid());
        $this->assertTrue($this->receiver->isValid());
        $this->assertEquals($this->exchange->save()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }
    
    /** @test */
    public function testExchangeIsNotValidBecauseProductAndReceiverAreNotValid() {

        $this->product->setName('');
        $this->receiver->setAge(10);

        $this->assertTrue($this->product->getOwner()->isValid());
        $this->assertFalse($this->product->isValid());
        $this->assertFalse($this->receiver->isValid());
        $this->assertEquals($this->exchange->save()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }
    
    protected function setUp(): void
    {
        $this->owner = new User("jhondoe@test.com", "Jhon", "Doe", 23);
        $this->receiver = new User("albkras@test.com", "Albert", "Krasniqi", 17);
        $this->product = new Product("Bouteille", $this->owner);

        $date_start = Carbon::createFromDate(2019, 5, 10);
        $date_end = Carbon::createFromDate(2019, 5, 15);

        $dbConnection = $this->createMock(\App\DBConnection::class);
        $dbConnection->expects($this->any())->method("saveExchange")->willReturn(true);

        $emailSender = $this->createMock(\App\EmailSender::class);
        $emailSender->expects($this->any())->method("sendEmail")->willReturn(true);

        $this->exchange = new Exchange($this->receiver, $this->product, $date_start, $date_end);
        
        parent::setUp();
    }
}