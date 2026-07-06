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
                'key'           => 'field_articoli_is_download',
                'name'          => 'is_download',
                'label'         => 'Tipo',
                'type'          => 'true_false',
                'required'      => 0,
                'default_value' => 0,
                'ui'            => 1,
                'ui_on_text'    => 'Download',
                'ui_off_text'   => 'Esterno',
                'instructions'  => 'Download = attributo download sul link. Esterno = target _blank.',
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
