<div id="sp-lightbox" class="lightbox dark" aria-hidden="true">
    <div class="lightbox__backdrop" data-lightbox-close></div>

    <button type="button" class="lightbox__nav lightbox__nav--prev" data-lightbox-prev
            aria-label="<?php echo esc_attr(pll__('Foto precedente')); ?>">
        <span class="lightbox__chevron lightbox__chevron--prev" aria-hidden="true"></span>
    </button>

    <button type="button" class="lightbox__nav lightbox__nav--next" data-lightbox-next
            aria-label="<?php echo esc_attr(pll__('Foto successiva')); ?>">
        <span class="lightbox__chevron lightbox__chevron--next" aria-hidden="true"></span>
    </button>

    <div class="lightbox__body">
        <div class="lightbox__header">
            <p class="lightbox__counter secondary-font fs-mono-body text-weiss"></p>

            <button type="button" class="lightbox__close sp-btn ghost" data-lightbox-close>
                <?php pll_e('Chiudi'); ?>
            </button>
        </div>

        <div class="lightbox__content">
            <img class="lightbox__img" src="" alt="">
            <img class="lightbox__img" src="" alt="">
        </div>
    </div>
</div>
