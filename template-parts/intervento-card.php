<?php
$intervento = $args['intervento'];
$copertina  = get_field('copertina', $intervento->ID);
$sizes      = $args['sizes'] ?? SP_SIZES_CARD;            // displayed width, see sp_image()
$loading    = !empty($args['eager']) ? 'eager' : 'lazy';  // eager only for cards visible on load
?>
<a href="<?php echo esc_url(get_permalink($intervento)); ?>" class="front-page__intervento">
    <?php if ($copertina) : ?>
        <span class="front-page__intervento-img hover-border-img">
            <?php echo sp_image($copertina, 'large', $sizes, ['loading' => $loading]); ?>
        </span>
    <?php endif; ?>
    <span class="front-page__intervento-title fs-body mb-2"><?php echo esc_html(get_the_title($intervento)); ?></span>
</a>
