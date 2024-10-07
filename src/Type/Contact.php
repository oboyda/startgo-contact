<?php 
namespace SGC\Type;

class Contact extends Base {
    
    public function __construct($post){
        parent::__construct($post, [
            'customer_first_name' => [
                'type' => 'meta'
            ],
            'customer_last_name' => [
                'type' => 'meta'
            ],
            'customer_email' => [
                'type' => 'meta'
            ],
            'comments' => [
                'key' => 'post_content',
                'type' => 'data'
            ]
        ]);
    }
}