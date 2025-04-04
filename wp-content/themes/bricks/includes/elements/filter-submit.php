<?php
namespace Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Filter_Submit extends Filter_Element {
	public $name        = 'filter-submit';
	public $icon        = 'ti-mouse';
	public $filter_type = 'apply';
	public $button_type = 'button';

	public function get_label() {
		return esc_html__( 'Filter', 'bricks' ) . ' - ' . esc_html__( 'Submit', 'bricks' ) . ' / ' . esc_html__( 'Reset', 'bricks' );
	}

	public function get_keywords() {
		return [
			'input',
			'form',
			'field',
			'filter',
			'apply',
			'submit',
			'reset',
		];
	}

	public function set_controls() {
		// SORT / FILTER
		$filter_controls = $this->get_filter_controls();

		if ( ! empty( $filter_controls ) ) {
			unset( $filter_controls['filterApplyOn'] );
			unset( $filter_controls['filterNiceName'] );

			$filter_controls['filterButtonType'] = [
				'type'        => 'select',
				'inline'      => true,
				'label'       => esc_html__( 'Action', 'bricks' ),
				'options'     => [
					'apply' => esc_html__( 'Submit', 'bricks' ),
					'reset' => esc_html__( 'Reset', 'bricks' ),
				],
				'placeholder' => esc_html__( 'Submit', 'bricks' ),
				'required'    => [ 'filterQueryId', '!=', '' ],
			];

			// Redirect to a URL with parameters (@since 1.11)
			$filter_controls['redirectTo'] = [
				'type'           => 'text',
				'inline'         => true,
				'label'          => esc_html__( 'Redirect to', 'bricks' ),
				'description'    => esc_html__( 'URL redirect to after applying the filter. Leave it empty if not intended to.', 'bricks' ),
				'hasDynamicData' => false,
				'required'       => [
					[ 'filterQueryId', '!=', '' ],
					[ 'filterButtonType', '!=', 'reset' ],
				],
			];

			// Open in new tab (@since 1.11)
			$filter_controls['newTab'] = [
				'type'     => 'checkbox',
				'inline'   => true,
				'label'    => esc_html__( 'Open in new tab', 'bricks' ),
				'required' => [
					[ 'filterQueryId', '!=', '' ],
					[ 'filterButtonType', '!=', 'reset' ],
					[ 'redirectTo', '!=', '' ],
				],
			];

			// Hide if no active filter (@since 1.11)
			$filter_controls['hideOnNoFilter'] = [
				'type'        => 'checkbox',
				'inline'      => true,
				'label'       => esc_html__( 'Hide if no active filter', 'bricks' ),
				'required'    => [
					[ 'filterQueryId', '!=', '' ],
					[ 'filterButtonType', '=', 'reset' ],
				],
				'description' => sprintf( esc_html__( '%s added to this button if there is no active filter.', 'bricks' ), '.brx-no-active-filter' ),
			];

			$this->controls = array_merge( $this->controls, $filter_controls );
		}

		// BUTTON
		$this->controls['buttonSep'] = [
			'label' => esc_html__( 'Button', 'bricks' ),
			'type'  => 'separator',
		];

		$this->controls['text'] = [
			'label'   => esc_html__( 'Text', 'bricks' ),
			'type'    => 'text',
			'inline'  => true,
			'default' => esc_html__( 'Filter', 'bricks' ),
		];

		$this->controls['size'] = [
			'label'       => esc_html__( 'Size', 'bricks' ),
			'type'        => 'select',
			'options'     => $this->control_options['buttonSizes'],
			'inline'      => true,
			'placeholder' => esc_html__( 'Default', 'bricks' ),
		];

		$this->controls['style'] = [
			'label'       => esc_html__( 'Style', 'bricks' ),
			'type'        => 'select',
			'options'     => $this->control_options['styles'],
			'inline'      => true,
			'default'     => 'primary',
			'placeholder' => esc_html__( 'None', 'bricks' ),
		];

		$this->controls['circle'] = [
			'label' => esc_html__( 'Circle', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['outline'] = [
			'label' => esc_html__( 'Outline', 'bricks' ),
			'type'  => 'checkbox',
		];

		$this->controls['icon'] = [
			'label' => esc_html__( 'Icon', 'bricks' ),
			'type'  => 'icon',
		];

		$this->controls['iconColor'] = [
			'label'    => esc_html__( 'Icon color', 'bricks' ),
			'type'     => 'color',
			'required' => [ 'icon.icon', '!=', '' ],
			'css'      => [
				[
					'selector' => '.icon',
					'property' => 'color',
				],
				[
					'selector' => '.icon',
					'property' => 'fill',
				],
			],
		];

		$this->controls['iconSize'] = [
			'label'    => esc_html__( 'Icon size', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'required' => [ 'icon.icon', '!=', '' ],
			'css'      => [
				[
					'selector' => '.icon',
					'property' => 'font-size',
				],
			],
		];

		$this->controls['gap'] = [
			'label'    => esc_html__( 'Gap', 'bricks' ),
			'type'     => 'number',
			'units'    => true,
			'required' => [ 'icon', '!=', '' ],
			'css'      => [
				[
					'property' => 'gap',
				],
			],
		];

		// Text & Icon position
		$this->controls['direction'] = [
			'label'    => esc_html__( 'Direction', 'bricks' ),
			'type'     => 'direction',
			'inline'   => true,
			'tooltip'  => [
				'content'  => 'flex-direction',
				'position' => 'top-left',
			],
			'css'      => [
				[
					'property' => 'flex-direction',
				],
			],
			'required' => [ 'icon', '!=', '' ],
		];
	}

	private function set_as_filter() {
		$this->input_name  = $this->settings['name'] ?? "form-field-{$this->id}";
		$this->filter_type = $this->settings['filterButtonType'] ?? 'apply';
		$redirect_to       = $this->settings['redirectTo'] ?? '';
		$new_tab           = $this->settings['newTab'] ?? false;
		$filter_settings   = $this->get_common_filter_settings();

		if ( $this->filter_type === 'apply' && ! empty( $redirect_to ) ) {
			$filter_settings['redirectTo'] = esc_url( $redirect_to );
			$filter_settings['newTab']     = $new_tab;
		}

		// Insert filter settings as 'data-brx-filter' attribute
		$this->set_attribute( '_root', 'data-brx-filter', wp_json_encode( $filter_settings ) );
	}

	public function render() {
		$settings          = $this->settings;
		$text              = ! empty( $settings['text'] ) ? $this->render_dynamic_data( $settings['text'] ) : '';
		$icon              = ! empty( $settings['icon'] ) ? self::render_icon( $settings['icon'], [ 'icon' ] ) : '';
		$query_id          = $settings['filterQueryId'] ?? false;
		$hide_on_no_filter = $settings['hideOnNoFilter'] ?? false;

		if ( $this->is_filter_input() ) {
			$this->set_as_filter();
		}

		$this->set_attribute( '_root', 'type', $this->filter_type === 'apply' ? 'submit' : 'reset' );
		$this->set_attribute( '_root', 'name', $this->input_name );

		if ( $icon ) {
			$text .= $icon;
		}

		$this->set_attribute( '_root', 'class', 'bricks-button' );

		if ( $this->filter_type === 'reset' && $hide_on_no_filter && ! bricks_is_builder() && ! bricks_is_builder_call() ) {
			$active_filters = Query_Filters::$active_filters[ $query_id ] ?? [];
			if ( empty( $active_filters ) ) {
				$this->set_attribute( '_root', 'class', 'brx-no-active-filter' ); // Default: style="display: none"
				$this->set_attribute( '_root', 'disabled', 'disabled' ); // Disable the button, user might overwrite brx-no-active-filter style in CSS
			}
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->set_attribute( '_root', 'class', $settings['size'] );
		}

		if ( ! empty( $settings['style'] ) ) {
			// Outline
			if ( isset( $settings['outline'] ) ) {
				$this->set_attribute( '_root', 'class', 'outline' );
				$this->set_attribute( '_root', 'class', "bricks-color-{$settings['style']}" );
			}

			// Fill (= default)
			else {
				$this->set_attribute( '_root', 'class', "bricks-background-{$settings['style']}" );
			}
		}

		// Button circle
		if ( isset( $settings['circle'] ) ) {
			$this->set_attribute( '_root', 'class', 'circle' );
		}

		echo "<button {$this->render_attributes('_root')}>$text</button>";
	}
}
