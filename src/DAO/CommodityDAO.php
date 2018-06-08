<?php
namespace ClickPizza\DAO;

use ClickPizza\Entity\Commodity;

class CommodityDAO extends DAO 
{
    /**
     * @Return array the list of all commodity of pizza type
     */
    public function pizzaList()
    {
        $sql = "SELECT * FROM commodity WHERE com_type='Pizza'";
        $result = $this->getDb()->fetchAll($sql);
    
        $commodities = array();
        foreach ($result as $row) {
            $commodityId = $row['com_id'];
            $commodities[$commodityId] = $this->buildEntityObject($row);
        }
        return $commodities;
    }
    
     /**
     * @Return array the list of all commodity of drink
     */
    public function drinkList()
    {
        $sql = "SELECT * FROM commodity WHERE com_type='Boisson'";
        $result = $this->getDb()->fetchAll($sql);
    
        $commodities = array();
        foreach ($result as $row) {
            $commodityId = $row['com_id'];
            $commodities[$commodityId] = $this->buildEntityObject($row);
        }
        return $commodities;
    }
    
     /**
     * @Return array the list of all commodity of salad
     */
    public function saladList()
    {
        $sql = "SELECT * FROM commodity WHERE com_type='Salade'";
        $result = $this->getdb()->fetchAll($sql);
    
        $commodities = array();
        foreach ($result as $row) {
            $commodityId = $row['com_id'];
            $commodities[$commodityId] = $this->buildEntityObject($row);
        }
        return $commodities;
    }
    
    /**
     * Internal method that instantiates an object of the Commodity class     from an SQL result line.
     */
    protected function buildEntityObject(array $row) {
        $commodity = new Commodity();
        $commodity->setTitle($row['com_title']);
        $commodity->setDescription($row['com_description']);
        $commodity->setPicture($row['com_picture']);
        $commodity->setPrice($row['com_price']);
        return $commodity;
    }
}
