<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    function testUserIsValid() {

        $user = new User("lancelotdemay@test.com", "Lancelot", "Demay", 23);

        $this->assertTrue($user->isValid());
    }

    /** @test */
    function testUserHasNoEmail() {

        $user = new User( "","Lancelot", "Demay", 23);

        $this->assertFalse($user->hasEmail());
    }

    /** @test */
    function testUserIsUnder13() {

        $user = new User("jhondoe@test.com", "Jhon", "Doe", 11);

        $this->assertFalse($user->isOver13());
    }

    /** @test */
    function testUserHasInvalidEmail() {

        $user = new User("oui.com", "Jhon", "Doe", 45);

        $this->assertFalse($user->emailIsValid());
    }

    /** @test */
    function testUserHasNoFirstName() {

        $user = new User("jhondoe@test.com", "", "Doe",20);

        $this->assertFalse($user->hasFirstName());
    }

    /** @test */
    function testUserHasNoLastName() {

        $user = new User("jhondoe@test.com", "Jhon", "",20);

        $this->assertFalse($user->hasLastName());
    }

}