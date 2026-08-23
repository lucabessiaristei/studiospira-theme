<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <?php $slug = get_post_field('post_name', get_the_ID()); ?>

    <main id="primary" class="site-main pb-a pb-lg-g page-<?php echo esc_attr($slug); ?>">

    <div class="container px-4 py-4 py-md-a pt-lg-f pb-lg-d">
            <?php get_template_part('template-parts/headline'); ?>
        </div>

        <div class="container px-4">
            <?php
            $part = 'template-parts/page/' . $slug;
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
