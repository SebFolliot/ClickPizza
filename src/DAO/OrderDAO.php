<?php
namespace ClickPizza\DAO;

use ClickPizza\Entity\Order;

class OrderDAO extends DAO
{
    /**
     * @var \ClickPizza\DBAL\UserDAO
     */
    protected $userDAO;
    
    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }
    
     /**
      * Add a order in the database
      */
     public function add(Order $order) {
        $orderData = array(
            'user_id' => $order->getUser(),
            'ord_content' => $order->getContent(),
            'ord_status' => $order->getStatus(),
            'ord_price' => $order->getPrice()
         );
         $this->getDb()->insert('t_order', $orderData);
         $id = $this->getDb()->lastInsertId();
         $order->setId($id);
    } 
    
    
     /**
      * Returns a list of all orders, sorted by date.
      *
      * @return array A list of all orders.
      */
    public function allOrders() {
        $sql = "SELECT * FROM t_order ORDER BY ord_date DESC";
        $result = $this->getDb()->fetchAll($sql);
        // Convert query result to an array of entity objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['ord_id'];
            $entities[$id] = $this->buildEntityObject($row);
        }
        return $entities;
    }
    
    /**
     * Creates an Order object based on a DB row.
     *
     * @param array $row the DB row containing Order data.
     * @return \ClickPizza\Entity\Order
     */
    protected function buildEntityObject(array $row) {
        $order = new Order();
       
        $order->setId($row['ord_id']);
        $order->setContent($row['ord_content']);
        $order->setStatus($row['ord_status']);
        $order->setPrice($row['ord_price']);        
        $order->setOrderDate($row['ord_date']);
        
        if (array_key_exists('user_id', $row)) { 
            // Find and set the associated user
            $userId = $row['user_id'];
            $user = $this->userDAO->userList($userId);
            $order->setUser($user); 

        } 
        return $order;
    }
}
