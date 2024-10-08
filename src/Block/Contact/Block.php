<?php 
namespace SGC\Block\Contact;

class Block {

    public function __construct(){
        add_action('init', [$this, 'register']);
    }

    public function register(){
        register_block_type(SGC_ROOT . '/build/Block/Contact', [
            'render_callback' => [$this, 'render']
        ]);
    }

    public function render(){
        return '<p>Block/Contact</p>';
    }
}
