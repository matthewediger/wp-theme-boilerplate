<?php
/**
 * FeatureBase class file.
 *
 * @package ThemeNamespace\Inc\Base
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.com
 */

namespace ThemeNamespace\Inc\Base;
use ThemeNamespace\Inc\Theme;

if (!defined('ABSPATH')) exit;

/**
 * Class FeatureBase
 *
 * This class serves as a base for all features in the theme.
 * It provides methods to get the theme name, version, and other common functionality.
 * 
 * @package ThemeNamespace\Inc\Base
 * @since 1.0.0
 * @link https://mediaworksweb.com
 */
abstract class FeatureBase {

  /**
   * The loader instance.
   *
   * @since 1.0.0
   * @var ThemeNamespace\Loader $loader The loader instance.
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
   * Initialize the class and set its properties.
   * 
   * @since 1.0.0
   * @param string $theme_name The name of the theme.
   * @param string $version The version of this theme.
   * @param ThemeNamespace\Inc\Loader $loader The loader instance.
   * @param array $manifest The manifest file for the theme.
   * @return void
   */
  public function __construct($loader, $manifest) {
    $this->loader = $loader;
    $this->manifest = $manifest;
    $this->option_group = str_replace('-', '_', Theme::get_slug() . '_settings');
    $this->option_name = str_replace('-', '_', Theme::get_slug() . '_options');
  }

  /**
   * Get a specific option value.
   * 
   * This method retrieves the value of a specific option from the theme's options.
   * 
   * @since 1.0.0
   * @param string $key The key of the option to retrieve.
   * @param mixed $default The default value to return if the option is not set.
   * @return mixed The value of the option or the default value if not set.
   * @see get_all_options()
   * @see get_option_name()
   */
  public function get_option($key, $default = '') {
    $options = get_option($this->get_option_name());
    return isset($options[$key]) ? $options[$key] : $default;
  }
  
  /**
   * Get all options.
   * 
   * This method retrieves all options from the theme's options.
   * 
   * @since 1.0.0
   * @return array The array of all options.
   * @see get_option()
   * @see get_option_name()
   */
  public function get_all_options() {
    return get_option($this->get_option_name(), array());
  }

  /**
   * Get the loader instance.
   *
   * @since 1.0.0
   * @return ThemeNamespace\Inc\Loader The loader instance.
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
}