<?php 
namespace SGC\Type;

class Contact extends Base {
    
    public function __construct($post=null){
        parent::__construct($post, 'sgc_contact', [
            'title' => [
                'key' => 'post_title',
                'type' => 'data'
            ],
            'comments' => [
                'key' => 'post_excerpt',
                'type' => 'data',
                'maxlength' => 150
            ],
            'customer_first_name' => [
                'type' => 'meta',
                'required' => true,
                'maxlength' => 25
            ],
            'customer_last_name' => [
                'type' => 'meta',
                'required' => true,
                'maxlength' => 25
            ],
            'customer_email' => [
                'type' => 'meta',
                'required' => true,
                'maxlength' => 25
            ],
            'customer_country' => [
                'type' => 'meta',
                'required' => true
            ]
        ]);
    }
}