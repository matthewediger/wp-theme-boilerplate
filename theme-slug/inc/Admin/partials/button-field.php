<?php
/**
 * Button Field Partial
 * 
 * This file is responsible for rendering the button field in the admin area.
 * 
 * @package ThemeNamespace\Inc\Admin\Partials
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.com
 * 
 * @var string $field_id The ID of the button field.
 * @var string $class The CSS class for the button.
 * @var string $depends_on The ID of the field this button depends on.
 * @var array $args The arguments for the button field.
 */

namespace ThemeNamespace\Inc\Admin\Partials;

if (!defined('ABSPATH')) exit;
?>
<button
  id="<?php echo esc_attr($field_id); ?>"
  class="<?php echo esc_attr($class); ?>"
  type="button"
  <?php echo $depends_on ? 'data-depends-on="' . esc_attr($depends_on) . '"' : ''; ?>
>
  <?php echo esc_html__($args['label'], 'ThemePackage'); ?>
</button>
