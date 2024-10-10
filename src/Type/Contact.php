<?php 
namespace SGC\Type;

class Contact extends Base {
    
    public function __construct($post=null){
        parent::__construct($post, 'sgc_contact', [
            'title' => [
                'key' => 'post_title',
                'type' => 'data'
            ],
            'slug' => [
                'key' => 'post_name',
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
                'maxlength' => 50
            ],
            'customer_last_name' => [
                'type' => 'meta',
                'required' => true,
                'maxlength' => 50
            ],
            'customer_email' => [
                'type' => 'meta',
                'required' => true,
                'maxlength' => 50
            ],
            'customer_country' => [
                'type' => 'meta',
                'required' => true
            ]
        ]);
    }
}