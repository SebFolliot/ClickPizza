<?php

use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Entity\Commodity;
use ClickPizza\Form\CreateAccountUserType;


// Page d'accueil
$app->get('/', function () use ($app) {
    $commodities = $app['dao.commodity']->pizzaList();
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('home');

$app->get('/drink', function () use ($app) {
    $commodities = $app['dao.commodity']->drinkList();
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('drink');

$app->get('/salad', function () use ($app) {
    $commodities = $app['dao.commodity']->saladList();
    return $app['twig']->render('index.html.twig', array(
        'commodities' => $commodities,
        'title'       => 'Accueil'
    ));
})->bind('salad');

// Formulaire de connexion
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'title'         => 'Page d\'authentification'
    ));
})->bind('login');

// Création d'un compte utilisateur
$app->match('/create_account', function(Request $request) use ($app) {
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
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été créé avec succès.');
     }
    return $app['twig']->render('user_form.html.twig', array(
        'title' => 'Création d\'un compte',
        'userForm' => $userForm->createView()));
})->bind('create_account');
