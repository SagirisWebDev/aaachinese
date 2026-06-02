/**
 * Issue #38 — PRD v1.3.0 Slice 5: Mutual exclusion with native block controls
 *
 * Jest unit tests covering the SAVE-TIME SUPPRESSION logic:
 *
 *   AC3 — When a native control value is present (contentSize, wideSize, or
 *          style.border.radius), the blocks.getSaveContent.extraProps filter
 *          must NOT write the Dynamo CSS variable — even if dynamoWidth /
 *          dynamoRadius is also set.
 *
 *   AC5 — Detection rule: undefined, '', and 0 are treated as "unset" (no
 *          suppression). Any other truthy string value IS set (suppression
 *          active).
 *
 *   Attribute preservation (AC3) — Suppression is a save-time concern only.
 *          The blocks.registerBlockType filter must still store dynamoWidth /
 *          dynamoRadius normally. The raw attribute value is preserved even
 *          when save-time output is suppressed.
 *
 * NOTE: The editor HOC (disabled state, note text, re-enable) CANNOT be unit
 *       tested here because it lives behind the
 *       `if (wp.compose && wp.blockEditor && ...)` guard and requires a real
 *       browser DOM. Those cases are covered by Playwright in
 *       tests/frontend/ui/mutual-exclusion.spec.js.
 *
 * RED PHASE — These tests MUST FAIL in the current codebase because the
 * save-time suppression logic does not yet exist in src/editor/token-presets.js.
 */

// ---------------------------------------------------------------------------
// WordPress block editor globals stub — mirrors existing test files exactly
// ---------------------------------------------------------------------------

const registeredFilters = {};

function addFilter(hookName, namespace, callback, priority = 10) {
    if (!registeredFilters[hookName]) {
        registeredFilters[hookName] = [];
    }
    registeredFilters[hookName].push({ namespace, callback, priority });
}

function applyFilters(hookName, value, ...args) {
    const filters = (registeredFilters[hookName] || [])
        .slice()
        .sort((a, b) => a.priority - b.priority);
    return filters.reduce((acc, { callback }) => callback(acc, ...args), value);
}

const blockTypeRegistry = {};

function registerBlockType(name, settings) {
    blockTypeRegistry[name] = settings;
    return settings;
}

function getBlockType(name) {
    return blockTypeRegistry[name] || null;
}

beforeAll(() => {
    global.wp = {
        hooks: { addFilter, applyFilters },
        blocks: { getBlockType, registerBlockType },
    };
});

beforeEach(() => {
    Object.keys(registeredFilters).forEach((key) => {
        delete registeredFilters[key];
    });
    Object.keys(blockTypeRegistry).forEach((key) => {
        delete blockTypeRegistry[key];
    });
    jest.resetModules();
});

afterAll(() => {
    delete global.wp;
});

// ---------------------------------------------------------------------------
// Load the module under test
// ---------------------------------------------------------------------------

function loadModule() {
    return require('../../src/editor/token-presets');
}

// ---------------------------------------------------------------------------
// Helper: normalise a style prop (React object or CSS string) for uniform assertions
// ---------------------------------------------------------------------------

function styleContains(style, substring) {
    if (!style) return false;
    if (typeof style === 'string') return style.includes(substring);
    return JSON.stringify(style).includes(substring);
}

// ---------------------------------------------------------------------------
// AC3 — Save-time suppression: Width
//
// When layout.contentSize or layout.wideSize is present, the extraProps filter
// must NOT write maxWidth even when dynamoWidth is set.
// ---------------------------------------------------------------------------

describe('AC3 — save-time suppression: Width preset', () => {
    test('filter suppresses maxWidth when layout.contentSize is set alongside dynamoWidth', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { contentSize: '640px' },
            }
        );

        // The Dynamo CSS variable must NOT appear in the saved output
        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(false);
        // maxWidth must be absent or not set to the Dynamo variable
        if (extraProps.style && typeof extraProps.style === 'object') {
            const maxWidth = extraProps.style.maxWidth ?? extraProps.style['max-width'];
            expect(maxWidth ?? '').not.toContain('dynamo');
        }
    });

    test('filter suppresses maxWidth when layout.wideSize is set alongside dynamoWidth', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { wideSize: '1200px' },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(false);
    });

    test('filter does NOT suppress maxWidth when layout.contentSize is undefined', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { contentSize: undefined },
            }
        );

        // No native override — Dynamo CSS variable SHOULD be present
        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(true);
    });

    test('filter does NOT suppress maxWidth when layout.contentSize is empty string', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { contentSize: '' },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(true);
    });

    test('filter does NOT suppress maxWidth when layout.contentSize is 0', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { contentSize: 0 },
            }
        );

        // 0 is "unset" per detection rule — Dynamo CSS variable SHOULD be present
        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(true);
    });

    test('filter does NOT suppress maxWidth when layout attribute is absent entirely', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            { dynamoWidth: 'narrow' }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(true);
    });

    test('suppression is active when layout.contentSize is a real measurement string', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'wide',
                layout: { contentSize: '800px' },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-wide')).toBe(false);
    });

    test('suppression is active when layout.wideSize is a real measurement string', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'full',
                layout: { wideSize: '1400px' },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-full')).toBe(false);
    });

    test('suppression preserves other extraProps (does not strip className etc.)', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            { className: 'my-group' },
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { contentSize: '640px' },
            }
        );

        expect(extraProps.className).toBe('my-group');
    });
});

// ---------------------------------------------------------------------------
// AC3 — Save-time suppression: Radius preset
//
// When style.border.radius is present, the extraProps filter must NOT write
// borderRadius even when dynamoRadius is set.
// ---------------------------------------------------------------------------

describe('AC3 — save-time suppression: Radius preset', () => {
    test('filter suppresses borderRadius when style.border.radius is set alongside dynamoRadius', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: { border: { radius: '8px' } },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(false);
        if (extraProps.style && typeof extraProps.style === 'object') {
            const borderRadius = extraProps.style.borderRadius ?? extraProps.style['border-radius'];
            expect(borderRadius ?? '').not.toContain('dynamo');
        }
    });

    test('filter does NOT suppress borderRadius when style.border.radius is undefined', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: { border: { radius: undefined } },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });

    test('filter does NOT suppress borderRadius when style.border.radius is empty string', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: { border: { radius: '' } },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });

    test('filter does NOT suppress borderRadius when style.border.radius is 0', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: { border: { radius: 0 } },
            }
        );

        // 0 is "unset" per detection rule
        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });

    test('filter does NOT suppress borderRadius when style attribute is absent entirely', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            { dynamoRadius: 'lg' }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });

    test('filter does NOT suppress borderRadius when style.border is absent', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: {},
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });

    test('suppression is active when style.border.radius is a real pixel value', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'sm',
                style: { border: { radius: '4px' } },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-sm')).toBe(false);
    });

    test('suppression preserves other extraProps (does not strip className etc.)', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            { className: 'my-image' },
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: { border: { radius: '8px' } },
            }
        );

        expect(extraProps.className).toBe('my-image');
    });
});

// ---------------------------------------------------------------------------
// AC3 — Dynamo attribute preservation (registerBlockType filter)
//
// The registerBlockType filter must store dynamoWidth / dynamoRadius normally
// regardless of whether a native value is present. Suppression is a save-time
// concern only — the attribute value must survive in the block data.
// ---------------------------------------------------------------------------

describe('AC3 — Dynamo attribute preservation via registerBlockType filter', () => {
    test('dynamoWidth attribute is registered on layout-supporting blocks normally', () => {
        loadModule();

        const settings = {
            name: 'core/group',
            supports: { layout: true },
            attributes: {},
        };

        const filtered = applyFilters('blocks.registerBlockType', settings, 'core/group');

        // The attribute must always be registered — suppression does not affect registration
        expect(filtered.attributes).toHaveProperty('dynamoWidth');
        expect(filtered.attributes.dynamoWidth.type).toBe('string');
    });

    test('dynamoRadius attribute is registered on borders.radius-supporting blocks normally', () => {
        loadModule();

        const settings = {
            name: 'core/image',
            supports: { borders: { radius: true } },
            attributes: {},
        };

        const filtered = applyFilters('blocks.registerBlockType', settings, 'core/image');

        expect(filtered.attributes).toHaveProperty('dynamoRadius');
        expect(filtered.attributes.dynamoRadius.type).toBe('string');
    });

    test('setting dynamoWidth and then passing layout.contentSize: registration is unchanged', () => {
        loadModule();

        // Registration does not look at per-instance attribute values —
        // this verifies the filter schema is not accidentally altered by
        // the presence of native attribute paths in the settings object.
        const settings = {
            name: 'core/group',
            supports: { layout: true },
            attributes: {
                layout: { type: 'object' },  // layout is a schema attribute
            },
        };

        const filtered = applyFilters('blocks.registerBlockType', settings, 'core/group');

        expect(filtered.attributes).toHaveProperty('dynamoWidth');
        expect(filtered.attributes).toHaveProperty('layout');
    });
});

// ---------------------------------------------------------------------------
// AC5 — Detection rule edge cases: explicit boundary testing
//
// undefined, '', and 0 all mean "unset" → no suppression
// Any other truthy value means "set" → suppression active
// ---------------------------------------------------------------------------

describe('AC5 — Detection rule edge cases', () => {
    // Width: contentSize
    test.each([
        ['undefined',     undefined],
        ['empty string',  ''],
        ['zero',          0],
    ])('Width: contentSize = %s → NO suppression (Dynamo var written)', (_, contentSizeValue) => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { contentSize: contentSizeValue },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(true);
    });

    test.each([
        ['640px'],
        ['100%'],
        ['var(--some-token)'],
        ['1'],
    ])('Width: contentSize = "%s" → suppression ACTIVE (Dynamo var NOT written)', (contentSizeValue) => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                layout: { contentSize: contentSizeValue },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(false);
    });

    // Width: wideSize
    test.each([
        ['undefined',     undefined],
        ['empty string',  ''],
        ['zero',          0],
    ])('Width: wideSize = %s → NO suppression (Dynamo var written)', (_, wideSizeValue) => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'wide',
                layout: { wideSize: wideSizeValue },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-wide')).toBe(true);
    });

    test.each([
        ['1200px'],
        ['80vw'],
    ])('Width: wideSize = "%s" → suppression ACTIVE (Dynamo var NOT written)', (wideSizeValue) => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'wide',
                layout: { wideSize: wideSizeValue },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-wide')).toBe(false);
    });

    // Radius: style.border.radius
    test.each([
        ['undefined',     undefined],
        ['empty string',  ''],
        ['zero',          0],
    ])('Radius: style.border.radius = %s → NO suppression (Dynamo var written)', (_, radiusValue) => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: { border: { radius: radiusValue } },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });

    test.each([
        ['8px'],
        ['50%'],
        ['1rem'],
    ])('Radius: style.border.radius = "%s" → suppression ACTIVE (Dynamo var NOT written)', (radiusValue) => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoRadius: 'lg',
                style: { border: { radius: radiusValue } },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(false);
    });
});

// ---------------------------------------------------------------------------
// AC3 — Both Dynamo presets set alongside native overrides: correct selective suppression
// ---------------------------------------------------------------------------

describe('AC3 — Selective suppression when both Dynamo presets are set', () => {
    test('only Width is suppressed when only contentSize is native; Radius still written', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                dynamoRadius: 'lg',
                layout: { contentSize: '640px' },
                // no native radius
            }
        );

        // Width Dynamo var must be suppressed
        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(false);
        // Radius Dynamo var must still be written
        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });

    test('only Radius is suppressed when only style.border.radius is native; Width still written', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/image' },
            {
                dynamoWidth: 'narrow',
                dynamoRadius: 'lg',
                style: { border: { radius: '8px' } },
                // no native width
            }
        );

        // Radius Dynamo var must be suppressed
        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(false);
        // Width Dynamo var must still be written
        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(true);
    });

    test('both are suppressed when both native values are present', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                dynamoRadius: 'lg',
                layout: { contentSize: '640px' },
                style: { border: { radius: '8px' } },
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(false);
        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(false);
    });

    test('neither is suppressed when no native values are present', () => {
        loadModule();

        const extraProps = applyFilters(
            'blocks.getSaveContent.extraProps',
            {},
            { name: 'core/group' },
            {
                dynamoWidth: 'narrow',
                dynamoRadius: 'lg',
            }
        );

        expect(styleContains(extraProps.style, '--dynamo-layout-width-narrow')).toBe(true);
        expect(styleContains(extraProps.style, '--dynamo-borders-radius-lg')).toBe(true);
    });
});

// ---------------------------------------------------------------------------
// Approved note copy (AC4) — strings exist in the module exports
//
// The HOC cannot run in Jest, but we can verify the note copy constants are
// exported from the module so they can be audited without a browser.
// ---------------------------------------------------------------------------

describe('AC4 — Approved note copy exported from module', () => {
    test('module exports the approved Width note copy', () => {
        const mod = loadModule();

        // The implementation is expected to export these strings as named constants
        const widthNote =
            mod.WIDTH_NATIVE_NOTE ??
            mod.widthNativeNote ??
            mod.NATIVE_WIDTH_NOTE ??
            mod.nativeWidthNote;

        expect(widthNote).toBe(
            'Native width is set above. Clear it to use a Dynamo preset.'
        );
    });

    test('module exports the approved Radius note copy', () => {
        const mod = loadModule();

        const radiusNote =
            mod.RADIUS_NATIVE_NOTE ??
            mod.radiusNativeNote ??
            mod.NATIVE_RADIUS_NOTE ??
            mod.nativeRadiusNote;

        expect(radiusNote).toBe(
            'Native radius is set above. Clear it to use a Dynamo preset.'
        );
    });
});
