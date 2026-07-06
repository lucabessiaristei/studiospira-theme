<?php

if (!defined('ABSPATH')) exit;

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_pubblicazioni',
        'title'  => 'Dati pubblicazione',
        'fields' => [

            [
                'key'      => 'field_pubblicazioni_a_cura_di',
                'name'     => 'a_cura_di',
                'label'    => 'A cura di',
                'type'     => 'text',
                'required' => 0,
                'instructions' => 'Curatore o autore principale, es. Maurizio De Vita',
            ],
            [
                'key'      => 'field_pubblicazioni_editore',
                'name'     => 'editore',
                'label'    => 'Editore',
                'type'     => 'text',
                'required' => 0,
            ],
            [
                'key'      => 'field_pubblicazioni_anno',
                'name'     => 'anno',
                'label'    => 'Anno',
                'type'     => 'number',
                'required' => 0,
                'min'      => 1900,
                'max'      => 2100,
                'step'     => 1,
            ],
            [
                'key'      => 'field_pubblicazioni_isbn',
                'name'     => 'isbn',
                'label'    => 'ISBN',
                'type'     => 'text',
                'required' => 0,
            ],
            [
                'key'      => 'field_pubblicazioni_pagine',
                'name'     => 'pagine',
                'label'    => 'Pagine',
                'type'     => 'number',
                'required' => 0,
                'min'      => 1,
                'step'     => 1,
            ],
            [
                'key'          => 'field_pubblicazioni_info',
                'name'         => 'info',
                'label'        => 'Info',
                'type'         => 'textarea',
                'required'     => 0,
                'rows'         => 4,
                'instructions' => 'Note estese: saggio, pagine del contributo, autore specifico.',
            ],
            [
                'key'           => 'field_pubblicazioni_tipo_url',
                'name'          => 'tipo_url',
                'label'         => 'Tipo link',
                'type'          => 'radio',
                'required'      => 0,
                'choices'       => [
                    'acquisto' => 'Acquisto',
                    'download' => 'Download',
                ],
                'default_value' => 'acquisto',
                'layout'        => 'horizontal',
                'allow_null'    => 1,
            ],
            [
                'key'              => 'field_pubblicazioni_url',
                'name'             => 'url',
                'label'            => 'URL',
                'type'             => 'url',
                'required'         => 0,
                'conditional_logic'=> [[
                    ['field' => 'field_pubblicazioni_tipo_url', 'operator' => '!=empty'],
                ]],
            ],
        ],
        'location' => [[
            ['param' => 'post_type', 'operator' => '==', 'value' => 'pubblicazioni'],
        ]],
        'menu_order'      => 0,
        'position'        => 'normal',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);
});
