<div id="sp-lightbox" class="lightbox dark" aria-hidden="true">
    <div class="lightbox__backdrop" data-lightbox-close></div>

    <div class="lightbox__container">
        <div class="lightbox__header">
            <p class="lightbox__counter secondary-font fs-mono-body text-weiss"></p>

            <button type="button" class="lightbox__close sp-btn ghost" data-lightbox-close>
                <?php pll_e('Chiudi'); ?>
            </button>
        </div>

        <div class="lightbox__body">
            <button type="button" class="lightbox__nav lightbox__nav--prev" data-lightbox-prev
                aria-label="<?php echo esc_attr(pll__('Foto precedente')); ?>">
                <span class="lightbox__chevron lightbox__chevron--prev" aria-hidden="true"></span>
            </button>

            <div class="lightbox__content">
                <?php // da md in su: le due immagini si alternano in crossfade ?>
                <img class="lightbox__img" src="" alt="">
                <img class="lightbox__img" src="" alt="">

                <?php // sotto md: lo script riempie lo scroller con le slide e i due cloni ?>
                <div class="lightbox__track"></div>
            </div>

            <button type="button" class="lightbox__nav lightbox__nav--next" data-lightbox-next
                aria-label="<?php echo esc_attr(pll__('Foto successiva')); ?>">
                <span class="lightbox__chevron lightbox__chevron--next" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</div>