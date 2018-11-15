<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Form\Type\CreateAccountUserType;
use ClickPizza\Form\Type\ResetPwdUserType;
use ClickPizza\Form\Type\ChangePwdType;
use ClickPizza\Form\Type\UpdateAccountType;

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
            $password = $app['service.encode']->encodePasswordOfAccount($user, $app);
            // We check if the login and / or the email exists before creating a user account
            $check = $app['service.check']->checkLoginEmailForCreateAccountUser($user, $app);
            if ($check === true) {
                return $app->redirect($app['url_generator']->generate('login'));
            }
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Création d\'un compte utilisateur',
            'userForm' => $userForm->createView()
        ));
    }
    
    
    /**
     * Reset the password for the user account controller
     *
     * @param Application $app Silex application
     */ 
    public function resetPwdUserAction(Request $request, Application $app) {
        $user = $app['dao.user']->allUsers();
        $resetPwdForm = $app['form.factory']->create(ResetPwdUserType::class, $user);
        $resetPwdForm->handleRequest($request);
        if ($resetPwdForm->isSubmitted() && $resetPwdForm->isValid()) {
            $username = $resetPwdForm['username']->getData();
            // We check if the login exist before reset the password
            $check = $app['service.check']->checkLoginForResetPwd($username, $app);
            if($check === true) {
                // Redirect to login home page
                return $app->redirect($app['url_generator']->generate('login'));    
            }
        }
        return $app['twig']->render('reset_pwd_form.html.twig', array(
            'title' => 'Réinitialisation du mot de passe',
            'resetPwdForm' => $resetPwdForm->createView()
            ));        
    }
    
    /**
     * User account controller
     *
     * @param Application $app Silex application
     */
    public function userAccountAction(Application $app) {
        return $app['twig']->render('user_account.html.twig', array(
            'title' => 'Mon compte'
        ));
    }
    
    /**
     * Update an account controller
     *
     * @param Application $app Silex application
     * @param integer $id User id
     */ 
    public function editUserAccountAction($id, Request $request, Application $app) {
        $user = $app['dao.user']->userList($id);
        $oldEmail = $user->getEmail();
        $userForm = $app['form.factory']->create(UpdateAccountType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // We check if the email exist before edit a user account
            $check = $app['service.check']->checkEmailForEditAccountUser($user, $oldEmail, $app);
            if ($check === true) {
                return $app->redirect($app['url_generator']->generate('user_account'));
            }
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Modifier le compte utilisateur',
            'userForm' => $userForm->createView()));
    }
    
    /**
     * Update a user's password controller
     *
     * @param Application $app Silex application
     * @param integer $id User id
     */ 
    public function changePwdAction ($id, Request $request, Application $app) {
        $user = $app['dao.user']->userList($id);
        $currentPassword = $user->getPassword();
       
        $changePwdForm = $app['form.factory']->create(ChangePwdType::class, $user);
        $changePwdForm->handleRequest($request);
        if ($changePwdForm->isSubmitted() && $changePwdForm->isValid()) {
            $data = $changePwdForm->getData();
            $oldPassword = $data->oldPassword;
               
            $salt = substr(md5(time()), 0, 23);
            $encoder = $app['security.encoder.bcrypt'];
            // Check if the old password is the correct one
            $checkPwd = $encoder->isPasswordValid($currentPassword, $oldPassword, $salt);

            if($checkPwd === true) {
                $app['service.encode']->encodePasswordOfAccount($user, $app);
                $app['dao.user']->updatePwd($user);
                $name = $user->getName();
                $civility = $user->getCivility();
                $role = $user->getRole();
                $app['session']->getFlashBag()->add('success', $civility. ' '.$name.', votre mot de passe a été mis à jour avec succès.');
                if ($role === 'ROLE_ADMIN') {
                    // Redirect to admin home page
                    return $app->redirect($app['url_generator']->generate('admin'));
                } else {
                    // Redirect to the user card
                    return $app->redirect($app['url_generator']->generate('user_account'));
                }  
            }  else {
                $app['session']->getFlashBag()->add('warning', 'Votre ancien mot de passe n\'est pas bon');
            } 
        }
        return $app['twig']->render('change_pwd_form.html.twig', array(
            'title' => 'Modification du mot de passe',
            'changePwdForm' => $changePwdForm->createView()));
    }
}
