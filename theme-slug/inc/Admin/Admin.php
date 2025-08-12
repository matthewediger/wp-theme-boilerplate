<?php
/**
 * Admin class file.
 * 
 * @package ThemeNamespace\Inc\Admin
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.com
 */

namespace ThemeNamespace\Inc\Admin;
use ThemeNamespace\Inc\Theme;
use ThemeNamespace\Inc\Base\AdminBase;
use HomeSpecofColoradoTwentyTwentyFive\Inc\Admin\WPToTailwindSafelistAdmin;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin class.
 * 
 * This class is responsible for handling the admin functionality of the theme.
 * 
 * @package ThemeNamespace\Inc\Admin
 * @since 1.0.0
 * @link https://mediaworksweb.com
 */
class Admin extends AdminBase {

  /**
   * The constructor for the Admin class.
   *
   * @since 1.0.0
   * @param ThemeNamespace\Inc\Loader $loader The loader instance.
   * @param array $manifest The manifest file for the plugin.
   * @return void
   */
  public function __construct($loader, $manifest) {
    parent::__construct($loader, $manifest);
    $this->define_admin_hooks();
  }

  /**
   * Define the hooks for the admin area.
   * 
   * This method is responsible for defining the hooks that will be used in the admin area of the theme.
   * 
   * @since 1.0.0
   * @return void
   */
  private function define_admin_hooks() {
    $this->loader->add_action('admin_menu', $this, 'add_admin_menu');
    $this->loader->add_action('admin_init', $this, 'register_settings');
    $this->loader->add_action('admin_enqueue_scripts', $this, 'enqueue_admin_scripts');
  }

  /**
   * Add the admin menu.
   * 
   * This method adds a new menu item to the WordPress admin dashboard for the theme settings.
   * 
   * @since 1.0.0
   * @return void
   */
  public function add_admin_menu() {
    add_theme_page(
      __('ThemePackage Twenty Twenty-Five Settings', Theme::get_slug()),
      __('Theme Settings', Theme::get_slug()),
      'manage_options',
      $this->get_page_slug(),
      array($this, 'render_admin_page')
    );
  }

  /**
   * Render the admin page.
   * 
   * This method renders the content of the admin page for the theme settings.
   * 
   * @since 1.0.0
   * @return void
   */
  public function render_admin_page() {
    include Theme::get_theme_path() . '/inc/admin/views/admin-page.php';
  }

  /**
   * Register the settings for the theme.
   * 
   * This method initializes the admin settings for the theme.
   * 
   * @since 1.0.0
   * @return void
   */
  public function register_settings() {
    register_setting(
      $this->get_option_group(),
      $this->get_option_name(),
      array(
        'type' => 'array',
        'sanitize_callback' => array($this, 'sanitize_settings'),
        'default' => array(),
      )
    );
  }

  /**
   * Sanitize the settings.
   * 
   * This method sanitizes the settings before saving them to the database.
   * 
   * @since 1.0.0
   * @param array $settings The settings to sanitize.
   * @return array The sanitized settings.
   */
  public function sanitize_settings($settings) {
    $sanitized = array();

    // Sanitize core fields
    $sanitized['core_field'] = sanitize_text_field($settings['core_field'] ?? '');

    // Call static method for child classes to sanitize their fields
    $sanitized = array_merge(
      $sanitized,
      WPToTailwindSafelistAdmin::sanitize_wp_to_tailwind_safelist_fields($settings)
    );

    return $sanitized;
  }

  /**
   * Enqueue admin scripts.
   * 
   * This method enqueues the necessary scripts and styles for the admin area.
   * 
   * @since 1.0.0
   * @param string $hook The current admin page hook.
   * @return void
   */
  public function enqueue_admin_scripts($hook) {
    if ('appearance_page_' . $this->get_page_slug() !== $hook || ! $this->manifest) {
      return;
    }

    $js_key = 'src/admin/js/admin.ts';
    if (isset($this->manifest[$js_key])) {
      $js_path = Theme::get_theme_url() . '/dist/' . $this->manifest[$js_key]['file'];
      wp_enqueue_script(
        Theme::get_slug() . '-admin',
        $js_path,
        array(),
        Theme::get_version(),
        true
      );
    }

    $css_key = 'src/admin/css/admin-style.css';
    if (isset($this->manifest[$css_key])) {
      $css_path = Theme::get_theme_url() . '/dist/' . $this->manifest[$css_key]['file'];
      wp_enqueue_style(
        Theme::get_slug() . '-admin',
        $css_path,
        array(),
        Theme::get_version()
      );
    }
  }
}