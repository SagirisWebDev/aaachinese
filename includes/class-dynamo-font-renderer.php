<?php
declare(strict_types=1);

class Dynamo_Font_Renderer {

    private const CACHE_OPTION = 'dynamo_font_renderer_cache';

    private const FORMAT_MAP = [
        'woff2' => 'woff2',
        'woff'  => 'woff',
        'ttf'   => 'truetype',
        'otf'   => 'opentype',
    ];

    private Dynamo_Font_Manifest $manifest;
    private string $base_url;

    public function __construct(Dynamo_Font_Manifest $manifest, string $base_url) {
        $this->manifest = $manifest;
        $this->base_url = $base_url;
    }

    public function init(): void {
        add_action('wp_head', [$this, 'print_styles'], 5);
    }

    public function print_styles(): void {
        $css = $this->render();
        if ($css === '') {
            return;
        }
        echo '<style id="dynamo-font-face">' . $css . "</style>\n";
    }

    public function render(): string {
        $entries = $this->manifest->all();
        $hash    = sha1($this->base_url . '|' . json_encode($entries));

        $cached = get_option(self::CACHE_OPTION);
        if (is_array($cached) && ($cached['hash'] ?? null) === $hash && isset($cached['css'])) {
            return (string) $cached['css'];
        }

        $css = $this->build_css($entries);
        update_option(self::CACHE_OPTION, ['hash' => $hash, 'css' => $css]);
        return $css;
    }

    private function build_css(array $entries): string {
        $blocks = [];
        foreach ($entries as $entry) {
            $label = (string) ($entry['label'] ?? '');
            $faces = $entry['faces'] ?? [];
            if (!is_array($faces) || empty($faces) || $label === '') {
                continue;
            }
            foreach ($faces as $face) {
                $blocks[] = $this->build_face($label, (array) $face);
            }
        }
        return implode("\n", $blocks);
    }

    private function build_face(string $family, array $face): string {
        $file    = (string) ($face['file'] ?? '');
        $weight  = $face['weight'] ?? 400;
        $style   = (string) ($face['style'] ?? 'normal');
        $display = (string) ($face['display'] ?? 'swap');
        $format  = $this->infer_format($file);
        $url     = $this->base_url . $file;

        return "@font-face {\n"
            . "  font-family: \"{$family}\";\n"
            . "  src: url(\"{$url}\") format(\"{$format}\");\n"
            . "  font-weight: {$weight};\n"
            . "  font-style: {$style};\n"
            . "  font-display: {$display};\n"
            . "}";
    }

    private function infer_format(string $file): string {
        $ext = strtolower((string) pathinfo($file, PATHINFO_EXTENSION));
        return self::FORMAT_MAP[$ext] ?? 'woff2';
    }
}
