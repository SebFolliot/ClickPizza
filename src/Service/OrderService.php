<?php

namespace ClickPizza\Service;

use Silex\Application;
use ClickPizza\Entity\OrderCommodity;

class OrderService
{
    
    /**
     * add a order to the database 
     *
     * @param $order
     * @param $cookie_products
     * @param $cookie_userId
     * @param $cookie_price
     * @param $cookie_comId
     * @param Application $app Silex application
     */
    public function addOrder($order, $cookie_products, $cookie_userId, $cookie_price, $cookie_comId, Application $app) {
            
        $userId_record = $app['service.decode']->jsonDecode($cookie_userId);
        $user = $app['dao.user']->userList($userId_record);
        $price_record = $app['service.decode']->jsonDecode($cookie_price);
        $status = 'En cours';
        $products_record = $app['service.decode']->jsonDecode($cookie_products);
        
        $order->setUser($user);
        $order->setStatus($status);
        $order->setPrice($price_record);
                      
        $app['dao.order']->add($order);
        $comId_record = $app['service.decode']->jsonDecode($cookie_comId);
            
        // instantiation of an OrderCommodity object
        $orderCommodity = new OrderCommodity();
            
        foreach ($products_record as $v) {                    
            // We recover the products
            $commodity = $app['dao.commodity']->commodityId($v['id']);                            
            // We link it to the order
            $orderCommodity->setOrder($order);                             
            // We link it to the commodity
            $orderCommodity->setCommodity($commodity);                    
            $quantity = $v['qt'];                
            // each commodity is with so much amount
            $orderCommodity->setQuantity($quantity);                
            // Preparing the message for the mail
            $message[] = '<tr><td style="text-align: center">' .$v['name']. '</td><td style="text-align: center">' .$v['price']. ' €</td><td style="text-align: center">' .$v['qt']. '</td><td style="text-align: center">' .$v['price']*$v['qt']. ' €</td></tr>';
                
            $app['dao.orderCommodity']->add($orderCommodity);
        } 
                              
        $number_order = $user->getOrderNumber();
        $number_order++;
        $user->setOrderNumber($number_order);
            
        if($number_order % 3 == 0) {
             $message[].='<br />Vous bénéficiez d\'une remise de 10% lors de cette commande.';
         }
               
        $app['dao.user']->updateOrderNumber($user);
            
        setcookie('caddyProductsNumber', 0, 1 * 24 * 60 * 60 * 1000);
        setcookie('caddyProducts', '', 1 * 24 * 60 * 60 * 1000);
            
        // Sending the order by mail
        $app['service.email']->emailAddOrder($order, $user, $message, $price_record);
    }
} 
