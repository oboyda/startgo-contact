<?php 

if(function_exists('acf_add_options_page')){
    acf_add_options_page([
        'page_title'    => __('SGC Options', 'sgc'),
        'menu_title'    => __('SGC Options', 'sgc'),
        'menu_slug'     => 'sgc-options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ]);
}
