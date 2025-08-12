<?php
/**
 * WPToTailwindSafelistAdmin class file.
 * 
 * @package ThemeNamespace\Inc\Admin
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.com
 */

namespace ThemeNamespace\Inc\Admin;
use ThemeNamespace\Inc\Theme;
use ThemeNamespace\Inc\Base\AdminBase;

if (!defined('ABSPATH')) exit;

/**
 * Class WPToTailwindSafelistAdmin.
 * 
 * This class is responsible for handling the admin functionality of the WPToTailwindSafelist feature.
 * 
 * @package ThemeNamespace\Inc\Admin
 * @since 1.0.0
 * @link https://mediaworksweb.com
 */
class WPToTailwindSafelistAdmin extends AdminBase {

  /**
   * The constructor for the WPToTailwindSafelistAdmin class.
   *
   * @since 1.0.0
   * @param ThemePackageToolkit\Inc\Loader $loader The loader instance.
   * @param array $manifest The manifest file for the theme.
   * @return void
   */
  public function __construct( $loader, $manifest ) {
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
  protected function define_admin_hooks() {
    $this->loader->add_action('admin_init', $this, 'register_settings');
    $this->loader->add_action('admin_enqueue_scripts', $this, 'add_inline_feature_script');
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
    add_settings_section(
      'wp_to_tailwind_safelist_section',
      __('Safelist Settings', Theme::get_slug()),
      array($this, 'wp_to_tailwind_safelist_section_callback'),
      $this->get_page_slug()
    );

    foreach (self::get_settings_fields() as $type => $fields) {
      foreach ($fields as $id => $field) {
        add_settings_field(
          $id,
          $field['label'],
          array($this, $type . '_field_callback'),
          $this->get_page_slug(),
          'wp_to_tailwind_safelist_section',
          array(
            'field_id' => $id,
            'default' => false,
            'description' => $field['description'],
            'depends_on' => isset($field['depends_on']) ? $field['depends_on'] : null,
            'label' => isset($field['label']) ? $field['label'] : '',
          )
        );
      }
    }
  }

  /**
   * Callback for the WP To Tailwind Safelist section.
   * 
   * This method outputs the description for the WP To Tailwind Safelist settings section.
   * 
   * @since 1.0.0
   * @return void
   */
  public function wp_to_tailwind_safelist_section_callback() {
    echo __('WP To Tailwind Safelist settings.', Theme::get_slug());
  }

  /**
   * Get the settings fields for the WP To Tailwind Safelist.
   * 
   * This method returns the settings fields for the WP To Tailwind Safelist.
   * 
   * @since 1.0.0
   * @return array The settings fields.
   */
  private static function get_settings_fields() {
    return array(
      'checkbox' => array(
        'enable_safelist' => array(
          'label' => __('Enable Safelist', Theme::get_slug()),
          'description' => __('This enables an export of Tailwind CSS classes upon post updates that will be used the next time your assets are compiled.', Theme::get_slug()),
        ),
      ),
      'button' => array(
        'run_safelist_export' => array(
          'label' => __('Run Safelist Export', Theme::get_slug()),
          'description' => __('Manually trigger the safelist export.', Theme::get_slug()),
          'depends_on' => 'enable_safelist', // Only show if safelist is enabled
        ),
      ),
    );
  }

  /**
   * Sanitize WooCommerce fields.
   *
   * Sanitizes the WooCommerce fields before saving them to the database.
   * 
   * @since 1.0.0
   * @param array $input The input fields to sanitize.
   * @return array Sanitized fields.
   */
  public static function sanitize_wp_to_tailwind_safelist_fields($input) {
    $sanitized = array();
    foreach (self::get_settings_fields() as $type => $fields) {
      foreach ($fields as $field => $field_data) {
        if (isset($input[$field])) {
          if ($type === 'checkbox') {
            $sanitized[$field] = !empty($input[$field]) ? 1 : 0;
          } else {
            $sanitized[$field] = sanitize_text_field($input[$field]);
          }
        } else {
          $sanitized[$field] = 0; // Default value for checkboxes
        }
      }
    }
    return $sanitized;
  }

  /**
   * Add inline script for the admin feature.
   *
   * This method adds an inline script to the admin script. It provides the
   * `ajax_url` and `_ajax_nonce` for the admin area to use in AJAX requests.
   *
   * @since 1.0.0
   * @param string $hook The current admin page hook.
   * @return void
   */
  public function add_inline_feature_script($hook) {
    if ('appearance_page_' . $this->get_page_slug() !== $hook || !$this->manifest) {
      return;
    }

    if (wp_script_is(Theme::get_slug() . '-admin')) {
      wp_add_inline_script(
        Theme::get_slug() . '-admin',
        sprintf(
          'const homeSpecOfColoradoTwentyTwentyFiveAdmin = %s;',
          wp_json_encode( array(
            'ajax_url' => admin_url('admin-ajax.php'),
            '_ajax_nonce' => wp_create_nonce('homespecofcolorado_twentytwentyfive_export_wp_classes_nonce')
          ))
        ),
        'before'
      );
    }
  }
}