<?php
namespace ClickPizza\Entity;


class Order
{
    /**
     * Order id
     *
     * @var integer
     */ 
    protected $id;
    
    /**
     * Associated user
     *
     * @var \ClickPizza\Entity\User
     */
    protected $user;
    
    /**
    * Order content
    *
    * @var string
    */
    protected $content;
    
    /**
     * Order price
     *
     * @var decimal
     */
    protected $price;
    
    /**
     * Order status
     *
     * @var string
     */
    protected $status;
    
     /**
      * Order orderDate
      *
      * @var date
      */
    protected $orderDate;
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getOrderDate() {
        return $this->orderDate;
    }
    // Setters
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }
    
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }
    
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
    
    public function setOrderDate($orderDate) {
        $this->orderDate = $orderDate;
        return $this;
    }
        
}
