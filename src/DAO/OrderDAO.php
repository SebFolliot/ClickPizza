<?php
namespace ClickPizza\DAO;

use Doctrine\DBAL\Connection;
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
            'ord_price' => $order->getPrice(),
            'ord_date' => $order->getOrderDate()           
        );
         $this->getDb()->insert('order', $orderData);
         $id = $this->getDb()->lastInsertId();
         $order->setId($id);
    }
    
    public function test(Order $order) {
        require_once '/../../views/vue_caddy_test.php';
        $_SESSION['caddy'] = $caddy;
        if(createCaddy()) {
        foreach ($caddy as $amount) {
        $i++; 
        $sql = "INSERT INTO order (ord_content, ord_price, ord_status, ord_date) VALUES ('$content','$price','En cours','".$_SESSION['caddy']['ProductName'][$i]."','$total','En cours','NOW()')";
        $result = $this->getDb()->fetchAll($sql);
            }
        // Convert query result to an array of entity objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['user_id'];
            $entities[$id] = $this->buildEntityObject($row);
        }
        return $entities;
        }
    }
    
    /**
     * Creates an Order object based on a DB row.
     *
     * @param array $row The DB row containing Order data.
     * @return \ClickPizza\Entity\Order
     */
    protected function buildEntityObject(array $row) {
        $order = new Order();
        $order->setId($row['ord_id']);
        $order->setContent($row['ord_content']);
        $order->setStatus($row['ord_status']);
        $order->setPrice($row['ord_price']);        
        $order->setDate($row['ord_date']);
        
        if (array_key_exists('user_id', $row)) {
            // Find and set the associated user
            $user = $row['user_id'];
        }
    }
}
