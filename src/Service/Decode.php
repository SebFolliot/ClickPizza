<?php

namespace ClickPizza\Service;

class Decode 
{
    public function base64Decode($cookie) {
        $cookieBase64Decode = utf8_encode(base64_decode($cookie));
        return $cookieBase64Decode;
    }
    
    public function jsonDecode($cookie) {
        $cookieBase64Decode = $this->base64Decode($cookie);
        $cookieJsonDecode = json_decode($cookieBase64Decode, true);
        return $cookieJsonDecode; 
    }
}