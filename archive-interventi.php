<?php get_header(); ?>

<main id="primary" class="site-main interventi-archive">

    <section class="pt-a pt-md-f pb-a">
        <div class="container px-4">
            <?php get_template_part('template-parts/headline', null, ['title' => pll__('Interventi di restauro e recupero')]); ?>
        </div>
    </section>

    <?php if (have_posts()) : ?>
        <section class="pb-a pb-md-g">
            <div class="container px-4">

                <?php get_template_part('template-parts/interventi-filters'); ?>

                <div class="pt-b">
                    <div class="row row-cols-3 gx-c gy-d interventi-grid">
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
                </div>

            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
