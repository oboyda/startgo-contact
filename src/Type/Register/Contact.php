<?php
namespace SGC\Type\Register;

class Contact 
{
    public function __construct(){
        add_action('init', __CLASS__ . '::register');
    }

    static function register(){
        $labels = [
            'name'               => _x('Contacts', 'post type general name', 'sgc'),
            'singular_name'      => _x('Contact', 'post type singular name', 'sgc'),
            'menu_name'          => _x('Contacts', 'admin menu', 'sgc'),
            'name_admin_bar'     => _x('Contacts', 'add new on admin bar', 'sgc'),
            'add_new'            => _x('Add Contact', 'Contact type', 'sgc'),
            'add_new_item'       => __('Add new Contact', 'sgc'),
            'new_item'           => __('New Contact', 'sgc'),
            'edit_item'          => __('Edit Contact', 'sgc'),
            'view_item'          => __('View Contact', 'sgc'),
            'all_items'          => __('All Contacts', 'sgc'),
            'search_items'       => __('Search Contacts', 'sgc'),
            'parent_item_colon'  => __('Contact parent:', 'sgc'),
            'not_found'          => __('No Contacts found.', 'sgc'),
            'not_found_in_trash' => __('No Contacts found in trash.', 'sgc')
        ];
        $args = [
            'labels'              => $labels,
            'description'         => __('Contact post type.', 'sgc'),
            'public'              => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => _x('contact', 'URL slug', 'sgc')),
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-email-alt',
            'supports'            => array('title', 'excerpt', 'custom-fields'),

            // 'show_in_rest'        => true,
            // 'rest_base'           => 'contacts',
            // 'rest_controller_class' => '\SGC\Type\Rest\Contact',
        ];

        register_post_type('sgc_contact', $args);
    }
}
