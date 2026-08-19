<?php

if (!defined('ABSPATH')) exit;

add_action('admin_menu', function() {
    add_menu_page(
        'Footer',
        'Footer',
        'manage_options',
        'footer-settings',
        'sp_render_footer_settings_page',
        'dashicons-phone',
        90 // near the bottom of the admin menu
    );
});

add_action('admin_init', function() {
    register_setting('sp_footer', 'sp_contatti_indirizzo',      ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('sp_footer', 'sp_contatti_email',          ['sanitize_callback' => 'sanitize_email']);
    register_setting('sp_footer', 'sp_contatti_telefono',       ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('sp_footer', 'sp_footer_registro_imprese', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('sp_footer', 'sp_footer_capitale_sociale', ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('sp_footer', 'sp_footer_piva',             ['sanitize_callback' => 'sanitize_text_field']);

    add_settings_section('sp_footer_contatti', 'Contatti', '__return_false', 'footer-settings');

    add_settings_field(
        'sp_contatti_indirizzo',
        'Indirizzo',
        function() {
            printf(
                '<input type="text" name="sp_contatti_indirizzo" value="%s" class="regular-text">
                 <p class="description">L\'indirizzo della sede, mostrato in fondo al sito con un link diretto a Google Maps.</p>',
                esc_attr(get_option('sp_contatti_indirizzo'))
            );
        },
        'footer-settings',
        'sp_footer_contatti'
    );

    add_settings_field(
        'sp_contatti_email',
        'Email',
        function() {
            printf(
                '<input type="email" name="sp_contatti_email" value="%s" class="regular-text">
                 <p class="description">L\'email di contatto mostrata in fondo al sito, protetta automaticamente dallo spam.</p>',
                esc_attr(get_option('sp_contatti_email'))
            );
        },
        'footer-settings',
        'sp_footer_contatti'
    );

    add_settings_field(
        'sp_contatti_telefono',
        'Telefono',
        function() {
            printf(
                '<input type="text" name="sp_contatti_telefono" value="%s" class="regular-text">
                 <p class="description">Il numero di telefono mostrato in fondo al sito. Usa il formato internazionale, es. +39 06 1234567.</p>',
                esc_attr(get_option('sp_contatti_telefono'))
            );
        },
        'footer-settings',
        'sp_footer_contatti'
    );

    add_settings_section('sp_footer_societa', 'Dati societari', '__return_false', 'footer-settings');

    add_settings_field(
        'sp_footer_registro_imprese',
        'Registro Imprese',
        function() {
            printf(
                '<input type="text" name="sp_footer_registro_imprese" value="%s" class="regular-text">
                 <p class="description">Es: Registro Imprese di Firenze</p>',
                esc_attr(get_option('sp_footer_registro_imprese'))
            );
        },
        'footer-settings',
        'sp_footer_societa'
    );

    add_settings_field(
        'sp_footer_capitale_sociale',
        'Capitale Sociale',
        function() {
            printf(
                '<input type="text" name="sp_footer_capitale_sociale" value="%s" class="regular-text">
                 <p class="description">Es: Capitale Sociale 10.000 € i.v.</p>',
                esc_attr(get_option('sp_footer_capitale_sociale'))
            );
        },
        'footer-settings',
        'sp_footer_societa'
    );

    add_settings_field(
        'sp_footer_piva',
        'Codice Fiscale / Partita IVA',
        function() {
            printf(
                '<input type="text" name="sp_footer_piva" value="%s" class="regular-text">
                 <p class="description">Es: C.F. / P.IVA 05449070480</p>',
                esc_attr(get_option('sp_footer_piva'))
            );
        },
        'footer-settings',
        'sp_footer_societa'
    );
});

function sp_render_footer_settings_page() {
    ?>
    <div class="wrap">
        <h1>Footer</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sp_footer');
            do_settings_sections('footer-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
