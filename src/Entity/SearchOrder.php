<?php
namespace ClickPizza\Entity;

class SearchOrder
{
    /**
     * SearchOrder status
     *
     * @var string
     */
    public $status;
    
    /**
     * SearchOrder id
     *
     * @var integer
     */
    public $id;
    
    public function __toString() {
        return (string)$this->status;
    }
}

