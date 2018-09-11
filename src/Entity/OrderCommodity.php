<?php
namespace ClickPizza\Entity;

class OrderCommodity 
{
    
    /**
     * OrderCommodity id
     * @var integer
     */ 
    protected $id;
    
    /** 
     * Associated order
     * @var \ClickPizza\Entity\Order
     */
    protected $order;
    
    /** 
     * Associated commodity
     * @var \ClickPizza\Entity\Commodity
     */
    protected $commodity;
    
    /** 
     * OrderCommodity quantity
     * @var integer
     */
    protected $quantity;
    
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getOrder() {
        return $this->order;
    }
    
    public function getCommodity() {
        return $this->commodity;
    }
    
    public function getQuantity() {
        return $this->quantity;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setOrder(Order $order) {
        $this->order = $order;
        return $this;
    }
    
    public function setCommodity(Commodity $commodity) {
        $this->commodity = $commodity;
        return $this;
    }
    
    
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }
}