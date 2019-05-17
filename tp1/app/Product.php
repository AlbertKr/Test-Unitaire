<?php

namespace App;


class Product
{
    private $name;
    private $owner;

    public function __construct(String $name, User $owner)
    {
        $this->name = $name;
        $this->owner = $owner;
    }

    public function hasName() {
        return $this->name != "";
    }

    public function isValid() {
        return $this->hasName() && $this->owner->isValid();
    }

    public function setName(String $name) {
        $this->name = $name;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setOwner(User $owner) {
        $this->owner = $owner;
    }
}