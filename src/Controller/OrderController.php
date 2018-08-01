<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\Order;
    
class OrderController
{
    /**
     * Add a order controller
     *
     * @param Application $app Silex application
     */
    public function addOrderAction (Application $app) {
        $order = new Order();
        if (isset($_COOKIE['caddyProducts']) && isset($_COOKIE['caddyUser']) && isset($_COOKIE['caddyPrice']) && $_COOKIE['caddyProductsNumber']) {
            $cookie_userId = $_COOKIE['caddyUser'];
            $removebase64UserId = base64_decode($cookie_userId);
            $userId_record = json_decode($removebase64UserId, true);
            $user = $app['dao.user']->userList($userId_record);
            
            $cookie_products = $_COOKIE['caddyProducts'];
            $products_record = utf8_encode(base64_decode($cookie_products)); 
            

            $test = json_decode($products_record, true);
                    
            $cookie_price = $_COOKIE['caddyPrice'];
            $removebase64Price = base64_decode($cookie_price);
            $price_record = json_decode($removebase64Price, true);
    
            $status = 'En cours';
            
            $order->setUser($user);

            if (isset($test)) {
                foreach ($test as $i => $v) {
                    $products[$i] = $v['qt'] . ' ' . $v['name'] . '<br />';
                    $order->setContent($products[$i]);        
                }
            }  
            
            $order->setStatus($status);
            $order->setPrice($price_record);
            
            
            $number_order = $user->getOrderNumber();
            $number_order++;
            $user->setOrderNumber($number_order); 
            
            $app['dao.order']->add($order);
            
            $app['dao.user']->updateOrderNumber($user);
            
            setcookie('caddyProductsNumber', 0, 1 * 24 * 60 * 60 * 1000);
            setcookie('caddyProducts', '', 1 * 24 * 60 * 60 * 1000);
            
        }
        return $app['twig']->render('order.html.twig', array(
        'title' => 'Confirmation de commande'            
        ));
    }
}
