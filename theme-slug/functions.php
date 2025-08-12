<?php
/**
 * Child-theme functions file.
 * 
 * Functions file for the HomeSpec Of Colorado Twenty Twenty-Five child theme.
 * 
 * @package ThemeNamespace
 * @since 1.0.0
 * @author mediaworksmatt <matt@mediaworksweb.com>
 * @link https://www.mediaworksweb.com/
 */

use ThemeNamespace\Inc\Theme;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Include Composer's autoloader.
 * 
 * @since 1.0.0
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Run the theme.
 * 
 * @since 1.0.0
 */
Theme::get_instance()->run();