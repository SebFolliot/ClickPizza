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
     * @return \ClickPizza\Entity\Commodity or an exception if no user found
     */
    public function commodityId($id) {
        $sql = "SELECT * FROM commodity WHERE com_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildEntityObject($row);
        else
            throw new \Exception("Aucun identifiant correspondant à ce produit" . $id);
    }
    
     /**
     * Returns a list of all commodities, sorted by role and name.
     *
     * @return array A list of all users.
     */
    public function allCommodities() {
        $sql = "SELECT * FROM commodity ORDER BY com_type";
        $result = $this->getDb()->fetchAll($sql);
        // Convert query result to an array of entity objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['com_id'];
            $entities[$id] = $this->buildEntityObject($row);
        }
        return $entities;
    }
    
    /**
     * Add or update an commodity into the database
     *
     */
    
    public function update(Commodity $commodity) {
        $commodityData = array(
            'com_type' => $commodity->getType(),
            'com_title' => $commodity->getTitle(),
            'com_description' => $commodity->getDescription(),
            'com_picture' => $commodity->getPicture(),
            'com_price' => $commodity->getPrice(),
        );
       if($commodity->getId()) {
            // Update if commodity already registered
            $this->getDb()->update('commodity', $commodityData, array('com_id' => $commodity->getId()));
        }
        else {            
            // Add if commodity never saved
            $this->getDb()->insert('commodity', $commodityData);
            // Get the id of the created commodity
            $id = $this->getDb()->lastInsertId();
            $commodity->setId($id);
        }
    
        
    }
    
    /**
     * Delete a commodity in the database
     */
    public function delete($id) {
        $this->getDb()->delete('commodity', array('com_id' => $id));
    }

    
     /**
     * Internal method that instantiates an object of the Commodity class from an SQL result line.
     */
    protected function buildEntityObject(array $row) {
        $commodity = new Commodity();
        $commodity->setId($row['com_id']);
        $commodity->setType($row['com_type']);
        $commodity->setTitle($row['com_title']);
        $commodity->setDescription($row['com_description']);
        $commodity->setPicture($row['com_picture']);
        $commodity->setPrice($row['com_price']);
        return $commodity;
    }
}
