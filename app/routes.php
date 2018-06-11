<?php

use Symfony\Component\HttpFoundation\Request;

// Page d'accueil
$app->get('/', function () use ($app) {
    $commodities = $app['dao.commodity']->pizzaList();
    return $app['twig']->render('index.html.twig', array('commodities' => $commodities));
})->bind('home');

$app->get('/drink', function () use ($app) {
    $commodities = $app['dao.commodity']->drinkList();
    return $app['twig']->render('index.html.twig', array('commodities' => $commodities));
})->bind('drink');

$app->get('/salad', function () use ($app) {
    $commodities = $app['dao.commodity']->saladList();
    return $app['twig']->render('index.html.twig', array('commodities' => $commodities));
})->bind('salad');

// Formulaire de connexion
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');
