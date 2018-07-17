<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Form\Type\CreateAccountUserType;

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
     * Delete a account user/admin
     *
     * @param integer $id User id
     * @param Application $app Silex application
     */
    public function deleteAction($id, Request $request, Application $app) {
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'Le compte a été supprimé de la base de données.');
         
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
    
    public function editAdminAccountAction($id, Request $request, Application $app) {
        $user = $app['dao.user']->userList($id);
        $userForm = $app['form.factory']->create(CreateAccountUserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $simplePassword = $user->getPassword();
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($simplePassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->update($user);
            $app['session']->getFlashBag()->add('success', 'Le compte administrateur a été mis à jour avec succès.');
        }
        return $app['twig']->render('admin_form.html.twig', array(
            'title' => 'Modifier le compte administrateur',
            'userForm' => $userForm->createView()));
        }
}
