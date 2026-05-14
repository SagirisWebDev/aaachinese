<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class FontRendererTest extends TestCase {

    private const BASE_URL = 'http://example.test/wp-content/themes/dynamo/fonts/';

    protected function setUp(): void {
        $GLOBALS['wp_options']             = [];
        $GLOBALS['wp_filter']              = [];
        $GLOBALS['wp_update_option_calls'] = [];
    }

    private function fixture(string $name): string {
        return __DIR__ . "/fixtures/font-manifest/{$name}.json";
    }

    private function makeRenderer(string $fixture): Dynamo_Font_Renderer {
        $manifest = new Dynamo_Font_Manifest($this->fixture($fixture));
        return new Dynamo_Font_Renderer($manifest, self::BASE_URL);
    }

    public function test_render_emits_font_face_for_single_face_entry(): void {
        $css = $this->makeRenderer('single-face')->render();
        $this->assertStringContainsString('@font-face', $css);
        $this->assertStringContainsString('font-family: "Inter";', $css);
        $this->assertStringContainsString('src: url("' . self::BASE_URL . 'inter/Inter-Regular.woff2") format("woff2");', $css);
        $this->assertStringContainsString('font-weight: 400;', $css);
        $this->assertStringContainsString('font-style: normal;', $css);
        $this->assertStringContainsString('font-display: swap;', $css);
    }

    public function test_render_emits_one_font_face_per_face_in_declaration_order(): void {
        // valid.json has Inter with Regular (400) and Bold (700) in that order.
        $css = $this->makeRenderer('valid')->render();
        $this->assertSame(2, substr_count($css, '@font-face'));
        $regular_pos = strpos($css, 'Inter-Regular.woff2');
        $bold_pos    = strpos($css, 'Inter-Bold.woff2');
        $this->assertNotFalse($regular_pos);
        $this->assertNotFalse($bold_pos);
        $this->assertLessThan($bold_pos, $regular_pos, 'Regular face should appear before Bold');
        $this->assertStringContainsString('font-weight: 700;', $css);
    }

    public function test_render_honours_italic_style(): void {
        $css = $this->makeRenderer('italic-face')->render();
        $this->assertStringContainsString('font-style: italic;', $css);
    }

    public function test_render_emits_variable_weight_range_verbatim(): void {
        $css = $this->makeRenderer('variable-weight')->render();
        $this->assertStringContainsString('font-weight: 100 900;', $css);
    }

    public function test_render_honours_per_face_display_override(): void {
        $css = $this->makeRenderer('display-override')->render();
        $this->assertStringContainsString('font-display: block;', $css);
        $this->assertStringNotContainsString('font-display: swap;', $css);
    }

    public function test_render_omits_fontless_entries(): void {
        // valid.json: system-sans has empty faces; should produce no @font-face.
        $css = $this->makeRenderer('valid')->render();
        $this->assertStringNotContainsString('System Sans', $css);
        $this->assertStringNotContainsString('system-sans', $css);
    }

    public function test_second_render_with_unchanged_manifest_uses_cache(): void {
        $renderer = $this->makeRenderer('single-face');
        $first    = $renderer->render();
        $writes_after_first = count($GLOBALS['wp_update_option_calls']);
        $second   = $renderer->render();
        $this->assertSame($first, $second);
        $this->assertSame($writes_after_first, count($GLOBALS['wp_update_option_calls']),
            'Second render should hit the cache and not write the option again');
    }

    public function test_manifest_content_change_invalidates_cache(): void {
        // Render with single-face manifest → caches its CSS.
        $this->makeRenderer('single-face')->render();
        $writes_after_first = count($GLOBALS['wp_update_option_calls']);

        // Different manifest with different content → different hash → re-render.
        $second_css = $this->makeRenderer('italic-face')->render();
        $this->assertStringContainsString('Inter-Italic.woff2', $second_css);
        $this->assertGreaterThan($writes_after_first, count($GLOBALS['wp_update_option_calls']),
            'Different manifest hash should regenerate and re-write the option');
    }

    public function test_init_registers_wp_head_action_before_dynamic_css(): void {
        $this->makeRenderer('single-face')->init();
        $this->assertArrayHasKey('wp_head', $GLOBALS['wp_filter']);
        // Ensure at least one registered callback sits at a priority earlier
        // than Dynamo_CSS_Output's default priority of 10.
        $priorities = array_keys($GLOBALS['wp_filter']['wp_head']);
        $this->assertNotEmpty(array_filter($priorities, fn($p) => $p < 10),
            'Font Renderer should hook wp_head at a priority earlier than 10');
    }

    public function test_print_styles_outputs_inline_style_tag_with_font_face(): void {
        $renderer = $this->makeRenderer('single-face');
        ob_start();
        $renderer->print_styles();
        $output = ob_get_clean();
        $this->assertStringContainsString('<style id="dynamo-font-face">', $output);
        $this->assertStringContainsString('@font-face', $output);
        $this->assertStringContainsString('</style>', $output);
    }

    public function test_print_styles_outputs_nothing_for_empty_render(): void {
        // valid.json's manifest minus face-bearing entries: build a manifest with
        // only fontless entries by using a fontless-only fixture.
        $renderer = $this->makeRenderer('fontless-only');
        ob_start();
        $renderer->print_styles();
        $output = ob_get_clean();
        $this->assertSame('', $output);
    }
}
