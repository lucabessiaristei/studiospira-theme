<?php

if (!defined('ABSPATH')) exit;

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_articoli',
        'title'  => 'Dati articolo',
        'fields' => [

            [
                'key'      => 'field_articoli_url',
                'name'     => 'url',
                'label'    => 'URL',
                'type'     => 'url',
                'required' => 1,
            ],
            [
                'key'           => 'field_articoli_tipo_url',
                'name'          => 'tipo_url',
                'label'         => 'Tipo link',
                'type'          => 'radio',
                'required'      => 0,
                'choices'       => [
                    'esterno'  => 'Esterno',
                    'download' => 'Download',
                ],
                'default_value' => 'esterno',
                'layout'        => 'horizontal',
                'allow_null'    => 0,
            ],
        ],
        'location' => [[
            ['param' => 'post_type', 'operator' => '==', 'value' => 'articoli'],
        ]],
        'menu_order'      => 0,
        'position'        => 'normal',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);
});
