<?php get_header(); ?>

<?php while (have_posts()) : the_post();

$copertina    = get_field('copertina');
$anno_inizio  = get_field('anno_inizio');
$anno_fine    = get_field('anno_fine');
$posizione    = get_field('posizione');
$committenza  = get_field('committenza');
$team         = get_field('team');
$budget       = get_field('budget');
$categoria    = get_field('categoria');
$servizi      = get_field('servizi');   // relationship, array of IDs
$articoli     = get_field('articoli');  // relationship, array of IDs

$destinazioni = wp_get_post_terms(get_the_ID(), 'destinazione_uso', ['fields' => 'names']);
if (is_wp_error($destinazioni)) $destinazioni = [];

$galleria = get_post_meta(get_the_ID(), 'galleria', true);
if (!is_array($galleria)) $galleria = [];
?>

<main id="primary" class="site-main intervento">

    <section class="intervento__hero">
        <div class="container px-4 py-5 py-md-a pt-lg-f pb-lg-c">
            <?php get_template_part('template-parts/headline'); ?>
        </div>
        <?php if ($copertina) : ?>
            <div class="hero-photo container px-4">
                <img src="<?php echo esc_url($copertina['sizes']['large']); ?>"
                     alt="<?php echo esc_attr($copertina['alt']); ?>"
                     class="banner_photo">
            </div>
        <?php endif; ?>
    </section>

    <section class="intervento__body pt-5 pt-lg-a pb-b pb-lg-g">
        <div class="container px-4">
            <div class="row g-5 g-lg-0">

                <div class="intervento__main col-12 col-lg-7 order-last order-lg-first">

                    <div class="intervento__descrizione">
                        <?php the_content(); ?>
                    </div>

                    <?php if ($articoli) : ?>
                        <div class="intervento__articoli mt-b mt-lg-f">
                            <h6 class="secondary-font fs-mono-body-small mb-2 mb-lg-3 text-accent"><?php pll_e('Articoli'); ?></h6>
                            <?php foreach ($articoli as $aid) :
                                $a_url = get_field('url', $aid);
                                $a_dl  = (get_field('tipo_url', $aid) === 'download');
                            ?>
                                <div class="intervento__articolo py-3 d-flex justify-content-between align-items-center gap-3 border-bottom">
                                    <span><?php echo esc_html(get_the_title($aid)); ?></span>
                                    <a href="<?php echo esc_url($a_url); ?>" class="sp-btn ghost"
                                       <?php echo $a_dl ? 'download' : 'target="_blank" rel="noopener"'; ?>>
                                        <?php pll_e('Leggi articolo'); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>

                <aside class="intervento__dati col-12 col-lg-4 offset-lg-1 d-flex flex-column">

                    <?php if ($anno_inizio) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Anno inizio</span>
                            <span><?php echo esc_html($anno_inizio); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($anno_fine) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Anno fine</span>
                            <span><?php echo esc_html($anno_fine); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($posizione) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Posizione</span>
                            <span><?php echo esc_html($posizione); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($destinazioni) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Destinazione d'uso</span>
                            <span><?php echo esc_html(implode(', ', $destinazioni)); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($committenza) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Committenza</span>
                            <span><?php echo esc_html($committenza); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($servizi) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Servizi</span>
                            <span><?php echo esc_html(implode(', ', array_map('get_the_title', $servizi))); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($team) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Team</span>
                            <span><?php echo esc_html($team); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($budget) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Budget</span>
                            <span><?php echo esc_html($budget); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($categoria) : ?>
                        <div class="intervento__dato d-grid align-items-baseline gap-3">
                            <span class="secondary-font fs-mono-body text-textlight">Categoria</span>
                            <span><?php echo esc_html($categoria); ?></span>
                        </div>
                    <?php endif; ?>

                </aside>

            </div>
        </div>
    </section>

    <?php if ($galleria) : ?>
        <section class="intervento__galleria pb-d pb-lg-g">
            <div class="container px-4">
                <div class="row row-cols-2 row-cols-lg-3 g-3 g-md-4 align-items-start">
                    <?php foreach ($galleria as $img_id) : ?>
                        <div class="col">
                            <a href="<?php echo esc_url(wp_get_attachment_image_url($img_id, 'large')); ?>"
                               data-lightbox="galleria-<?php the_ID(); ?>">
                                <span class="intervento__galleria-img hover-border-img">
                                    <?php echo wp_get_attachment_image($img_id, 'medium'); ?>
                                </span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // next intervento: database order (ID asc), wraps to first
    $ids = get_posts([
        'post_type'      => 'interventi',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'posts_per_page' => -1,
    ]);
    $next_id = null;
    if (count($ids) > 1) {
        $pos     = array_search(get_the_ID(), $ids, true);
        $next_id = $ids[($pos + 1) % count($ids)];
    }
    ?>

    <?php if ($next_id) :
        $next_cop = get_field('copertina', $next_id);
    ?>
        <section class="intervento__prossimo pb-d pb-lg-a">
            <div class="container px-4">
                <div class="position-relative">
                    <h3 class="section-label pb-3"><?php pll_e('Prossimo intervento'); ?></h3>
                    <div class="intervento__prossimo-card pt-4 d-flex flex-column flex-lg-row align-items-lg-center border-top">
                        <?php if ($next_cop) : ?>
                            <?php echo wp_get_attachment_image($next_cop['ID'], 'medium_large', false, [
                                'alt'   => $next_cop['alt'],
                            ]); ?>
                        <?php endif; ?>
                        <h3 class="pt-3 pb-4 py-lg-0 text-bg"><?php echo esc_html(get_the_title($next_id)); ?></h3>
                        <p class="dark ms-lg-auto">
                            <a href="<?php echo esc_url(get_permalink($next_id)); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Esplora intervento'); ?></span></a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php endwhile; ?>

<?php get_footer(); ?>
