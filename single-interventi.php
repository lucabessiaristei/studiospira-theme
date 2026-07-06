<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<?php
$descrizione     = get_field('descrizione');
$anno_inizio     = get_field('anno_inizio');
$anno_fine       = get_field('anno_fine');
$posizione       = get_field('posizione');
$destinazione    = get_field('destinazione_uso');
$committenza     = get_field('committenza');
$team            = get_field('team');
$budget          = get_field('budget');
$categoria       = get_field('categoria');
$copertina       = get_field('copertina');
$servizi         = get_field('servizi');
$articoli        = get_field('articoli');
$foto            = get_field('foto');
?>

<main class="intervento">

    <section class="intervento__hero">
        <div class="container">
            <h1><?php the_title(); ?></h1>
        </div>
        <?php if ($copertina) : ?>
            <img src="<?php echo esc_url($copertina['url']); ?>"
                 alt="<?php echo esc_attr($copertina['alt']); ?>">
        <?php endif; ?>
    </section>

    <section class="intervento__body">
        <div class="container">

            <?php if ($descrizione) : ?>
                <div class="intervento__descrizione">
                    <p><?php echo esc_html($descrizione); ?></p>
                </div>
            <?php endif; ?>

            <aside class="intervento__dati">
                <?php if ($anno_inizio) : ?>
                    <div class="intervento__dato">
                        <span class="label">Anno inizio</span>
                        <span><?php echo esc_html($anno_inizio); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($anno_fine) : ?>
                    <div class="intervento__dato">
                        <span class="label">Anno fine</span>
                        <span><?php echo esc_html($anno_fine); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($posizione) : ?>
                    <div class="intervento__dato">
                        <span class="label">Posizione</span>
                        <span><?php echo esc_html($posizione); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($destinazione) : ?>
                    <div class="intervento__dato">
                        <span class="label">Destinazione d'uso</span>
                        <span><?php echo esc_html(implode(', ', $destinazione)); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($committenza) : ?>
                    <div class="intervento__dato">
                        <span class="label">Committenza</span>
                        <span><?php echo esc_html($committenza); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($servizi) : ?>
                    <div class="intervento__dato">
                        <span class="label">Servizi</span>
                        <ul>
                            <?php foreach ($servizi as $servizio) : ?>
                                <li><?php echo esc_html($servizio->post_title); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($team) : ?>
                    <div class="intervento__dato">
                        <span class="label">Team</span>
                        <span><?php echo esc_html($team); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($budget) : ?>
                    <div class="intervento__dato">
                        <span class="label">Budget</span>
                        <span><?php echo esc_html($budget); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($categoria) : ?>
                    <div class="intervento__dato">
                        <span class="label">Categoria</span>
                        <span><?php echo esc_html($categoria); ?></span>
                    </div>
                <?php endif; ?>
            </aside>

        </div>
    </section>

    <?php if ($articoli) : ?>
        <section class="intervento__articoli">
            <div class="container">
                <?php foreach ($articoli as $articolo) : ?>
                    <div class="intervento__articolo">
                        <span><?php echo esc_html($articolo['titolo']); ?></span>
                        <a href="<?php echo esc_url($articolo['url']); ?>"
                           target="_blank" rel="noopener">Leggi</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($foto) : ?>
        <section class="intervento__galleria">
            <div class="container">
                <?php foreach ($foto as $img) : ?>
                    <a href="<?php echo esc_url($img['url']); ?>"
                       data-lightbox="galleria-<?php the_ID(); ?>">
                        <img src="<?php echo esc_url($img['sizes']['medium']); ?>"
                             alt="<?php echo esc_attr($img['alt']); ?>">
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // next intervento by menu_order
    $next = new WP_Query([
        'post_type'      => 'interventi',
        'posts_per_page' => 1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'meta_query'     => [[
            'key'     => 'ordine',
            'value'   => get_post_field('menu_order', get_the_ID()),
            'compare' => '>',
            'type'    => 'NUMERIC',
        ]],
    ]);
    ?>

    <?php if ($next->have_posts()) : $next->the_post(); ?>
        <section class="intervento__prossimo">
            <div class="container">
                <span class="label">Prossimo</span>
                <?php $cop = get_field('copertina'); ?>
                <?php if ($cop) : ?>
                    <img src="<?php echo esc_url($cop['sizes']['thumbnail']); ?>"
                         alt="<?php echo esc_attr($cop['alt']); ?>">
                <?php endif; ?>
                <h3><?php the_title(); ?></h3>
                <a href="<?php the_permalink(); ?>">Esplora</a>
            </div>
        </section>
    <?php endif; wp_reset_postdata(); ?>

</main>

<?php endwhile; ?>

<?php get_footer(); ?>
