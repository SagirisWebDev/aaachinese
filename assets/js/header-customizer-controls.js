(function () {
    if (!window.wp || !wp.customize) {
        return;
    }

    var styleInjected = false;
    function injectDisabledStyles() {
        if (styleInjected) return;
        styleInjected = true;
        var s = document.createElement('style');
        s.textContent =
            '.dynamo-control-justify-locked { opacity: 0.45; pointer-events: none; }' +
            '.dynamo-control-justify-locked::after {' +
            '  content: "Locked while Header menu/cart alignment is not \\"Space Between\\".";' +
            '  display: block; margin-top: 6px; font-size: 11px; color: #757575; font-style: italic;' +
            '  pointer-events: auto;' +
            '}';
        document.head.appendChild(s);
    }

    function applyDisabledState(value) {
        var control = wp.customize.control('dynamo_woocommerce_header_cart_position');
        if (!control || !control.container) {
            return;
        }
        var locked = value !== 'space-between';
        control.container.toggleClass('dynamo-control-justify-locked', locked);
    }

    wp.customize.bind('ready', function () {
        injectDisabledStyles();
        var setting = wp.customize('dynamo_header_menu_cart');
        if (!setting) {
            return;
        }
        applyDisabledState(setting.get());
        setting.bind(applyDisabledState);

        // The position control may not exist yet if WooCommerce panel hasn't been
        // expanded; re-apply once it registers so the lock state is correct on first view.
        wp.customize.control.bind('add', function (control) {
            if (control && control.id === 'dynamo_woocommerce_header_cart_position') {
                applyDisabledState(setting.get());
            }
        });
    });
})();
