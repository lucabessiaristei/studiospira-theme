<?php
$banner_photo   = get_field('banner_photo');
$fondazione     = get_field('fondazione');
$interventi     = get_field('interventi');
$sede           = get_field('sede');
$certificazione = get_field('certificazione');
$articoli       = get_field('articoli');       // relationship, array of IDs
$pubblicazioni  = get_field('pubblicazioni');   // relationship, array of IDs
?>

<?php if ($banner_photo) : ?>
    <div class="hero-photo mb-b">
        <img src="<?php echo esc_url($banner_photo['sizes']['large']); ?>"
             alt="<?php echo esc_attr($banner_photo['alt']); ?>"
             class="banner_photo">
    </div>
<?php endif; ?>

<div class="row">

    <div class="col-lg-7 chi-siamo__main">

        <div class="entry-content">
            <?php the_content(); ?>
        </div>

    </div>

    <aside class="col-lg-4 offset-lg-1 chi-siamo__dati d-flex flex-column justify-content-between gap-5">

        <div class="chi-siamo__dati-list d-flex flex-column">

            <?php if ($fondazione) : ?>
                <div class="intervento__dato d-grid align-items-baseline gap-3">
                    <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('Fondazione'); ?></span>
                    <span><?php echo esc_html($fondazione); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($interventi) : ?>
                <div class="intervento__dato d-grid align-items-baseline gap-3">
                    <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('Interventi'); ?></span>
                    <span><?php echo esc_html($interventi); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($sede) : ?>
                <div class="intervento__dato d-grid align-items-baseline gap-3">
                    <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('Sede'); ?></span>
                    <span><?php echo esc_html($sede); ?></span>
                </div>
            <?php endif; ?>

        </div>

        <?php if ($certificazione) : ?>
            <div class="w-100">
                <img class="h-auto w-100 mb-4" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/certif.jpg'); ?>">
                <p class="chi-siamo__certificazione fs-body-small text-textlight mb-0"><?php echo nl2br(esc_html($certificazione)); ?></p>
            </div>
        <?php endif; ?>

    </aside>

    <div class="col-12">


        <?php if ($articoli) : ?>
            <div class="position-relative mt-g">
                <h3 class="section-label d-flex align-items-center pb-2 position-absolute start-0 top-0"><?php pll_e('Articoli'); ?></h3>
                <div class="intervento__articoli pt-b border-top">
                    <?php foreach ($articoli as $aid) :
                        $a_url = get_field('url', $aid);
                        $a_dl  = (get_field('tipo_url', $aid) === 'download');
                    ?>
                        <div class="intervento__articolo d-flex justify-content-between align-items-center gap-3 py-3 border-bottom">
                            <span><?php echo esc_html(get_the_title($aid)); ?></span>
                            <a href="<?php echo esc_url($a_url); ?>" class="sp-btn ghost no-underline"
                               <?php echo $a_dl ? 'download' : 'target="_blank" rel="noopener"'; ?>>
                                <?php pll_e('Leggi articolo'); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($pubblicazioni) : ?>
            <div class="position-relative mt-g pb-5 border-bottom">
                <h3 class="section-label d-flex align-items-center pb-2 position-absolute start-0 top-0"><?php pll_e('Pubblicazioni'); ?></h3>
                <div class="chi-siamo__pubblicazioni pt-b border-top d-flex flex-column gap-b">
                    <?php foreach ($pubblicazioni as $pid) :
                        $p_sottotitolo = get_field('sottotitolo', $pid);
                        $p_copertina   = get_field('copertina', $pid);
                        $p_a_cura_di   = get_field('a_cura_di', $pid);
                        $p_editore     = get_field('editore', $pid);
                        $p_anno        = get_field('anno', $pid);
                        $p_isbn        = get_field('isbn', $pid);
                        $p_pagine      = get_field('pagine', $pid);
                        $p_url         = get_field('url', $pid);
                        $p_dl          = (get_field('tipo_url', $pid) === 'download');
                    ?>
                        <div class="chi-siamo__pubblicazione d-flex gap-4">

                            <div class="chi-siamo__pubblicazione-info">
                                <h4 class="mb-3"><?php echo esc_html(get_the_title($pid)); ?></h4>

                                <?php if ($p_sottotitolo) : ?>
                                    <p class="text-textlight mb-5"><?php echo esc_html($p_sottotitolo); ?></p>
                                <?php endif; ?>

                                <div class="chi-siamo__pubblicazione-dati mt-3 d-flex flex-column gap-2">
                                    <?php if ($p_a_cura_di) : ?>
                                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                                            <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('A cura di'); ?></span>
                                            <span><?php echo esc_html($p_a_cura_di); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($p_editore) : ?>
                                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                                            <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('Editore'); ?></span>
                                            <span><?php echo esc_html($p_editore); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($p_anno) : ?>
                                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                                            <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('Anno'); ?></span>
                                            <span><?php echo esc_html($p_anno); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($p_isbn) : ?>
                                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                                            <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('ISBN'); ?></span>
                                            <span><?php echo esc_html($p_isbn); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($p_pagine) : ?>
                                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                                            <span class="secondary-font fs-mono-body text-textlight"><?php pll_e('Pagine'); ?></span>
                                            <span><?php echo esc_html($p_pagine); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="chi-siamo__pubblicazione-cover position-relative">
                                <?php if ($p_copertina) : ?>
                                    <img src="<?php echo esc_url($p_copertina['sizes']['medium']); ?>"
                                         alt="<?php echo esc_attr($p_copertina['alt']); ?>"
                                         class="chi-siamo__pubblicazione-cover-img">
                                <?php endif; ?>

                                <?php if ($p_url) : ?>
                                    <div class="dark position-absolute bottom-0 w-100">
                                        <a href="<?php echo esc_url($p_url); ?>" class="sp-btn w-100 justify-content-center"
                                           <?php echo $p_dl ? 'download' : 'target="_blank" rel="noopener"'; ?>>
                                            <span class="sp-btn__label"><?php echo esc_html($p_dl ? pll__('Scarica') : pll__('Compra')); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
