<?php
/**
 * AdminBase class file.
 * 
 * @package ThemeNamespace/Inc
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://www.mediaworksweb.com/
 */

namespace ThemeNamespace\Inc\Base;
use ThemeNamespace\Inc\Theme;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class AdminBase
 *
 * This class serves as a base for all admin features in the theme.
 * It provides methods to get the theme name, version, and other common functionality.
 * 
 * @package FossiliciousTwentyTwentyFive\Inc\Base
 * @since 1.0.0
 * @link https://mediaworksweb.com
 */
abstract class AdminBase {

  /**
   * The loader instance.
   *
   * @since 1.0.0
   * @var FossiliciousTwentyTwentyFive\Inc\Loader $loader The loader instance.
   */
	protected $loader;

  /**
   * The manifest file for the theme.
   *
   * @since 1.0.0
   * @var array $manifest The manifest file for the theme.
   */
	protected $manifest;

  /**
   * The name of the options group.
   *
   * @since 1.0.0
   * @var string $option_group The name of the options group.
   */
  protected $option_group;

  /**
   * The name of the options group.
   * 
   * @since 1.0.0
   * @var string $option_name The name of the options group.
   */
  protected $option_name;

  /**
   * The slug of the admin page.
   *
   * @since 1.0.0
   * @var string $page_slug The slug of the admin page.
   */
  protected $page_slug;

  /**
   * Initialize the class and set its properties.
   * 
   * This constructor sets the theme name, version, loader, manifest, option group, option name, and page slug.
   * 
   * @since 1.0.0
   * @param FossiliciousTwentyTwentyFive\Inc\Loader $loader The loader instance.
   * @param array $manifest The manifest file for the theme.
   * @return void
   */
  public function __construct($loader, $manifest) {
    $this->loader = $loader;
    $this->manifest = $manifest;
    
    $slug = Theme::get_slug();
    $this->option_group = str_replace('-', '_', $slug . '_settings');
    $this->option_name = str_replace('-', '_', $slug . '_options');
    $this->page_slug = $slug . '-settings';
  }

  /**
   * Text field callback.
   * 
   * This method renders a text field for the theme settings.
   * 
   * @since 1.0.0
   * @param array $args The arguments for the text field.
   * @return void
   */
  public function text_field_callback($args) {
    $options = get_option($this->option_name);
    $field_id = $args['field_id'];
    $default = isset($args['default']) ? $args['default'] : '';
    $value = isset($options[$field_id]) ? $options[$field_id] : $default;
    $description = isset($args['description']) ? $args['description'] : '';
    $depends_on = isset($args['depends_on']) ? $args['depends_on'] : '';
    
    // Add conditional display class if depends_on is set
    $class = 'regular-text';
    if ($depends_on) {
      $class .= ' fossilicious-conditional-field';
      $depends_value = isset($options[$depends_on]) ? $options[$depends_on] : false;
      if (!$depends_value) {
        $class .= ' hidden';
      }
    }
    
    include Theme::get_theme_path() . '/inc/admin/partials/text-field.php';
  }

  /**
   * Checkbox field callback.
   * 
   * This method renders a checkbox field for the theme settings.
   * 
   * @since 1.0.0
   * @param array $args The arguments for the checkbox field.
   * @return void
   */
  public function checkbox_field_callback($args) {
    $options = get_option($this->option_name);
    $field_id = $args['field_id'];
    $default = isset($args['default']) ? $args['default'] : false;
    $value = isset($options[$field_id]) ? $options[$field_id] : $default;
    $description = isset($args['description']) ? $args['description'] : '';
    $depends_on = isset($args['depends_on']) ? $args['depends_on'] : '';
    
    // Add conditional display class if depends_on is set
    $class = 'regular-text';
    if ($depends_on) {
      $class .= ' fossilicious-conditional-field';
      $depends_value = isset($options[$depends_on]) ? $options[$depends_on] : false;
      if (!$depends_value) {
        $class .= ' hidden';
      }
    }
    
    include Theme::get_theme_path() . '/inc/admin/partials/checkbox-field.php';
  }

  /**
   * Button field callback.
   * 
   * This method renders a button field for the theme settings.
   * 
   * @since 1.0.0
   * @param array $args The arguments for the button field.
   * @return void
   */
  public function button_field_callback($args) {
    $field_id = $args['field_id'];
    $depends_on = isset($args['depends_on']) ? $args['depends_on'] : '';

    // Add conditional display class if depends_on is set
    $class = 'button button-primary';
    if ($depends_on) {
      $class .= ' fossilicious-conditional-field';
      $depends_value = isset($options[$depends_on]) ? $options[$depends_on] : false;
      if (!$depends_value) {
        $class .= ' hidden';
      }
    }

    include Theme::get_theme_path() . '/inc/admin/partials/button-field.php';
  }

  /**
   * Get a specific option value.
   * 
   * This method retrieves a specific option value from the theme options.
   * 
   * @since 1.0.0
   * @param string $key The key of the option to retrieve.
   * @param mixed $default The default value to return if the option is not set.
   * @return mixed The value of the option or the default value if not set.
   * @see get_all_options()
   */
  public function get_option($key, $default = '') {
    $options = get_option($this->get_option_name());
    return isset($options[$key]) ? $options[$key] : $default;
  }
  
  /**
   * Get all options.
   * 
   * This method retrieves all options from the theme options.
   * 
   * @since 1.0.0
   * @return array The array of all options.
   * @see get_option()
   */
  public function get_all_options() {
    return get_option($this->get_option_name(), array());
  }

  /**
   * Get the loader instance.
   *
   * @since 1.0.0
   * @return FossiliciousTwentyTwentyFive\Inc\Loader The loader instance.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Get the manifest file.
   *
   * @since 1.0.0
   * @return array The manifest file.
   */
  public function get_manifest() {
    return $this->manifest;
  }

  /**
   * Get the option group name.
   *
   * @since 1.0.0
   * @return string The option group name.
   */
  public function get_option_group() {
    return $this->option_group;
  }

  /**
   * Get the option name.
   *
   * @since 1.0.0
   * @return string The option name.
   */
  public function get_option_name() {
    return $this->option_name;
  }

  /**
   * Get the page slug.
   *
   * @since 1.0.0
   * @return string The slug of the admin page.
   */
  public function get_page_slug() {
    return $this->page_slug;
  }
}