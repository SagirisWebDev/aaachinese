<?php
declare(strict_types=1);

class Dynamo_Header_Customizer {

    public const JUSTIFY_CHOICES = [
        'flex-start'    => 'Flex Start',
        'center'        => 'Center',
        'flex-end'      => 'Flex End',
        'space-between' => 'Space Between',
        'space-around'  => 'Space Around',
        'space-evenly'  => 'Space Evenly',
    ];

    private Dynamo_Token_Registry $registry;

    public function __construct(Dynamo_Token_Registry $registry) {
        $this->registry = $registry;
    }

    public function init(): void {
        add_action('customize_register', [$this, 'register']);
        add_action('customize_controls_enqueue_scripts', [$this, 'enqueue_controls_assets']);
    }

    public function register(object $wp_customize): void {
        $wp_customize->add_panel('dynamo_header', [
            'title'    => __('Dynamo: Header', 'dynamo'),
            'priority' => 31.5,
        ]);

        $wp_customize->add_section('dynamo_header_section', [
            'title' => __('Header', 'dynamo'),
            'panel' => 'dynamo_header',
        ]);

        $wp_customize->add_setting('dynamo_header_menu_cart', [
            'default'           => $this->registry->get('header-menu-cart') ?? 'flex-end',
            'sanitize_callback' => [$this, 'sanitize_justify'],
            'transport'         => 'postMessage',
        ]);

        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dynamo_header_menu_cart', [
            'label'       => __('Menu / Cart alignment (desktop)', 'dynamo'),
            'description' => __('Controls the justify-content of the flex wrapper that holds the primary menu and the header cart icon. Applies on viewports wider than 921px only.', 'dynamo'),
            'section'     => 'dynamo_header_section',
            'type'        => 'select',
            'choices'     => self::JUSTIFY_CHOICES,
        ]));
    }

    public function sanitize_justify(mixed $value): string {
        $value = is_string($value) ? $value : '';
        return array_key_exists($value, self::JUSTIFY_CHOICES) ? $value : 'flex-end';
    }

    public function enqueue_controls_assets(): void {
        wp_enqueue_script(
            'dynamo-header-customizer-controls',
            DYNAMO_URL . 'assets/js/header-customizer-controls.js',
            ['customize-controls'],
            DYNAMO_VERSION,
            true
        );
    }
}
