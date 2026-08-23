<?php
$variant     = isset($args['variant']) ? $args['variant'] : 'dark';
$fields      = isset($args['fields']) ? $args['fields'] : ['indirizzo', 'email', 'telefono'];
$label_class = $variant === 'light' ? 'text-accent' : 'text-weiss-75';
$link_class  = $variant === 'light' ? 'text-accent' : '';

$contatti_indirizzo = in_array('indirizzo', $fields, true) ? get_option('sp_contatti_indirizzo') : '';
$contatti_email     = in_array('email', $fields, true) ? get_option('sp_contatti_email') : '';
$contatti_telefono  = in_array('telefono', $fields, true) ? get_option('sp_contatti_telefono') : '';
?>
<div class="footer-contacts d-flex flex-column gap-4">

    <?php if ($contatti_indirizzo) : ?>
        <div class="footer-contact">
            <h5 class="secondary-font fs-body-small <?php echo esc_attr($label_class); ?>"><?php pll_e('Indirizzo'); ?></h5>
            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($contatti_indirizzo); ?>"
               target="_blank" rel="noopener" class="fs-body mb-0 <?php echo esc_attr($link_class); ?>">
                <?php echo esc_html($contatti_indirizzo); ?>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($contatti_email) : ?>
        <div class="footer-contact">
            <h5 class="secondary-font fs-body-small <?php echo esc_attr($label_class); ?>"><?php pll_e('Email'); ?></h5>
            <a href="#" class="js-protected-email fs-body mb-0 <?php echo esc_attr($link_class); ?>"
               data-protected-email="<?php echo esc_attr(base64_encode($contatti_email)); ?>"></a>
        </div>
    <?php endif; ?>

    <?php if ($contatti_telefono) : ?>
        <div class="footer-contact">
            <h5 class="secondary-font fs-body-small <?php echo esc_attr($label_class); ?>"><?php pll_e('Telefono'); ?></h5>
            <a href="#" class="js-protected-tel fs-body mb-0 <?php echo esc_attr($link_class); ?>"
               data-protected-tel="<?php echo esc_attr(base64_encode($contatti_telefono)); ?>"></a>
        </div>
    <?php endif; ?>

</div>
