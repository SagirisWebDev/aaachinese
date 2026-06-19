(function () {
    'use strict';

    const field = document.querySelector('.dynamo-nav-search .search-field');
    if (!field) return;

    const container = field.closest('.dynamo-nav-search');
    const dropdown  = document.createElement('ul');
    dropdown.className = 'aaa-search-results';
    dropdown.setAttribute('role', 'listbox');
    dropdown.hidden = true;
    container.appendChild(dropdown);

    let timer;

    function clearResults() {
        dropdown.innerHTML = '';
        dropdown.hidden = true;
    }

    function render(items) {
        dropdown.innerHTML = '';
        if (!items.length) {
            dropdown.hidden = true;
            return;
        }
        items.forEach(function (item) {
            const li = document.createElement('li');
            li.setAttribute('role', 'option');
            const img = item.thumb
                ? '<img src="' + item.thumb + '" alt="" width="48" height="48">'
                : '<span class="aaa-search-results__no-img"></span>';
            li.innerHTML =
                '<a href="' + item.url + '">' +
                    img +
                    '<span class="aaa-search-results__info">' +
                        '<span class="aaa-search-results__title">' + item.title + '</span>' +
                        '<span class="aaa-search-results__price">' + item.price + '</span>' +
                    '</span>' +
                '</a>';
            dropdown.appendChild(li);
        });
        dropdown.hidden = false;
    }

    field.addEventListener('input', function () {
        clearTimeout(timer);
        const q = field.value.trim();
        if (q.length < 2) {
            clearResults();
            return;
        }
        timer = setTimeout(function () {
            const url = aaaSearch.ajaxUrl + '?action=aaa_live_search&q=' + encodeURIComponent(q);
            fetch(url)
                .then(function (r) { return r.json(); })
                .then(render)
                .catch(clearResults);
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!container.contains(e.target)) {
            clearResults();
        }
    });

    field.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            clearResults();
            field.blur();
        }
    });
}());
