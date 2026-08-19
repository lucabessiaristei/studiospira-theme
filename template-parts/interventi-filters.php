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
<div class="interventi-filters d-flex flex-wrap justify-content-between gap-4 position-relative">
    <div class="position-absolute w-100 top-100 border-bottom" style="z-index: 1000; pointer-events: none;"></div>

    <div class="interventi-filters__list d-flex flex-wrap gap-5">
        <?php foreach ($interventi_filter_groups as $key => $group) :
            if (!$group['options']) continue;
        ?>
            <div class="interventi-filter" data-filter="<?php echo esc_attr($key); ?>">
                <button type="button" class="interventi-filter__toggle sp-btn ghost no-underline">
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
            </div>
        <?php endforeach; ?>
    </div>

    <div class="interventi-filters__reset-wrap dark">
        <button type="button" class="interventi-filters__reset sp-btn mb-4 is-hidden">
            <span class="sp-btn__label"><?php pll_e('Reset filtri'); ?></span>
        </button>
    </div>

</div>
