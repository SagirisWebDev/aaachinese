# Dynamo Theme — Manual Testing Checklist

**Site:** `http://localhost:10017` · **Admin:** `http://localhost:10017/wp-admin`

---

## 1. Core Token System

### Customizer live preview
- [ ] Open **Appearance → Customizer**
- [ ] Change a **colour** token — confirm the frontend preview updates without a page reload
- [ ] Change a **font size** or **typography** token — confirm live preview updates
- [ ] Change a **spacing** token — confirm live preview updates
- [ ] Change a **border radius** token — confirm live preview updates
- [ ] **Save & Publish** — confirm changes persist after a full page reload

### CSS custom properties
- [ ] On the frontend, open DevTools → Elements, inspect `<html>` or `<body>` — confirm `--dynamo-*` custom properties are present on `:root`
- [ ] Verify tokens exist for all 6 modules: colours, typography, spacing, layout, borders, shadows

### CSS caching
- [ ] After saving Customizer changes, check DevTools → Network — confirm the theme stylesheet loads (not a 404)
- [ ] Confirm the stylesheet reflects the latest saved values

---

## 2. Admin Options Page

- [ ] Go to **Appearance → Dynamo Settings**
- [ ] Confirm three tabs are present: **Layout**, **Features**, **Performance**
- [ ] **Layout tab:** change a layout setting, save, confirm it takes effect on the frontend
- [ ] **Features tab:** toggle a feature on/off, save, confirm the change is visible
- [ ] **Performance tab:** options are present and saveable without errors

---

## 3. Navigation

- [ ] On the frontend, confirm the primary menu renders
- [ ] **Resize the browser to mobile width** (~375px) — confirm the menu collapses and a **toggle button** appears
- [ ] Click the toggle button — confirm the menu opens and closes
- [ ] Confirm no JavaScript errors in the console

---

## 4. Typography & Fonts

- [ ] In **Customizer → Typography**, confirm font family dropdowns are present
- [ ] The dropdown should list at least: **System Sans**, **System Serif**, **System Mono**
- [ ] Select a different font family, confirm the live preview updates
- [ ] On the frontend, open DevTools → Sources — confirm no failed `@font-face` requests (system fonts have no external files, so no network requests expected)

---

## 5. WooCommerce

> Requires WooCommerce active and at least one product in the catalogue.

### Shop grid
- [ ] Visit the **Shop page** — confirm products render in a grid
- [ ] Go to **Customizer → WooCommerce → Shop** — change **columns per row**, confirm the grid updates in preview
- [ ] Change **products per page**, save, confirm the shop page pagination reflects it

### Product card toggles
- [ ] In **Customizer → WooCommerce → Product Card**, toggle off **product image** — confirm images disappear in preview
- [ ] Toggle off **title**, **rating**, **price**, **short description**, **add-to-cart** one by one — confirm each disappears
- [ ] Re-enable all, save, confirm all elements are back on the live shop page

### Header cart icon
- [ ] In **Customizer → WooCommerce**, find the **Header Cart** settings
- [ ] Confirm the cart icon is visible in the header on the frontend
- [ ] Add a product to the cart — confirm the **item count badge** updates without a page reload
- [ ] Toggle the cart icon off, save — confirm it disappears from the header

### Single product page
- [ ] Visit a **single product page**
- [ ] In **Customizer → WooCommerce → Single Product**, toggle off **title**, **price**, **rating**, **excerpt**, **add-to-cart**, **meta** one at a time — confirm each disappears in preview
- [ ] Re-enable all, save
- [ ] Change **related products columns**, confirm the related products grid reflects it

### Quantity buttons
- [ ] On a single product page, confirm **+** and **−** quantity buttons are present alongside the quantity input
- [ ] Click **+** — quantity increases; click **−** — quantity decreases, stops at 1
- [ ] Add to cart and go to the **Cart page** — confirm +/− buttons work there too and the cart totals update

### Cart & checkout
- [ ] On the **Cart page**, confirm the **Proceed to Checkout** button label matches the Customizer setting (**Customizer → WooCommerce → Cart**, button label field)
- [ ] Change the label in the Customizer, save, confirm the cart page reflects it
- [ ] On the **Checkout page**, confirm the layout renders without broken styles

### WooCommerce design tokens
- [ ] In **Customizer → WooCommerce → Colours**, confirm controls for **sale badge background**, **sale badge text**, and **star rating colour**
- [ ] Change the sale badge colour — on the shop page, confirm sale badges reflect it
- [ ] In DevTools on the frontend, confirm `--dynamo-woocommerce-sale-badge-bg`, `--dynamo-woocommerce-sale-badge-color`, and `--dynamo-woocommerce-star-color` are present on `:root`

---

## 6. Cookie / Consent

> Requires Complianz (or Borlabs) active.

### Banner token sync
- [ ] With Complianz active, trigger the **cookie consent banner** (clear cookies and reload)
- [ ] Inspect the banner's stylesheet in DevTools — confirm `--dynamo-*` colour variables are injected into it
- [ ] Change a colour token in the Customizer — confirm the banner reflects the new colour

### Cookie categories endpoint
- [ ] Visit `http://localhost:10017/wp-json/dynamo/v1/cookie-categories` in a browser
- [ ] Confirm a JSON array of consent categories is returned (e.g. `[{"slug":"marketing","label":"Marketing"}, ...]`)

### Consent Placeholder
- [ ] Create or find a post that contains an embed (YouTube, Vimeo, etc.) gated by consent
- [ ] Clear cookies and visit the post — confirm a **styled placeholder** is shown instead of the embed
- [ ] Grant the required consent category — confirm the embed **replaces the placeholder without a page reload**

---

## 7. Token Preset Dropdown (v1.3.0)

### Width dropdown — Group block
- [ ] Open a new page in the block editor (`/wp-admin/post-new.php?post_type=page`)
- [ ] Insert a **Group block**
- [ ] In the right sidebar, open the **Dynamo** panel — confirm a **Width** dropdown is present
- [ ] Confirm the dropdown has 6 options: `— Default —`, Narrow, Default, Wide, Container, Full
- [ ] Select **Narrow** — switch to **Code editor** and confirm `"dynamoWidth":"narrow"` and `var(--dynamo-layout-width-narrow)` are in the block markup
- [ ] Publish the page, visit the frontend — confirm the Group block has `max-width: var(--dynamo-layout-width-narrow)` applied

### Width dropdown — Customizer scale controls
- [ ] In the Customizer, confirm a **Width Scale** subsection exists under Layout
- [ ] Confirm controls for Narrow, Wide, Full (Default and Container alias role tokens — no separate controls)
- [ ] Change the **Narrow** value — confirm `--dynamo-layout-width-narrow` updates on the frontend

### Radius dropdown — Cover block
- [ ] Insert a **Cover block** in the block editor
- [ ] In the **Dynamo** panel, confirm a **Radius** dropdown is present
- [ ] Confirm the dropdown has 7 options: `— None —`, None, Small, Default, Large, X-Large, Pill
- [ ] Select **Large** — confirm `var(--dynamo-borders-radius-lg)` appears in the block markup
- [ ] Publish, visit the frontend — confirm the Cover block has `border-radius: var(--dynamo-borders-radius-lg)` applied

### Radius dropdown — Customizer scale controls
- [ ] In the Customizer, confirm a **Radius Scale** subsection exists under Borders
- [ ] Confirm controls for None, Small, Large, X-Large, Pill (Default aliases the role token — no separate control)
- [ ] Change the **Large** value — confirm `--dynamo-borders-radius-lg` updates on the frontend

### Mutual exclusion — Width
- [ ] Select a Group block with no Dynamo width set — confirm Width dropdown is **enabled**
- [ ] Via browser console, run:
  ```js
  const b = wp.data.select('core/block-editor').getBlocks().find(b => b.name === 'core/group');
  wp.data.dispatch('core/block-editor').updateBlockAttributes(b.clientId, { layout: { ...b.attributes.layout, contentSize: '500px' } });
  ```
- [ ] Confirm Width dropdown becomes **disabled** and note appears: *"Native width is set above. Clear it to use a Dynamo preset."*
- [ ] Clear it via console (`contentSize: ''`) — confirm dropdown **re-enables** and note disappears
- [ ] Set Dynamo Width to Narrow, then set native `contentSize: '500px'` — confirm `var(--dynamo-layout-width-narrow)` is **absent** from Code editor markup while native is set, then **reappears** after clearing

### Mutual exclusion — Radius
- [ ] Select a Cover block — confirm Radius dropdown is **enabled**
- [ ] Via browser console:
  ```js
  const b = wp.data.select('core/block-editor').getBlocks().find(b => b.name === 'core/cover');
  wp.data.dispatch('core/block-editor').updateBlockAttributes(b.clientId, { style: { ...b.attributes.style, border: { radius: '8px' } } });
  ```
- [ ] Confirm Radius dropdown becomes **disabled** and note: *"Native radius is set above. Clear it to use a Dynamo preset."*
- [ ] Clear it (`border: { radius: '' }`) — confirm dropdown **re-enables** and note disappears
