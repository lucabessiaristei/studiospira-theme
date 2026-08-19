<?php

if (!defined('ABSPATH')) exit;

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_home',
        'title'  => 'Hero home',
        'fields' => [

            [
                'key'      => 'field_home_subheadline',
                'name'     => 'subheadline',
                'label'    => 'Subheadline',
                'type'     => 'text',
                'required' => 0,
            ],
            [
                'key'          => 'field_home_headline',
                'name'         => 'headline',
                'label'        => 'Headline',
                'type'         => 'text',
                'required'     => 0,
                'instructions' => 'Titolo mostrato nell\'hero della home, al posto del titolo.',
            ],
            [
                'key'           => 'field_home_banner_photo',
                'name'          => 'banner_photo',
                'label'         => 'Foto banner',
                'type'          => 'image',
                'required'      => 0,
                'return_format' => 'array',
                'instructions'  => 'Foto a tutta larghezza, ratio 16:9.',
            ],
            [
                'key'      => 'field_home_testo_1',
                'name'     => 'testo_1',
                'label'    => 'Testo 1',
                'type'     => 'wysiwyg',
                'required' => 0,
                'tabs'     => 'visual',
                'media_upload' => 0,
            ],
            [
                'key'              => 'field_home_interventi_evidenza',
                'name'             => 'interventi_evidenza',
                'label'            => 'Interventi in evidenza',
                'type'             => 'relationship',
                'required'         => 0,
                'post_type'        => ['interventi'],
                'filters'          => ['search'],
                'max'              => 6,
                'return_format'    => 'object',
                'instructions'     => 'Max 6, mostrati nella griglia della home.',
            ],
            [
                'key'      => 'field_home_testo_2',
                'name'     => 'testo_2',
                'label'    => 'Testo 2',
                'type'     => 'wysiwyg',
                'required' => 0,
                'tabs'     => 'visual',
                'media_upload' => 0,
            ],
        ],
        'location' => [[
            ['param' => 'page_type', 'operator' => '==', 'value' => 'front_page'],
        ]],
        'menu_order'      => 0,
        'position'        => 'normal',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);
});
