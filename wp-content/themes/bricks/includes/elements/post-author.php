<?php
namespace Bricks;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Post_Author extends Element {
	public $category = 'single';
	public $name     = 'post-author';
	public $icon     = 'ti-user';

	public function get_label() {
		return esc_html__( 'Author', 'bricks' );
	}

	public function set_control_groups() {
		$this->control_groups['avatar'] = [
			'title' => esc_html__( 'Avatar', 'bricks' ),
		];

		$this->control_groups['name'] = [
			'title' => esc_html__( 'Name', 'bricks' ),
		];

		$this->control_groups['bio'] = [
			'title' => esc_html__( 'Bio', 'bricks' ),
		];

		$this->control_groups['posts'] = [
			'title' => esc_html__( 'posts', 'bricks' ),
		];
	}

	public function set_controls() {

		// Group: avatar

		$this->controls['avatar'] = [
			'group'   => 'avatar',
			'label'   => esc_html__( 'Show avatar', 'bricks' ),
			'type'    => 'checkbox',
			'default' => true,
		];

		$this->controls['avatarSize'] = [
			'group'       => 'avatar',
			'label'       => esc_html__( 'Avatar size', 'bricks' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => [
				[
					'property' => 'height',
					'selector' => '.avatar',
				],
				[
					'property' => 'width',
					'selector' => '.avatar',
				],
			],
			'placeholder' => 60,
			'required'    => [ 'avatar', '!=', '' ],
		];

		$this->controls['avatarPosition'] = [
			'group'       => 'avatar',
			'label'       => esc_html__( 'Avatar position', 'bricks' ),
			'type'        => 'select',
			'options'     => [
				'top'    => esc_html__( 'Top', 'bricks' ),
				'right'  => esc_html__( 'Right', 'bricks' ),
				'bottom' => esc_html__( 'Bottom', 'bricks' ),
				'left'   => esc_html__( 'Left', 'bricks' ),
			],
			'inline'      => true,
			'placeholder' => esc_html__( 'Left', 'bricks' ),
			'required'    => [ 'avatar', '!=', '' ],
		];

		$this->controls['avatarBorder'] = [
			'group'    => 'avatar',
			'label'    => esc_html__( 'Avatar border', 'bricks' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.avatar',
				],
			],
			'required' => [ 'avatar', '!=', '' ],
		];

		$this->controls['avatarBoxShadow'] = [
			'group'    => 'avatar',
			'label'    => esc_html__( 'Avatar box shadow', 'bricks' ),
			'type'     => 'box-shadow',
			'css'      => [
				[
					'property' => 'box-shadow',
					'selector' => '.avatar',
				],
			],
			'required' => [ 'avatar', '!=', '' ],
		];

		// Group: name

		$this->controls['name'] = [
			'group'   => 'name',
			'label'   => esc_html__( 'Show name', 'bricks' ),
			'type'    => 'checkbox',
			'default' => true,
		];

		$this->controls['website'] = [
			'group'    => 'name',
			'label'    => esc_html__( 'Link to website', 'bricks' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => [ 'name', '=', true ],
		];

		$this->controls['nameTypography'] = [
			'group'    => 'name',
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.author-name',
				],
			],
			'required' => [ 'name', '=', true ],
		];

		// Author name tag (@since 1.11.1)
		$this->controls['nameTag'] = [
			'group'          => 'name',
			'label'          => esc_html__( 'HTML tag', 'bricks' ),
			'type'           => 'text',
			'inline'         => true,
			'hasDynamicData' => false,
			'placeholder'    => 'h2',
			'required'       => [ 'name', '=', true ],
		];

		// Group: bio

		$this->controls['bio'] = [
			'group'   => 'bio',
			'label'   => esc_html__( 'Show bio', 'bricks' ),
			'type'    => 'checkbox',
			'default' => true,
		];

		$this->controls['bioTypography'] = [
			'group'    => 'bio',
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.author-bio',
				],
			],
			'required' => [ 'bio', '!=', '' ],
		];

		// Group: posts

		$this->controls['postsLink'] = [
			'group'   => 'posts',
			'label'   => esc_html__( 'Show link to author posts', 'bricks' ),
			'type'    => 'checkbox',
			'default' => true,
		];

		$this->controls['postsPadding'] = [
			'group'    => 'posts',
			'label'    => esc_html__( 'Padding', 'bricks' ),
			'type'     => 'spacing',
			'css'      => [
				[
					'property' => 'padding',
					'selector' => '.bricks-button',
				],
			],
			'required' => [ 'postsLink', '!=', '' ],
		];

		$this->controls['postsText'] = [
			'group'          => 'posts',
			'label'          => esc_html__( 'Text', 'bricks' ),
			'type'           => 'text',
			'inline'         => true,
			'hasDynamicData' => false,
			'placeholder'    => esc_html__( 'All author posts', 'bricks' ),
			'required'       => [ 'postsLink', '!=', '' ],
		];

		$this->controls['postsSize'] = [
			'group'       => 'posts',
			'label'       => esc_html__( 'Size', 'bricks' ),
			'type'        => 'select',
			'options'     => $this->control_options['buttonSizes'],
			'inline'      => true,
			'placeholder' => esc_html__( 'Default', 'bricks' ),
			'required'    => [ 'postsLink', '!=', '' ],
		];

		$this->controls['postsStyle'] = [
			'group'       => 'posts',
			'label'       => esc_html__( 'Style', 'bricks' ),
			'type'        => 'select',
			'options'     => $this->control_options['styles'],
			'inline'      => true,
			'required'    => [ 'postsLink', '!=', '' ],
			'placeholder' => esc_html__( 'None', 'bricks' ),
			'default'     => 'primary',
		];

		$this->controls['postsBackgroundColor'] = [
			'group'    => 'posts',
			'label'    => esc_html__( 'Background', 'bricks' ),
			'type'     => 'color',
			'css'      => [
				[
					'property' => 'background-color',
					'selector' => '.bricks-button',
				],
			],
			'required' => [ 'postsLink', '!=', '' ],
		];

		$this->controls['postsBorder'] = [
			'group'    => 'posts',
			'label'    => esc_html__( 'Border', 'bricks' ),
			'type'     => 'border',
			'css'      => [
				[
					'property' => 'border',
					'selector' => '.bricks-button',
				],
			],
			'required' => [ 'postsLink', '!=', '' ],
		];

		$this->controls['postsTypography'] = [
			'group'    => 'posts',
			'label'    => esc_html__( 'Typography', 'bricks' ),
			'type'     => 'typography',
			'css'      => [
				[
					'property' => 'font',
					'selector' => '.bricks-button',
				],
			],
			'required' => [ 'postsLink', '!=', '' ],
		];
	}

	public function render() {
		$post_id  = $this->post_id;
		$settings = $this->settings;

		// Get author ID from dynamic tag (@since 1.10.2)
		$post_author_id  = Integrations\Dynamic_Data\Providers::render_tag( '{author_id}', $post_id );
		$avatar_position = $settings['avatarPosition'] ?? 'left';

		$this->set_attribute( '_root', 'class', "avatar-{$avatar_position}" );

		echo "<div {$this->render_attributes( '_root' )}>";

		if ( isset( $settings['avatar'] ) ) {
			if ( $avatar_position === 'top' || $avatar_position === 'bottom' ) {
				echo '<div class="avatar-wrapper">' . get_avatar( get_the_author_meta( 'ID', $post_author_id ), isset( $settings['avatarSize'] ) ? $settings['avatarSize'] : 60 ) . '</div>';
			} else {
				echo get_avatar( get_the_author_meta( 'ID', $post_author_id ), isset( $settings['avatarSize'] ) ? $settings['avatarSize'] : 60 );
			}
		}

		echo '<div class="content">';

		if ( isset( $settings['name'] ) ) {
			// Author name tag (@since 1.11.1)
			$author_name_tag = ! empty( $settings['nameTag'] ) ? Helpers::sanitize_html_tag( $settings['nameTag'], 'h2' ) : 'h2';
			echo "<$author_name_tag class=\"author-name\">";

			if ( isset( $settings['website'] ) && get_the_author_meta( 'user_url', $post_author_id ) ) {
				echo '<a href="' . get_the_author_meta( 'user_url', $post_author_id ) . '">' . get_the_author_meta( 'display_name', $post_author_id ) . '</a>';
			} else {
				echo get_the_author_meta( 'display_name', $post_author_id );
			}

			echo "</$author_name_tag>";
		}

		if ( isset( $settings['bio'] ) ) {
			echo '<p class="author-bio">' . get_the_author_meta( 'description', $post_author_id ) . '</p>';
		}

		if ( isset( $settings['postsLink'] ) ) {
			$button_classes[] = 'bricks-button';

			if ( isset( $settings['postsSize'] ) ) {
				$button_classes[] = $settings['postsSize'];
			}

			if ( isset( $settings['postsStyle'] ) ) {
				$button_classes[] = "bricks-background-{$settings['postsStyle']}";
			}

			$this->set_attribute( 'button', 'class', $button_classes );
			$this->set_attribute( 'button', 'href', get_author_posts_url( get_the_author_meta( 'ID', $post_author_id ) ) );

			$author_posts = isset( $settings['postsText'] ) ? $settings['postsText'] : esc_html__( 'All author posts', 'bricks' );

			echo "<a {$this->render_attributes( 'button' )}>{$author_posts}</a>";
		}

		echo '</div>';
		echo '</div>';
	}
}
