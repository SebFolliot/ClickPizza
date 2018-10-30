<?php

namespace ClickPizza\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * User id.
     *
     * @var integer
     */
    private $id;
    
    /**
     * User login
     *
     * @var string
     */
    private $username;
    
    /**
     * User civility
     *
     * @var string
     */
    private $civility;
    
    /**
     * User name
     *
     * @var string
     */
    private $name;
    
    /**
     * User first name
     *
     * @var string
     */
    private $firstName;
    
     /**
      * User email
      *
      * @ var string
      * 
      */
    private $email;
    
    /**
     * User password
     *
     * @var string
     */
    private $password;
    
    /**
     * For password security
     *
     * @var string
     */
    private $salt;
    
    /**
     * User phone number
     *
     * @var string
     */
    private $phoneNumber;
    
    /**
     * User order number
     *
     * @var integer
     */
    private $orderNumber;
    
    /**
     * Values : ROLE_USER or ROLE_ADMIN
     *
     *@var string
     */
    private $role;
    
    /**
     * User oldPassword
     *
     * @var string
     */
    public $oldPassword;
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }
    
    public function getCivility() {
        return $this->civility;
    }
    
    public function setCivility($civility) {
        $this->civility = $civility;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }
    
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }
    
    public function getEmail() {
        return $this->email;
    }
     
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }
    
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getSalt() {
        return $this->salt;
    }
    
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }
    
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }
    
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }
    
    public function getOrderNumber() {
        return $this->orderNumber;
    }
    
    public function setOrderNumber($orderNumber) {
        $this->orderNumber = $orderNumber;
        return $this;
    }
    
    public function getRole() {
        return $this->role;
    }
    
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getRoles() {
        return array($this->getRole());
    }
    
    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        
    }
    
    public function __toString() {
        return $this->getId();
    } 
}
