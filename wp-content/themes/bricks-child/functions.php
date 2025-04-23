<?php 
/**
 * Register/enqueue custom scripts and styles
 */
add_action( 'wp_enqueue_scripts', function() {
	// Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
	if ( ! bricks_is_builder_main() ) {
		wp_enqueue_style( 'bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
} );

/**
 * Register custom elements
 */
add_action( 'init', function() {
  $element_files = [
    __DIR__ . '/elements/title.php',
  ];

  foreach ( $element_files as $file ) {
    \Bricks\Elements::register_element( $file );
  }
}, 11 );

/**
 * Add text strings to builder
 */
add_filter( 'bricks/builder/i18n', function( $i18n ) {
  // For element category 'custom'
  $i18n['custom'] = esc_html__( 'Custom', 'bricks' );

  return $i18n;
} );

/**
 * Add _bricks_data to REST API
 */
add_action( 'rest_api_init', function () {
  // Register post_meta_fields dump (optional for debugging)
  register_rest_field( 'page', 'post_meta_fields', [
    'get_callback' => 'get_post_meta_for_api',
    'schema'       => null,
  ] );

  register_rest_field( 'post', 'post_meta_fields', [
    'get_callback' => 'get_post_meta_for_api',
    'schema'       => null,
  ] );

  // ✅ Register individual meta fields for Bricks
  register_post_meta( 'page', '_bricks_page_content_2', [
    'single'       => true,
    'type'         => 'object', // or 'string' if you're sending raw string
    'show_in_rest' => true,
  ] );

  register_post_meta( 'page', '_bricks_template_type', [
    'single'       => true,
    'type'         => 'string',
    'show_in_rest' => true,
  ] );

  register_post_meta( 'page', '_bricks_editor_mode', [
    'single'       => true,
    'type'         => 'string',
    'show_in_rest' => true,
  ] );
} );

function get_post_meta_for_api( $object ) {
  return get_post_meta( $object['id'] );
}