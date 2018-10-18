<?php

use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Entity\Commodity;
use ClickPizza\Form\Type\CreateAccountUserType;
use ClickPizza\Form\Type\CommodityType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ClickPizza\Entity\Order;

// Login form
$app->get('/login', "ClickPizza\Controller\UserController::loginAction")
    ->bind('login');

// Reset password
$app->match('/reset_password', "ClickPizza\Controller\UserController::resetPwdUserAction")
    ->bind('reset_password');

// User account
$app->get('/user_account/', "ClickPizza\Controller\UserController::userAccountAction")
    ->bind('user_account');

// Create a user account
$app->match('/create_account', "ClickPizza\Controller\UserController::createUserAccountAction")
    ->bind('create_account');

// Update a user account
$app->match('/edit_account/{id}',
"ClickPizza\Controller\UserController::editUserAccountAction")
    ->bind('edit_account');

// Order
$app->match('/order', "ClickPizza\Controller\OrderController::addOrderAction")
    ->bind('order');

// Admin home page (Admin)
$app->get('/admin', "ClickPizza\Controller\AdminController::adminHomePageAction")
    ->bind('admin');

// Add an admin account (Admin)
$app->match('/admin/user/add', "ClickPizza\Controller\AdminController::createAdminAccountAction")
    ->bind('admin_user_add');

// Delete an account user/admin (Admin)
$app->match('/admin/user/{id}/delete', "ClickPizza\Controller\AdminController::deleteAction")
    ->bind('admin_user_delete'); 

// Edit a admin account (Admin)
$app->match('/admin/user/{id}/edit', "ClickPizza\Controller\AdminController::editAdminAccountAction")
    ->bind('admin_user_edit');

// Edit a commodity (Admin)
$app->match('/admin/commodity/{id}/edit', "ClickPizza\Controller\CommodityController::editCommodityAction")
    ->bind('admin_commodity_edit');

// Add a new commodity (Admin)
$app->match('/admin/commodity/add', "ClickPizza\Controller\CommodityController::addCommodityAction")
    ->bind('admin_commodity_add');

// Delete a commodity (Admin)
$app->get('/admin/commodity/{id}/delete', "ClickPizza\Controller\CommodityController::deleteCommodityAction")
    ->bind('admin_commodity_delete');

// Update the status validate of the order (Admin)
$app->get('/admin/order/validate/{id}/update', "ClickPizza\Controller\OrderController::updateStatusValidateAction")
    ->bind('admin_order_validate_update');

// Update the status cancel of the order (Admin)
$app->get('/admin/order/cancel/{idOrder}/{idUser}/update', "ClickPizza\Controller\OrderController::updateStatusCancelAction")
    ->bind('admin_order_cancel_update');

// Pending order (Admin)
$app->match('/admin/order/page/{currentPage}/{status}', "ClickPizza\Controller\AdminController::orderPageAction")
    ->bind('admin_order_page');

// home page
// List of pizza
$app->get('/', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Pizza');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('home');
// list of salad
$app->get('/salad', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Salade');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('salad');
// list of drink
$app->get('/drink', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Boisson');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('drink');
// list of dessert
$app->get('/dessert', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Dessert');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('dessert');

// Caddy
$app->match('/caddy', function() use ($app) {
    return $app['twig']->render('caddy.html.twig', array(
        'title' => 'Votre panier'
    ));
})->bind('caddy');



