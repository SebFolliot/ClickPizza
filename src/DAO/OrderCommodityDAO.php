<?php
namespace ClickPizza\DAO;

use ClickPizza\Entity\OrderCommodity;

class OrderCommodityDAO extends DAO
{
    /**
     * @var \ClickPizza\DBAL\OrderDAO
     */
    protected $orderDAO;
    
    /**
     * @var \ClickPizza\DBAL\CommodityDAO
     */
    protected $commodityDAO;
    
    
    public function setOrderDAO(OrderDAO $orderDAO) {
        $this->orderDAO = $orderDAO;
    }
    
    public function setCommodityDAO(CommodityDAO $commodityDAO) {
        $this->commodityDAO = $commodityDAO;
    }
    
     /**
      * Returns a list of all orders commodities.
      *
      * @return array A list of all orders commodities.
      */
    public function allOrdersCommodities() {
        $sql = "SELECT * FROM order_commodity";
        $result = $this->getDb()->fetchAll($sql);
        // Convert query result to an array of entity objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['ordcom_id'];
            $entities[$id] = $this->buildEntityObject($row);
        }
        return $entities;
    }
    
     /**
      * Add a orderCommodity in the database
      */
     public function add(OrderCommodity $orderCommodity) {
        $orderCommodityData = array(
            'ord_id' => $orderCommodity->getOrder(),
            'com_id' => $orderCommodity->getCommodity(),
            'ordcom_quantity' => $orderCommodity->getQuantity()
         );

         $this->getDb()->insert('order_commodity', $orderCommodityData);
         $id = $this->getDb()->lastInsertId();
         $orderCommodity->setId($id);
         
    }
    
    /**
     * Creates an OrderCommodity object based on a DB row.
     *
     * @param array $row the DB row containing OrderCommodity data.
     * @return \ClickPizza\Entity\OrderCommodity
     */
    protected function buildEntityObject(array $row) {
        $orderCommodity = new OrderCommodity();
        
        $orderCommodity->setId($row['ordcom_id']);
        $orderCommodity->setQuantity($row['ordcom_quantity']);
                
        if (array_key_exists('ord_id', $row) && array_key_exists('com_id', $row)) { 
            // Find and set the associated order and commodity
            $orderId = $row['ord_id'];
            $order = $this->orderDAO->orderList($orderId);
            $orderCommodity->setOrder($order);
            
            $commodityId = $row['com_id'];
            $commodity = $this->commodityDAO->commodityId($commodityId);            
            $orderCommodity->setCommodity($commodity);
        } 
        return $orderCommodity;
    }

}