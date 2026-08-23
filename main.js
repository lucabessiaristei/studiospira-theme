'use strict';

(function () {
    var header = document.getElementById('masthead');
    var hero = document.querySelector('.front-page__hero');

    if (!header || !hero) return;

    var threshold = 0;

    function updateThreshold() {
        threshold = hero.offsetTop + hero.offsetHeight - header.offsetHeight;
    }

    function onScroll() {
        header.classList.toggle('is-scrolled', window.scrollY >= threshold);
    }

    window.addEventListener('resize', updateThreshold);
    window.addEventListener('scroll', onScroll, { passive: true });

    updateThreshold();
    onScroll();
})();

(function () {
    var header = document.getElementById('masthead');
    var toggle = header ? header.querySelector('.menu-toggle') : null;
    var navigation = header ? header.querySelector('.main-navigation') : null;

    if (!header || !toggle || !navigation) return;

    function closeMenu() {
        header.classList.remove('menu-is-open');
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function () {
        var isOpen = header.classList.toggle('menu-is-open');
        toggle.setAttribute('aria-expanded', String(isOpen));
    });

    navigation.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    document.addEventListener('click', function (event) {
        if (!header.contains(event.target)) closeMenu();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeMenu();
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) closeMenu();
    });
})();

(function () {
    var testoSections = document.querySelectorAll('.front-page__testo');

    if (!testoSections.length || !('IntersectionObserver' in window)) return;

    testoSections.forEach(function (testo) {
        var prev = testo.previousElementSibling;
        if (prev && prev.classList.contains('front-page__divider')) {
            prev.classList.add('front-page__divider--down');
        }

        var next = testo.nextElementSibling;
        if (next && next.classList.contains('front-page__divider')) {
            next.classList.add('front-page__divider--up');
        }
    });

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;

            var testo = entry.target;
            var prev = testo.previousElementSibling;
            var next = testo.nextElementSibling;

            if (prev && prev.classList.contains('front-page__divider')) prev.classList.add('is-visible');
            if (next && next.classList.contains('front-page__divider')) next.classList.add('is-visible');

            observer.unobserve(testo);
        });
    }, { threshold: 0 });

    testoSections.forEach(function (testo) {
        observer.observe(testo);
    });
})();

(function () {
    var dati = document.querySelector('.intervento__dati');
    var header = document.getElementById('masthead');

    if (!dati || !header) return;

    var mq = window.matchMedia('(min-width: 992px)');

    function updateStickyState() {
        if (!mq.matches) {
            dati.classList.remove('no-sticky');
            return;
        }

        var headerHeight = header.offsetHeight;
        var readableHeight = window.innerHeight - (2 * headerHeight);

        dati.classList.toggle('no-sticky', dati.getBoundingClientRect().height > readableHeight);
    }

    window.addEventListener('resize', updateStickyState);
    mq.addEventListener('change', updateStickyState);

    updateStickyState();
})();

(function () {
    var root = document.getElementById('sp-lightbox');
    var triggers = document.querySelectorAll('a[data-lightbox]');

    if (!root || !triggers.length) return;

    var imgEls    = root.querySelectorAll('.lightbox__img');
    var counterEl = root.querySelector('.lightbox__counter');
    var prevBtn   = root.querySelector('[data-lightbox-prev]');
    var nextBtn   = root.querySelector('[data-lightbox-next]');

    var groups = {};
    var counts = {};

    triggers.forEach(function (a) {
        var group = a.dataset.lightbox;
        if (!groups[group]) { groups[group] = []; counts[group] = 0; }

        var img = a.querySelector('img');
        groups[group].push({ href: a.href, alt: img ? img.alt : '' });

        var index = counts[group]++;
        a.addEventListener('click', function (e) {
            e.preventDefault();
            open(group, index);
        });
    });

    var currentGroup = null;
    var currentIndex = 0;
    var activePos    = 0;

    function preload(index) {
        var items = groups[currentGroup];
        var item = items[index];
        if (item) (new Image()).src = item.href;
    }

    function render() {
        var items = groups[currentGroup];
        var item = items[currentIndex];

        var outgoing = imgEls[activePos];
        var incoming = imgEls[1 - activePos];

        incoming.onload = function () {
            outgoing.classList.remove('is-active');
            incoming.classList.add('is-active');
        };
        incoming.src = item.href;
        incoming.alt = item.alt;
        activePos = 1 - activePos;

        counterEl.textContent = (currentIndex + 1) + ' / ' + items.length;

        var loopable = items.length > 1;
        prevBtn.hidden = !loopable;
        nextBtn.hidden = !loopable;

        preload((currentIndex + 1) % items.length);
        preload((currentIndex - 1 + items.length) % items.length);
    }

    function open(group, index) {
        currentGroup = group;
        currentIndex = index;
        render();

        root.classList.add('is-open');
        root.setAttribute('aria-hidden', 'false');
        document.body.classList.add('lightbox-open');
    }

    function close() {
        root.classList.remove('is-open');
        root.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('lightbox-open');
    }

    function step(delta) {
        var items = groups[currentGroup];
        currentIndex = (currentIndex + delta + items.length) % items.length;
        render();
    }

    root.querySelectorAll('[data-lightbox-close]').forEach(function (el) {
        el.addEventListener('click', close);
    });

    prevBtn.addEventListener('click', function () { step(-1); });
    nextBtn.addEventListener('click', function () { step(1); });

    document.addEventListener('keydown', function (e) {
        if (!root.classList.contains('is-open')) return;

        if (e.key === 'Escape')     close();
        if (e.key === 'ArrowLeft')  step(-1);
        if (e.key === 'ArrowRight') step(1);
    });
})();

(function () {
    document.querySelectorAll('.js-protected-email').forEach(function (el) {
        var email = atob(el.dataset.protectedEmail);
        el.href = 'mailto:' + email;
        el.textContent = email;
    });

    document.querySelectorAll('.js-protected-tel').forEach(function (el) {
        var tel = atob(el.dataset.protectedTel);
        el.href = 'tel:' + tel.replace(/[^0-9+]/g, '');
        el.textContent = tel;
    });
})();

(function () {
    var filters  = document.querySelectorAll('.interventi-filter');
    var resetBtn = document.querySelector('.interventi-filters__reset');

    if (!filters.length) return;

    function updateFilterState(filter) {
        var selectedOption = filter.querySelector('.interventi-filter__option.is-selected');
        filter.classList.toggle('has-selection', !!selectedOption);

        var anySelection = false;
        filters.forEach(function (f) {
            if (f.classList.contains('has-selection')) anySelection = true;
        });
        if (resetBtn) resetBtn.classList.toggle('is-hidden', !anySelection);

        document.dispatchEvent(new CustomEvent('interventi-filter:change', {
            detail: {
                filter: filter.dataset.filter,
                value: selectedOption ? selectedOption.dataset.value : null
            }
        }));
    }

    filters.forEach(function (filter) {
        var options = filter.querySelectorAll('.interventi-filter__option');
        var toggleClear = filter.querySelector('.interventi-filter__toggle-clear');

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                var wasSelected = option.classList.contains('is-selected');

                options.forEach(function (o) { o.classList.remove('is-selected'); });
                if (!wasSelected) option.classList.add('is-selected');

                updateFilterState(filter);
            });
        });

        if (toggleClear) {
            toggleClear.addEventListener('click', function (e) {
                e.stopPropagation();
                options.forEach(function (o) { o.classList.remove('is-selected'); });
                updateFilterState(filter);
            });
        }

        filter.addEventListener('mouseleave', function () {
            if (filter.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            filters.forEach(function (filter) {
                filter.querySelectorAll('.interventi-filter__option.is-selected').forEach(function (o) {
                    o.classList.remove('is-selected');
                });
                updateFilterState(filter);
            });
        });
    }
})();

(function () {
    var cols = Array.prototype.slice.call(document.querySelectorAll('.interventi-archive .row > .col'));
    if (!cols.length) return;

    var gridWrap = document.querySelector('.interventi-grid-wrap');
    var emptyState = document.querySelector('.interventi-grid__empty');

    var FADE_MS = 250;
    var EMPTY_HEIGHT = 64;
    var filterTimer = null;

    var DATASET_KEYS = {
        servizio: 'servizi',
        destinazione_uso: 'destinazioneUso',
        posizione: 'posizione'
    };

    var activeFilters = {
        servizio: null,
        destinazione_uso: null,
        posizione: null
    };

    function matches(col) {
        return Object.keys(activeFilters).every(function (filterKey) {
            var value = activeFilters[filterKey];
            if (!value) return true;

            var raw = col.dataset[DATASET_KEYS[filterKey]] || '';
            return raw.split(',').indexOf(value) !== -1;
        });
    }

    function applyFilters() {
        var firstRects = new Map();
        var startHeight = gridWrap ? gridWrap.offsetHeight : 0;
        var hasMatchingCols = cols.some(matches);

        if (filterTimer) window.clearTimeout(filterTimer);
        if (hasMatchingCols) setEmptyState(false);
        if (gridWrap) gridWrap.style.height = startHeight + 'px';

        cols.forEach(function (col) {
            if (col.style.display !== 'none') firstRects.set(col, col.getBoundingClientRect());
        });

        var toHide = [];
        var toShow = [];

        cols.forEach(function (col) {
            var shouldShow = matches(col);
            var isVisible = col.style.display !== 'none' && !col.classList.contains('is-fading');

            if (shouldShow && !isVisible) toShow.push(col);
            if (!shouldShow && isVisible) toHide.push(col);
        });

        toHide.forEach(function (col) {
            col.classList.add('is-fading');
        });

        filterTimer = window.setTimeout(function () {
            toHide.forEach(function (col) {
                col.style.display = 'none';
            });

            toShow.forEach(function (col) {
                col.style.display = '';
                col.classList.add('is-fading');
            });

            cols.forEach(function (col) {
                if (col.style.display === 'none') return;

                var first = firstRects.get(col);
                if (first && toShow.indexOf(col) === -1) {
                    var last = col.getBoundingClientRect();
                    var dx = first.left - last.left;
                    var dy = first.top - last.top;

                    if (dx || dy) {
                        col.style.transition = 'none';
                        col.style.transform = 'translate(' + dx + 'px, ' + dy + 'px)';
                    }
                }
            });

            // forces layout so the transform above actually paints before
            // it's cleared below — otherwise there's nothing to animate from
            void document.body.offsetHeight;

            cols.forEach(function (col) {
                if (col.style.display === 'none') return;
                col.style.transition = '';
                col.style.transform = '';
                col.classList.remove('is-fading');
            });

            setEmptyState(!hasMatchingCols);

            if (gridWrap) {
                var endHeight = EMPTY_HEIGHT;

                if (hasMatchingCols) {
                    gridWrap.style.height = 'auto';
                    void gridWrap.offsetHeight;
                    endHeight = gridWrap.scrollHeight;
                    gridWrap.style.height = startHeight + 'px';
                    void gridWrap.offsetHeight;
                }

                window.requestAnimationFrame(function () {
                    gridWrap.style.height = endHeight + 'px';
                });
            }
        }, FADE_MS);
    }

    function setEmptyState(isVisible) {
        if (!emptyState) return;

        if (gridWrap) gridWrap.classList.toggle('is-empty', isVisible);
        emptyState.classList.toggle('is-visible', isVisible);
        emptyState.setAttribute('aria-hidden', String(!isVisible));
    }

    if (gridWrap) {
        gridWrap.addEventListener('transitionend', function (event) {
            if (event.propertyName === 'height') gridWrap.style.height = '';
        });
    }

    document.addEventListener('interventi-filter:change', function (e) {
        if (!(e.detail.filter in activeFilters)) return;
        activeFilters[e.detail.filter] = e.detail.value;
        applyFilters();
    });
})();
