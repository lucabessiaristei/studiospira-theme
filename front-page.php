<?php get_header(); ?>

<?php while (have_posts()) : the_post();

$subheadline         = get_field('subheadline');
$headline            = get_field('headline');
$banner_photo        = get_field('banner_photo');
$testo_1             = get_field('testo_1');
$interventi_evidenza = get_field('interventi_evidenza');
$testo_2             = get_field('testo_2');
?>

<main id="primary" class="site-main front-page">

    <section class="front-page__hero text-weiss mb-b">
        <div class="container px-4 text-center py-a pt-md-f pb-md-d">

            <?php if ($subheadline) : ?>
                <h3 class="front-page__kicker fs-body secondary-font mb-3 text-weiss-75"><?php echo esc_html($subheadline); ?></h3>
            <?php endif; ?>

            <h1 class="front-page__headline display mb-c"><?php echo esc_html($headline ?: get_the_title()); ?></h1>

            <a href="<?php echo esc_url(home_url('/contatti')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Parlaci del tuo edificio'); ?></span></a>

        </div>
    </section>

    <?php if ($banner_photo) : ?>
        <section class="front-page__banner mb-4">
            <div class="container px-4">
                <img src="<?php echo esc_url($banner_photo['sizes']['large']); ?>"
                     alt="<?php echo esc_attr($banner_photo['alt']); ?>"
                     class="banner_photo">
            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider" aria-hidden="true"></div>

    <?php if ($testo_1) : ?>
        <section class="front-page__testo py-a text-center">
            <div class="container-md px-4 px-lg-c">
                <div class="front-page__testo-content"><?php echo $testo_1; ?></div>
            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider" aria-hidden="true"></div>

    <?php if ($interventi_evidenza) : ?>
        <section class="front-page__interventi">
            <div class="container px-4">

                <div class="position-relative">
                    <h3 class="section-label d-flex align-items-center pb-2 position-absolute start-0 top-0"><?php pll_e('Interventi in evidenza'); ?></h3>
    
                    <div class="pt-b pb-4 border-top">
                        <div class="row row-cols-3 gx-c gy-d">
                            <?php foreach ([...$interventi_evidenza, ...$interventi_evidenza, ...$interventi_evidenza] as $intervento) : // TEMP: x3 per test layout ?>
                                <div class="col">
                                    <?php get_template_part('template-parts/intervento-card', null, ['intervento' => $intervento]); ?>
                                </div>
                            <?php endforeach; ?>
                            
                        </div>
        
                        <div class="front-page__interventi-cta dark text-center mt-b">
                            <a href="<?php echo esc_url(get_post_type_archive_link('interventi')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Tutti gli interventi'); ?></span></a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider" aria-hidden="true"></div>

    <?php if ($testo_2) : ?>
        <section class="front-page__testo front-page__testo--cta pt-a pb-4 text-center">
            <div class="container px-4">
                <div class="front-page__testo-content"><?php echo $testo_2; ?></div>
                <p class="front-page__testo-cta dark mt-b">
                    <a href="<?php echo esc_url(home_url('/chi-siamo')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Scopri chi siamo'); ?></span></a>
                </p>
            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider mb-md-a" aria-hidden="true"></div>

</main>

<?php endwhile; ?>

<?php get_footer(); ?>
