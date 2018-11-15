<?php

namespace ClickPizza\Service;

use Silex\Application;


class Check
{
    
    /**
     * Check if login and email do not exist before creating a user
     *
     * @param $user
     * @param Application $app Silex application
     */
    public function checkLoginEmailForCreateAccountUser($user, Application $app) {
        $login = $user->getUsername();
        $email = $user->getEmail();
        $row = $app['dao.user']->checkLoginEmail($login, $email);
        $bool = false;
        if ($row['count1'] > 0) {
             $app['session']->getFlashBag()->add('warning', 'Le login ' .$login. ' existe déjà, merci d\'en choisir un autre.');
        } elseif ($row['count2'] > 0) {
           $app['session']->getFlashBag()->add('warning', 'L\'adresse mail ' .$email. ' existe déjà, merci d\'en choisir une autre.');
        } else {
           $bool = true;
           $app['service.email']->emailCreateUserAccount($user);
           $app['dao.user']->add($user);
           $app['session']->getFlashBag()->add('success', 'L\'utilisateur a été créé avec succès.');
           return $bool;
        }
    }
    
    /**
     * Check if email do not exist before edit a user
     *
     * @param $user
     * @param string $oldEmail
     * @param Application $app Silex application
     */
    public function checkEmailForEditAccountUser($user, $oldEmail, Application $app) {
        $civility = $user->getCivility();
        $email = $user->getEmail();
        $name = $user->getName();
        $row = $app['dao.user']->checkEmail($email);    
        $bool = false;       
        if (($row['count'] > 1) || (($row['count'] + 1 == 2) && $oldEmail != $email)) {
            $app['session']->getFlashBag()->add('warning', 'L\'adresse mail ' .$email. ' existe déjà, merci d\'en choisir une autre.');
        } else {
            $bool = true;
            $app['dao.user']->update($user);
            $app['session']->getFlashBag()->add('success', $civility. ' '.$name.', votre compte a été mis à jour avec succès.');
            return $bool;
        }                        
     }
    
    /**
     * Check if login do not exist before reset a password
     *
     * @param string $username
     * @param Application $app Silex application
     */
    public function checkLoginForResetPwd($username, Application $app) {
        $row = $app['dao.user']->checkLogin($username);
        $bool = false;
        if ($row['count'] == 1) {
            $bool = true;
            $user = $app['dao.user']->loadUserByUsername($username);
            $newPwd = bin2hex(random_bytes(5));
            $password = $app['service.encode']->encodePasswordWhenResetPwd($user, $newPwd, $app);
            $email = $user->getEmail();
            $app['service.email']->emailResetPwdUser($user, $newPwd);       
            $app['session']->getFlashBag()->add('success', 'Nous vous avons fait parvenir un nouveau mot de passe à l\' adresse '.$email);
            return $bool;
            } else {
                $app['session']->getFlashBag()->add('warning', 'Le login '.$username. ' n\'existe pas.');
        } 
    }
    
    /**
     * Check if the login does not exist before creating an admin account
     *
     * @param $user
     * @param Application $app Silex application
     */
    public function checkLoginForCreateAdminAccount($user, Application $app) {
        $login = $user->getUsername();
        $row = $app['dao.user']->checkLogin($login);
        $bool = false;
        if ($row['count'] > 0) {
            $app['session']->getFlashBag()->add('warning', 'Le login ' .$login. ' existe déjà, merci d\'en choisir un autre.');
        } else {
            $bool = true;
            $app['dao.user']->add($user);
            $app['session']->getFlashBag()->add('success', 'Le compte administrateur ' .$login. ' a été créé avec succès.');
            return $bool;
        }                        
     }
    
    /**
     * Check the old password and update it if the check is ok
     *
     * @param $user
     * @param $data
     * @param $currentPassword
     * @param Application $app Silex application
     */
    public function checkPwdForChangePwd($user, $data, $currentPassword, Application $app) {
        $oldPassword = $data->oldPassword;         
        $salt = substr(md5(time()), 0, 23);
        $encoder = $app['security.encoder.bcrypt'];
        $checkPwd = $encoder->isPasswordValid($currentPassword, $oldPassword, $salt);
        $view = '';
        if($checkPwd === true) {
            $app['service.encode']->encodePasswordOfAccount($user, $app);
            $app['dao.user']->updatePwd($user);
            $name = $user->getName();
            $civility = $user->getCivility();
            $role = $user->getRole();
            $app['session']->getFlashBag()->add('success', $civility. ' '.$name.', votre mot de passe a été mis à jour avec succès.');
            if ($role === 'ROLE_ADMIN') {
                $view = 'adminView';
                return $view;
            } else {
                $view = 'userView';
                return $view;
            }               
        } else {
            $app['session']->getFlashBag()->add('warning', 'Votre ancien mot de passe n\'est pas bon');
        } 
    }
}
