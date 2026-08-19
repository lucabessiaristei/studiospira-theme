<?php
$intervento = $args['intervento'];
$copertina  = get_field('copertina', $intervento->ID);
?>
<a href="<?php echo esc_url(get_permalink($intervento)); ?>" class="front-page__intervento d-flex flex-column gap-3">
    <?php if ($copertina) : ?>
        <span class="front-page__intervento-img hover-border-img">
            <img src="<?php echo esc_url($copertina['sizes']['large']); ?>"
                 alt="<?php echo esc_attr($copertina['alt']); ?>">
        </span>
    <?php endif; ?>
    <span class="front-page__intervento-title fs-body"><?php echo esc_html(get_the_title($intervento)); ?></span>
</a>
