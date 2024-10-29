<?php

namespace SimpleProductBadges\Functionality;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class CustomFields
{

	protected $plugin_name;
	protected $plugin_version;

	public function __construct($plugin_name, $plugin_version)
	{
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;

		add_action('after_setup_theme', [$this, 'load_cf']);
		add_action('carbon_fields_register_fields', [$this, 'register_fields']);
	}

	public function load_cf()
	{
		\Carbon_Fields\Carbon_Fields::boot();
	}

	public function register_fields()
	{
		Container::make('post_meta', esc_html__('Badge', 'simple-product-badges'))
			->where('post_type', '=', 'badges')
			->add_fields(
				[
					Field::make('text', 'badge_priority', esc_html__('Badge priority (1 is max)', 'simple-product-badges'))
						->set_required(true)
						->set_attribute('type', 'number')
						->set_visible_in_rest_api(true),
					Field::make('select', 'badge_type', esc_html__('Badge Type'))
						->set_required(true)
						->add_options([
							'type_custom' => esc_html__('Custom Badge', 'simple-product-badges'),
							'type_new' => esc_html__('New Product', 'simple-product-badges'),
							'type_lowstock' => esc_html__('Low in Stock', 'simple-product-badges'),
							'type_sale' => esc_html__('Sale', 'simple-product-badges')
						])
						->set_visible_in_rest_api(true),
					// CUSTOM PRODUCT
					Field::make('checkbox', 'badge_custom_set_date', esc_html__('You want the tag to be activated and deactivated automatically on selected dates?', 'simple-product-badges'))
						->set_visible_in_rest_api(true)
						->set_conditional_logic([
							[
								'field' => 'badge_type',
								'value' => 'type_custom'
							]
						]),
					Field::make('date', 'badge_initial_date', esc_html__('Start Date', 'simple-product-badges'))
						->set_required(true)
						->set_visible_in_rest_api(true)
						->set_storage_format('Y-m-d')
						->set_conditional_logic([
							[
								'field' => 'badge_custom_set_date',
								'value' => true
							],
							[
								'field' => 'badge_type',
								'value' => 'type_custom'
							]
						]),
					Field::make('date', 'badge_end_date', esc_html__('End Date', 'simple-product-badges'))
						->set_required(true)
						->set_visible_in_rest_api(true)
						->set_storage_format('Y-m-d')
						->set_conditional_logic([
							[
								'field' => 'badge_custom_set_date',
								'value' => true,
							],
							[
								'field' => 'badge_type',
								'value' => 'type_custom'
							]
						]),

					// NEW PRODUCT
					Field::make('text', 'badge_new_days', esc_html__('Number of days to be considered new', 'simple-product-badges'))
						->set_required(true)
						->set_attribute('type', 'number')
						->set_visible_in_rest_api(true)
						->set_conditional_logic([
							[
								'field' => 'badge_type',
								'value' => 'type_new'
							]
						]),

					//LOW IN STOCK
					Field::make('separator', 'crb_separator', esc_html__('In products with variations show a tooltip with the stock of each variation', 'simple-product-badges'))
						->set_conditional_logic([
							[
								'field' => 'badge_type',
								'value' => 'type_lowstock'
							]
						]),
					Field::make('text', 'badge_lowstock_low', esc_html__('Quantity of stock to be considered low', 'simple-product-badges'))
						->set_required(true)
						->set_attribute('type', 'number')
						->set_visible_in_rest_api(true)
						->set_conditional_logic([
							[
								'field' => 'badge_type',
								'value' => 'type_lowstock'
							]
						]),
					Field::make(
						'text',
						'badge_lowstock_medium',
						esc_html__('Quantity of stock to be considered High (For modal in product with variations)', 'simple-product-badges')
					)
						->set_required(true)
						->set_attribute('type', 'number')
						->set_visible_in_rest_api(true)
						->set_conditional_logic([
							[
								'field' => 'badge_type',
								'value' => 'type_lowstock'
							]
						]),

					//FOR ALL && SALE && CUSTOM
					Field::make('text', 'badge_text', esc_html__('Display Name:', 'simple-product-badges'))
						->set_required(true)
						->set_visible_in_rest_api(true),
					Field::make('text', 'badge_text_size', esc_html__('Text Size, in px (By default 12px):', 'simple-product-badges'))
						->set_attribute('placeholder', '16')
						->set_attribute('type', 'number')
						->set_visible_in_rest_api(true),
					Field::make('color', 'badge_text_color', esc_html__('Text Color: (By default black)', 'simple-product-badges'))
						->set_visible_in_rest_api(true),
					Field::make('color', 'badge_bg_color', esc_html__('Badge Color: (Click on X, to set transparent)', 'simple-product-badges'))
						->set_visible_in_rest_api(true),
					Field::make('select', 'badge_style', esc_html__('Style Badge (Coming soon)'))
						->add_options([
							'badge_default' => esc_html__('Default Badge', 'simple-product-badges'),
						])
						->set_visible_in_rest_api(true),
					Field::make('select', 'badge_position', esc_html__('Badge Position (More coming soon)'))
						->set_required(true)
						->add_options([
							'position_tl' => esc_html__('Top Left', 'simple-product-badges'),
							'position_tr' => esc_html__('Top Right', 'simple-product-badges')
						])
						->set_visible_in_rest_api(true),

					//TRANSOFRMAR ESTO A UN SELECT
					Field::make('select', 'badge_apply', esc_html__('Apply Badge In:'))
						->set_required(true)
						->add_options([
							'apply_tag' => esc_html__('Product Tags', 'simple-product-badges'),
							'apply_category' => esc_html__('Product Category', 'simple-product-badges'),
							'apply_all' => esc_html__('All Products', 'simple-product-badges'),
						])
						->set_visible_in_rest_api(true),
					Field::make('multiselect', 'apply_tags', esc_html__('Select Tags', 'simple-product-badges'))
						->set_required(true)
						->set_options(function () {
							return $this->get_tags();
						})
						->set_conditional_logic([
							[
								'field' => 'badge_apply',
								'value' => 'apply_tag'
							]
						])
						->set_visible_in_rest_api(true),
					Field::make('multiselect', 'apply_categories', esc_html__('Select categories', 'simple-product-badges'))
						->set_required(true)
						->set_options(function () {
							return $this->get_categories();
						})
						->set_conditional_logic([
							[
								'field' => 'badge_apply',
								'value' => 'apply_category'
							]
						])
						->set_visible_in_rest_api(true)
				]
			);
		return;
	}

	private function get_tags()
	{
		return (array) get_terms(
			[
				'taxonomy' => 'product_tag',
				'parent' => 0,
				'hide_empty' => false,
				'fields' => 'id=>name'
			]
		);
	}

	private function get_categories()
	{
		return (array) get_terms(
			[
				'taxonomy' => 'product_cat',
				'parent' => 0,
				'hide_empty' => false,
				'fields' => 'id=>name'
			]
		);
	}
}
