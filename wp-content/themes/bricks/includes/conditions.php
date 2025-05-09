<?php
namespace Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Conditions {
	public static $groups  = [];
	public static $options = [];

	public function __construct() {
		// Init conditions after WP is loaded
		add_action( 'wp_loaded', [ $this, 'init' ] );
	}

	public function init() {
		$this->set_groups();
		$this->set_options();
	}

	/**
	 * Set condition groups
	 *
	 * @return void
	 *
	 * @since 1.8.4
	 */
	public function set_groups() {
		$groups = [];

		$groups[] = [
			'name'  => 'post',
			'label' => esc_html__( 'Post', 'bricks' ),
		];

		$groups[] = [
			'name'  => 'user',
			'label' => esc_html__( 'User', 'bricks' ),
		];

		$groups[] = [
			'name'  => 'date',
			'label' => esc_html__( 'Date & time', 'bricks' ),
		];

		if ( \Bricks\Woocommerce::is_woocommerce_active() ) {
			$groups[] = [
				'name'  => 'woocommerce',
				'label' => 'WooCommerce',
			];
		}

		$groups[] = [
			'name'  => 'other',
			'label' => esc_html__( 'Other', 'bricks' ),
		];

		// Filter: Add groups
		$groups = apply_filters( 'bricks/conditions/groups', $groups );

		self::$groups = $groups;
	}

	/**
	 * Set condition options
	 *
	 * @return void
	 *
	 * @since 1.8.4
	 */
	public function set_options() {
		/**
		 * Return: No need to get condition controls if not in builder
		 *
		 * Results in better performance as we no longer query get_users, etc.
		 *
		 * @since 1.11.1
		 */
		if ( ! bricks_is_builder() ) {
			return;
		}

		// OPTIONS
		$math_options = [
			'==' => '==',
			'!=' => '!=',
			'>=' => '>=',
			'<=' => '<=',
			'>'  => '>',
			'<'  => '<',
		];

		$is_not_options = [
			'==' => esc_html__( 'is', 'bricks' ),
			'!=' => esc_html__( 'is not', 'bricks' ),
		];

		// post_author: 'id' => 'display_name' of all users with 'edit_posts' capability
		$authors = get_users(
			[
				'fields'     => [ 'ID', 'display_name' ],
				'orderby'    => 'display_name',
				'capability' => 'edit_posts', // @since 1.11.1
			]
		);

		// Get product categories and tags for WooCommerce conditions (@since 1.11.1)
		if ( \Bricks\Woocommerce::is_woocommerce_active() ) {
			// Category options
			$product_categories = get_terms(
				[
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
				]
			);

			$product_category_options = [];
			foreach ( $product_categories as $category ) {
				$product_category_options[ $category->term_id ] = $category->name;
			}

			// Tag options
			$product_tags = get_terms(
				[
					'taxonomy'   => 'product_tag',
					'hide_empty' => false,
				]
			);

			$product_tag_options = [];
			foreach ( $product_tags as $tag ) {
				$product_tag_options[ $tag->term_id ] = $tag->name;
			}
		}

		// Author options
		$author_options = [];
		foreach ( $authors as $author ) {
			$author_options[ $author->ID ] = $author->display_name;
		}

		$options = [];

		// POST
		$options[] = [
			'key'     => 'post_id',
			'group'   => 'post',
			'label'   => esc_html__( 'Post ID', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type' => 'text',
			],
		];

		$options[] = [
			'key'     => 'post_title',
			'group'   => 'post',
			'label'   => esc_html__( 'Post title', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => [
					'=='           => esc_html__( 'is', 'bricks' ),
					'!='           => esc_html__( 'is not', 'bricks' ),
					'contains'     => esc_html__( 'contains', 'bricks' ),
					'contains_not' => esc_html__( 'does not contain', 'bricks' ),
				],
				'placeholder' => esc_html__( 'is', 'bricks' ),
			],
			'value'   => [
				'type' => 'text',
			],
		];

		$options[] = [
			'key'     => 'post_parent',
			'group'   => 'post',
			'label'   => esc_html__( 'Post parent', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type'        => 'text',
				'placeholder' => 0,
			],
		];

		$options[] = [
			'key'     => 'post_status',
			'group'   => 'post',
			'label'   => esc_html__( 'Post status', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $is_not_options,
				'placeholder' => esc_html__( 'is', 'bricks' ),
			],
			'value'   => [
				'type'        => 'select',
				'options'     => get_post_statuses(),
				'multiple'    => true,
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
		];

		$options[] = [
			'key'     => 'post_author',
			'group'   => 'post',
			'label'   => esc_html__( 'Post author', 'bricks' ),
			'compare' => [
				'type'    => 'select',
				'options' => $is_not_options,
			],
			'value'   => [
				'type'    => 'select',
				'options' => $author_options,
			],
		];

		$options[] = [
			'key'     => 'post_date',
			'group'   => 'post',
			'label'   => esc_html__( 'Post date', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type'       => 'datepicker',
				'enableTime' => false,
			],
		];

		// set OR not set
		$options[] = [
			'key'     => 'featured_image',
			'group'   => 'post',
			'label'   => esc_html__( 'Featured image', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $is_not_options,
				'placeholder' => esc_html__( 'Select', 'bricks' ),
				// 'required' => ['key', '!=', 'featured_image'],
			],
			'value'   => [
				'type'        => 'select',
				'options'     => [
					'1' => esc_html__( 'set', 'bricks' ),
					'0' => esc_html__( 'not set', 'bricks' ),
				],
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
		];

		// USER
		$options[] = [
			'key'     => 'user_logged_in',
			'group'   => 'user',
			'label'   => esc_html__( 'User login', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $is_not_options,
				'placeholder' => esc_html__( 'is', 'bricks' ),
			],
			'value'   => [
				'type'        => 'select',
				'options'     => [
					1 => esc_html__( 'Logged in', 'bricks' ),
					0 => esc_html__( 'Logged out', 'bricks' ),
				],
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
		];

		$options[] = [
			'key'     => 'user_id',
			'group'   => 'user',
			'label'   => esc_html__( 'User ID', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type'        => 'text',
				'placeholder' => '',
			],
		];

		$options[] = [
			'key'     => 'user_registered',
			'group'   => 'user',
			'label'   => esc_html__( 'User registered', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => [
					'<' => esc_html__( 'after', 'bricks' ),
					'>' => esc_html__( 'before', 'bricks' ),
				],
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
			'value'   => [
				'type'        => 'datepicker',
				'enableTime'  => false,
				'placeholder' => date( 'Y-m-d' ),
			],
		];

		$options[] = [
			'key'     => 'user_role',
			'group'   => 'user',
			'label'   => esc_html__( 'User role', 'bricks' ),
			'compare' => [
				'type'    => 'select',
				'options' => $is_not_options,
			],
			'value'   => [
				'type'        => 'select',
				'options'     => wp_roles()->get_names(),
				'multiple'    => true,
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
		];

		// DATE
		$options[] = [
			'key'     => 'weekday',
			'group'   => 'date',
			'label'   => esc_html__( 'Weekday', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type'        => 'select',
				'options'     => [
					1 => esc_html__( 'Monday', 'bricks' ),
					2 => esc_html__( 'Tuesday', 'bricks' ),
					3 => esc_html__( 'Wednesday', 'bricks' ),
					4 => esc_html__( 'Thursday', 'bricks' ),
					5 => esc_html__( 'Friday', 'bricks' ),
					6 => esc_html__( 'Saturday', 'bricks' ),
					7 => esc_html__( 'Sunday', 'bricks' ),
				],
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
		];

		/**
		 * Note about WP time zone being used
		 *
		 * Example: Timezone: UTC+2:00
		 *
		 * @since 1.9.3
		 */
		$timezone_description = esc_html__( 'Timezone', 'bricks' ) . ': UTC' . wp_timezone_string() . ' (<a href="' . admin_url( 'options-general.php' ) . '" target="_blank">' . esc_html__( 'Edit', 'bricks' ) . '</a>)';

		$options[] = [
			'key'     => 'date',
			'group'   => 'date',
			'label'   => esc_html__( 'Date', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type'        => 'datepicker',
				'enableTime'  => false,
				'placeholder' => date( 'Y-m-d', current_time( 'timestamp' ) ),
				'description' => $timezone_description,
			],
		];

		$options[] = [
			'key'     => 'time',
			'group'   => 'date',
			'label'   => esc_html__( 'Time', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type'        => 'text',
				'placeholder' => date( 'H:i a', current_time( 'timestamp' ) ),
				'description' => $timezone_description,
			],
		];

		$options[] = [
			'key'     => 'datetime',
			'group'   => 'date',
			'label'   => esc_html__( 'Datetime', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $math_options,
				'placeholder' => '==',
			],
			'value'   => [
				'type'        => 'datepicker',
				'enableTime'  => true,
				'placeholder' => date( 'Y-m-d h:i a', current_time( 'timestamp' ) ),
				'description' => $timezone_description,
			],
		];

		// WOOCOMMERCE (@since 1.11.1)
		if ( \Bricks\Woocommerce::is_woocommerce_active() ) {
			// Product price
			$options[] = [
				'key'     => 'woo_product_type',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product type', 'bricks' ),
				'compare' => [
					'type'    => 'select',
					'options' => $is_not_options,
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'simple'   => esc_html__( 'Simple', 'bricks' ),
						'grouped'  => esc_html__( 'Grouped', 'bricks' ),
						'external' => esc_html__( 'External', 'bricks' ) . '/' . esc_html__( 'Affiliate', 'bricks' ),
						'variable' => esc_html__( 'Variable', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product on sale
			$options[] = [
				'key'     => 'woo_product_sale',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product sale status', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'On sale', 'bricks' ),
						'0' => esc_html__( 'Not on sale', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product is new
			$options[] = [
				'key'     => 'woo_product_new',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product new status', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'New', 'bricks' ),
						'0' => esc_html__( 'Not new', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product stock status
			$options[] = [
				'key'     => 'woo_product_stock_status',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product stock status', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'instock'     => esc_html__( 'In stock', 'bricks' ),
						'outofstock'  => esc_html__( 'Out of stock', 'bricks' ),
						'onbackorder' => esc_html__( 'On backorder', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product stock quantity
			$options[] = [
				'key'     => 'woo_product_stock_quantity',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product stock quantity', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $math_options,
					'placeholder' => '==',
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => '',
				],
			];

			// Product stock management
			$options[] = [
				'key'     => 'woo_product_stock_management',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product stock management', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'Enabled', 'bricks' ),
						'0' => esc_html__( 'Disabled', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product is sold individually
			$options[] = [
				'key'     => 'woo_product_sold_individually',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product sold individually', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'Enabled', 'bricks' ),
						'0' => esc_html__( 'Disabled', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product was purchased by current user
			$options[] = [
				'key'     => 'woo_product_purchased_by_user',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product purchased by user', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'True', 'bricks' ),
						'0' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product is featured
			$options[] = [
				'key'     => 'woo_product_featured',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product featured', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => [
						'1' => esc_html__( 'True', 'bricks' ),
						'0' => esc_html__( 'False', 'bricks' ),
					],
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product rating
			$options[] = [
				'key'     => 'woo_product_rating',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product rating', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $math_options,
					'placeholder' => '==',
				],
				'value'   => [
					'type'        => 'text',
					'placeholder' => '',
				],
			];

			// Product category
			$options[] = [
				'key'     => 'woo_product_category',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product category', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => $product_category_options,
					'multiple'    => true,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];

			// Product tag
			$options[] = [
				'key'     => 'woo_product_tag',
				'group'   => 'woocommerce',
				'label'   => esc_html__( 'Product tag', 'bricks' ),
				'compare' => [
					'type'        => 'select',
					'options'     => $is_not_options,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
				'value'   => [
					'type'        => 'select',
					'options'     => $product_tag_options,
					'multiple'    => true,
					'placeholder' => esc_html__( 'Select', 'bricks' ),
				],
			];
		}

		// OTHER
		$options[] = [
			'key'     => 'dynamic_data',
			'group'   => 'other',
			'label'   => esc_html__( 'Dynamic data', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => array_merge(
					[
						'contains'     => esc_html__( 'contains', 'bricks' ),
						'contains_not' => esc_html__( 'does not contain', 'bricks' ),
						'empty'        => esc_html__( 'is empty', 'bricks' ), // @since 1.10
						'empty_not'    => esc_html__( 'is not empty', 'bricks' ), // @since 1.10
					],
					$math_options
				),
				'placeholder' => '==',
			],
			'value'   => [
				'type' => 'text',
			],
		];

		$options[] = [
			'key'     => 'browser',
			'group'   => 'other',
			'label'   => esc_html__( 'Browser', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $is_not_options,
				'placeholder' => esc_html__( 'is', 'bricks' ),
			],
			'value'   => [
				'type'        => 'select',
				'options'     => [
					'chrome'  => 'Chrome',
					'firefox' => 'Firefox',
					'safari'  => 'Safari',
					'edge'    => 'Edge',
					'opera'   => 'Opera',
					'msie'    => 'Internet Explorer'
				],
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
		];

		$options[] = [
			'key'     => 'operating_system',
			'group'   => 'other',
			'label'   => esc_html__( 'Operating system', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => $is_not_options,
				'placeholder' => esc_html__( 'is', 'bricks' ),
			],
			'value'   => [
				'type'        => 'select',
				'options'     => [
					'windows'    => 'Windows',
					'mac'        => 'macOS',
					'linux'      => 'Linux',
					'ubuntu'     => 'Ubuntu',
					'iphone'     => 'iPhone',
					'ipad'       => 'iPad',
					'ipod'       => 'iPod',
					'android'    => 'Android',
					'blackberry' => 'Blackberry',
					'webos'      => 'Mobile (webOS)',
				],
				'placeholder' => esc_html__( 'Select', 'bricks' ),
			],
		];

		// Current URL incl. params
		$options[] = [
			'key'     => 'current_url',
			'group'   => 'other',
			'label'   => esc_html__( 'Current URL', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => [
					'=='           => esc_html__( 'is', 'bricks' ),
					'!='           => esc_html__( 'is not', 'bricks' ),
					'contains'     => esc_html__( 'contains', 'bricks' ),
					'contains_not' => esc_html__( 'does not contain', 'bricks' ),
				],
				'placeholder' => esc_html__( 'is', 'bricks' ),
			],
			'value'   => [
				'type' => 'text',
			],
		];

		$options[] = [
			'key'     => 'referer',
			'group'   => 'other',
			'label'   => esc_html__( 'Referrer URL', 'bricks' ),
			'compare' => [
				'type'        => 'select',
				'options'     => [
					'=='           => esc_html__( 'is', 'bricks' ),
					'!='           => esc_html__( 'is not', 'bricks' ),
					'contains'     => esc_html__( 'contains', 'bricks' ),
					'contains_not' => esc_html__( 'does not contain', 'bricks' ),
				],
				'placeholder' => esc_html__( 'is', 'bricks' ),
			],
			'value'   => [
				'type' => 'text',
			],
		];

		// Filter: Add options
		$options = apply_filters( 'bricks/conditions/options', $options );

		self::$options = $options;
	}

	/**
	 * Return all controls (builder)
	 *
	 * @return array
	 */
	public static function get_controls_data() {
		// Return: Prevent querying database outside of builder for condition controls
		if ( ! bricks_is_builder() ) {
			return;
		}

		// STEP: Populate controls for builder
		$controls = [];

		// Loop over groups
		foreach ( self::$groups as $group ) {
			// Skip if $group has no name or label
			if ( ! isset( $group['name'] ) || empty( $group['name'] ) || ! isset( $group['label'] ) || empty( $group['label'] ) ) {
				continue;
			}

			// Add group title - backwards compatibility. e.g. $controls['postGroupTitle']
			$controls[ $group['name'] . 'GroupTitle' ] = [
				'label' => $group['label'],
			];

			// Use array_filter to get controls for current group and must have a key
			$group_controls = array_filter(
				self::$options,
				function( $option ) use ( $group ) {
					return $option['group'] === $group['name'] && ! empty( $option['key'] );
				}
			);

			// Add controls for current group
			foreach ( $group_controls as $control ) {
				$controls[ $control['key'] ] = $control;
			}
		}

		return $controls;
	}

	/**
	 * Transform dynamic data tag
	 *
	 * Add ':value' to ACF true_false tag to get unlocalized value.
	 *
	 * @since 1.9.9
	 */
	public static function maybe_transform_dynamic_tag( $dynamic_tag ) {
		// Return: Not a string
		if ( ! is_string( $dynamic_tag ) ) {
			return $dynamic_tag;
		}

		// Add ':value' filter to ACF true_false tag to avoid localisation (in element conditions)
		if (
			strpos( $dynamic_tag, '{acf_' ) === 0 &&
			strpos( $dynamic_tag, ':value}' ) === false &&
			$dynamic_tag !== '{acf_get_row_layout}'
		) {
			// ACF: Get field type from dynamic data tag
			$acf_provider = Integrations\Dynamic_Data\Providers::get_registered_provider( 'acf' );

			if ( $acf_provider ) {
				$tags       = $acf_provider->get_tags();
				$my_key     = str_replace( [ '{','}' ], '', $dynamic_tag );
				$field_type = $tags[ $my_key ]['field']['type'] ?? false;

				// Add ':value' to true_false DD tag to get unlocalized value
				if ( $field_type === 'true_false' ) {
					$dynamic_tag = str_replace( '}', ':value}', $dynamic_tag );
				}
			}
		}

		return $dynamic_tag;
	}

	/**
	 * Convert boolean-like strings to actual booleans for proper true/false comparisions
	 *
	 * @since 1.7
	 */
	public static function boolean_converter( &$value, &$required ) {
		$possible_boolean = [ 'True', 'False', 'true', 'false', true, false, '1', '0', '' ];

		if ( in_array( $required, $possible_boolean, true ) && in_array( $value, $possible_boolean, true ) ) {
			$required = filter_var( $required, FILTER_VALIDATE_BOOLEAN );
			$value    = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		}
	}

	/**
	 * Check element conditions
	 *
	 * At least one condition set must be fulfilled for the element to be rendered.
	 *
	 * Inside a condition all items must evaluate to true.
	 *
	 * @return boolean true = render element | false = don't render element
	 *
	 * @since 1.5.4
	 */
	public static function check( $conditions, $instance ) {
		// Return: Always render element in builder
		if ( bricks_is_builder() || bricks_is_builder_call() ) {
			return true;
		}

		$user_agent     = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$post_id        = $instance->post_id;
		$post           = get_post( $post_id );
		$user           = wp_get_current_user();
		$render_element = false;
		$product        = \Bricks\Woocommerce::is_woocommerce_active() ? wc_get_product( $post_id ) : false;

		// Loop over condition sets (logic between sets: OR)
		foreach ( $conditions as $condition_set ) {
			$render_set = true;

			// Loop over conditions inside a set (logic inside a set: AND)
			foreach ( $condition_set as $condition ) {
				// Skip further checks in condition set if we already have a false condition inside this set
				if ( $render_set === false ) {
					continue;
				}

				$key     = isset( $condition['key'] ) ? $condition['key'] : false;
				$compare = isset( $condition['compare'] ) ? $condition['compare'] : '==';
				// NOTE: Might need to use maybe_transform_dynamic_tag() here (@since 1.9.9)
				$required = isset( $condition['value'] ) ? $instance->render_dynamic_data( $condition['value'] ) : false;

				$value = false;

				// STEP: Get current value
				switch ( $key ) {
					// POST
					case 'post_id':
						$value = $post_id;
						break;

					case 'post_title':
						$value = $post->post_title ?? '';
						break;

					case 'post_parent':
						$value = $post->post_parent ?? 0;
						break;

					case 'post_status':
						$value = $post->post_status ?? '';
						break;

					case 'post_author':
						$value = $post->post_author ?? 0;
						break;

					case 'post_date':
						$value = isset( $post->post_date ) ? date( 'Y-m-d', strtotime( $post->post_date ) ) : false; // 2022-12-31
						break;

					case 'featured_image':
						$value = has_post_thumbnail( $post_id );
						break;

					// USER
					case 'user_logged_in':
						$value = is_user_logged_in();
						break;

					case 'user_id':
						$value = is_a( $user, 'WP_User' ) ? $user->ID : 0;
						break;

					case 'user_registered':
						$value = date( 'Y-m-d', strtotime( $user->user_registered ) );

						if ( ! $required ) {
							$required = date( 'Y-m-d' );
						}
						break;

					case 'user_role':
						$value = is_a( $user, 'WP_User' ) ? $user->roles : [];
						break;

					// DATE
					case 'weekday':
						$value = date( 'N' ); // 1 = monday, 2 = tuesday, etc.
						break;

					// DATE, TIME, DATETIME
					case 'date':
					case 'time':
					case 'datetime':
						// Use website current time
						$value = current_time( 'timestamp' );

						if ( $required ) {
							// Convert user input to timestamp for comparison
							$required = strtotime( $required );
						} else {
							// No user input, use current time
							$required = $value;
						}

						if ( $key === 'date' ) {
							// Just get the date part and compare
							$value    = date( 'Y-m-d', $value );
							$required = date( 'Y-m-d', $required );
						}

						elseif ( $key === 'time' ) {
							// Just get the time part and compare
							$value    = date( 'H:i', $value );
							$required = date( 'H:i', $required );
						}

						break;

						// WOOCOMMERCE
					case 'woo_product_type':
						$value = $product ? $product->get_type() : '';
						break;

					case 'woo_product_sale':
						$value = $product ? $product->is_on_sale() : 0;
						break;

					case 'woo_product_stock_status':
						$value = $product ? $product->get_stock_status() : '';
						break;

					case 'woo_product_stock_quantity':
						$value = $product ? $product->get_stock_quantity() : 0;
						break;

					case 'woo_product_stock_management':
						$value = $product ? $product->managing_stock() : 0;
						break;

					case 'woo_product_sold_individually':
						$value = $product ? $product->is_sold_individually() : 0;
						break;

					case 'woo_product_purchased_by_user':
						$value = $product ? wc_customer_bought_product( $user->user_email, $user->ID, $product->get_id() ) : 0;
						break;

					case 'woo_product_featured':
						$value = $product ? $product->is_featured() : 0;
						break;

					case 'woo_product_new':
						$newness_in_days = Database::get_setting( 'woocommerceBadgeNew', false );
						$value           = 0;

						if ( $newness_in_days ) {
							$newness_timestamp = time() - ( 60 * 60 * 24 * $newness_in_days );
							$created           = $product ? strtotime( $product->get_date_created() ) : 0;
							$value             = $newness_timestamp < $created;
						}

						break;

					case 'woo_product_category':
						$value = $product ? $product->get_category_ids() : [];
						break;

					case 'woo_product_tag':
						$value = $product ? $product->get_tag_ids() : [];
						break;

					case 'woo_product_rating':
						$value = $product ? $product->get_average_rating() : 0;
						break;

						// OTHER
					case 'dynamic_data':
						$dynamic_data_tag = $condition['dynamic_data'] ?? false;
						if ( $dynamic_data_tag ) {
							// Transform dynamic data tag - Add ':value' to ACF true_false tag to get unlocalized value
							$dynamic_data_tag = self::maybe_transform_dynamic_tag( $dynamic_data_tag );
							// Render dynamic data and assign to value
							$value = $instance->render_dynamic_data( $dynamic_data_tag );
						}

						// Re-evaluate value field again because user might use the condition in value field
						$condition_value = $condition['value'] ?? false;
						if ( $condition_value ) {
							// Transform dynamic data tag: Add ':value' filter to ACF true_false tag to get unlocalized value
							$condition_value = self::maybe_transform_dynamic_tag( $condition_value );
							// Render dynamic data and assign to required
							$required = $instance->render_dynamic_data( $condition_value );
						}

						break;

					case 'browser':
						// Logic moved to Helpers::user_agent_to_browser()
						$value = Helpers::user_agent_to_browser( $user_agent );
						break;

					case 'operating_system':
						// Logic moved to Helpers::user_agent_to_os()
						$value = Helpers::user_agent_to_os( $user_agent );
						break;

					case 'referer':
						$value = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
						break;

					case 'current_url':
						global $wp;
						// Retrieve all GET query parameters and sanitize them
						$get_query_params = isset( $_GET ) ? wp_unslash( $_GET ) : [];
						$value            = home_url( add_query_arg( $get_query_params, $wp->request ? trailingslashit( $wp->request ) : '' ) );
						$value            = esc_url_raw( $value );
						break;
				}

				// COMPARISON OPERANDS
				switch ( $compare ) {
					case '==':
						// Convert boolean-like strings to actual booleans (@since 1.10)
						self::boolean_converter( $value, $required );
						// user_role (one of the user roles must be in requested roles array)
						if ( is_array( $value ) && is_array( $required ) ) {
							$render_set = count( array_intersect( $value, $required ) ) > 0;
						}

						// Handle array (e.g. post_status) and string value
						elseif ( is_array( $required ) ) {
							$render_set = in_array( $value, $required );
						} else {
							$render_set = $value == $required;
						}
						break;

					case '!=':
						// Convert boolean-like strings to actual booleans (@since 1.10)
						self::boolean_converter( $value, $required );
						// User role (one of the user roles must be in requested roles array) (#862jj0afz)
						if ( is_array( $value ) && is_array( $required ) ) {
							$render_set = count( array_intersect( $value, $required ) ) == 0;
						}

						// Handle array (e.g. post_status) and string value
						elseif ( is_array( $required ) ) {
							$render_set = ! in_array( $value, $required );
						} else {
							$render_set = $value != $required;
						}
						break;

					case '>=':
						$render_set = $value >= $required;
						break;

					case '<=':
						$render_set = $value <= $required;
						break;

					case '>':
						$render_set = $value > $required;
						break;

					case '<':
						$render_set = $value < $required;
						break;

					// post_title
					case 'contains':
						// Check if string contains keyword
						if ( $value && gettype( $value ) === 'string' && gettype( $required ) === 'string' ) {
							$render_set = strpos( $value, $required ) !== false;
						} else {
							$render_set = false;
						}
						break;

					// post_title
					case 'contains_not':
						// Check if string does not contain keyword
						if ( $value && gettype( $value ) === 'string' && gettype( $required ) === 'string' ) {
							$render_set = strpos( $value, $required ) === false;
						} else {
							$render_set = false;
						}
						break;

					// dynamic_data (@since 1.10)
					case 'empty':
						$render_set = (string) $value === '';
						break;

					// dynamic_data (@since 1.10)
					case 'empty_not':
						$render_set = (string) $value !== '';
						break;
				}

				/**
				 * Allow third party plugins to modify the boolean value of a condition
				 *
				 * @since 1.8.4
				 */
				$render_set = apply_filters( 'bricks/conditions/result', $render_set, $key, $condition );
			}

			// All items inside condition are fulfilled: Render element
			if ( $render_set ) {
				$render_element = true;
			}
		}

		return $render_element;
	}
}
