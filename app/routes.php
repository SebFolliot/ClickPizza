<?php

use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Entity\Commodity;
use ClickPizza\Form\CreateAccountUserType;


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
