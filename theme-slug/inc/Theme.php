<?php
/**
 * Theme class file.
 * 
 * @package ThemeNamespace/Inc
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://www.mediaworksweb.com/
 */

namespace ThemeNamespace\Inc;
use ThemeNamespace\Inc\Admin\Admin;
use ThemeNamespace\Inc\Admin\WPToTailwindSafelistAdmin;
use ThemeNamespace\Inc\Features\WPToTailwindSafelist;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Theme class.
 * 
 * This class initializes the theme and provides methods to get theme information.
 * 
 * @package ThemeNamespace/Inc
 * @since 1.0.0
 * @link https://www.mediaworksweb.com/
 */
class Theme {

  /**
   * The slug of the theme.
   *
   * @since 1.0.0
   */
  const THEME_SLUG = 'theme-slug';

  /**
   * The version of the theme.
   *
   * @since 1.0.0
   */
  const VERSION = '1.0.0';

  /**
   * The loader instance.
   *
   * @since 1.0.0
   * @var Loader $loader The loader instance.
   */
  protected $loader;

  /**
   * The vite manifest.
   *
   * @since 1.0.0
   * @var array $manifest The vite manifest file.
   */
  protected $manifest;

  /**
   * The admin instance.
   *
   * @since 1.0.0
   * @var Admin $admin The admin instance.
   */
  protected $admin;

  /**
   * The WP to Tailwind Safelist admin instance.
   *
   * @since 1.0.0
   * @var WPToTailwindSafelistAdmin $wp_to_tailwind_safelist_admin The WP to Tailwind Safelist admin instance.
   */
  protected $wp_to_tailwind_safelist_admin;

  /**
   * The WP to Tailwind Safelist instance.
   *
   * @since 1.0.0
   * @var WPToTailwindSafelist $wp_to_tailwind_safelist The WP to Tailwind Safelist instance.
   */
  protected $wp_to_tailwind_safelist;

  /**
   * The i18n instance.
   *
   * @since 1.0.0
   * @var I18n $i18n The i18n instance.
   */
  protected $i18n;

  /**
   * Get the instance of the class.
   *
   * @since 1.0.0
   * @return Theme The instance of the class.
   */
  private static $instance = null;

  /**
   * Get the instance of the class.
   *
   * @since 1.0.0
   * @return Theme The instance of the class.
   */
  public static function get_instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Constructor.
   * 
   * @since 1.0.0
   * @return void
   */
  private function __construct() {
    $this->load_manifest();
    $this->initialize_classes();
    $this->define_hooks();
  }

  /**
   * Load the Vite manifest file.
   *
   * @since 1.0.0
   * @return void
   */
  private function load_manifest() {
    $manifest_path = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if ( file_exists( $manifest_path ) ) {
      $this->manifest = json_decode( file_get_contents( $manifest_path ), true );
    }
  }

  /**
   * Initialize the theme classes.
   *
   * This is a modular theme structure where each class can register its own hooks and filters.
   *
   * @since 1.0.0
   * @return void
   */
  private function initialize_classes() {
    $this->loader = new Loader();

    $loader = $this->loader;
    $manifest = $this->manifest;

    if ( is_admin() ) {
      $this->admin = new Admin( $loader, $manifest );
      $this->wp_to_tailwind_safelist_admin = new WPToTailwindSafelistAdmin( $loader, $manifest );
    }

    $this->wp_to_tailwind_safelist = new WPToTailwindSafelist( $loader, $manifest );
    $this->i18n = new I18n( $loader );
  }

  /**
   * Define hooks.
   *
   * @since 1.0.0
   * @return void
   */
  private function define_hooks() {
    $this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts' );
  }

  /**
   * Enqueue scripts.
   *
   * @since 1.0.0
   * @return void
   */
  public function enqueue_scripts() {
    $js_key = 'src/js/main.ts';
    if (isset($this->manifest[$js_key])) {
      $js_path = self::get_theme_url() . '/dist/' . $this->manifest[$js_key]['file'];
      wp_enqueue_script(
        self::get_slug() . '-main',
        $js_path,
        array(),
        self::get_version(),
        true
      );
    }

    $css_key = 'src/css/main-style.css';
    if (isset($this->manifest[$css_key])) {
      $css_path = self::get_theme_url() . '/dist/' . $this->manifest[$css_key]['file'];
      wp_enqueue_style(
        self::get_slug() . '-main',
        $css_path,
        array(),
        self::get_version()
      );
    }
  }

  /**
   * Run the theme.
   *
   * @since 1.0.0
   * @return void
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * Get the theme path.
   *
   * @since 1.0.0
   * @return string
   */
  public static function get_theme_path() {
    return get_stylesheet_directory();
  }

  /**
   * Get the theme url.
   * 
   * @since 1.0.0
   * @return string
   */
  public static function get_theme_url() {
    return get_stylesheet_directory_uri();
  }

  /**
   * Get the theme slug.
   *
   * @since 1.0.0
   * @return string
   */
  public static function get_slug() {
    return self::THEME_SLUG;
  }

  /**
   * Get the theme version.
   *
   * @since 1.0.0
   * @return string
   */
  public static function get_version() {
    return self::VERSION;
  }
}