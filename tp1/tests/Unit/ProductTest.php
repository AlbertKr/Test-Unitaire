<?php

namespace Tests\Unit;

use App\Product;
use App\User;
use Tests\TestCase;

class ProductTest extends TestCase
{
    private $product;
    private $user;


    /** @test */
    public function testPproductIsValid() {

        $this->assertTrue($this->product->hasName());
        $this->assertTrue($this->product->isValid());
    }

    /** @test */
    public function testProductHasNoName() {
        $this->product->setName("");

        $this->assertTrue($this->product->getOwner()->isValid());
        $this->assertFalse($this->product->isValid());
    }

    /** @test */
    public function testProductHasInvalidUser() {
       $this->user->setEmail("");

        $this->assertFalse($this->product->isValid());
    }

    /** @test */
    public function testProductHasInvalidUserAndNoName() {
       $this->user->setEmail("");
       $this->product->setName("");

        $this->assertFalse($this->product->isValid());
    }

    protected function setUp(): void
    {
        $this->user = new User("jhondoe@test.com", "Jhon", "Doe", 23);
        $this->product = new Product("Bouteille", $this->user);

        parent::setUp();
    }
}