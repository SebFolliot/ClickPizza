<?php

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