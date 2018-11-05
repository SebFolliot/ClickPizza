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
}