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
                'key' => 'post_content',
                'type' => 'data'
            ],
            'customer_first_name' => [
                'type' => 'meta'
            ],
            'customer_last_name' => [
                'type' => 'meta'
            ],
            'customer_email' => [
                'type' => 'meta'
            ]
        ]);
    }
}