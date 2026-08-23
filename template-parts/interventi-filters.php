<?php
$interventi_filter_destinazione_terms = get_terms(['taxonomy' => 'destinazione_uso', 'hide_empty' => true]);
if (is_wp_error($interventi_filter_destinazione_terms)) $interventi_filter_destinazione_terms = [];

$interventi_filter_destinazione_options = array_map(function($term) {
    return ['value' => $term->term_id, 'label' => $term->name];
}, $interventi_filter_destinazione_terms);

$interventi_filter_posizione_options = array_map(function($posizione) {
    return ['value' => sanitize_title($posizione), 'label' => $posizione];
}, sp_get_used_posizioni());

$interventi_filter_servizi_options = array_map(function($servizio) {
    return ['value' => $servizio->ID, 'label' => get_the_title($servizio)];
}, sp_get_used_servizi());

$interventi_filter_groups = [
    'destinazione_uso' => ['label' => pll__('Destinazione d\'uso'), 'options' => $interventi_filter_destinazione_options],
    'posizione'        => ['label' => pll__('Posizione'),          'options' => $interventi_filter_posizione_options],
    'servizio'         => ['label' => pll__('Servizio'),           'options' => $interventi_filter_servizi_options],
];
?>
<?php // heading, reset and list are flex siblings; CSS order/basis moves the
      // reset between the two rows per breakpoint ?>
<div class="interventi-filters d-flex flex-wrap justify-content-between position-relative">
    <?php // desktop only: shared line the dropdowns hang from ?>
    <div class="interventi-filters__rule position-absolute w-100 top-100 border-bottom" style="z-index: 1000; pointer-events: none;"></div>

    <h6 class="interventi-filters__heading secondary-font fs-mono-body-small mb-0 text-accent"><?php pll_e('Filtri'); ?></h6>

    <div class="interventi-filters__reset-wrap dark">
        <button type="button" class="interventi-filters__reset mb-lg-4 sp-btn sp-small is-hidden">
            <span class="sp-btn__label"><?php pll_e('Reset filtri'); ?></span>
        </button>
    </div>

    <div class="interventi-filters__list d-flex flex-column flex-lg-row flex-wrap gap-3 gap-lg-5">
        <?php foreach ($interventi_filter_groups as $key => $group) :
            if (!$group['options']) continue;
        ?>
            <div class="interventi-filter" data-filter="<?php echo esc_attr($key); ?>">
                <button type="button" class="interventi-filter__toggle sp-btn ghost sp-small no-underline">
                    <?php echo esc_html($group['label']); ?>
                    <span class="interventi-filter__toggle-clear" aria-hidden="true">&times;</span>
                </button>
                <div class="interventi-filter__menu">
                    <?php foreach ($group['options'] as $option) : ?>
                        <button type="button" class="interventi-filter__option" data-value="<?php echo esc_attr($option['value']); ?>">
                            <span class="interventi-filter__option-label"><?php echo esc_html($option['label']); ?></span>
                            <span class="interventi-filter__tick" aria-hidden="true"></span>
                        </button>
                    <?php endforeach; ?>
                </div>
                <?php // mobile only: doubles as the open menu's top border ?>
                <div class="interventi-filter__rule" aria-hidden="true"></div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
