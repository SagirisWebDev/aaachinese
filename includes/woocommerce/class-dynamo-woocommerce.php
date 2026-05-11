<?php
declare(strict_types=1);

class Dynamo_WooCommerce {

    public function init(): void {
        add_action('after_setup_theme', [$this, 'register_theme_support']);
        add_action('after_setup_theme', [$this, 'replace_content_wrappers'], 11);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function register_theme_support(): void {
        add_theme_support('woocommerce');
    }

    public function replace_content_wrappers(): void {
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        add_action('woocommerce_before_main_content', [$this, 'output_content_wrapper_open'], 10);
        add_action('woocommerce_after_main_content', [$this, 'output_content_wrapper_close'], 10);
    }

    public function output_content_wrapper_open(): void {
        echo '<main id="main" class="site-main">'
            . '<div class="dynamo-container dynamo-content-wrap">'
            . '<div class="dynamo-primary">';
    }

    public function output_content_wrapper_close(): void {
        echo '</div></div></main>';
    }

    public function enqueue_assets(): void {
        if (!$this->is_woocommerce_page()) {
            return;
        }
        wp_enqueue_style(
            'dynamo-woocommerce',
            DYNAMO_URL . 'assets/css/woocommerce.css',
            [],
            DYNAMO_VERSION
        );
    }

    private function is_woocommerce_page(): bool {
        return (function_exists('is_woocommerce') && is_woocommerce())
            || (function_exists('is_cart') && is_cart())
            || (function_exists('is_checkout') && is_checkout())
            || (function_exists('is_account_page') && is_account_page());
    }
}
