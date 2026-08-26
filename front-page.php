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

    <section class="front-page__hero mb-5 mb-lg-b text-weiss">
        <div class="container px-4 pt-d pt-md-f pb-c text-center">

            <?php if ($subheadline) : ?>
                <h3 class="front-page__kicker fs-body secondary-font mb-3 text-weiss-75"><?php echo esc_html($subheadline); ?></h3>
            <?php endif; ?>

            <h1 class="front-page__headline display mb-5 mb-md-c"><?php echo esc_html($headline ?: get_the_title()); ?></h1>

            <a href="<?php echo esc_url(home_url('/contatti')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Parlaci del tuo edificio'); ?></span></a>

        </div>
    </section>

    <?php if ($banner_photo) : ?>
        <section class="front-page__banner mb-md-4">
            <div class="container px-4">
                <img src="<?php echo esc_url($banner_photo['sizes']['large']); ?>"
                     alt="<?php echo esc_attr($banner_photo['alt']); ?>"
                     class="banner_photo">
            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider h-d h-lg-g" aria-hidden="true"></div>

    <?php if ($testo_1) : ?>
        <section class="front-page__testo py-md-a text-md-center">
            <div class="container px-4 px-lg-c">
                <div class="front-page__testo-content"><?php echo $testo_1; ?></div>
            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider h-d h-lg-g" aria-hidden="true"></div>

    <?php if ($interventi_evidenza) : ?>
        <section class="front-page__interventi">
            <div class="container px-4">
                <div class="position-relative">

                    <h3 class="section-label position-md-absolute top-0 start-0 pb-3 d-flex align-items-center"><?php pll_e('Interventi in evidenza'); ?></h3>

                    <div class="pt-4 pt-md-5 pt-lg-a pt-xxl-b pb-md-4 border-top">

                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gx-3 gx-md-4 gx-lg-a gx-xl-c gy-4 gy-md-5 gy-lg-b gy-xl-d">
                            <?php foreach ([...$interventi_evidenza, ...$interventi_evidenza, ...$interventi_evidenza] as $intervento) : // TEMP: x3 per test layout ?>
                                <div class="col">
                                    <?php get_template_part('template-parts/intervento-card', null, ['intervento' => $intervento]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="front-page__interventi-cta dark mt-5 mt-lg-b text-md-center">
                            <a href="<?php echo esc_url(get_post_type_archive_link('interventi')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Tutti gli interventi'); ?></span></a>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider h-d h-lg-g" aria-hidden="true"></div>

    <?php if ($testo_2) : ?>
        <section class="front-page__testo front-page__testo--cta py-md-a text-md-center">
            <div class="container px-4 px-lg-c">
                <div class="front-page__testo-content"><?php echo $testo_2; ?></div>
                <p class="front-page__testo-cta dark mt-5 mt-lg-b">
                    <a href="<?php echo esc_url(home_url('/chi-siamo')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Scopri chi siamo'); ?></span></a>
                </p>
            </div>
        </section>
    <?php endif; ?>

    <div class="front-page__divider h-d h-lg-g" aria-hidden="true"></div>

</main>

<?php endwhile; ?>

<?php get_footer(); ?>
