<?php

namespace ClickPizza\Service;

use Silex\Application;

class Encode
{
    /**
     * @returns an encoded password to the user
     *
     * @param $user
     * @param string $password
     * @param Application $app Silex application
     */
    public function encodePassword($user, $password, Application $app) {
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $encoder = $app['security.encoder.bcrypt'];
        $passwordEncode = $encoder->encodePassword($password, $user->getSalt());
        $pwd = $user->setPassword($passwordEncode);
        return $pwd;
    }
    
    /**
     * @returns the password encoded for a password chosen by the user
     *
     * @param $user
     * @param Application $app Silex application
     */
    public function encodePasswordOfAccount($user, Application $app) {
        $password = $user->getPassword();
        $pwd = $this->encodePassword($user, $password, $app);
        return $pwd;
    }
    
    /**
     * @returns the password encoded for a password chosen randomly
     *
     * @param $user
     * @param string $newPwd
     * @param Application $app Silex application
     */
    public function encodePasswordWhenResetPwd($user, $newPwd, Application $app) {
        $pwd = $this->encodePassword($user, $newPwd, $app);
        return $app['dao.user']->updatePwd($user);         
    }
}