<?php

use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Entity\Commodity;
use ClickPizza\Form\CreateAccountUserType;
use ClickPizza\DAO\CaddyDAO;
use ClickPizza\Form\Type\CommodityType;
use Symfony\Component\HttpFoundation\File\UploadedFile;


// Home page
$app->get('/', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Pizza');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('home');

$app->get('/salad', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Salade');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('salad');

$app->get('/drink', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Boisson');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('drink');

$app->get('/dessert', function () use ($app) {
    $commodities = $app['dao.commodity']->commodityList('Dessert');
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('dessert');

// Login form
$app->get('/login', "ClickPizza\Controller\UserController::loginAction")
    ->bind('login');

// Create a user account
$app->match('/create_account', "ClickPizza\Controller\UserController::createAccountAction")
    ->bind('create_account');

// Admin home page
$app->get('/admin', function () use ($app) {
    $users = $app['dao.user']->allUsers();
    $commodities = $app['dao.commodity']->allCommodities();
    return $app['twig']->render('admin.html.twig', array(
        'users' => $users,
        'commodities' => $commodities,
        'title' => 'Administration'));
})->bind('admin');

// Add an admin account
$app->match('/admin/user/add', "ClickPizza\Controller\AdminController::createAdminAccountAction")
    ->bind('admin_user_add');

// Delete an account user/admin 
$app->match('/admin/user/{id}/delete', "ClickPizza\Controller\AdminController::deleteAction")
    ->bind('admin_user_delete'); 

// Edit a admin account 
$app->match('/admin/user/{id}/edit', "ClickPizza\Controller\AdminController::editAdminAccountAction")->bind('admin_user_edit');

// Edit a commodity (Admin)
$app->match('/admin/commodity/{id}/edit', function($id, Request $request) use ($app) {
    $commodity = $app['dao.commodity']->commodityId($id);
    $commodityForm = $app['form.factory']->create(CommodityType::class, $commodity);
    $commodityForm->handleRequest($request);
    if ($commodityForm->isSubmitted() && $commodityForm->isValid()) {
        $directory = __DIR__.'/../web/images/upload';
        $file = $commodityForm['picture']->getData();
        $file->move($directory, $file->getClientOriginalName());
        $commodity->setPicture($file->getClientOriginalName());
        $app['dao.commodity']->update($commodity);
        $app['session']->getFlashBag()->add('success', 'Le produit a été mis à jour avec succès.');
    }
    return $app['twig']->render('commodity_form.html.twig', array(
        'title' => 'Modifier le produit',
        'commodityForm' => $commodityForm->createView()));
})->bind('admin_commodity_edit');

// Add a new commodity (Admin)
$app->match('/admin/commodity/add', function(Request $request) use ($app) {
    $commodity = new Commodity();
    $commodityForm = $app['form.factory']->create(CommodityType::class, $commodity);
    $commodityForm->handleRequest($request);
    if ($commodityForm->isSubmitted() && $commodityForm->isValid()) {
        
        $directory = __DIR__.'/../web/images/upload';
        $file = $commodityForm['picture']->getData();
        $file->move($directory, $file->getClientOriginalName());
        $commodity->setPicture($file->getClientOriginalName());
        $app['dao.commodity']->update($commodity);
        $app['session']->getFlashBag()->add('success', 'Le produit a été mis à jour sur la carte.');
    }
    return $app['twig']->render('commodity_form.html.twig', array(
        'title' => 'Ajout d\'un produit',
        'commodityForm' => $commodityForm->createView()));
})->bind('admin_commodity_add');

// Delete a commodity (Admin)
$app->get('/admin/commodity/{id}/delete', function($id, Request $request) use ($app) {
    $app['dao.commodity']->delete($id);
    $app['session']->getFlashBag()->add('success', 'Le produit a été supprimé de la base de données.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_commodity_delete');
