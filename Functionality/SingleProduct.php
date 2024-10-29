<?php

namespace SimpleProductBadges\Functionality;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class SingleProduct
{

    protected $plugin_name;
    protected $plugin_version;

    public function __construct($plugin_name, $plugin_version)
    {
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;

        add_filter('manage_badges_posts_columns', [$this, 'set_custom_edit_badges_columns']);
        add_action('manage_badges_posts_custom_column', [$this, 'custom_badges_column'], 10, 2);

        add_action('carbon_fields_register_fields', [$this, 'register_single_badge_fields']);
    }

    public function register_single_badge_fields()
    {
        Container::make('post_meta', __('Single Product Badge', 'simple-product-badges'))
            ->where('post_type', '=', 'product')
            ->add_fields(array(
                Field::make('checkbox', 's_badge_disabled_all', __('Disable ALL BADGES for this product', 'simple-product-badges'))
                    ->set_visible_in_rest_api(true),
                Field::make('checkbox', 's_badge_single_active', __('Create a Badge ONLY for this product', 'simple-product-badges'))
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        )
                    )),
                Field::make('select', 'badge_type', __('Badge Type'))
                    ->set_required(true)
                    ->add_options(array(
                        'type_custom' => __('Custom Badge', 'simple-product-badges'),
                        'type_new' => __('New Product', 'simple-product-badges'),
                        'type_lowstock' => __('Low in Stock', 'simple-product-badges'),
                        'type_sale' => __('Sale', 'simple-product-badges'),
                    ))
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                // CUSTOM PRODUCT
                Field::make('checkbox', 'badge_custom_set_date', __('You want the tag to be activated and deactivated automatically on selected dates?', 'simple-product-badges'))
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'badge_type',
                            'value' => 'type_custom',
                        ),
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                Field::make('date', 'badge_initial_date', __('Start Date', 'simple-product-badges'))
                    ->set_required(true)
                    ->set_visible_in_rest_api(true)
                    ->set_storage_format('Y-m-d')
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'badge_custom_set_date',
                            'value' => true,
                        ),
                        array(
                            'field' => 'badge_type',
                            'value' => 'type_custom',
                        ),
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                Field::make('date', 'badge_end_date', __('End Date', 'simple-product-badges'))
                    ->set_required(true)
                    ->set_visible_in_rest_api(true)
                    ->set_storage_format('Y-m-d')
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'badge_custom_set_date',
                            'value' => true,
                        ),
                        array(
                            'field' => 'badge_type',
                            'value' => 'type_custom',
                        ),
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                // NEW PRODUCT
                Field::make('text', 'badge_new_days', __('Number of days to be considered new', 'simple-product-badges'))
                    ->set_required(true)
                    ->set_attribute('type', 'number')
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'badge_type',
                            'value' => 'type_new',
                        ),
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                //LOW IN STOCK
                Field::make('separator', 'crb_separator', __('In products with variations show a tooltip with the stock of each variation', 'simple-product-badges'))
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'badge_type',
                            'value' => 'type_lowstock',
                        ),
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                Field::make('text', 'badge_lowstock_low', __('Quantity of stock to be considered low', 'simple-product-badges'))
                    ->set_required(true)
                    ->set_attribute('type', 'number')
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'badge_type',
                            'value' => 'type_lowstock',
                        ),
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),

                Field::make('text', 'badge_lowstock_medium', __('Quantity of stock to be considered High (For modal in product with variations)', 'simple-product-badges'))
                    ->set_required(true)
                    ->set_attribute('type', 'number')
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(
                        [
                            [
                                'field' => 'badge_type',
                                'value' => 'type_lowstock'
                            ],
                            [
                                'field' => 's_badge_disabled_all',
                                'value' => false
                            ],
                            [
                                'field' => 's_badge_single_active',
                                'value' => true
                            ]
                        ]
                    ),

                //FOR ALL && SALE && CUSTOM
                Field::make('text', 'badge_text', esc_html__('Display Name:', 'simple-product-badges'))
                    ->set_required(true)
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                Field::make('text', 'badge_text_size', esc_html__('Text Size, in px (By default 12px):', 'simple-product-badges'))
                    ->set_attribute('placeholder', '16')
                    ->set_attribute('type', 'number')
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                Field::make('color', 'badge_text_color', esc_html__('Text Color:', 'simple-product-badges'))
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                Field::make('color', 'badge_bg_color', esc_html__('Badge Color: (Click on X, to set transparent)', 'simple-product-badges'))
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic(array(
                        array(
                            'field' => 's_badge_disabled_all',
                            'value' => false,
                        ),
                        array(
                            'field' => 's_badge_single_active',
                            'value' => true,
                        )
                    )),
                Field::make('select', 'badge_style', __('Style Badge (Coming soon)'))
                    ->add_options([
                        'badge_default' => esc_html__('Default Badge', 'simple-product-badges'),
                    ])
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic([
                        [
                            'field' => 's_badge_disabled_all',
                            'value' => false
                        ],
                        [
                            'field' => 's_badge_single_active',
                            'value' => true
                        ]
                    ]),
                Field::make('select', 'badge_position', __('Badge Position (More coming soon)'))
                    ->set_required(true)
                    ->add_options([
                        'position_tl' => esc_html__('Top Left', 'simple-product-badges'),
                        'position_tr' => esc_html__('Top Right', 'simple-product-badges')
                    ])
                    ->set_visible_in_rest_api(true)
                    ->set_conditional_logic([
                        [
                            'field' => 's_badge_disabled_all',
                            'value' => false
                        ],
                        [
                            'field' => 's_badge_single_active',
                            'value' => true
                        ]

                    ]),
            ));
        return;
    }

    function set_custom_edit_badges_columns($columns)
    {
        $custom_col_order = [
            'cb' => $columns['cb'],
            'title' => $columns['title'],
            'b_name' => esc_html__('Display Name', 'simple-product-badges'),
            'b_type' => esc_html__('Badge Type', 'simple-product-badges'),
            'b_priority' => esc_html__('Badge Priority', 'simple-product-badges'),
            'b_tags' => esc_html__('Apply In', 'simple-product-badges'),
            'b_position' => esc_html__('Badge Position', 'simple-product-badges'),
            'date' => $columns['date']
        ];

        return $custom_col_order;
    }

    function custom_badges_column($column, $post_id)
    {
        switch ($column) {
            case 'b_name':
                $name = carbon_get_post_meta($post_id, 'badge_text');
                echo esc_html($name);
                break;
            case 'b_type':
                $type = carbon_get_post_meta($post_id, 'badge_type');
                switch ($type) {
                    case 'type_custom':
                        echo esc_html__('Custom', 'simple-product-badges');
                        break;
                    case 'type_new':
                        echo esc_html__('New', 'simple-product-badges');
                        break;
                    case 'type_lowstock':
                        echo esc_html__('Low Stock', 'simple-product-badges');
                        break;
                    case 'type_sale':
                        echo esc_html__('Sale', 'simple-product-badges');
                        break;
                }
                break;
            case 'b_priority':
                $priority = carbon_get_post_meta($post_id, 'badge_priority');
                echo esc_html($priority);
                break;
            case 'b_tags':
                $tags = carbon_get_post_meta($post_id, 'badge_apply');
                switch ($tags) {
                    case 'apply_tag':
                        echo esc_html__('Tags', 'simple-product-badges');
                        break;
                    case 'apply_category':
                        echo esc_html__('Categories', 'simple-product-badges');
                        break;
                    case 'apply_all':
                        echo esc_html__('All', 'simple-product-badges');
                        break;
                }
                break;
            case 'b_position':
                $position = carbon_get_post_meta($post_id, 'badge_position');
                switch ($position) {
                    case 'position_tl':
                        echo esc_html__('Top Left', 'simple-product-badges');
                        break;
                    case 'position_tr':
                        echo esc_html__('Top Right', 'simple-product-badges');
                        break;
                }
                break;
        }
    }
}
