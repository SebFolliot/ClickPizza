<?php
namespace ClickPizza\Entity;

class Commodity 
{

    /**
     * Commodity id
     *
     * @var integer
     */    
    private $id;
    
    /**
     * Commodity title
     *
     * @var string
     */
     private $title;
    
    /**
    * Commodity description
    *
    * @var string
    */
    private $description;
    
    /**
    * Commodity picture
    *
    * @var string
    */
    private $picture;
    
    /**
    * Commodity price
    *
    * @var decimal
    */
    private $price;
    
    /**
    * Commodity type
    *
    * @var string
    */
    private $type;
    
    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getPicture() {
        return $this->picture;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getType() {
        return $this->type;
    }
    
    // Setters
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function setPicture($picture) {
        $this->picture = $picture;
        return $this;
    }
    
    public function setPrice($price) {
        $this->price = $price;
        return $price;
    }
    
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
}