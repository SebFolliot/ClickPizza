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
     * @return \ClickPizza\Entity\Order or an exception if no order found
     */
    public function orderList($id) {
    $sql = "SELECT * FROM t_order WHERE ord_id=?";
    $row = $this->getDb()->fetchAssoc($sql, array($id));

    if ($row) {
        return $this->buildEntityObject($row); }
    else {
        throw new \Exception("Aucun identifiant correspondant à la commande " . $id);
        }
    }
    
    /**
     * Update the status of the order into the database
     *
     */
    public function updateStatus(Order $order) {
        $userData = array(
            'ord_status' => $order->getStatus()
        );
       if($order->getId()) {
            // Update if order already registered
            $this->getDb()->update('t_order', $userData, array('ord_id' => $order->getId()));
        } 
     }
    
    /**
     * Returns the number of pages for 10 orders per page, sorted by status.
     *
     * @param string $status
     */     
    public function numberOfPagesForOrders($status) {
        $sql = 'SELECT COUNT(*) AS total FROM t_order WHERE ord_status="'.$status.'"';
        $result = $this->getDb()->fetchAssoc($sql);
        $total = (int)$result['total'];
        $orderByPage = 10;
        // number of pages
        $numberOfPages = ceil($total/$orderByPage);

        return $numberOfPages;
    }
    
    /**
     * Get a list of 10 orders per page, sorted by status.
     *
     * @param integer $currentPage
     * @param string $status
     * @return array
     */
    public function getListOrders($currentPage, $status) {
        $orderByPage = 10;
        $firstEntry = ($currentPage-1)*$orderByPage;
        $sql = ('SELECT * FROM t_order WHERE ord_status="'.$status.'" ORDER BY ord_id DESC LIMIT '.$firstEntry.','.$orderByPage.'');

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
