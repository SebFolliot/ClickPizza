<?php

namespace ClickPizza\Service;

class Email
{
    /**
     * 
     * @return the header of an email
     */
    public function emailHeader() {
        $headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'From: ClickPizza'. "\r\n" .				
				'Content-Type: text/html; charset="UTF-8"; DelSp="Yes"; image/png; format=flowed '."\r\n" .
				'Content-Disposition: inline'. "\r\n" .
				'Content-Transfer-Encoding: 7bit'." \r\n" .
				'X-Mailer:PHP/'.phpversion();
        return $headers;
    }
    
    /**
     * @return the message from the mail when creating a user account
     *
     * @param $user
     */  
    public function emailMessageCreateUserAccount($user) {
        $civility = $user->getCivility();
        $name = $user->getName();
        $pseudo = $user->getUsername();
        
        $messageUser = "<div style='background-color: #669900; height: 102px; margin-bottom: 140px'><div><img src='http://www.clickpizza.construksite.fr/web/images/logo.png' alt='logo clickpizza' title='ClickPizza' /></div></div><blockquote><p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, bienvenue chez <strong>ClickPizza</strong>.<br />Votre compte a été créé avec succès.</p><p>Votre pseudo : <span style='font-weight :bold'>".$pseudo."</span><br /><br /><small style='float: right'>L'équipe ClickPizza</small></blockquote><div style='text-align: center; background-color: rgba(0, 0, 0, 0.7); margin-top: 30px'><img src='http://www.clickpizza.construksite.fr/web/images/logo_min.png' alt='logo clickpizza' title='ClickPizza' /><div style='color: white'><p style='font-style: italic; font-size: x-small'>Pour votre santé, évitez de manger entre les repas, <a href='http://www.mangerbouger.fr' target='_blank'>www.mangerbouger.fr</a><br /> L’abus d’alcool est dangereux pour la santé. Sachez consommer et apprécier avec modération</p>
                <p style='font-style: italic'>Site créé pour projet personnel - OpenClassrooms</p></div></div>";
        return $messageUser;
    }
    
    /**
     * @return the message when the password is reset
     *
     * @param $user
     * @param string $newPwd
     */ 
    public function emailMessageResetPwdUser($user, $newPwd) {
          $civility = $user->getCivility();
          $name = $user->getName();
       
          $messageUser = "<div style='background-color: #669900; height: 102px; margin-bottom: 140px'><div><img src='http://www.clickpizza.construksite.fr/web/images/logo.png' alt='logo clickpizza' title='ClickPizza' /></div></div><blockquote><p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, votre nouveau mot de passe est " . $newPwd . "</p><br /><p>Nous vous invitons à le modifier dès votre prochaine connexion<small style='float: right'><br />L'équipe ClickPizza</small></blockquote><div style='text-align: center; background-color: rgba(0, 0, 0, 0.7); margin-top: 30px'><img src='http://www.clickpizza.construksite.fr/web/images/logo_min.png' alt='logo clickpizza' title='ClickPizza' /><div style='color: white'><p style='font-style: italic; font-size: x-small'>Pour votre santé, évitez de manger entre les repas, <a href='http://www.mangerbouger.fr' target='_blank'>www.mangerbouger.fr</a><br /> L’abus d’alcool est dangereux pour la santé. Sachez consommer et apprécier avec modération</p><p style='font-style: italic'>Site créé pour projet personnel - OpenClassrooms</p></div></div>";
          return $messageUser;
    }
    
    /**
     * @return the message of the mail following an order
     *
     * @param $order
     * @param $user
     $ @param string $message
     * @param decimal $price_record
     */ 
    public function emailMessageAddOrder($order, $user, $message, $price_record) {
        $ord_id = $order->getId();
        $civility = $user->getCivility();
        $name = $user->getName();
        
        $messageUser = "<div style='background-color: #669900; height: 102px; margin-bottom: 140px'><div><img src='http://www.clickpizza.construksite.fr/web/images/logo.png' alt='logo clickpizza' title='ClickPizza' /></div></div><p><span style='font-weight :bold'>" . $civility . " " . $name ."</span>, voici le récapitulatif de votre commande.</p><p style='color :#669900'><span style='text-decoration :underline'>N° de commande</span> : " . $ord_id . "</p><table><thead><tr style='font-weight: bold; background-color: #669900; color:white'><th style='text-align: center'>Nom du produit</th><th style='text-align: center'>Tarif unitaire</th><th style='text-align: center'>Quantité</th><th style='text-align: center'>Prix</th></tr></thead>" . implode('', $message) . "</table><p style='color :#669900'><span='text-decoration :underline'>Montant total de votre facture :</span> " . $price_record . " €.</p><em><strong>ClickPizza</strong> vous remercie de votre commande et vous souhaite un bon appétit.</em><div style='text-align: center; background-color: rgba(0, 0, 0, 0.7); margin-top: 30px'><img src='http://www.clickpizza.construksite.fr/web/images/logo_min.png' alt='logo clickpizza' title='ClickPizza' /><div style='color: white'><p style='font-style: italic; font-size: x-small'>Pour votre santé, évitez de manger entre les repas, <a href='http://www.mangerbouger.fr' target='_blank'>www.mangerbouger.fr</a><br /> L’abus d’alcool est dangereux pour la santé. Sachez consommer et apprécier avec modération</p><p style='font-style: italic'>Site créé pour projet personnel - OpenClassrooms</p></div></div>";
        return $messageUser;
    }
    
    /**
     * @return the object of the email when creating a user account
     */
    public function emailObjectCreateUserAccount() {
        $object = 'Compte ClickPizza';
        return $object;
    }
    
    /**
     * @return the object of the email when creating a user account
     */
    public function emailObjectResetPwdUser() {
        $object = 'Réinitialisation du mot de passe';
        return $object;
    }
    
    /**
     * @returns the object of the mail following a command
     */
    public function emailObjectAddOrder() {
        $object = 'Récapitulatif de votre commande ';
        return $object;
    }
    
    /**
     * @return the recipient of the email
     *
     * @param $user
     */
    public function emailRecipient($user) {
        $email = $user->getEmail();
        return $email;
    }
    
    /**
     * @return the user account creation email
     *
     * @param $user
     */
    public function emailCreateUserAccount($user) {
        $headers = $this->emailHeader();
        $messageUser = $this->emailMessageCreateUserAccount($user);
        $to = $this->emailRecipient($user);
        $object = $this->emailObjectCreateUserAccount();
        $email = mail($to, $object, $messageUser, $headers);
        return $email;
    }
    
    /**
     * return the password reset email
     *
     * @param $user
     * @param string $newPwd
     */
    public function emailResetPwdUser($user, $newPwd) {
        $headers = $this->emailHeader();
        $messageUser = $this->emailMessageResetPwdUser($user, $newPwd);
        $to = $this->emailRecipient($user);
        $object = $this->emailObjectResetPwdUser();
        $email = mail($to, $object, $messageUser, $headers);
        return $email;
    }
    
    /**
     * return return the order summary email
     *
     * @param $order
     * @param $user
     * @param string $message
     * @ param decimal $price_record
     */
    public function emailAddOrder($order, $user, $message, $price_record) {
        $headers = $this->emailHeader();
        $messageUser = $this->emailMessageAddOrder($order, $user, $message, $price_record);
        $to = $this->emailRecipient($user);
        $object = $this->emailObjectAddOrder();
        $email = mail($to, $object, $messageUser, $headers);
        return $email;
    }
}