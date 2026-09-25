<?php

if (!defined('ABSPATH')) exit;

add_action('template_redirect', function () {
    if (current_user_can('manage_options')) return;

    status_header(503);
    nocache_headers();
    header('Retry-After: 3600');

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php bloginfo('name'); ?></title>
        <style>
            html, body {
                height: 100%;
                margin: 0;
            }
            body {
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                background: #111;
                color: #fff;
                text-align: center;
                padding: 0 24px;
            }
            h1 {
                font-size: 1.4rem;
                margin: 0 0 8px;
            }
            p {
                font-size: 1rem;
                opacity: 0.8;
                margin: 0;
            }
            a {
                color: inherit;
            }
        </style>
    </head>
    <body>
        <div>
            <h1><?php bloginfo('name'); ?></h1>
            <p>Sito in manutenzione. Torneremo presto online.</p>
            <p><a href="<?php echo esc_url(wp_login_url()); ?>">Accedi</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
});
