<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

// Save global errors and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register error handler
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 403:
            $message = 'Acces refusé.';
            break;
        case 404:
            $message = 'La page demandée n\'a pas pu être trouvée.';
            break;
        default:
            $message = 'Cette page ne s\'ouvre pas';
            break;
    }
    return $app['twig']->render('error.html.twig', array(
        'message' => $message));
});

// Register service providers
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
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => function () use ($app) {
                return new ClickPizza\DAO\UserDAO($app['db']);
            },
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.acces_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
    ),
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

// Register services
$app['dao.commodity'] = function ($app) {
    return new ClickPizza\DAO\CommodityDAO($app['db']);
};
$app['dao.user'] = function ($app) {
    return new ClickPizza\DAO\UserDAO($app['db']);
};
$app['dao.order'] = function ($app) {
    $orderDAO = new ClickPizza\DAO\OrderDAO($app['db']);
    $orderDAO->setUserDAO($app['dao.user']);
    return $orderDAO;
};
$app['dao.orderCommodity'] = function ($app) {
    $orderCommodityDAO = new ClickPizza\DAO\OrderCommodityDAO($app['db']);
    $orderCommodityDAO->setOrderDAO($app['dao.order']);
    $orderCommodityDAO->setCommodityDAO($app['dao.commodity']);
    return $orderCommodityDAO;
};
$app['service.decode'] = function () {
    return new ClickPizza\Service\Decode();
};
$app['service.email'] = function () {
    return new ClickPizza\Service\Email();
};
$app['service.calculation'] = function () {
    return new ClickPizza\Service\Calculation();
};
$app['service.encode'] = function () {
    return new ClickPizza\Service\Encode();
};