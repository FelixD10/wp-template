<?php
namespace Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Social_Icons extends Element {
	public $category     = 'general';
	public $name         = 'social-icons';
	public $icon         = 'ti-twitter';
	public $css_selector = 'li.has-link a, li.no-link';

	public function get_label() {
		return esc_html__( 'Icon List', 'bricks' );
	}

	public function set_controls() {
		// Overwrite base.php root selector for all height controls
		$this->controls['_width']['css'][0]['selector']    = $this->css_selector;
		$this->controls['_widthMin']['css'][0]['selector'] = $this->css_selector;
		$this->controls['_widthMax']['css'][0]['selector'] = $this->css_selector;

		$this->controls['_margin']['css'][0]['selector']        = 'li';
		$this->controls['_background']['css'][0]['selector']    = 'li';
		$this->controls['_border']['css'][0]['selector']        = 'li';
		$this->controls['_boxShadow']['css'][0]['selector']     = 'li';
		$this->controls['_gradient']['css'][0]['selector']      = 'li';
		$this->controls['_cssTransition']['css'][0]['selector'] = 'li';
		$this->controls['_transform']['css'][0]['selector']     = 'li';

		$this->controls['icons'] = [
			'type'          => 'repeater',
			'label'         => esc_html__( 'Icons', 'bricks' ),
			'placeholder'   => esc_html__( 'Icon', 'bricks' ),
			'titleProperty' => 'label',
			'fields'        => [
				'icon'       => [
					'type'    => 'icon',
					'label'   => esc_html__( 'Icon', 'bricks' ),
					'inline'  => true,
					'default' => [
						'library' => 'ionicons',
						'icon'    => 'ion-logo-twitter',
					],
				],

				'iconColor'  => [
					'type'     => 'color',
					'label'    => esc_html__( 'Icon', 'bricks' ) . ': ' . esc_html__( 'Color', 'bricks' ),
					'css'      => [
						[
							'property' => 'color',
							'selector' => '.icon',
						],
					],
					'required' => [ 'icon.icon', '!=', '' ],
				],

				'iconSize'   => [
					'type'     => 'number',
					'units'    => true,
					'label'    => esc_html__( 'Icon', 'bricks' ) . ': ' . esc_html__( 'Size', 'bricks' ),
					'css'      => [
						[
							'property' => 'font-size',
							'selector' => '.icon',
						],
					],
					'required' => [ 'icon.icon', '!=', '' ],
				],

				'label'      => [
					'type'   => 'text',
					'label'  => esc_html__( 'Label', 'bricks' ),
					'inline' => true,
				],

				'labelSize'  => [
					'type'     => 'number',
					'units'    => true,
					'label'    => esc_html__( 'Label', 'bricks' ) . ': ' . esc_html__( 'Size', 'bricks' ),
					'css'      => [
						[
							'property' => 'font-size',
							'selector' => 'span',
						],
					],
					'required' => [ 'icon.icon', '!=', '' ],
				],

				'color'      => [
					'type'  => 'color',
					'label' => esc_html__( 'Color', 'bricks' ),
					'css'   => [
						[
							'selector' => '&.has-link a',
							'property' => 'color',
						],
						[
							'selector' => '&.no-link',
							'property' => 'color',
						],
					],
				],

				'background' => [
					'type'  => 'color',
					'label' => esc_html__( 'Background', 'bricks' ),
					'css'   => [
						[
							'property' => 'background-color',
						],
					],
				],

				'link'       => [
					'label' => esc_html__( 'Link', 'bricks' ),
					'type'  => 'link',
				],
			],
			'default'       => [
				[
					'label'      => 'X',
					'icon'       => [
						'library' => 'fontawesomeBrands',
						'icon'    => 'fab fa-x-twitter',
					],
					'background' => [
						'hex' => '#4cc2ff',
					],
				],

				[
					'label'      => 'Facebook',
					'icon'       => [
						'library' => 'fontawesomeBrands',
						'icon'    => 'fab fa-facebook-square',
					],
					'background' => [
						'hex' => '#3b5998',
					],
				],

				[
					'label'      => 'Instagram',
					'icon'       => [
						'library' => 'fontawesomeBrands',
						'icon'    => 'fab fa-instagram',
					],
					'background' => [
						'hex' => '#4E433C',
					],
				],
			],
		];

		$this->controls['iconColor'] = [
			'type'     => 'color',
			'label'    => esc_html__( 'Icon', 'bricks' ) . ': ' . esc_html__( 'Color', 'bricks' ),
			'css'      => [
				[
					'property' => 'color',
					'selector' => '.icon',
				],
			],
			'required' => [ 'icons', '!=', '' ],
		];

		$this->controls['iconSize'] = [
			'type'     => 'number',
			'units'    => true,
			'label'    => esc_html__( 'Icon', 'bricks' ) . ': ' . esc_html__( 'Size', 'bricks' ),
			'css'      => [
				[
					'property' => 'font-size',
					'selector' => '.icon',
				],
				[
					'property' => 'height',
					'selector' => 'svg',
				],
				[
					'property' => 'width',
					'selector' => 'svg',
					'value'    => 'auto',
				],
			],
			'required' => [ 'icons', '!=', '' ],
		];

		// Alignment

		$this->controls['alignmentSeparator'] = [
			'label' => esc_html__( 'Alignment', 'bricks' ),
			'type'  => 'separator',
		];

		$this->controls['direction'] = [
			'label'  => esc_html__( 'Direction', 'bricks' ),
			'title'  => 'flex-direction',
			'type'   => 'direction',
			'inline' => true,
			'css'    => [
				[
					'property' => 'flex-direction',
					'selector' => '',
				],
			],
		];

		$this->controls['alignIcons'] = [
			'label'   => esc_html__( 'Align items', 'bricks' ),
			'type'    => 'align-items',
			'exclude' => 'stretch',
			'css'     => [
				[
					'property' => 'align-items',
				],
			],
			'inline'  => true,
		];

		$this->controls['justifyIcons'] = [
			'label'   => esc_html__( 'Justify content', 'bricks' ),
			'type'    => 'justify-content',
			'exclude' => 'space',
			'css'     => [
				[
					'property' => 'justify-content',
				],
			],
			'inline'  => true,
		];

		$this->controls['gap'] = [
			'label'       => esc_html__( 'Spacing', 'bricks' ) . ' (' . esc_html__( 'Items', 'bricks' ) . ')',
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'selector' => '',
					'property' => 'gap',
				],
			],
			'placeholder' => 0,
		];

		$this->controls['gapItem'] = [
			'label'       => esc_html__( 'Spacing', 'bricks' ) . ' (' . esc_html__( 'Item', 'bricks' ) . ')',
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'gap',
				],
			],
			'placeholder' => '5px',
		];

		/**
		 * Defaults
		 */

		$this->controls['_padding']['default'] = [
			'top'    => 15,
			'right'  => 15,
			'bottom' => 15,
			'left'   => 15,
		];

		$this->controls['_typography']['default'] = [
			'color' => [
				'hex' => '#ffffff',
			],
		];
	}

	public function render() {
		$settings = $this->settings;
		$icons    = ! empty( $settings['icons'] ) ? $settings['icons'] : false;

		if ( ! $icons ) {
			return $this->render_element_placeholder( [ 'title' => esc_html__( 'No social icon added.', 'bricks' ) ] );
		}

		$output = "<ul {$this->render_attributes( '_root' )}>";

		foreach ( $icons as $index => $icon ) {
			$icon_html  = ! empty( $icon['icon'] ) ? self::render_icon( $icon['icon'], [ 'icon' ] ) : false;
			$icon_link  = ! empty( $icon['link'] ) ? $icon['link'] : false;
			$icon_label = isset( $icon['label'] ) ? $this->render_dynamic_data( $icon['label'] ) : false;

			$this->set_attribute( "li-{$index}", 'class', 'repeater-item' );
			$this->set_attribute( "li-{$index}", 'class', $icon_link ? 'has-link' : 'no-link' );

			$output .= "<li {$this->render_attributes( "li-{$index}" )}>";

			if ( $icon_link ) {
				$this->set_link_attributes( "a-{$index}", $icon['link'] );

				$output .= "<a {$this->render_attributes( "a-{$index}" )}>";
			}

			if ( $icon_html ) {
				$output .= $icon_html;
			}

			if ( $icon_label !== false ) {
				$output .= "<span>{$icon_label}</span>";
			}

			if ( $icon_link ) {
				$output .= '</a>';
			}

			$output .= '</li>';
		}

		$output .= '</ul>';

		echo $output;
	}
}
