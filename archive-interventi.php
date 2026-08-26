<?php get_header(); ?>

<main id="primary" class="site-main interventi-archive">

    <section class="py-5 py-md-a pt-lg-f pb-lg-c">
        <div class="container px-4">
            <?php get_template_part('template-parts/headline', null, ['title' => pll__('Interventi di restauro e recupero')]); ?>
        </div>
    </section>

    <?php if (have_posts()) : ?>
        <section class="pb-d pb-lg-g">
            <div class="container px-4">

                <?php get_template_part('template-parts/interventi-filters'); ?>

                <div class="interventi-grid-wrap pt-5 pt-lg-a pt-xxl-b">
                    <div class="interventi-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 gx-3 gx-md-4 gx-lg-a gx-xl-c gy-4 gy-md-5 gy-lg-b gy-xl-d">
                        <?php while (have_posts()) :
                            the_post();
                            $servizi_ids = get_field('servizi'); // relationship, array of IDs

                            $destinazione_ids = wp_get_post_terms(get_the_ID(), 'destinazione_uso', ['fields' => 'ids']);
                            if (is_wp_error($destinazione_ids)) $destinazione_ids = [];

                            $posizione = get_field('posizione');
                            $posizione_slug = $posizione ? sanitize_title($posizione) : '';
                        ?>
                            <div class="col"
                                 data-servizi="<?php echo esc_attr(implode(',', (array) $servizi_ids)); ?>"
                                 data-destinazione-uso="<?php echo esc_attr(implode(',', $destinazione_ids)); ?>"
                                 data-posizione="<?php echo esc_attr($posizione_slug); ?>">
                                <?php get_template_part('template-parts/intervento-card', null, ['intervento' => get_post()]); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <p class="interventi-grid__empty" role="status" aria-live="polite" aria-hidden="true">
                        <?php pll_e('Nessun intervento corrispondente ai filtri'); ?>
                    </p>
                </div>

            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
