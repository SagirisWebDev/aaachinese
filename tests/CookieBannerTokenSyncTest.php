<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * TDD Red Phase — Issue #27: Banner Token Sync
 *
 * These tests are written BEFORE the implementation exists and are expected
 * to fail until the production code satisfies all acceptance criteria.
 *
 * Acceptance criteria covered:
 *
 *  AC1 — Both drivers implement register_palette_sync_hooks() and register
 *         the correct plugin-specific WP hook:
 *           • Complianz: cmplz_banner_css
 *           • Borlabs:   borlabsCookie/styleBuilder/modifyCss
 *
 *  AC2 — The default token map injects exactly five CSS custom properties:
 *           --cookie-primary, --cookie-background, --cookie-text,
 *           --cookie-link, --cookie-font-family
 *         Each must be sourced from the corresponding Token Registry value.
 *
 *  AC3 — A dynamo_cookie_banner_tokens filter allows developers to add,
 *         remove, or remap entries in the token map.
 *
 *  AC4 — Covered implicitly: the drivers read live values from
 *         Dynamo_Token_Registry on every hook invocation, so a Customizer
 *         change propagates on the next page load without additional tests.
 *
 *  AC5 — Unit tests assert that each driver registers its hook (AC1) and
 *         that the default token map produces correct CSS custom property
 *         output (AC2). Both are covered here.
 */
class CookieBannerTokenSyncTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Test lifecycle
    // -----------------------------------------------------------------------

    protected function setUp(): void
    {
        $GLOBALS['wp_filter']          = [];
        $GLOBALS['wp_theme_supports']  = [];
        $GLOBALS['wp_removed_actions'] = [];
        $GLOBALS['wp_doing_it_wrong']  = [];

        $this->loadCookieFiles();
    }

    protected function tearDown(): void
    {
        // Remove any dynamo_cookie_banner_tokens filters added during a test
        // so they do not leak into subsequent test methods.
        unset($GLOBALS['wp_filter']['dynamo_cookie_banner_tokens']);
        unset($GLOBALS['wp_filter']['dynamo_token_defaults']);
    }

    // -----------------------------------------------------------------------
    // AC1 — Complianz driver registers the correct hook
    // -----------------------------------------------------------------------

    /** @test */
    public function complianz_driver_register_palette_sync_hooks_adds_cmplz_banner_css_action(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $this->assertArrayHasKey(
            'cmplz_banner_css',
            $GLOBALS['wp_filter'],
            'Complianz driver must register a callback on the cmplz_banner_css hook.'
        );

        $registeredCallbacks = array_merge(...array_values($GLOBALS['wp_filter']['cmplz_banner_css']));
        $this->assertNotEmpty(
            $registeredCallbacks,
            'At least one callback must be registered on cmplz_banner_css.'
        );
    }

    /** @test */
    public function complianz_driver_does_not_register_wrong_hook(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        // The stub registers cmplz_after_consent — that must be gone.
        // The real hook is cmplz_banner_css.
        $this->assertArrayNotHasKey(
            'cmplz_after_consent',
            $GLOBALS['wp_filter'],
            'Complianz driver must NOT register on cmplz_after_consent (old stub hook).'
        );
    }

    // -----------------------------------------------------------------------
    // AC1 — Borlabs driver registers the correct hook
    // -----------------------------------------------------------------------

    /** @test */
    public function borlabs_driver_register_palette_sync_hooks_adds_borlabs_style_builder_action(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $this->assertArrayHasKey(
            'borlabsCookie/styleBuilder/modifyCss',
            $GLOBALS['wp_filter'],
            'Borlabs driver must register a callback on the borlabsCookie/styleBuilder/modifyCss hook.'
        );

        $registeredCallbacks = array_merge(...array_values($GLOBALS['wp_filter']['borlabsCookie/styleBuilder/modifyCss']));
        $this->assertNotEmpty(
            $registeredCallbacks,
            'At least one callback must be registered on borlabsCookie/styleBuilder/modifyCss.'
        );
    }

    /** @test */
    public function borlabs_driver_does_not_register_wrong_hook(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        // The stub registers borlabs_cookie_consent_updated — that must be gone.
        $this->assertArrayNotHasKey(
            'borlabs_cookie_consent_updated',
            $GLOBALS['wp_filter'],
            'Borlabs driver must NOT register on borlabs_cookie_consent_updated (old stub hook).'
        );
    }

    // -----------------------------------------------------------------------
    // AC2 — Complianz callback injects all 5 CSS custom properties
    // -----------------------------------------------------------------------

    /** @test */
    public function complianz_callback_appends_cookie_primary_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-primary'); // '#3b82f6'

        $this->assertStringContainsString(
            '--cookie-primary',
            $css,
            'Complianz callback must inject the --cookie-primary custom property.'
        );
        $this->assertStringContainsString(
            $expected,
            $css,
            "--cookie-primary must equal the Token Registry value for 'colors-primary' ({$expected})."
        );
    }

    /** @test */
    public function complianz_callback_appends_cookie_background_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-background'); // '#ffffff'

        $this->assertStringContainsString('--cookie-background', $css);
        $this->assertStringContainsString(
            $expected,
            $css,
            "--cookie-background must equal the Token Registry value for 'colors-background' ({$expected})."
        );
    }

    /** @test */
    public function complianz_callback_appends_cookie_text_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-text'); // '#111827'

        $this->assertStringContainsString('--cookie-text', $css);
        $this->assertStringContainsString(
            $expected,
            $css,
            "--cookie-text must equal the Token Registry value for 'colors-text' ({$expected})."
        );
    }

    /** @test */
    public function complianz_callback_appends_cookie_link_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-link'); // '#2563eb'

        $this->assertStringContainsString('--cookie-link', $css);
        $this->assertStringContainsString(
            $expected,
            $css,
            "--cookie-link must equal the Token Registry value for 'colors-link' ({$expected})."
        );
    }

    /** @test */
    public function complianz_callback_appends_cookie_font_family_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('typography-body-font-family'); // 'system-sans'

        $this->assertStringContainsString('--cookie-font-family', $css);
        $this->assertStringContainsString(
            $expected,
            $css,
            "--cookie-font-family must equal the Token Registry value for 'typography-body-font-family' ({$expected})."
        );
    }

    /** @test */
    public function complianz_callback_preserves_existing_css_and_appends_properties(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $existing = '.cmplz-banner { color: red; }';
        $css       = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', $existing);

        $this->assertStringContainsString(
            $existing,
            $css,
            'Complianz callback must preserve existing CSS passed into the filter.'
        );
    }

    // -----------------------------------------------------------------------
    // AC2 — Borlabs callback injects all 5 CSS custom properties
    // -----------------------------------------------------------------------

    /** @test */
    public function borlabs_callback_appends_cookie_primary_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-primary');

        $this->assertStringContainsString('--cookie-primary', $css);
        $this->assertStringContainsString(
            $expected,
            $css,
            "--cookie-primary must equal the Token Registry value for 'colors-primary' ({$expected})."
        );
    }

    /** @test */
    public function borlabs_callback_appends_cookie_background_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-background');

        $this->assertStringContainsString('--cookie-background', $css);
        $this->assertStringContainsString($expected, $css);
    }

    /** @test */
    public function borlabs_callback_appends_cookie_text_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-text');

        $this->assertStringContainsString('--cookie-text', $css);
        $this->assertStringContainsString($expected, $css);
    }

    /** @test */
    public function borlabs_callback_appends_cookie_link_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-link');

        $this->assertStringContainsString('--cookie-link', $css);
        $this->assertStringContainsString($expected, $css);
    }

    /** @test */
    public function borlabs_callback_appends_cookie_font_family_from_token_registry(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('typography-body-font-family');

        $this->assertStringContainsString('--cookie-font-family', $css);
        $this->assertStringContainsString($expected, $css);
    }

    /** @test */
    public function borlabs_callback_preserves_existing_css_and_appends_properties(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $existing = '.BorlabsCookie { color: blue; }';
        $css       = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', $existing);

        $this->assertStringContainsString(
            $existing,
            $css,
            'Borlabs callback must preserve existing CSS passed into the filter.'
        );
    }

    // -----------------------------------------------------------------------
    // AC3 — dynamo_cookie_banner_tokens filter: add a custom token
    // -----------------------------------------------------------------------

    /** @test */
    public function complianz_callback_respects_filter_adding_new_token(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        add_filter('dynamo_cookie_banner_tokens', static function (array $map): array {
            $map['--cookie-accent'] = 'colors-accent';
            return $map;
        });

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-accent'); // '#f59e0b'

        $this->assertStringContainsString(
            '--cookie-accent',
            $css,
            'Complianz callback must include custom properties added via dynamo_cookie_banner_tokens filter.'
        );
        $this->assertStringContainsString(
            $expected,
            $css,
            "--cookie-accent must equal the Token Registry value for 'colors-accent' ({$expected})."
        );
    }

    /** @test */
    public function complianz_callback_respects_filter_removing_a_token(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        add_filter('dynamo_cookie_banner_tokens', static function (array $map): array {
            unset($map['--cookie-font-family']);
            return $map;
        });

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $this->assertStringNotContainsString(
            '--cookie-font-family',
            $css,
            'Complianz callback must NOT include --cookie-font-family when it is removed via the filter.'
        );
    }

    /** @test */
    public function complianz_callback_respects_filter_remapping_a_token(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        // Remap --cookie-primary to read from colors-secondary instead.
        add_filter('dynamo_cookie_banner_tokens', static function (array $map): array {
            $map['--cookie-primary'] = 'colors-secondary';
            return $map;
        });

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $registry         = new Dynamo_Token_Registry();
        $remappedValue    = $registry->get('colors-secondary'); // '#6b7280'
        $originalValue    = $registry->get('colors-primary');   // '#3b82f6'

        $this->assertStringContainsString(
            $remappedValue,
            $css,
            "--cookie-primary must equal the remapped Token Registry value '{$remappedValue}'."
        );
        $this->assertStringNotContainsString(
            $originalValue,
            $css,
            "--cookie-primary must NOT equal the original value '{$originalValue}' after remapping."
        );
    }

    /** @test */
    public function borlabs_callback_respects_filter_adding_new_token(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        add_filter('dynamo_cookie_banner_tokens', static function (array $map): array {
            $map['--cookie-accent'] = 'colors-accent';
            return $map;
        });

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $registry = new Dynamo_Token_Registry();
        $expected  = $registry->get('colors-accent');

        $this->assertStringContainsString('--cookie-accent', $css);
        $this->assertStringContainsString($expected, $css);
    }

    /** @test */
    public function borlabs_callback_respects_filter_removing_a_token(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        add_filter('dynamo_cookie_banner_tokens', static function (array $map): array {
            unset($map['--cookie-font-family']);
            return $map;
        });

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $this->assertStringNotContainsString(
            '--cookie-font-family',
            $css,
            'Borlabs callback must NOT include --cookie-font-family when removed via the filter.'
        );
    }

    // -----------------------------------------------------------------------
    // AC4 — Changing Token Registry value propagates to the CSS output
    // (covered implicitly by live registry reads; this test makes it explicit)
    // -----------------------------------------------------------------------

    /** @test */
    public function complianz_callback_reflects_customizer_change_to_primary_color(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Complianz');

        // Simulate a Customizer change by overriding the token via the WP filter.
        add_filter('dynamo_token_defaults', static function (array $defaults): array {
            $defaults['colors-primary'] = '#ff0000';
            return $defaults;
        });

        $driver = new Dynamo_Cookie_Driver_Complianz();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('cmplz_banner_css', '');

        $this->assertStringContainsString(
            '#ff0000',
            $css,
            '--cookie-primary must reflect the updated Customizer value (#ff0000) read from Token Registry.'
        );
        $this->assertStringNotContainsString(
            '#3b82f6',
            $css,
            '--cookie-primary must NOT retain the original default value (#3b82f6) after a Customizer change.'
        );
    }

    /** @test */
    public function borlabs_callback_reflects_customizer_change_to_primary_color(): void
    {
        $this->assertClassExists('Dynamo_Cookie_Driver_Borlabs');

        add_filter('dynamo_token_defaults', static function (array $defaults): array {
            $defaults['colors-primary'] = '#00ff00';
            return $defaults;
        });

        $driver = new Dynamo_Cookie_Driver_Borlabs();
        $driver->register_palette_sync_hooks();

        $css = $this->invokeFirstCallbackOnFilter('borlabsCookie/styleBuilder/modifyCss', '');

        $this->assertStringContainsString(
            '#00ff00',
            $css,
            '--cookie-primary must reflect the updated Customizer value (#00ff00) read from Token Registry.'
        );
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    /**
     * Soft-load the cookie driver files. During the red phase these files
     * exist but contain stubs; tests will fail at the assertion level rather
     * than with a fatal require error.
     */
    private function loadCookieFiles(): void
    {
        $files = [
            DYNAMO_PATH . 'includes/cookie/interface-dynamo-cookie-driver.php',
            DYNAMO_PATH . 'includes/cookie/class-dynamo-cookie-driver-complianz.php',
            DYNAMO_PATH . 'includes/cookie/class-dynamo-cookie-driver-borlabs.php',
            DYNAMO_PATH . 'includes/cookie/class-dynamo-cookie-integration.php',
        ];
        foreach ($files as $file) {
            if (is_file($file)) {
                require_once $file;
            }
        }
    }

    /**
     * Assert a class exists, producing a clear failure message if it does not.
     */
    private function assertClassExists(string $className): void
    {
        $this->assertTrue(
            class_exists($className),
            "Class {$className} must exist. Ensure the cookie driver files are loaded."
        );
    }

    /**
     * Invoke every registered callback for $hookName in priority order,
     * starting with $initialValue, and return the final filtered value.
     * This mirrors how apply_filters() works in the stub bootstrap.
     */
    private function invokeFirstCallbackOnFilter(string $hookName, string $initialValue): string
    {
        $this->assertArrayHasKey(
            $hookName,
            $GLOBALS['wp_filter'],
            "Expected hook '{$hookName}' to be registered, but it was not found in \$wp_filter."
        );

        $value = $initialValue;
        ksort($GLOBALS['wp_filter'][$hookName]);
        foreach ($GLOBALS['wp_filter'][$hookName] as $callbacks) {
            foreach ($callbacks as $callback) {
                $value = $callback($value);
            }
        }
        return (string) $value;
    }
}
