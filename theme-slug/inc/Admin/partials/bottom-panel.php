<?php
/**
 * Bottom Panel Partial
 *
 * This file is responsible for rendering the bottom panel for the admin page.
 *
 * @package ThemeNamespace\Inc\Admin\Partials
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.com
 * @var object $this The admin class instance available in this view
 */

namespace ThemeNamespace\Inc\Admin\Partials;
use ThemeNamespace\Inc\Theme;

if (!defined('ABSPATH')) exit;
?>
<div class="bottom-panel">
  <div class="postbox">
    <div class="inside">
      <h3 class="hndle">Theme Information</h3>
      <p><strong>Version:</strong> <?php echo Theme::get_version(); ?></p>
      <p><strong>Support:</strong> <a href="mailto:support@mediaworksweb.com">support@mediaworksweb.com</a></p>
    </div>
  </div>
</div>