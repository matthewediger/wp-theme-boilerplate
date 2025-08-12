<?php
/**
 * WPToTailwindSafelist class file.
 * 
 * @package ThemeNamespace\Inc\Features
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://mediaworksweb.com
 */

namespace ThemeNamespace\Inc\Features;
use ThemeNamespace\Inc\Base\FeatureBase;
use ThemeNamespace\Inc\Theme;

if (!defined('ABSPATH')) exit;

/**
 * Class WPToTailwindSafelist.
 * 
 * This class handles exporting post classes to facilitate Tailwind CSS style generation and CI/CD deployment.
 * 
 * @package ThemeNamespace\Inc\Features
 * @since 1.0.0
 * @link https://mediaworksweb.com
 */
class WPToTailwindSafelist extends FeatureBase {

  /**
   * The export event.
   *
   * @since 1.0.0
   * @var string $export_event The export event name.
   */
  protected static $export_event;

  /**
   * Constructor.
   *
   * @since 1.0.0
   * @param ThemeNamespace\Inc\Loader $loader The loader instance.
   * @param array $manifest The manifest file for the theme.
   * @return void
   */
  public function __construct($loader, $manifest) {
    parent::__construct($loader, $manifest);

    if (!$this->get_option('enable_safelist')) {
      return;
    }

    $this->export_event = strtolower(str_replace('-', '_', Theme::get_slug())) . '_export_wordpress_to_tailwind_classes';
    $this->define_hooks();
  }


  /**
   * Define the hooks for this utility.
   * 
   * This method registers the necessary actions and filters for the utility to function correctly.
   * 
   * @since 1.0.0
   * @return void
   */
  public function define_hooks() {
    $this->loader->add_action('post_updated', $this, 'schedule_single_event_to_export_wp_classes', 10, 2);
    $this->loader->add_action('switch_theme', $this, 'clear_scheduled_export_wp_classes');
    $this->loader->add_action(self::get_export_event(), $this, 'export_wp_classes');
    $this->loader->add_action('wp_ajax_homespecofcolorado_twentytwentyfive_export_wp_classes', $this, 'ajax_export_wp_classes');
    $this->loader->add_action('wp_ajax_nopriv_homespecofcolorado_twentytwentyfive_export_wp_classes', $this, 'ajax_export_wp_classes');
  }

  /**
   * Schedule single event to export cms classes.
   * 
   * Schedule the export cms classes event if not already scheduled.
   * 
   * @since 1.0.0
   * @param int $post_id The ID of the post that was updated.
   * @param WP_Post $post_after The post object after the update.
   * @return void
   * @see wp_next_scheduled()
   * @see wp_schedule_single_event()
   */
  public function schedule_single_event_to_export_wp_classes($post_id, $post_after) {
    if (in_array($post_after->post_type, $this->get_theme_post_types()) && $post_after->post_status === 'publish') {
      if (!wp_next_scheduled(self::get_export_event())) {
        wp_schedule_single_event(time(), self::get_export_event());
      }
    }
  }

  /**
   * Export CMS classes.
   * 
   * Export all CMS classes to a separate file we can parse for Tailwind.
   * 
   * @since 1.0.0
   * @return array An array containing the count of classes, the file path, and the number of posts processed.
   */
  public function export_wp_classes() {
    $classes = array();

    $posts = get_posts([
      'numberposts' => -1,
      'post_type'   => $this->get_theme_post_types()
    ]);

    foreach ($posts as $post) {
      if (function_exists('wc_get_notices')) {
        $content = apply_filters('the_content', $post->post_content);
      } else {
        $content = $post->post_content;
      }

      // Extract classes from rendered HTML
      preg_match_all('/class="([^"]+)"/', $content, $matches);
      if (!empty($matches[1])) {
        foreach ($matches[1] as $class) {
          $classes = array_merge($classes, explode(' ', $class));
        }
      }

      // Extract classes from block comments (dynamic blocks)
      preg_match_all('/<!--\s+wp:[^ ]+.*?({.*?})\s*-->/', $post->post_content, $block_matches);
      if (!empty($block_matches[1])) {
        foreach ($block_matches[1] as $json) {
          $attrs = json_decode($json, true);
          if (isset($attrs['className'])) {
          $classes = array_merge($classes, explode(' ', $attrs['className']));
          }
        }
      }
    }

    // Remove duplicates and sort
    $classes = array_unique($classes);
    sort($classes);

    // Filter out empty classes, classes that start with `is-`, `has-`, `wp-`, or `wc-`.
    $classes = array_filter($classes, function($class) {
      return !empty($class) && !preg_match('/^(is-|has-|wp-|wc-)/', $class);
    });

    // Save as a plain text file with one class per line for Tailwind v4.1+ @source inline
    $txt_data = implode("\n", array_values($classes)) . "\n";
    $file = Theme::get_theme_path() . '/src/css/generated-tailwind-safelist.txt';
    file_put_contents($file, $txt_data);

    // Return info for AJAX
    return [
      'class_count' => count($classes),
      'file' => $file,
      'post_count' => count($posts)
    ];
  }

  /**
   * Clear scheduled event.
   * 
   * Clear the scheduled event upon theme deactivation.
   * 
   * @since 1.0.0
   * @return void
   * @see wp_next_scheduled()
   * @see wp_unschedule_event()
   * @see get_export_event()
   */
  public function clear_scheduled_export_wp_classes() {
    $timestamp = wp_next_scheduled($this->get_export_event());
    if ($timestamp) {
      wp_unschedule_event($timestamp, $this->get_export_event());
    }
  }

  /**
   * AJAX handler for exporting CMS classes.
   * 
   * This method handles the AJAX request to export CMS classes.
   * It checks user capabilities and triggers the export process.
   * 
   * @since 1.0.0
   * @return void
   * @throws WP_Error If the user is not authorized.
   * @see export_wp_classes()
   * @see wp_send_json_success()
   * @see wp_send_json_error()
   * @see current_user_can()
   */
  public function ajax_export_wp_classes() {
    // Verify nonce
    check_ajax_referer('homespecofcolorado_twentytwentyfive_export_wp_classes_nonce');

    // Check user capability
    if (!current_user_can('manage_options')) {
      wp_send_json_error(['message' => 'Unauthorized'], 403);
    }

    // Export and return the result
    $result = $this->export_wp_classes();

    wp_send_json_success([
      'message' => sprintf(
        __("Exported %d classes from %d posts.\nFile saved at %s", Theme::get_slug()),
        $result['class_count'],
        $result['post_count'],
        $result['file']
      ),
      'file' => $result['file']
    ]);
  }

  /**
   * Get the theme post types.
   * 
   * Get the post types used in the theme.
   * 
   * @since 1.0.0
   * @return array
   * @see get_post_types()
   */
  public function get_theme_post_types() {
    return get_post_types();
  }

  /**
   * Get the export event name.
   * 
   * @since 1.0.0
   * @return string The export event name.
   */
  public static function get_export_event() {
    return self::$export_event;
  }
}