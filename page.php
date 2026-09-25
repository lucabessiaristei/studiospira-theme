<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <?php
    $slug = get_post_field('post_name', get_the_ID());
    $part = 'template-parts/page/' . $slug;

    // a translation gets its own slug (e.g. "contact" for "contatti"), so if
    // that slug has no matching template, fall back to the default-language
    // translation's slug/template — the page-specific markup (forms, data
    // blocks, etc.) lives there, not in the_content()
    if (!locate_template($part . '.php') && function_exists('pll_get_post') && function_exists('pll_default_language')) {
        $default_id = pll_get_post(get_the_ID(), pll_default_language());
        if ($default_id) {
            $default_slug = get_post_field('post_name', $default_id);
            $default_part = 'template-parts/page/' . $default_slug;
            if (locate_template($default_part . '.php')) {
                $part = $default_part;
            }
        }
    }
    ?>

    <main id="primary" class="site-main pb-d pb-lg-g page-<?php echo esc_attr($slug); ?>">

        <div class="container px-4 py-5 py-md-a pt-lg-f pb-lg-c">
            <?php get_template_part('template-parts/headline'); ?>
        </div>

        <div class="container px-4">
            <?php
            if (locate_template($part . '.php')) :
                get_template_part($part);
            else :
                the_content();
            endif;
            ?>
        </div>

    </main>

<?php endwhile; ?>

<?php get_footer(); ?>
