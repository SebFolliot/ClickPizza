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
                $headers  = 'MIME-Version: 1.0' . "\r\n";
		        $headers .= 'From: ClickPizza'. "\r\n" .				
				'Content-Type: text/html; charset="UTF-8"; DelSp="Yes"; image/png; format=flowed '."\r\n" .
				'Content-Disposition: inline'. "\r\n" .
				'Content-Transfer-Encoding: 7bit'." \r\n" .
				'X-Mailer:PHP/'.phpversion();
            
                $to = $email;
                $civility = $user->getCivility();
                $name = $user->getName();
                $pseudo = $user->getUsername();
                $object = 'Compte ClickPizza';         
              
                $messageOrd = "<div style='background-color: #669900; height: 102px; margin-bottom: 140px'><div><img src='http://www.clickpizza.construksite.fr/web/images/logo.png' alt='logo clickpizza' title='ClickPizza' /></div></div><blockquote><p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, bienvenue chez <strong>ClickPizza</strong>.<br />Votre compte a été créé avec succès.</p><p>Votre pseudo : <span style='font-weight :bold'>".$pseudo."</span><br /><br /><small style='float: right'>L'équipe ClickPizza</small></blockquote><div style='text-align: center; background-color: rgba(0, 0, 0, 0.7); margin-top: 30px'><img src='http://www.clickpizza.construksite.fr/web/images/logo_min.png' alt='logo clickpizza' title='ClickPizza' /><div style='color: white'><p style='font-style: italic; font-size: x-small'>Pour votre santé, évitez de manger entre les repas, <a href='http://www.mangerbouger.fr' target='_blank'>www.mangerbouger.fr</a><br /> L’abus d’alcool est dangereux pour la santé. Sachez consommer et apprécier avec modération</p>
                <p style='font-style: italic'>Site créé pour projet personnel - OpenClassrooms</p></div></div>";              
                mail($to, $object, $messageOrd, $headers);
                
                $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été créé avec succès.');
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
				'Content-Type: text/html; charset="UTF-8"; DelSp="Yes"; image/png; format=flowed '."\r\n" .
				'Content-Disposition: inline'. "\r\n" .
				'Content-Transfer-Encoding: 7bit'." \r\n" .
				'X-Mailer:PHP/'.phpversion();
            
                $to = $email;
                $civility = $user->getCivility();
                $name = $user->getName();
                $object = 'Réinitialisation du mot de passe';         
                $messageOrd = "<div style='background-color: #669900; height: 102px; margin-bottom: 140px'><div><img src='http://www.clickpizza.construksite.fr/web/images/logo.png' alt='logo clickpizza' title='ClickPizza' /></div></div><blockquote><p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, votre nouveau mot de passe est " . $newPwd . "</p><br /><p>Nous vous invitons à le modifier dès votre prochaine connexion<small style='float: right'><br />L'équipe ClickPizza</small></blockquote><div style='text-align: center; background-color: rgba(0, 0, 0, 0.7); margin-top: 30px'><img src='http://www.clickpizza.construksite.fr/web/images/logo_min.png' alt='logo clickpizza' title='ClickPizza' /><div style='color: white'><p style='font-style: italic; font-size: x-small'>Pour votre santé, évitez de manger entre les repas, <a href='http://www.mangerbouger.fr' target='_blank'>www.mangerbouger.fr</a><br /> L’abus d’alcool est dangereux pour la santé. Sachez consommer et apprécier avec modération</p>
                <p style='font-style: italic'>Site créé pour projet personnel - OpenClassrooms</p></div></div>";              

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
        $userForm = $app['form.factory']->create(UpdateAccountType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
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
            $user->setSalt($salt);
            $simplePassword = $user->getPassword();
            $encoder = $app['security.encoder.bcrypt'];
            // Check if the old password is the correct one
            $checkPwd = $encoder->isPasswordValid($currentPassword, $oldPassword, $salt);

            if($checkPwd === true) {
                $password = $encoder->encodePassword($simplePassword, $user->getSalt());
                $user->setPassword($password);
                $app['dao.user']->updatePwd($user);
                $name = $user->getName();
                $civility = $user->getCivility();
                $app['session']->getFlashBag()->add('success', $civility. ' '.$name.', votre mot de passe a été mis à jour avec succès.');
            }  else {
                $app['session']->getFlashBag()->add('warning', 'Votre ancien mot de passe n\'est pas bon');
            } 
        }
        return $app['twig']->render('change_pwd_form.html.twig', array(
            'title' => 'Modification du mot de passe',
            'changePwdForm' => $changePwdForm->createView()));
    }
}
