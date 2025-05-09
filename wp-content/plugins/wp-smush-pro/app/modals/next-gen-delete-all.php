<?php
/**
 * Delete all Next-Gen files modal.
 *
 * @since 3.8.0
 * @package WP_Smush
 */

use Smush\Core\Next_Gen\Next_Gen_Manager;

if ( ! defined( 'WPINC' ) ) {
	die;
}

$next_gen_manager = Next_Gen_Manager::get_instance();
?>

<div class="sui-modal sui-modal-sm">
	<div
		role="dialog"
		id="wp-smush-wp-delete-all-dialog"
		class="sui-modal-content smush-dawif sui-content-fade-in"
		aria-modal="true"
		aria-labelledby="smush-dawif-title"
		aria-describedby="smush-dawif-description"
	>
		<div class="sui-box">
			<div id="smush-dawif-content">
				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
					<button type="button" class="sui-button-icon sui-button-float--right" data-modal-close>
						<i class="sui-icon-close sui-md" aria-hidden="true"></i>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this modal', 'wp-smushit' ); ?></span>
					</button>

					<h3 class="sui-box-title sui-lg" id="smush-dawif-title">
						<?php
						/* translators: %s: Next-Gen format name */
						printf( esc_html__( 'Delete %s files', 'wp-smushit' ), esc_html( $next_gen_manager->get_active_format_name() ) );
						?>
					</h3>
				</div>
				<div class="sui-box-body sui-flatten sui-content-center sui-spacing-top--20 sui-spacing-bottom--50">
					<p class="sui-description" id="smush-dawif-description" style="margin-bottom:15px;">
						<?php
						/* translators: %s: Next-Gen format name */
						printf( esc_html__( 'Are you sure you want to delete all %s files?', 'wp-smushit' ), esc_html( $next_gen_manager->get_active_format_name() ) );
						?>
					</p>
					<div
						id="wp-smush-<?php echo esc_attr( $next_gen_manager->get_active_format_key() ); ?>-delete-all-error-notice"
						class="sui-notice sui-notice-error"
						style="margin-bottom:15px;"
						role="alert"
						aria-live="assertive"
					></div>
					<div class="sui-block-content-center" style="padding-top:15px;">
						<button type="button" class="sui-button sui-button-ghost" data-modal-close="">
							<?php esc_html_e( 'Cancel', 'wp-smushit' ); ?>
						</button>
						<button
							type="button"
							id="wp-smush-<?php echo esc_attr( $next_gen_manager->get_active_format_key() ); ?>-delete-all"
							class="sui-button sui-button-red"
						>
							<span class="sui-loading-text"><?php esc_html_e( 'Delete', 'wp-smushit' ); ?></span>
							<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>