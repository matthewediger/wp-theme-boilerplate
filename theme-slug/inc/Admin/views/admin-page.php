<?php
/**
 * Admin Page Partial
 *
 * This file is responsible for rendering the admin page for the theme settings.
 *
 * @package ThemeNamespace\Inc\Admin\Partials
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.comw
 */

namespace ThemeNamespace\Inc\Admin\Views;
use ThemeNamespace\Inc\Theme;

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
  <h1><?php echo get_admin_page_title(); ?></h1>
  <form method="post" action="options.php">
    <?php
    settings_fields( $this->get_option_group() );
    do_settings_sections( $this->get_page_slug() );
    submit_button( __( 'Save Settings', Theme::get_slug() ) );
    ?>
  </form>
  <?php include Theme::get_theme_path() . '/inc/admin/partials/bottom-panel.php'; ?>
</div>
</div>