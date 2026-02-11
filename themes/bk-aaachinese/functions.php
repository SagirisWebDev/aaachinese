<?php
/**
 * AAA Chinese functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package aaachinese
 * @since aaachinese 1.0
 */

/**
 * Enqueue styles and scripts
 */
if (!function_exists('aaachinese_enqueue_styles_scripts')) {
  function aaachinese_enqueue_styles_scripts() {
    wp_enqueue_style('aaachinese-reset', get_template_directory_uri() . '/assets/css/reset.css');
    
    wp_enqueue_style('aaachinese-style', get_template_directory_uri() . '/style.css');
  }
}
add_action('wp_enqueue_scripts', 'aaachinese_enqueue_styles_scripts');

/**
 * Link style.css to Editor
 */
if (!function_exists('aaachinese_style_css_editor')) {
  function aaachinese_style_css_editor() {
    // Enqueue editor styles.
    add_editor_style( 'style.css' );
  }
}

add_action('after_setup_theme', 'aaachinese_style_css_editor');

/**
 * Disable lazy loading for specific images
 */
function disable_lazy_load($attr, $attachment, $size){
    // Target single product pages to improve LCP
    if(is_product() || is_shop()){
        // Remove lazy loading from the main product image
        if(strpos($attr['class'], 'wp-post-image') !== false){
            $attr['loading'] = 'eager';
        }
    }
    return $attr;
}

add_filter("wp_get_attachment_image_attributes", "disable_lazy_load", 10, 3);