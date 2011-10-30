<?php

namespace App\Document;

/**
 * @Document
 */
class User
{
    /**
     * @Id
     */
    private $id;

    /**
     * @String
     */
    private $firstname;

    /**
     * @String
     */
    private $lastname;

    /**
     * @String
     */
    private $username;

    /**
     * @String
     */
    private $password;

    public function __get($property) {
        return $this->$property;
    }

    public function __set($property, $value) {
        $this->$property = $value;
    }
}