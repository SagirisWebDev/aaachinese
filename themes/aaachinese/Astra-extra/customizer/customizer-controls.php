<?php
/**
 * AAA Chinese Theme Customizer Controls.
 *
 * @package     AAA Chinese
 *
 * @since       AAA Chinese 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$aaa_chinese_control_dir = AAA_THEME_DIR . 'inc/customizer/custom-controls';

// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require $aaa_chinese_control_dir . '/class-aaa-chinese-customizer-control-base.php';
require $aaa_chinese_control_dir . '/typography/class-aaa-chinese-control-typography.php';
require_once $aaa_chinese_control_dir . '/logo-svg-icon/class-aaa-chinese-control-logo-svg-icon.php';
require $aaa_chinese_control_dir . '/description/class-aaa-chinese-control-description.php';
require $aaa_chinese_control_dir . '/customizer-link/class-aaa-chinese-control-customizer-link.php';
require $aaa_chinese_control_dir . '/description-with-link/class-aaa-chinese-control-description-with-link.php';
// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
