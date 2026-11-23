<?php
// phases = top-level servizi, services = their children (see inc/acf-servizi.php)
$fasi = sp_get_servizi_fasi();

// phases with at least one intervento: only these get the "Interventi di ..."
// button, the same rule that puts them in the Interventi filter
$fasi_usate = wp_list_pluck(sp_get_used_servizi(), 'ID');

$banner_photo = sp_shared_field('banner_photo'); // field shared with Chi siamo (acf-chi-siamo.php), always the Italian page's (i18n-fields.php)
?>

<?php if ($banner_photo) : ?>
    <div class="hero-photo mb-5 mb-lg-a">
        <?php echo sp_banner_image($banner_photo); ?>
    </div>
<?php endif; ?>

<?php if ($fasi) : ?>

    <nav class="servizi__indice d-flex flex-column align-items-center text-center pt-3" aria-label="<?php echo esc_attr(pll__('Fasi')); ?>">
        <?php foreach ($fasi as $i => $fase) : ?>
            <?php if ($i > 0) : ?>
                <span class="servizi__indice-line h-a" aria-hidden="true"></span>
            <?php endif; ?>
            <a href="#<?php echo esc_attr($fase->post_name); ?>" class="servizi__indice-link secondary-font fs-h5 py-3"><?php echo ($i + 1); ?>. <span class="servizi__indice-title"><?php echo esc_html(sp_title_i18n($fase->ID)); ?></span></a>
        <?php endforeach; ?>
    </nav>

    <?php foreach ($fasi as $i => $fase) :
        $fase_desc = sp_field_i18n('descrizione', $fase->ID);
        $servizi   = sp_get_servizi_of_fase($fase->ID);
        $esempi    = sp_get_esempi_fase($fase->ID);
    ?>
        <?php // one F between blocks like the other pages: top margin only (margin, not
              // padding, so index anchors land on the title, not on the gap above it) ?>
        <section id="<?php echo esc_attr($fase->post_name); ?>" class="servizi__fase mt-d mt-lg-g">
        <?php // the sticky intro is bounded by this row, so it releases before the examples ?>
        <div class="row g-0">

            <div class="col-12 col-lg-5">
                <div class="servizi__fase-intro sp-sticky">
                    <h2 class="servizi__fase-title fs-h1 text-uppercase d-flex align-items-center align-items-lg-start gap-3">
                        <span class="servizi__fase-num-line" aria-hidden="true">
                            <span class="fs-h3 servizi__fase-num bg-bg text-weiss"><span class="servizi__fase-num-inner"><?php echo $i + 1; ?></span></span>
                        </span>
                        <span><?php echo esc_html(sp_title_i18n($fase->ID)); ?></span>
                    </h2>
                    <?php if ($fase_desc) : ?>
                        <p class="servizi__fase-desc mt-3"><?php echo nl2br(esc_html($fase_desc)); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($servizi) : ?>
                <div class="servizi__lista col-12 col-lg-6 offset-lg-1 mt-4 mt-lg-2">
                    <?php foreach ($servizi as $servizio) :
                        $icona = get_field('icona', $servizio->ID);
                        $desc  = sp_field_i18n('descrizione', $servizio->ID);
                    ?>
                        <div class="servizi__servizio d-flex gap-4 py-4 py-lg-5 border-bottom">
                            <div class="servizi__servizio-icona">
                                <?php echo sp_phosphor_svg($icona); // Phosphor Light, see inc/acf-servizi.php ?>
                            </div>
                            <div class="servizi__servizio-testo">
                                <h3 class="fs-h4 mb-2"><?php echo esc_html(sp_title_i18n($servizio->ID)); ?></h3>
                                <?php if ($desc) : ?>
                                    <p class="fs-body text-textlight mb-0"><?php echo nl2br(esc_html($desc)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

        <?php if ($esempi) : ?>
            <div class="servizi__esempi intervento-cards--centered row row-cols-1 row-cols-md-2 row-cols-lg-3 gx-3 gx-md-4 gx-lg-a gx-xl-c gy-4 gy-md-5 gy-lg-b gy-xl-d intervento-cards pt-5 pt-lg-d">
                <?php foreach ($esempi as $intervento) : ?>
                    <div class="col">
                        <?php get_template_part('template-parts/intervento-card', null, [
                            'intervento' => $intervento,
                            // phones: carousel cards are 80% of the container
                            'sizes'      => '(min-width: 1400px) 380px, (min-width: 992px) 30vw, (min-width: 768px) 45vw, calc(80vw - 2.5rem)',
                        ]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (in_array($fase->ID, $fasi_usate, true)) : ?>
            <div class="servizi__fase-cta dark mt-5 mt-lg-a text-md-center">
                <a href="<?php echo esc_url(add_query_arg('servizio', $fase->post_name, get_post_type_archive_link('interventi'))); ?>" class="sp-btn">
                    <span class="sp-btn__label"><?php echo esc_html(sprintf(pll__('Interventi di %s'), sp_is_en() ? sp_title_i18n($fase->ID) : sp_lcfirst(sp_title_i18n($fase->ID))) /* IT "Interventi di progettazione", EN "Design projects" */); ?></span>
                </a>
            </div>
        <?php endif; ?>

        </section>
    <?php endforeach; ?>

<?php endif; ?>
