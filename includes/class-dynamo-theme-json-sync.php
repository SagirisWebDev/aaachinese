<?php
declare(strict_types=1);

class Dynamo_Theme_JSON_Sync {

    private const SLUG_PREFIX = 'dynamo-';

    private static array $colour_map = [
        'primary'     => 'colors-primary',
        'secondary'   => 'colors-secondary',
        'accent'      => 'colors-accent',
        'background'  => 'colors-background',
        'text'        => 'colors-text',
        'link'        => 'colors-link',
        'section-alt' => 'colors-section-alt',
    ];

    public function __construct(private readonly Dynamo_Token_Registry $registry) {}

    public function init(): void {
        add_filter('wp_theme_json_data_theme', [$this, 'inject'], 10, 1);
    }

    public function inject(WP_Theme_JSON_Data $theme_json): WP_Theme_JSON_Data {
        $data = $theme_json->get_data();
        $data = $this->inject_colours($data);
        $data = $this->inject_typography($data);
        $theme_json->update_with($data);
        return $theme_json;
    }

    private function inject_colours(array $data): array {
        // get_data() returns WP's internal origin-keyed format; theme-file palette is under ['theme'].
        $palette = $data['settings']['color']['palette'] ?? [];
        $existing = $palette['theme'] ?? (isset($palette[0]) ? $palette : []);
        $existing = $this->strip_dynamo_entries($existing);

        foreach (self::$colour_map as $suffix => $token) {
            $existing[] = [
                'slug'  => self::SLUG_PREFIX . $suffix,
                'color' => $this->registry->get($token) ?? '',
                'name'  => ucwords(str_replace('-', ' ', $suffix)),
            ];
        }

        $data['settings']['color']['palette'] = $existing;
        return $data;
    }

    private function inject_typography(array $data): array {
        $raw_families = $data['settings']['typography']['fontFamilies'] ?? [];
        $families = $raw_families['theme'] ?? (isset($raw_families[0]) ? $raw_families : []);
        $families = $this->strip_dynamo_entries($families);
        $families[] = [
            'slug'       => self::SLUG_PREFIX . 'body',
            'fontFamily' => $this->registry->get('typography-body-font-family') ?? '',
            'name'       => 'Body',
        ];
        $data['settings']['typography']['fontFamilies'] = $families;

        $raw_sizes = $data['settings']['typography']['fontSizes'] ?? [];
        $sizes = $raw_sizes['theme'] ?? (isset($raw_sizes[0]) ? $raw_sizes : []);
        $sizes = $this->strip_dynamo_entries($sizes);
        $sizes[] = [
            'slug' => self::SLUG_PREFIX . 'body',
            'size' => $this->registry->get('typography-body-font-size') ?? '',
            'name' => 'Body',
        ];
        $data['settings']['typography']['fontSizes'] = $sizes;

        return $data;
    }

    private function strip_dynamo_entries(array $entries): array {
        return array_values(
            array_filter($entries, fn($e) => !str_starts_with($e['slug'] ?? '', self::SLUG_PREFIX))
        );
    }
}
