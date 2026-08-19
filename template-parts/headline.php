<?php $headline = isset($args['title']) ? $args['title'] : (get_field('headline') ?: get_the_title()); ?>

<div class="page-heading">
    <div class="page-heading__breadcrumb mb-3 text-textlight"><?php sp_breadcrumb(); ?></div>
    <div class="page-heading__title">
        <h1 class="display"><?php echo esc_html($headline); ?></h1>
    </div>
</div>
