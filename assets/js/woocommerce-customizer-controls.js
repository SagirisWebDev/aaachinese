(function () {
    if (!window.wp || !wp.customize || !window.dynamoWooCustomizer) {
        return;
    }

    var urls = dynamoWooCustomizer.sectionUrls || {};

    wp.customize.bind('ready', function () {
        Object.keys(urls).forEach(function (sectionId) {
            var section = wp.customize.section(sectionId);
            if (!section) {
                return;
            }
            section.expanded.bind(function (isExpanded) {
                if (!isExpanded) {
                    return;
                }
                var target = urls[sectionId];
                if (!target) {
                    return;
                }
                wp.customize.previewer.previewUrl.set(target);
            });
        });
    });
})();
