<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Form\CreateAccountUserType;

class UserController {
    
    /**
     * User login controller
     *
     * @param Application $app Silex application
     */
    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'title'         => 'Page d\'authentification'
        ));
    }
    
    /**
     * Create a user account controller
     *
     * @param Application $app Silex application
     */ 
    public function createUserAccountAction(Request $request, Application $app) {
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
            'title' => 'Création d\'un compte utilisateur',
            'userForm' => $userForm->createView()
        ));
    }  
}
