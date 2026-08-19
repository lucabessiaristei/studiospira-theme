<?php

if (!defined('ABSPATH')) exit;

add_action('acf/init', function() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'    => 'group_pages',
        'title'  => 'Testata pagina',
        'fields' => [

            [
                'key'          => 'field_pages_headline',
                'name'         => 'headline',
                'label'        => 'Headline',
                'type'         => 'text',
                'required'     => 0,
                'instructions' => 'Titolo mostrato nella parte alta della pagina, al posto del titolo.',
            ],
        ],
        'location' => [[
            ['param' => 'post_type', 'operator' => '==', 'value' => 'page'],
            ['param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'],
        ]],
        'menu_order'      => 0,
        'position'        => 'acf_after_title',
        'style'           => 'default',
        'label_placement' => 'top',
    ]);
});
