<?php
namespace ClickPizza\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use ClickPizza\Entity\User;
use ClickPizza\Form\Type\CreateAccountUserType;
use ClickPizza\Form\Type\ResetPwdUserType;

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
            
            $login = $user->getUsername();
            $email = $user->getEmail();
            
            $row = $app['dao.user']->checkLoginEmail($login, $email);
            
            if ($row['count1'] > 0) {
                $app['session']->getFlashBag()->add('warning', 'Le login ' .$login. ' existe déjà, merci d\'en choisir un autre.');
            } elseif ($row['count2'] > 0) {
                $app['session']->getFlashBag()->add('warning', 'L\'adresse mail ' .$email. ' existe déjà, merci d\'en choisir une autre.');
            } else {                
                $app['dao.user']->add($user);
                $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été créé avec succès.');
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
            $row = $app['dao.user']->checkLogin($username);
            if ($row['count'] == 1) {
                $user = $app['dao.user']->loadUserByUsername($username);
                $email = $user->getEmail();
                $newPwd = bin2hex(random_bytes(5));
                $salt = substr(md5(time()), 0, 23);
                $user->setSalt($salt);
                $encoder = $app['security.encoder.bcrypt'];
                $password = $encoder->encodePassword($newPwd, $user->getSalt());
       
                $user->setPassword($password);  
                $app['dao.user']->update($user);
             
                $headers  = 'MIME-Version: 1.0' . "\r\n";
		        $headers .= 'From: ClickPizza'. "\r\n" .				
				'Content-Type: text/html; charset="UTF-8"; DelSp="Yes"; format=flowed '."\r\n" .
				'Content-Disposition: inline'. "\r\n" .
				'Content-Transfer-Encoding: 7bit'." \r\n" .
				'X-Mailer:PHP/'.phpversion();
            
                $to = $email;
                $civility = $user->getCivility();
                $name = $user->getName();
                $object = 'Réinitialisation du mot de passe';         
                $messageOrd = "<blockquote><p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, votre nouveau mot de passe est " . $newPwd . "</p><br /><p>Nous vous invitons à le modifier dès votre prochaine connexion<small class='pull-right'><br />L'équipe ClickPizza</small></blockquote>";              

                mail($to, $object, $messageOrd, $headers);
                 
                 $app['session']->getFlashBag()->add('success', 'Nous vous avons fait parvenir un nouveau mot de passe à l\' adresse '.$email);
                return $app->redirect($app['url_generator']->generate('login'));
                $app['session']->getFlashBag()->add('success', 'Nous vous avons fait parvenir un nouveau mot de passe à l\' adresse '.$email);
                
            } else {
                    $app['session']->getFlashBag()->add('warning', 'Le login '.$username. ' n\'existe pas.');
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
        $userForm = $app['form.factory']->create(CreateAccountUserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $simplePassword = $user->getPassword();
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($simplePassword, $user->getSalt());
            $user->setPassword($password);
            $login = $user->getUsername();
            $email = $user->getEmail();
            $row = $app['dao.user']->checkLoginEmail($login, $email);
            if ($row['count1'] > 1) {
                $app['session']->getFlashBag()->add('warning', 'Le login ' .$login. ' existe déjà, merci d\'en choisir un autre.');
            } elseif ($row['count2'] > 1) {
                $app['session']->getFlashBag()->add('warning', 'L\'adresse mail ' .$email. ' existe déjà, merci d\'en choisir une autre.');
            } else {   
            $app['dao.user']->update($user);
            $name = $user->getName();
            $civility = $user->getCivility();
            $app['session']->getFlashBag()->add('success', $civility. ' '.$name.', votre compte a été mis à jour avec succès.');
        }
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Modifier le compte utilisateur',
            'userForm' => $userForm->createView()));
    }
}
