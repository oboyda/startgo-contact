<?php 

function sgc_register_styles(){
    wp_register_style(
        'sgc-bootstrap', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'
    );
}
add_action('wp_enqueue_scripts', 'sgc_register_styles');

function sgc_register_scripts(){
    wp_register_script(
        'sgc-bootstrap', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'
    );
}
add_action('wp_enqueue_scripts', 'sgc_register_scripts');
