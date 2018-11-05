<?php

namespace ClickPizza\Service;

class Decode 
{
     /**
     *
     * @param integer or string $cookie
     * @return a decoded string in base64 MIME
     */
    public function base64Decode($cookie) {
        $cookieBase64Decode = utf8_encode(base64_decode($cookie));
        return $cookieBase64Decode;
    }
    
     /**
     *
     * @param a decoded string in base64 MIME
     * @return a decoded string JSON
     */
    public function jsonDecode($cookie) {
        $cookieBase64Decode = $this->base64Decode($cookie);
        $cookieJsonDecode = json_decode($cookieBase64Decode, true);
        return $cookieJsonDecode; 
    }
}