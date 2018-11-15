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

        if (isset($_COOKIE['caddyProducts']) && isset($_COOKIE['caddyUser']) && isset($_COOKIE['caddyPrice']) && isset($_COOKIE['caddyComId'])) {
            // Use of the orderService application service and its addOrder method to add an order
            $app['service.orderService']->addOrder($order, $_COOKIE['caddyProducts'], $_COOKIE['caddyUser'], $_COOKIE['caddyPrice'], $_COOKIE['caddyComId'], $app);            
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
    
    // Redirect to orders page
        return $app->redirect($app['url_generator']->generate('admin_order_page',array('currentPage' => 1, 'status' => 'Toutes')));
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
    
    // Redirect to orders page
        return $app->redirect($app['url_generator']->generate('admin_order_page',array('currentPage' => 1, 'status' => 'Toutes')));
    } 
}
