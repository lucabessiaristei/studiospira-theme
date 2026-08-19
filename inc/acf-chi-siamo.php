<?php

if (!defined('ABSPATH')) exit;

add_filter('acf/location/rule_types', function($choices) {
    $choices['Post']['post_slug'] = 'Post Slug';
    return $choices;
});

add_filter('acf/location/rule_match/post_slug', function($match, $rule, $options) {
    if (empty($options['post_id'])) return false;

    $slug = get_post_field('post_name', $options['post_id']);
    $match = ($slug === $rule['value']);

    if ($rule['operator'] === '!=') $match = !$match;

    return $match;
}, 10, 3);

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_chi_siamo_cover',
        'title'  => 'Foto banner',
        'fields' => [

            [
                'key'           => 'field_chi_siamo_banner_photo',
                'name'          => 'banner_photo',
                'label'         => 'Foto banner',
                'type'          => 'image',
                'required'      => 0,
                'return_format' => 'array',
            ],
        ],
        'location' => [[
            ['param' => 'post_slug', 'operator' => '==', 'value' => 'chi-siamo'],
        ]],
        'menu_order'      => 0,
        'position'        => 'acf_after_title',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);

    acf_add_local_field_group([
        'key'    => 'group_chi_siamo',
        'title'  => 'Chi siamo — dati extra',
        'fields' => [

            [
                'key'      => 'field_chi_siamo_fondazione',
                'name'     => 'fondazione',
                'label'    => 'Fondazione',
                'type'     => 'text',
                'required' => 0,
            ],
            [
                'key'      => 'field_chi_siamo_interventi',
                'name'     => 'interventi',
                'label'    => 'Interventi',
                'type'     => 'text',
                'required' => 0,
            ],
            [
                'key'      => 'field_chi_siamo_sede',
                'name'     => 'sede',
                'label'    => 'Sede',
                'type'     => 'text',
                'required' => 0,
            ],
            [
                'key'          => 'field_chi_siamo_certificazione',
                'name'         => 'certificazione',
                'label'        => 'Certificazione',
                'type'         => 'textarea',
                'required'     => 0,
                'rows'         => 4,
                'instructions' => 'Paragrafo tecnico mostrato in fondo alla colonna dati.',
            ],
            [
                'key'           => 'field_chi_siamo_articoli',
                'name'          => 'articoli',
                'label'         => 'Articoli',
                'type'          => 'relationship',
                'required'      => 0,
                'post_type'     => ['articoli'],
                'filters'       => ['search'],
                'return_format' => 'id',
            ],
            [
                'key'           => 'field_chi_siamo_pubblicazioni',
                'name'          => 'pubblicazioni',
                'label'         => 'Pubblicazioni',
                'type'          => 'relationship',
                'required'      => 0,
                'post_type'     => ['pubblicazioni'],
                'filters'       => ['search'],
                'return_format' => 'id',
            ],
        ],
        'location' => [[
            ['param' => 'post_slug', 'operator' => '==', 'value' => 'chi-siamo'],
        ]],
        'menu_order'      => 1,
        'position'        => 'normal',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);
});
