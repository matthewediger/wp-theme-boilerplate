<?php
/**
 * Text Field Partial
 *
 * This file is responsible for rendering the text field in the admin area.
 *
 * @package ThemeNamespaceIncAdminPartials
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.com
 * 
 * @var string $field_id The ID of the button field.
 * @var string $class The CSS class for the button.
 * @var string $depends_on The ID of the field this button depends on.
 * @var array $args The arguments for the button field.
 */

namespace ThemeNamespaceIncAdminPartials;

if (!defined('ABSPATH')) exit;
?>
<input
  type="text"
  id="<?php echo esc_attr($field_id); ?>"
  name="<?php echo esc_attr($this->option_name); ?>[<?php echo esc_attr($field_id); ?>]"
  value="<?php echo esc_attr($value); ?>"
  class="<?php echo esc_attr($class); ?>"
  <?php if ($depends_on) : ?>data-depends-on="<?php echo esc_attr($depends_on); ?>"<?php endif; ?>
/>
<?php if ($description) : ?>
  <p class="description"><?php echo esc_html($description); ?></p>
<?php endif; ?>