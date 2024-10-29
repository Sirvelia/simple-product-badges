<?php

namespace SimpleProductBadges\Functionality;

class BadgeCPT
{

    protected $plugin_name;
    protected $plugin_version;

    public function __construct($plugin_name, $plugin_version)
    {
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;

        add_action('init', [$this, 'badge_custom_post_type']);
    }

    public function badge_custom_post_type()
    {
        $labels = [
            'name'                  => esc_html_x('Badges', 'Post Type General Name', 'simple-product-badges'),
            'singular_name'         => esc_html_x('Badge', 'Post Type Singular Name', 'simple-product-badges'),
            'menu_name'             => esc_html__('Custom Badge', 'simple-product-badges'),
            'all_items'             => esc_html_x('All Badges', 'simple-product-badges'),
            'name_admin_bar'        => esc_html__('Custom Badge', 'simple-product-badges'),
            'add_new_item'          => esc_html__('Add New Badge', 'simple-product-badges'),
            'add_new'               => esc_html__('Add Badge', 'simple-product-badges'),
        ];

        $args = [
            'label'                 => esc_html__('Badge', 'simple-product-badges'),
            'labels'                => $labels,
            'supports'              => ['title', 'thumbnail'],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
            'menu_icon'             => 'dashicons-editor-bold',
        ];

        register_post_type('badges', $args);
    }
}
