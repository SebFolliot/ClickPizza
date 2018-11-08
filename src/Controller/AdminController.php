<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Form\Type\CreateAccountUserType;
use ClickPizza\Form\Type\UpdateAccountType;
use ClickPizza\Form\Type\SearchOrderType;

class AdminController {
    
    /**
     * Create a admin account controller
     *
     * @param Application $app Silex application
     */ 
    public function createAdminAccountAction(Request $request, Application $app) {
        $user = new User();
        $userForm = $app['form.factory']->create(CreateAccountUserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $simplePassword = $user->getPassword();
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($simplePassword, $user->getSalt());
            $user->setPassword($password);

            $app['dao.user']->add($user);
       
            $app['session']->getFlashBag()->add('success', 'Le compte administrateur a été créé avec succès.');
             }
        
        return $app['twig']->render('admin_form.html.twig', array(
            'title' => 'Création d\'un compte administrateur',
            'userForm' => $userForm->createView()
        ));
    }
    
    /**
     * Delete a account user/admin controller
     *
     * @param integer $id User id
     * @param Application $app Silex application
     */
     public function deleteAction($id, Request $request, Application $app) {
        
        $orderNumber = $app['dao.user']->userList($id)->getOrderNumber();
        $name = $app['dao.user']->userList($id)->getName();
        $civility = $app['dao.user']->userList($id)->getCivility();
        
        if ($orderNumber === null) {
            $app['dao.user']->delete($id);
            $app['session']->getFlashBag()->add('success', 'Le compte de '.$civility.' '.$name.' a été supprimé de la base de données.');
        } else {
            $app['session']->getFlashBag()->add('warning', 'Le compte de '.$civility.' '.$name.' ne peut pas être supprimé car il est lié à des commandes.');    
        }
         
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
    
    /**
     * Update an admin account controller
     *
     * @param Application $app Silex application
     * @param integer $id User id
     */ 
    public function editAdminAccountAction($id, Request $request, Application $app) {
        $user = $app['dao.user']->userList($id);
        $users = $app['dao.user']->allUsers();
        $userForm = $app['form.factory']->create(UpdateAccountType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $app['dao.user']->update($user);
            $app['session']->getFlashBag()->add('success', 'Le compte administrateur a été mis à jour avec succès.');
            // Redirect to admin home page
            return $app->redirect($app['url_generator']->generate('admin'));
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Modifier le compte administrateur',
            'user' => $user,
            'userForm' => $userForm->createView()));
        }
    
    /**
     * Admin home page controller
     *
     * @param Application $app Silex application
     */
    public function adminHomePageAction(Application $app) {
        $users = $app['dao.user']->allUsers();
        $commodities = $app['dao.commodity']->allCommodities();
        $orders = $app['dao.order']->allOrders();
        $ordersCommodities = $app['dao.orderCommodity']->allOrdersCommodities();
                
        return $app['twig']->render('admin.html.twig', array(
            'users' => $users,
            'commodities' => $commodities,
            'orders' => $orders,
            'ordersCommodities' => $ordersCommodities,
            'title' => 'Administration'));
    }
    
    /**
     * Update an admin account controller
     *
     * @param Application $app Silex application
     * @param integer $currentPage
     * @param string $status
     */
    public function orderPageAction ($currentPage, $status, Request $request, Application $app) {
        $users = $app['dao.user']->allUsers();
        $commodities = $app['dao.commodity']->allCommodities();
        $ordersCommodities = $app['dao.orderCommodity']->allOrdersCommodities();
        
        $formSearchOrder = $app['form.factory']->create(SearchOrderType::class);
        $formSearchOrder->handleRequest($request);
   
        if ($formSearchOrder->isSubmitted() && $formSearchOrder->isValid()) {
   
            $data = $formSearchOrder->getData();
            $numberOfPages = $app['dao.order']->numberOfPagesForStatusOrders($data['status'], $app);
            $currentPage = 1;
            $orders = $app['dao.order']->searchListOrders($data['status'], $currentPage, $app);
                     
            if($data['status'] || (($app['dao.order']->orderList($data['id']) !== null) && $data['status'])) {
                
               if (($app['dao.order']->orderList($data['id']) === null) && isset($data['id'])) {
                   if($data['status'] === 'Toutes') {
                       $numberOfPages = $app['dao.order']->numberOfPagesForOrders($app);
                   }

                $app['session']->getFlashBag()->add('warning', 'La commande n° '.$data['id'].' n\'existe pas');
                
                return $app['twig']->render('list_order.html.twig', array(
                    'users' => $users,
                    'status' => $data['status'],
                    'formSearchOrder' => $formSearchOrder->CreateView(),
                    'commodities' => $commodities,
                    'orders' => $orders,
                    'numberOfPages' => $numberOfPages,
                    'currentPage' => $currentPage,
                    'ordersCommodities' => $ordersCommodities,
                    'title' => 'Les commandes'));
                   
               } 
                
               if(isset($data['id'])) {     
               $thisStatus = $app['dao.order']->orderList($data['id'])->getStatus();
                
                    if (($thisStatus !== $data['status']) && ($data['status'] !== 'Toutes')) {
                        $app['session']->getFlashBag()->add('warning', 'La commande n° '.$data['id']. ' n\'est pas une commande ' .mb_strtolower($data['status']).' mais une commande '.mb_strtolower($thisStatus));
                
                    return $app['twig']->render('list_order.html.twig', array(
                        'users' => $users,
                        'status' => $data['status'],
                        'formSearchOrder' => $formSearchOrder->CreateView(),
                        'commodities' => $commodities,
                        'orders' => $orders,
                        'numberOfPages' => $numberOfPages,
                        'currentPage' => $currentPage,
                        'ordersCommodities' => $ordersCommodities,
                        'title' => 'Les commandes'));
                  }
               }
                    
                $numberOfPages = $app['dao.order']->numberOfPagesWithSearchOrders($data, $app);
                $currentPage = 1;
                $orders = $app['dao.order']->searchListOrders($data, $currentPage, $app);

                return $app['twig']->render('list_order.html.twig', array(
                    'users' => $users,
                    'status' => $data['status'],
                    'id' => $data['id'],
                    'formSearchOrder' => $formSearchOrder->CreateView(),
                    'commodities' => $commodities,
                    'orders' => $orders,
                    'numberOfPages' => $numberOfPages,
                    'currentPage' => $currentPage,
                    'ordersCommodities' => $ordersCommodities,
                    'title' => 'Les commandes'));
            }   


        } else {
            if($status === 'Toutes') {
               $numberOfPages = $app['dao.order']->numberOfPagesForOrders($app);
             } else {
                $numberOfPages = $app['dao.order']->numberOfPagesForStatusOrders($status, $app);
            }
                
            $orders = $app['dao.order']->searchListOrders($status, $currentPage, $app);
            
            return $app['twig']->render('list_order.html.twig', array(
                'users' => $users,
                'status' => $status,
                'formSearchOrder' => $formSearchOrder->CreateView(),
                'commodities' => $commodities,
                'orders' => $orders,
                'numberOfPages' => $numberOfPages,
                'currentPage' => $currentPage,
                'ordersCommodities' => $ordersCommodities,
                'title' => 'Les commandes'));
        }
    }
}
