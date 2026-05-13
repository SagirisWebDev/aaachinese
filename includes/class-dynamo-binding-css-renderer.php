<?php
declare(strict_types=1);

class Dynamo_Binding_CSS_Renderer {

    private Dynamo_Binding_Registry $registry;

    public function __construct(Dynamo_Binding_Registry $registry) {
        $this->registry = $registry;
    }

    public function variable_lines(): array {
        $lines = [];
        foreach ($this->registry->all() as $binding) {
            $value = $this->resolve_value($binding);
            $lines[] = "  --dynamo-{$binding['id']}: {$value};";
        }
        return $lines;
    }

    public function rule_lines(): array {
        $lines = [];
        foreach ($this->registry->all() as $binding) {
            $lines[] = "{$binding['selector']} { {$binding['property']}: var(--dynamo-{$binding['id']}); }";
        }
        return $lines;
    }

    public function render(): string {
        $vars  = $this->variable_lines();
        $rules = $this->rule_lines();
        if (empty($vars) && empty($rules)) {
            return '';
        }
        $root = ":root {\n" . implode("\n", $vars) . "\n}";
        return $root . "\n" . implode("\n", $rules);
    }

    private function resolve_value(array $binding): string {
        $saved = get_theme_mod($binding['setting_id']);
        if ('' !== $saved && false !== $saved && null !== $saved) {
            $value = (string) $saved;
        } else {
            $value = (string) $binding['default'];
        }
        return self::apply_unit($value, $binding);
    }

    private static function apply_unit(string $value, array $binding): string {
        $unit = $binding['unit'] ?? '';
        if ('' === $unit || '' === $value) {
            return $value;
        }
        if (str_ends_with($value, $unit)) {
            return $value;
        }
        return $value . $unit;
    }
}
