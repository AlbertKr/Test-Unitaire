<?php

namespace App;

class User
{
    private $email = "";
    private $firstName = "";
    private $lastName = "";
    private $age = 0;

    public function __construct(String $email, String $firstName, String $lastName, Int $age)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
    }

    public function hasEmail() {
        return $this->email != "";
    }

    public function getEmail() {
        return $this->email;
    }

    public function emailIsValid() {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->email)) ? false : true;
    }

    public function hasFirstName() {
        return $this->firstName != "";
    }

    public function hasLastName() {
        return $this->lastName != "";
    }

    public function isOver13() {
        return $this->age > 13;
    }

    public function isValid() {
        return $this->hasEmail() && $this->emailIsValid() && $this->hasFirstName() && $this->hasLastName() && $this->isOver13();
    }

    public function setEmail(String $email) {
        $this->email = $email;
    }

    public function setAge(Int $age) {
        $this->age = $age;
    }

    public function isMajor(){
        return $this->age >= 18;
    }
}
