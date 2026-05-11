<?php
declare(strict_types=1);

class Dynamo_WooCommerce {

    private const COLOUR_TOKENS = [
        'woocommerce-sale-badge-bg'    => 'Sale Badge Background',
        'woocommerce-sale-badge-color' => 'Sale Badge Text',
        'woocommerce-star-color'       => 'Star Rating',
    ];

    public function init(): void {
        add_action('after_setup_theme', [$this, 'register_theme_support']);
        add_action('after_setup_theme', [$this, 'replace_content_wrappers'], 11);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('customize_register', [$this, 'register_customizer']);
        add_filter('loop_shop_columns', [$this, 'filter_loop_shop_columns']);
        add_filter('loop_shop_per_page', [$this, 'filter_loop_shop_per_page']);
    }

    public function register_customizer(object $wp_customize): void {
        $wp_customize->add_panel('dynamo_woocommerce', [
            'title'    => __('Dynamo: WooCommerce', 'dynamo'),
            'priority' => 35,
        ]);

        $this->register_colours_section($wp_customize);
        $this->register_shop_layout_section($wp_customize);
    }

    private function register_colours_section(object $wp_customize): void {
        $wp_customize->add_section('dynamo_woocommerce_colours', [
            'title' => __('WooCommerce Colours', 'dynamo'),
            'panel' => 'dynamo_woocommerce',
        ]);

        $registry = new Dynamo_Token_Registry();

        foreach (self::COLOUR_TOKENS as $token => $label) {
            $setting_id = 'dynamo_' . str_replace('-', '_', $token);
            $wp_customize->add_setting($setting_id, [
                'default'           => $registry->get($token) ?? '#000000',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            ]);
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $setting_id, [
                'label'   => __($label, 'dynamo'),
                'section' => 'dynamo_woocommerce_colours',
            ]));
        }
    }

    private function register_shop_layout_section(object $wp_customize): void {
        $wp_customize->add_section('dynamo_woocommerce_shop_layout', [
            'title' => __('Shop Layout', 'dynamo'),
            'panel' => 'dynamo_woocommerce',
        ]);

        $registry = new Dynamo_Token_Registry();

        $wp_customize->add_setting('dynamo_woocommerce_shop_columns', [
            'default'           => $registry->get('woocommerce-shop-columns') ?? '3',
            'sanitize_callback' => [$this, 'sanitize_shop_columns'],
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dynamo_woocommerce_shop_columns', [
            'label'       => __('Columns per row', 'dynamo'),
            'section'     => 'dynamo_woocommerce_shop_layout',
            'type'        => 'number',
            'input_attrs' => ['min' => 1, 'max' => 6, 'step' => 1],
        ]));

        $wp_customize->add_setting('dynamo_woocommerce_shop_products_per_page', [
            'default'           => $registry->get('woocommerce-shop-products-per-page') ?? '12',
            'sanitize_callback' => [$this, 'sanitize_products_per_page'],
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dynamo_woocommerce_shop_products_per_page', [
            'label'       => __('Products per page', 'dynamo'),
            'section'     => 'dynamo_woocommerce_shop_layout',
            'type'        => 'number',
            'input_attrs' => ['min' => 1, 'step' => 1],
        ]));

        // Future feature: Shop Style Switcher (grid / modern / list).
        // The grid layout is the only active style for v1.1.0; this stub will be
        // enabled when the modern card and list variants land. When unblocking,
        // also wire matching markup overrides in the WooCommerce template hooks.
        //
        // $wp_customize->add_setting('dynamo_woocommerce_shop_style', [
        //     'default'           => 'grid',
        //     'sanitize_callback' => 'sanitize_text_field',
        //     'transport'         => 'postMessage',
        // ]);
        // $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dynamo_woocommerce_shop_style', [
        //     'label'   => __('Shop Style', 'dynamo'),
        //     'section' => 'dynamo_woocommerce_shop_layout',
        //     'type'    => 'select',
        //     'choices' => [
        //         'grid'   => __('Grid', 'dynamo'),
        //         'modern' => __('Modern Card', 'dynamo'),
        //         'list'   => __('List', 'dynamo'),
        //     ],
        // ]));
    }

    public function sanitize_shop_columns(mixed $value): string {
        $int = (int) $value;
        if ($int < 1) {
            $int = 1;
        } elseif ($int > 6) {
            $int = 6;
        }
        return (string) $int;
    }

    public function sanitize_products_per_page(mixed $value): string {
        $int = (int) $value;
        return (string) max(1, $int);
    }

    public function filter_loop_shop_columns(mixed $default): int {
        $registry = new Dynamo_Token_Registry();
        $saved    = get_theme_mod('dynamo_woocommerce_shop_columns');
        if (false !== $saved && '' !== $saved) {
            return (int) $saved;
        }
        return (int) ($registry->get('woocommerce-shop-columns') ?? $default);
    }

    public function filter_loop_shop_per_page(mixed $default): int {
        $registry = new Dynamo_Token_Registry();
        $saved    = get_theme_mod('dynamo_woocommerce_shop_products_per_page');
        if (false !== $saved && '' !== $saved) {
            return (int) $saved;
        }
        return (int) ($registry->get('woocommerce-shop-products-per-page') ?? $default);
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
