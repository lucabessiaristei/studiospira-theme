'use strict';

(function () {
    var header = document.getElementById('masthead');
    var hero = document.querySelector('.front-page__hero');

    if (!header || !hero) return;

    var ticking = false;

    // read from the hero's live rect rather than a cached scroll threshold,
    // which went stale on any post-load layout shift (fonts, images)
    function apply() {
        ticking = false;
        header.classList.toggle(
            'is-scrolled',
            hero.getBoundingClientRect().bottom <= header.offsetHeight
        );
    }

    function onScroll() {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(apply);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);

    // initial state without transitions, so loading mid-page doesn't fade in
    // from the unscrolled colour
    header.classList.add('is-init');
    apply();
    window.requestAnimationFrame(function () {
        header.classList.remove('is-init');
    });
})();

(function () {
    var timer = null;

    window.addEventListener('resize', function () {
        document.body.classList.add('is-resizing');
        window.clearTimeout(timer);
        timer = window.setTimeout(function () {
            document.body.classList.remove('is-resizing');
        }, 200);
    });
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
    var container = root.querySelector('.lightbox__container');
    var track     = root.querySelector('.lightbox__track');

    // due modi di navigare: da md in su crossfade comandato dalle frecce, sotto
    // md uno scroller orizzontale che si trascina e gira all'infinito
    var mqMobile = window.matchMedia('(max-width: 767.98px)');

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
    var scrollTimer  = null;

    function items() {
        return currentGroup === null ? [] : groups[currentGroup];
    }

    function setCounter() {
        counterEl.textContent = (currentIndex + 1) + ' / ' + items().length;
    }

    function preload(index) {
        var item = items()[index];
        if (item) (new Image()).src = item.href;
    }

    // --- crossfade, da md in su ---

    function renderFade() {
        var list = items();
        var item = list[currentIndex];

        var outgoing = imgEls[activePos];
        var incoming = imgEls[1 - activePos];

        incoming.onload = function () {
            outgoing.classList.remove('is-active');
            incoming.classList.add('is-active');
        };
        incoming.src = item.href;
        incoming.alt = item.alt;
        activePos = 1 - activePos;

        preload((currentIndex + 1) % list.length);
        preload((currentIndex - 1 + list.length) % list.length);
    }

    // --- scroller infinito, sotto md ---

    // ordine [ultima, ...tutte, prima]: arrivati su un clone si salta di colpo
    // sulla slide vera corrispondente, cosi' lo scorrimento non ha capolinea
    function buildTrack() {
        var list = items();
        var slides = list.length > 1
            ? [list[list.length - 1]].concat(list, [list[0]])
            : list;

        track.innerHTML = '';

        slides.forEach(function (item) {
            var slide = document.createElement('div');
            var img   = document.createElement('img');

            slide.className = 'lightbox__slide';
            img.src = item.href;
            img.alt = item.alt;
            img.loading = 'lazy';

            slide.appendChild(img);
            track.appendChild(slide);
        });
    }

    function positionTrack() {
        var offset = items().length > 1 ? 1 : 0;

        stopGlide();
        track.scrollLeft = (currentIndex + offset) * track.clientWidth;
    }

    // discesa in ease-out sulla slide: lo snap del browser fermava di colpo,
    // qui la corsa si esaurisce da sola
    var animId = null;

    function stopGlide() {
        if (animId) window.cancelAnimationFrame(animId);
        animId = null;
    }

    function glideTo(left) {
        var from  = track.scrollLeft;
        var delta = left - from;
        var start = null;

        stopGlide();
        if (!delta) return;

        animId = window.requestAnimationFrame(function frame(now) {
            if (start === null) start = now;

            var t = Math.min(1, (now - start) / 480);
            track.scrollLeft = from + delta * (1 - Math.pow(1 - t, 3));

            animId = t < 1 ? window.requestAnimationFrame(frame) : null;
        });
    }

    // a fine corsa: il rientro dal clone e' un salto secco (deve restare
    // invisibile), l'assestamento sulla slide piu' vicina e' animato
    function onTrackSettled() {
        var list  = items();
        var width = track.clientWidth;

        if (list.length < 2 || !width || dragging || animId) return;

        var pos = Math.round(track.scrollLeft / width);

        if (pos === 0) {
            pos = list.length;
            track.scrollLeft = pos * width;
        } else if (pos === list.length + 1) {
            pos = 1;
            track.scrollLeft = width;
        } else if (Math.abs(track.scrollLeft - pos * width) > 2) {
            glideTo(pos * width);
        }

        currentIndex = pos - 1;
        setCounter();
    }

    track.addEventListener('scroll', function () {
        window.clearTimeout(scrollTimer);
        scrollTimer = window.setTimeout(onTrackSettled, 140);
    });

    // il touch scorre gia' da solo, con l'inerzia del browser: qui servono
    // mouse e penna, che su un contenitore scrollabile non trascinano niente
    var dragging   = false;
    var dragStartX = 0;
    var dragStartL = 0;

    track.addEventListener('pointerdown', function (e) {
        // un tocco nuovo interrompe la discesa in corso, dito o mouse che sia
        stopGlide();

        if (e.pointerType === 'touch' || !mqMobile.matches) return;

        dragging   = true;
        dragStartX = e.clientX;
        dragStartL = track.scrollLeft;
        track.classList.add('is-dragging');
        track.setPointerCapture(e.pointerId);
    });

    track.addEventListener('pointermove', function (e) {
        if (!dragging) return;

        track.scrollLeft = dragStartL - (e.clientX - dragStartX);
    });

    function endDrag() {
        if (!dragging) return;

        dragging = false;
        track.classList.remove('is-dragging');
        onTrackSettled();
    }

    track.addEventListener('pointerup', endDrag);
    track.addEventListener('pointercancel', endDrag);

    // --- comune ---

    function render() {
        if (mqMobile.matches) {
            buildTrack();
            positionTrack();
        } else {
            renderFade();
        }

        setCounter();

        var loopable = items().length > 1;
        prevBtn.hidden = !loopable;
        nextBtn.hidden = !loopable;
    }

    function step(delta) {
        var list = items();

        if (mqMobile.matches) {
            glideTo(track.scrollLeft + delta * track.clientWidth);
            return;
        }

        currentIndex = (currentIndex + delta + list.length) % list.length;
        renderFade();
        setCounter();
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

    root.querySelectorAll('[data-lightbox-close]').forEach(function (el) {
        el.addEventListener('click', close);
    });

    // il container copre tutta la viewport, quindi il backdrop non riceve piu'
    // click: chiudo quando il click cade nel vuoto attorno alla foto
    container.addEventListener('click', function (e) {
        if (e.target.closest('.lightbox__header, .lightbox__nav, .lightbox__content')) return;
        close();
    });

    prevBtn.addEventListener('click', function () { step(-1); });
    nextBtn.addEventListener('click', function () { step(1); });

    document.addEventListener('keydown', function (e) {
        if (!root.classList.contains('is-open')) return;

        if (e.key === 'Escape')     close();
        if (e.key === 'ArrowLeft')  step(-1);
        if (e.key === 'ArrowRight') step(1);
    });

    // rotazione o finestra ridimensionata oltre la soglia: si riparte nell'altro
    // modo dalla foto in cui si era
    mqMobile.addEventListener('change', function () {
        if (root.classList.contains('is-open')) render();
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

// staggered fade-in for the card grids (home, archive, gallery). One shared
// observer; the delay is per intersection batch, so each row staggers as it
// scrolls in instead of inheriting an ever-growing delay from its grid index.
(function () {
    var selectors = [
        '.front-page__interventi .row > .col',
        '.interventi-archive .interventi-grid > .col',
        '.intervento__galleria .row > .col'
    ];

    if (!('IntersectionObserver' in window)) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var items = [];
    selectors.forEach(function (selector) {
        items = items.concat(Array.prototype.slice.call(document.querySelectorAll(selector)));
    });

    if (!items.length) return;

    var observer = new IntersectionObserver(function (entries) {
        var i = 0;

        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;

            entry.target.style.transitionDelay = (i++ * 70) + 'ms';
            entry.target.classList.add('is-revealed');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.05 });

    items.forEach(function (item) {
        item.classList.add('sp-reveal');
        observer.observe(item);
    });
})();

(function () {
    var filters  = document.querySelectorAll('.interventi-filter');
    var resetBtn = document.querySelector('.interventi-filters__reset');

    if (!filters.length) return;

    // click-to-open is only for devices that can't hover; the rest open via
    // CSS hover at any width
    var mqHover = window.matchMedia('(hover: hover)');
    var mqDesktop = window.matchMedia('(min-width: 992px)');

    function closeAllMenus() {
        filters.forEach(function (f) { f.classList.remove('is-open'); });
    }

    mqDesktop.addEventListener('change', closeAllMenus);

    document.addEventListener('click', function (event) {
        if (mqHover.matches) return;
        if (!event.target.closest('.interventi-filter')) closeAllMenus();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeAllMenus();
    });

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
        var toggle = filter.querySelector('.interventi-filter__toggle');

        if (toggle) {
            toggle.addEventListener('click', function () {
                if (mqHover.matches) return;

                var wasOpen = filter.classList.contains('is-open');
                closeAllMenus();
                if (!wasOpen) filter.classList.add('is-open');
            });
        }

        options.forEach(function (option) {
            option.addEventListener('click', function (event) {
                if (!mqHover.matches) {
                    filter.classList.remove('is-open');
                    // tapping the option focuses it, and :focus-within would
                    // hold the menu open — there's no mouseleave on touch to
                    // blur it. detail is 0 for keyboard activation, which
                    // should keep its focus.
                    if (event.detail > 0) option.blur();
                }

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

        // when start and end heights match no transition fires, so the inline
        // height above is never cleared and clips the grid once it restacks
        window.addEventListener('resize', function () {
            gridWrap.style.height = '';
        });
    }

    document.addEventListener('interventi-filter:change', function (e) {
        if (!(e.detail.filter in activeFilters)) return;
        activeFilters[e.detail.filter] = e.detail.value;
        applyFilters();
    });
})();
