<?php 
namespace SGC\Service;

class Recaptcha {
    
    static function verify($token){

        $secretKey = '6LcgdVwqAAAAAPVbzrpUwV9HEjHVpvxwDHe9AWuh';

        $resp = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $secretKey,
                'response' => $token
            ]
        ]);
        // $resp_code = wp_remote_retrieve_response_code($resp);
        $resp_body_raw = wp_remote_retrieve_body($resp);
        $resp_body = $resp_body_raw ? json_decode($resp_body_raw): [];

        return !!$resp_body?->success;
    }
}