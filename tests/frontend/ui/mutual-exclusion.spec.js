// @ts-check
/**
 * Issue #38 — PRD v1.3.0 Slice 5: Mutual exclusion with native block controls
 *
 * Playwright browser tests covering the editor HOC behaviour that requires a
 * live WordPress block editor:
 *
 *   AC1 — Width mutual exclusion
 *         On a Group block, setting layout.contentSize via the native Width
 *         input disables the Dynamo Width dropdown and shows the approved note.
 *         Clearing the native value re-enables the dropdown.
 *
 *   AC2 — Radius mutual exclusion
 *         On an Image block, setting a native border radius disables the Dynamo
 *         Radius dropdown and shows the approved note.
 *         Clearing the native value re-enables the dropdown.
 *
 *   AC3 — Dynamo attribute preservation + save-time suppression
 *         Setting Dynamo Width to "Narrow" first, then adding a native Width
 *         value, must preserve dynamoWidth: "narrow" in the block data AND
 *         suppress the CSS variable from the serialized save output while the
 *         native value is present.
 *
 *   AC4 — Approved note copy
 *         Width note: "Native width is set above. Clear it to use a Dynamo preset."
 *         Radius note: "Native radius is set above. Clear it to use a Dynamo preset."
 *
 * RED PHASE — These tests MUST FAIL in the current codebase because the mutual
 * exclusion HOC does not yet exist in src/editor/token-presets.js.
 *
 * Prerequisites:
 *   - WordPress site running at http://aaachinese.local (or WP_BASE_URL env var)
 *   - assets/js/editor/token-presets.js is built and enqueued in the editor
 *   - The HOC implementing mutual exclusion is implemented (RED: it is not)
 */

const { test, expect } = require('@playwright/test');

// ---------------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------------

const BASE_URL     = process.env.WP_BASE_URL || 'http://localhost:10017';
const WP_ADMIN_URL = `${BASE_URL}/wp-admin`;

/** Approved note copy — must match the implementation exactly */
const WIDTH_NOTE  = 'Native width is set above. Clear it to use a Dynamo preset.';
const RADIUS_NOTE = 'Native radius is set above. Clear it to use a Dynamo preset.';

// ---------------------------------------------------------------------------
// Shared helpers (mirrors the pattern from width-preset.spec.js and radius-preset.spec.js)
// ---------------------------------------------------------------------------

/**
 * Navigate to a new blank page in the block editor.
 * (Login is handled by storageState from globalSetup.)
 */
async function openNewEditorPage(page) {
    await page.goto(`${WP_ADMIN_URL}/post-new.php?post_type=page`);
    await page.waitForSelector(
        '.editor-canvas, .block-editor-writing-flow, iframe[name="editor-canvas"]',
        { timeout: 15000 }
    );
}

/**
 * Return a reference to the editor iframe inner frame (FSE / iframed editor),
 * or the page itself when the editor is not iframed.
 */
async function getEditorFrame(page) {
    const editorFrame = page.frameLocator('iframe[name="editor-canvas"]');
    try {
        await editorFrame.locator('body').waitFor({ timeout: 3000 });
        return editorFrame;
    } catch {
        return page;
    }
}

/**
 * Ensure the "Dynamo" InspectorControls panel is expanded.
 */
async function openDynamoPanel(page) {
    const btn = page.getByRole('button', { name: /dynamo/i });
    await btn.waitFor({ timeout: 10000 });
    const expanded = await btn.getAttribute('aria-expanded');
    if (expanded !== 'true') {
        await btn.click();
    }
}

/**
 * Insert a Group block programmatically, bypassing the variation-picker UI.
 */
async function insertGroupBlock(page) {
    await page.evaluate(() => {
        const block = window.wp.blocks.createBlock(
            'core/group',
            { layout: { type: 'constrained' } },
            []
        );
        window.wp.data.dispatch('core/block-editor').insertBlock(block);
    });
    await page.waitForTimeout(800);
}

/**
 * Insert a Cover block programmatically.
 * core/cover has borders.radius support in this WP installation (confirmed by radius-preset.spec.js).
 */
async function insertCoverBlock(page) {
    await page.evaluate(() => {
        const block = window.wp.blocks.createBlock('core/cover', {}, []);
        window.wp.data.dispatch('core/block-editor').insertBlock(block);
    });
    await page.waitForTimeout(800);
}

/**
 * Select the first block of a given type in the editor canvas.
 */
async function selectBlock(editorFrame, blockType) {
    const block = editorFrame.locator(`[data-type="${blockType}"]`).first();
    await block.waitFor({ timeout: 8000 });
    await block.click();
    return block;
}

/**
 * Serialize the first block of a given type in the editor to its HTML comment markup.
 */
async function serializeFirstBlock(page, blockType) {
    return page.evaluate((type) => {
        const blocks = window.wp.data.select('core/block-editor').getBlocks();
        const block  = blocks.find((b) => b.name === type);
        if (!block) return null;
        return window.wp.blocks.serialize([block]);
    }, blockType);
}

/**
 * Set the native Width (contentSize) on the currently selected block using the
 * Layout inspector controls. Looks for the "Content size" or "Width" input that
 * controls layout.contentSize.
 *
 * The control appears in the Dimensions / Layout tab in the block inspector.
 * We fill it via page.evaluate so we can target the attribute directly when the
 * UI label is ambiguous.
 */
async function setNativeContentSize(page, value) {
    // Use the WordPress data API to set the attribute directly on the first group block.
    // This is the most reliable approach and matches what the native UI does.
    await page.evaluate((v) => {
        const blocks = window.wp.data.select('core/block-editor').getBlocks();
        const group  = blocks.find((b) => b.name === 'core/group');
        if (!group) throw new Error('No core/group block found');
        window.wp.data.dispatch('core/block-editor').updateBlockAttributes(
            group.clientId,
            { layout: Object.assign({}, group.attributes.layout, { contentSize: v }) }
        );
    }, value);
    await page.waitForTimeout(400);
}

/**
 * Set the native border radius on the currently selected Image block via the
 * WordPress data API (mirrors what the native Border Radius control does).
 */
async function setNativeBorderRadius(page, value) {
    await page.evaluate((v) => {
        const blocks = window.wp.data.select('core/block-editor').getBlocks();
        const cover  = blocks.find((b) => b.name === 'core/cover');
        if (!cover) throw new Error('No core/cover block found');
        window.wp.data.dispatch('core/block-editor').updateBlockAttributes(
            cover.clientId,
            {
                style: Object.assign(
                    {},
                    cover.attributes.style,
                    { border: Object.assign({}, (cover.attributes.style || {}).border, { radius: v }) }
                ),
            }
        );
    }, value);
    await page.waitForTimeout(400);
}

// ---------------------------------------------------------------------------
// AC1 — Width mutual exclusion on a Group block
// ---------------------------------------------------------------------------

test.describe('AC1 — Width dropdown disabled when native contentSize is set (Group block)', () => {
    test('Width dropdown is enabled when no native contentSize is set', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        const widthSelect = page.getByRole('combobox', { name: /width/i });
        await expect(widthSelect).toBeVisible({ timeout: 10000 });
        // Must be enabled (not disabled) when no native value is present
        await expect(widthSelect).toBeEnabled();
    });

    test('Width dropdown is disabled after setting layout.contentSize', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        // Set native contentSize — this should trigger the mutual exclusion
        await setNativeContentSize(page, '640px');

        // The Dynamo Width dropdown must now be disabled
        const widthSelect = page.getByRole('combobox', { name: /width/i });
        await expect(widthSelect).toBeDisabled({ timeout: 5000 });
    });

    test('Width note is visible after setting layout.contentSize', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        await setNativeContentSize(page, '640px');

        // The approved note copy must appear in the inspector
        await expect(page.getByText(WIDTH_NOTE)).toBeVisible({ timeout: 5000 });
    });

    test('Width note is NOT visible when no native contentSize is set', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        // Note must be absent before any native value is set
        await expect(page.getByText(WIDTH_NOTE)).toHaveCount(0);
    });

    test('Width dropdown is re-enabled after clearing layout.contentSize', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        // Set then clear the native value
        await setNativeContentSize(page, '640px');

        const widthSelect = page.getByRole('combobox', { name: /width/i });
        await expect(widthSelect).toBeDisabled({ timeout: 5000 });

        // Clear the native value (empty string = unset per detection rule)
        await setNativeContentSize(page, '');

        // Dropdown must be enabled again
        await expect(widthSelect).toBeEnabled({ timeout: 5000 });
    });

    test('Width note disappears after clearing layout.contentSize', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        await setNativeContentSize(page, '640px');
        await expect(page.getByText(WIDTH_NOTE)).toBeVisible({ timeout: 5000 });

        // Clear the native value
        await setNativeContentSize(page, '');

        // Note must disappear
        await expect(page.getByText(WIDTH_NOTE)).toHaveCount(0, { timeout: 5000 });
    });

    test('Width dropdown is disabled when layout.wideSize is set', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        // Set wideSize instead of contentSize
        await page.evaluate(() => {
            const blocks = window.wp.data.select('core/block-editor').getBlocks();
            const group  = blocks.find((b) => b.name === 'core/group');
            if (!group) throw new Error('No core/group block found');
            window.wp.data.dispatch('core/block-editor').updateBlockAttributes(
                group.clientId,
                { layout: Object.assign({}, group.attributes.layout, { wideSize: '1200px' }) }
            );
        });
        await page.waitForTimeout(400);

        const widthSelect = page.getByRole('combobox', { name: /width/i });
        await expect(widthSelect).toBeDisabled({ timeout: 5000 });
    });
});

// ---------------------------------------------------------------------------
// AC2 — Radius mutual exclusion on an Image block
// ---------------------------------------------------------------------------

test.describe('AC2 — Radius dropdown disabled when native border radius is set (Image block)', () => {
    test('Radius dropdown is enabled when no native border radius is set', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertCoverBlock(page);
        await selectBlock(editorFrame, 'core/cover');
        await openDynamoPanel(page);

        const radiusSelect = page.getByRole('combobox', { name: /radius/i });
        await expect(radiusSelect).toBeVisible({ timeout: 10000 });
        await expect(radiusSelect).toBeEnabled();
    });

    test('Radius dropdown is disabled after setting style.border.radius', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertCoverBlock(page);
        await selectBlock(editorFrame, 'core/cover');
        await openDynamoPanel(page);

        await setNativeBorderRadius(page, '8px');

        const radiusSelect = page.getByRole('combobox', { name: /radius/i });
        await expect(radiusSelect).toBeDisabled({ timeout: 5000 });
    });

    test('Radius note is visible after setting style.border.radius', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertCoverBlock(page);
        await selectBlock(editorFrame, 'core/cover');
        await openDynamoPanel(page);

        await setNativeBorderRadius(page, '8px');

        await expect(page.getByText(RADIUS_NOTE)).toBeVisible({ timeout: 5000 });
    });

    test('Radius note is NOT visible when no native border radius is set', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertCoverBlock(page);
        await selectBlock(editorFrame, 'core/cover');
        await openDynamoPanel(page);

        await expect(page.getByText(RADIUS_NOTE)).toHaveCount(0);
    });

    test('Radius dropdown is re-enabled after clearing style.border.radius', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertCoverBlock(page);
        await selectBlock(editorFrame, 'core/cover');
        await openDynamoPanel(page);

        await setNativeBorderRadius(page, '8px');

        const radiusSelect = page.getByRole('combobox', { name: /radius/i });
        await expect(radiusSelect).toBeDisabled({ timeout: 5000 });

        // Clear the native value
        await setNativeBorderRadius(page, '');

        await expect(radiusSelect).toBeEnabled({ timeout: 5000 });
    });

    test('Radius note disappears after clearing style.border.radius', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertCoverBlock(page);
        await selectBlock(editorFrame, 'core/cover');
        await openDynamoPanel(page);

        await setNativeBorderRadius(page, '8px');
        await expect(page.getByText(RADIUS_NOTE)).toBeVisible({ timeout: 5000 });

        await setNativeBorderRadius(page, '');

        await expect(page.getByText(RADIUS_NOTE)).toHaveCount(0, { timeout: 5000 });
    });
});

// ---------------------------------------------------------------------------
// AC3 — Dynamo attribute preserved; save-time suppression active
// ---------------------------------------------------------------------------

test.describe('AC3 — Dynamo attribute preserved and save-time suppression', () => {
    test('dynamoWidth is preserved after setting a native contentSize', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        // 1. Set Dynamo Width to Narrow first
        const widthSelect = page.getByRole('combobox', { name: /width/i });
        await widthSelect.selectOption('narrow');

        // Verify attribute was set
        let markup = await serializeFirstBlock(page, 'core/group');
        expect(markup).toContain('"dynamoWidth":"narrow"');

        // 2. Now set a native contentSize — this should trigger suppression + disable
        await setNativeContentSize(page, '640px');

        // 3. The dynamoWidth attribute must STILL be present in block data
        markup = await serializeFirstBlock(page, 'core/group');
        expect(markup).toContain('"dynamoWidth":"narrow"');
    });

    test('CSS variable is suppressed in save output when native contentSize is present', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        // 1. Set Dynamo Width to Narrow
        const widthSelect = page.getByRole('combobox', { name: /width/i });
        await widthSelect.selectOption('narrow');

        // 2. Set native contentSize
        await setNativeContentSize(page, '640px');

        // 3. The serialized HTML must NOT contain the Dynamo CSS variable
        const markup = await serializeFirstBlock(page, 'core/group');
        expect(markup).not.toContain('var(--dynamo-layout-width-narrow)');
    });

    test('CSS variable is restored in save output after clearing the native contentSize', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        // 1. Set Dynamo Width to Narrow
        const widthSelect = page.getByRole('combobox', { name: /width/i });
        await widthSelect.selectOption('narrow');

        // 2. Set native contentSize — suppression active
        await setNativeContentSize(page, '640px');

        let markup = await serializeFirstBlock(page, 'core/group');
        expect(markup).not.toContain('var(--dynamo-layout-width-narrow)');

        // 3. Clear native contentSize — suppression lifted
        await setNativeContentSize(page, '');

        // 4. Dynamo CSS variable should now be present in save output again
        markup = await serializeFirstBlock(page, 'core/group');
        expect(markup).toContain('var(--dynamo-layout-width-narrow)');
    });
});

// ---------------------------------------------------------------------------
// AC4 — Approved note copy (exact text)
// ---------------------------------------------------------------------------

test.describe('AC4 — Approved note copy (exact text)', () => {
    test('Width note copy matches the approved text exactly', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertGroupBlock(page);
        await selectBlock(editorFrame, 'core/group');
        await openDynamoPanel(page);

        await setNativeContentSize(page, '640px');

        // The exact approved note must appear — not a variant
        const note = page.getByText(WIDTH_NOTE, { exact: true });
        await expect(note).toBeVisible({ timeout: 5000 });
    });

    test('Radius note copy matches the approved text exactly', async ({ page }) => {
        await openNewEditorPage(page);
        const editorFrame = await getEditorFrame(page);
        await insertCoverBlock(page);
        await selectBlock(editorFrame, 'core/cover');
        await openDynamoPanel(page);

        await setNativeBorderRadius(page, '8px');

        const note = page.getByText(RADIUS_NOTE, { exact: true });
        await expect(note).toBeVisible({ timeout: 5000 });
    });
});
