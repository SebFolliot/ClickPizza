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
            $userId_record = $app['service.decode']->jsonDecode($cookie_userId);
    
            $user = $app['dao.user']->userList($userId_record);
            
            $cookie_price = $_COOKIE['caddyPrice'];
            $price_record = $app['service.decode']->jsonDecode($cookie_price);
            
            $status = 'En cours';
        
            $cookie_products = $_COOKIE['caddyProducts'];
            $products_record = $app['service.decode']->jsonDecode($cookie_products);
                     
            $order->setUser($user);
            $order->setStatus($status);
            $order->setPrice($price_record);

            $app['dao.order']->add($order);
            
            $cookie_comId = $_COOKIE['caddyComId'];
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
            
            $headers = $app['service.email']->emailHeader();
            
            $to = $user->getEmail();
            $ord_id = $order->getId();
            $civility = $user->getCivility();
            $name = $user->getName();
            $object = 'Récapitulatif de votre commande ';           
            $messageOrd = "<div style='background-color: #669900; height: 102px; margin-bottom: 140px'><div><img src='http://www.clickpizza.construksite.fr/web/images/logo.png' alt='logo clickpizza' title='ClickPizza' /></div></div><p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, voici le récapitulatif de votre commande.</p><p style='color :#669900'><span style='text-decoration :underline'>N° de commande</span> : " . $ord_id . "</p><table><thead><tr style='font-weight: bold; background-color: #669900; color:white'><th style='text-align: center'>Nom du produit</th><th style='text-align: center'>Tarif unitaire</th><th style='text-align: center'>Quantité</th><th style='text-align: center'>Prix</th></tr></thead>" . implode('', $message) . "</table><p style='color :#669900'><span='text-decoration :underline'>Montant total de votre facture :</span> " . $price_record . " €.</p><em><strong>ClickPizza</strong> vous remercie de votre commande et vous souhaite un bon appétit.</em><div style='text-align: center; background-color: rgba(0, 0, 0, 0.7); margin-top: 30px'><img src='http://www.clickpizza.construksite.fr/web/images/logo_min.png' alt='logo clickpizza' title='ClickPizza' /><div style='color: white'><p style='font-style: italic; font-size: x-small'>Pour votre santé, évitez de manger entre les repas, <a href='http://www.mangerbouger.fr' target='_blank'>www.mangerbouger.fr</a><br /> L’abus d’alcool est dangereux pour la santé. Sachez consommer et apprécier avec modération</p>
            <p style='font-style: italic'>Site créé pour projet personnel - OpenClassrooms</p></div></div>";
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
     * @param integer $id Order id
     */
    public function updateStatusValidateAction ($id, Request $request, Application $app) {
        $order = $app['dao.order']->orderList($id);
        $order->setStatus('Validée');
        $app['dao.order']->updateStatus($order);
        $ord_id = $order->getId();
        $app['session']->getFlashBag()->add('success', 'La commande ' .$ord_id. ' est validée.');
    
    // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    } 
    
    /**
     * Update a cancel order controller
     *
     * @param Application $app Silex application
     * @param integer $idOrder Order id
     * @param integer $idUser User id
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
        $ord_id = $order->getId();
        $app['session']->getFlashBag()->add('success', 'La commande '.$ord_id.' est annulée.');
    
    // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    } 
}
