<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\Order;
use ClickPizza\Entity\OrderCommodity;
    
class OrderController
{
    /**
     * Add a order controller
     *
     * @param Application $app Silex application
     */
    public function addOrderAction (Application $app) {
        $order = new Order();

        if (isset($_COOKIE['caddyProducts']) && isset($_COOKIE['caddyUser']) && isset($_COOKIE['caddyPrice']) && isset($_COOKIE['caddyComId'])) {
            
            $cookie_userId = $_COOKIE['caddyUser'];
            $removebase64UserId = base64_decode($cookie_userId);
            $userId_record = json_decode($removebase64UserId, true);
    
            $user = $app['dao.user']->userList($userId_record);
            
            $cookie_price = $_COOKIE['caddyPrice'];
            $removebase64Price = base64_decode($cookie_price);
            $price_record = json_decode($removebase64Price, true);
            
            $status = 'En cours';
        
            $cookie_products = $_COOKIE['caddyProducts'];
            $products = utf8_encode(base64_decode($cookie_products));
            
            $products_record = json_decode($products, true);
                     
            $order->setUser($user);
            $order->setStatus($status);
            $order->setPrice($price_record);

            $app['dao.order']->add($order);
            
            $cookie_comId = $_COOKIE['caddyComId'];
            $removebase64ComId = base64_decode($cookie_comId);
            $comId_record = json_decode($removebase64ComId, true);
                
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
                $message[] = '<li>' . $v['qt'] . ' ' . $v['name'] . '</li>';
                
                $app['dao.orderCommodity']->add($orderCommodity);
            } 
                              
            $number_order = $user->getOrderNumber();
            $number_order++;
            $user->setOrderNumber($number_order); 
                   
            $app['dao.user']->updateOrderNumber($user);
            
            setcookie('caddyProductsNumber', 0, 1 * 24 * 60 * 60 * 1000);
            setcookie('caddyProducts', '', 1 * 24 * 60 * 60 * 1000);
            
            $headers  = 'MIME-Version: 1.0' . "\r\n";
		    $headers .= 'From: ClickPizza'. "\r\n" .				
				'Content-Type: text/html; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
				'Content-Disposition: inline'. "\r\n" .
				'Content-Transfer-Encoding: 7bit'." \r\n" .
				'X-Mailer:PHP/'.phpversion();
            
            $to = $user->getEmail();
            $ord_id = $order->getId();
            $civility = $user->getCivility();
            $name = $user->getName();
            $object = 'Récapitulatif de votre commande ';           
            $messageOrd = "<p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, voici le récapitulatif de votre commande.</p><p style='color :#669900'><span style='text-decoration :underline'>N° de commande</span> : " . $ord_id . "</p><ul>" . implode('', $message) . "</ul><p style='color :#669900'><span='text-decoration :underline'>Montant total de votre facture :</span> " . $price_record . " €.</p><em><strong>ClickPizza</strong> vous remercie de votre commande et vous souhaite un bon appétit.</em>";
            // Sending the order by mail
            mail($to, $object, $messageOrd, $headers);
       }
        return $app['twig']->render('order.html.twig', array(
        'title' => 'Confirmation de commande'
            ));        
    }
    
    /**
     * Update a validate order controller
     *
     * @param Application $app Silex application
     */
    public function updateStatusValidateAction ($id, Request $request, Application $app) {
        $order = $app['dao.order']->orderList($id);
        $order->setStatus('Validée');
        $app['dao.order']->updateStatus($order);
        $app['session']->getFlashBag()->add('success', 'La commande est validée.');
    
    // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    } 
    
    /**
     * Update a cancel order controller
     *
     * @param Application $app Silex application
     */
    public function updateStatusCancelAction ($idOrder, $idUser, Request $request, Application $app) {
        $order = $app['dao.order']->orderList($idOrder);
        $user = $app['dao.user']->userList($idUser);
        $number_order = $user->getOrderNumber();
        $number_order--;
        $user->setOrderNumber($number_order);

        $order->setStatus('Annulée');
        $app['dao.order']->updateStatus($order);
        $app['dao.user']->updateOrderNumber($user);
        $app['session']->getFlashBag()->add('success', 'La commande est annulée.');
    
    // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    } 
}
