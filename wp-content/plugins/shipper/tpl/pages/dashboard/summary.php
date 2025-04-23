<?php
/**
 * Shipper dashboard page templates: summary
 *
 * @since v1.1.4
 * @package shipper
 */

$custom_logo_url = Shipper_Helper_Assets::get_custom_hero_image();

?>
<div class="sui-box sui-summary" <?php if(!empty( $custom_logo_url )): ?>
    style="background-image: url('<?php echo esc_url($custom_logo_url); ?>'); <?php endif;?>';">
    <div class="sui-summary-image-space" aria-hidden="true"></div>

    <div class="sui-summary-segment">
        <div class="sui-summary-details">
            <span class="sui-summary-large">
                <?php echo $has_package ? esc_attr( $package_size ) : '-'; ?>
            </span>

            <?php if ( $has_package ) : ?>
            <span class="shipper-dashboard-package-size">
                <?php echo esc_html( $package_size_text ); ?>
            </span>
            <?php endif; ?>

            <span class="sui-summary-sub">
                <?php esc_html_e( 'Website size (based on last migration)', 'sipper' ); ?>
            </span>
        </div>

    </div>

    <div class="sui-summary-segment">
        <ul class="sui-list">
            <li>
                <span class="sui-list-label"><?php esc_html_e( 'Last Migration', 'shipper' ); ?></span>
                <span class="sui-list-detail">
                    <?php if ( ! empty( $migration_in_progress['is_active'] ) || $package_migration_in_progress ) : ?>
                    <?php esc_html_e( 'In Progresss...', 'shipper' ); ?>
                    <?php else : ?>
                    <?php echo esc_html( $last_migration ); ?>
                    <?php endif; ?>
                </span>
            </li>

            <li>
                <span class="sui-list-label"><?php esc_html_e( 'Last Migration Method', 'shipper' ); ?></span>
                <span class="sui-list-detail">
                    <?php echo esc_html( $migration_method ); ?>
                </span>
            </li>
        </ul>
    </div>
</div>