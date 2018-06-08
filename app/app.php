<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Enregistrer les erreurs globales et les gestionnaires d'exceptions
ErrorHandler::register();
ExceptionHandler::register();

// Enregistrer les fournisseurs de services
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
});
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));

// Enregistrer les services
$app['dao.commodity'] = function ($app) {
    return new ClickPizza\DAO\CommodityDAO($app['db']);
};