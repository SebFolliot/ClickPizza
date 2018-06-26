<?php
namespace ClickPizza\DAO;

use ClickPizza\Entity\Commodity;

class CommodityDAO extends DAO 
{
     /**
     * @Returns in a table the list of products sorted by type
     */
    public function commodityList($type)
    {
        $sql = "SELECT * FROM commodity WHERE com_type=?";
        $result = $this->getDb()->fetchAll($sql, array($type));
    
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
