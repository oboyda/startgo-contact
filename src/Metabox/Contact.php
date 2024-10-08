<?php 
namespace SGC\Metabox;

class Contact {

    public function __construct(){
        add_action('add_meta_boxes', [$this, 'registerDetailsMetabox']);
    }

    public function registerDetailsMetabox(){

        add_meta_box(
            'contact-details-metabox',
            __('Contact details', 'sgc'),
            [$this, 'renderDetailsMetabox'],
            'sgc_contact',
            'normal',
            'low'
        );
    }
    public function renderDetailsMetabox($post){

        $type_contact = new \SGC\Type\Contact($post);


    }
}
