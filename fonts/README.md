# Fonts

Dynamo's typography pipeline reads `fonts/fonts.json` (the **font manifest**) at runtime. The manifest declares which font families are available to the Customizer's Typography panel and which `@font-face` rules get emitted in `<head>`.

Adding a font is a developer task — there is no admin upload UI.

## Adding a font

1. **Drop the files in a subdirectory of `fonts/`.** Convention is one directory per family, named after the slug:

   ```
   fonts/
     inter/
       Inter-Regular.woff2
       Inter-Bold.woff2
   ```

   `.woff2` is preferred. `.woff`, `.ttf`, and `.otf` are also recognised by the renderer.

2. **Add an entry to `fonts/fonts.json`.** Each top-level key is the slug used by typography tokens.

   ```json
   "inter": {
       "label": "Inter",
       "fallback": "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
       "faces": [
           {
               "file": "inter/Inter-Regular.woff2",
               "weight": 400,
               "style": "normal",
               "display": "swap"
           },
           {
               "file": "inter/Inter-Bold.woff2",
               "weight": 700,
               "style": "normal",
               "display": "swap"
           }
       ]
   }
   ```

3. **Deploy.** The renderer's cache is keyed on the manifest's content hash, so changes invalidate automatically — no manual cache bust needed.

### Field reference

Entry-level:

| Key        | Required | Notes                                                                                  |
| ---------- | -------- | -------------------------------------------------------------------------------------- |
| `label`    | yes      | Display name in the Customizer; also used as the CSS `font-family` name.               |
| `fallback` | yes      | Fallback stack appended after the label in the generated `font-family` value.          |
| `faces`    | no       | Array of `@font-face` descriptors. Omit or leave empty for system-stack-only entries.  |

Face-level:

| Key       | Default  | Notes                                                                |
| --------- | -------- | -------------------------------------------------------------------- |
| `file`    | —        | Path relative to `fonts/`. Extension determines the `format()` hint. |
| `weight`  | `400`    | Numeric weight or range (e.g. `"100 900"` for variable fonts).       |
| `style`   | `normal` | `normal` or `italic`.                                                |
| `display` | `swap`   | Any valid `font-display` value.                                      |

### Slug rules

Slugs must match `[a-z0-9-]+`. Invalid slugs are skipped and surface as an admin notice for users with `manage_options`.

### System-only entries

Entries with no `faces` (e.g. `system-sans`, `system-serif`, `system-mono`) declare a fallback stack only. The generator emits the `fallback` value directly as the `font-family`. Use these for OS-default typography without shipping any binaries.

## Selecting a font in the Customizer

Once the manifest entry is in place, the font appears in the Customizer:

1. **Appearance → Customize → Dynamo: Typography**
2. Pick a section (Body Text, H1, H2, …).
3. **Font Family** is a select populated from the manifest, showing each entry's `label`.

The selected slug is stored on the corresponding typography token (e.g. `typography-body-font-family`). At CSS generation time the slug is resolved to `"Label", <fallback>` and emitted as a CSS variable on `:root`.

If a token references a slug that no longer exists in the manifest, the generator falls back to a system sans stack and logs via `_doing_it_wrong` — the front-end never renders fontless.

## Troubleshooting

- **Font not showing in the Customizer dropdown.** Check JSON syntax and slug format. Manifest errors are reported as an admin notice on every screen for `manage_options` users.
- **`@font-face` block missing from `<head>`.** Confirm the entry has at least one face with a non-empty `file`. Entries without faces intentionally emit no `@font-face` rule.
- **Updated files, browser still serving the old font.** The renderer's cache invalidates on manifest changes, but browsers cache the font binary itself. Either bump the filename or hard-reload.
