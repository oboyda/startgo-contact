<?php 
namespace SGC\Service;

class Recaptcha {

    static function getSiteKey(){
        return '6LcgdVwqAAAAAB7VfixakTREkze985G9tBZtSZmh';
    }
    static function getSecretKey(){
        return '6LcgdVwqAAAAAPVbzrpUwV9HEjHVpvxwDHe9AWuh';
    }
    
    static function verify($token){

        $secretKey = self::getSecretKey();

        if(!$secretKey) return true;

        $resp = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $secretKey,
                'response' => $token
            ]
        ]);
        $resp_code = wp_remote_retrieve_response_code($resp);
        $resp_body_raw = wp_remote_retrieve_body($resp);
        $resp_body = $resp_body_raw ? json_decode($resp_body_raw): [];

        return ($resp_code == 200 && !!$resp_body?->success);
    }
}