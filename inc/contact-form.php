<?php

if (!defined('ABSPATH'))
    exit;

add_action('wp_enqueue_scripts', function () {
    if (!is_page() || !sp_post_slug_is('contatti'))
        return;

    wp_enqueue_script(
        'cloudflare-turnstile',
        'https://challenges.cloudflare.com/turnstile/v0/api.js',
        [],
        null,
        true
    );
});

function sp_turnstile_verify($token)
{
    $secret = get_option('sp_turnstile_secret_key');
    if (!$secret || !$token)
        return false;

    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        'body' => [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
        ],
    ]);

    if (is_wp_error($response))
        return false;

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return !empty($body['success']);
}

function sp_contact_form_email_wrap($title, $body_html)
{
    $indirizzo = get_option('sp_contatti_indirizzo');
    $email = get_option('sp_contatti_email');
    $telefono = get_option('sp_contatti_telefono');

    // Familjen Grotesk is a progressive enhancement here: Apple Mail/most
    // webmail will load it via this @import, Outlook/Gmail apps ignore it and
    // fall back to the system-sans stack declared on every inline font-family.
    $font_stack = "'Familjen Grotesk',Arial,Helvetica,sans-serif";

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="it">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <title><?php echo esc_html($title); ?></title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Familjen+Grotesk:ital,wght@0,400..700;1,400..700&display=swap');

            body,
            table,
            td,
            span,
            p,
            a,
            h1 {
                font-family:
                    <?php echo $font_stack; ?>
                ;
            }

            body {
                margin: 0;
                padding: 0;
                background-color: #FFFCFB;
            }

            .email-wrapper {
                background-color: #FFFCFB;
                padding: 40px 16px;
            }

            .email-container {
                max-width: 480px;
                width: 100%;
                background-color: #ffffff;
            }

            .email-header {
                background-color: #7C1715;
                padding: 18px 32px;
            }

            .email-header img {
                display: block;
            }

            .email-divider {
                background-color: #F9877A;
                font-size: 4px;
                line-height: 4px;
            }

            .email-content {
                padding: 40px 32px 42px;
                border-left: 1px solid #e5ded9;
                border-right: 1px solid #e5ded9;
                color: #211F26;
                font-size: 15px;
                line-height: 1.6;
            }

            .email-title {
                margin: 0 0 20px;
                font-size: 15px;
                font-weight: 700;
                text-transform: uppercase;
                color: #7C1715;
            }

            .email-footer {
                padding: 16px 32px 20px;
                color: #211F26;
                font-size: 12px;
                background-color: #F9877A;
                line-height: 1.7;
            }

            .email-footer a {
                color: inherit;
                text-decoration-line: underline;
                text-decoration-color: currentColor;
                text-underline-offset: 0.3em;
                text-decoration-thickness: 1px;
            }

            .email-subfooter {
                background-color: #7C1715;
                padding: 14px 32px;
                color: #FFFCFB;
                font-size: 11px;
                text-align: left;
            }

            .email-subfooter a {
                color: #FFFCFB;
                text-decoration-line: underline;
                text-decoration-color: currentColor;
                text-underline-offset: 0.3em;
                text-decoration-thickness: 1px;
            }

            .field {
                margin: 0 0 16px;
            }

            .field-label {
                display: block;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                color: #7C1715;
                margin-bottom: 4px;
            }

            .field-value {
                display: block;
                font-size: 15px;
                line-height: 1.5;
                color: #000000;
            }

            .message-box {
                margin-top: 24px;
                padding: 20px;
                background-color: #FFFCFB;
                border-left: 2px solid #F9877A;
            }

            .message-label {
                display: block;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                color: #7C1715;
                margin-bottom: 8px;
            }

            .message-text {
                margin: 0;
                font-size: 15px;
                line-height: 1.6;
                color: #000000;
            }

            .greeting {
                margin: 0 0 12px;
                font-size: 16px;
                color: #000000;
            }

            .text-muted {
                margin: 0;
                color: #211F26;
            }
        </style>
    </head>

    <body>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="email-wrapper">
            <tr>
                <td align="center">
                    <table role="presentation" width="480" cellpadding="0" cellspacing="0" class="email-container">
                        <tr>
                            <td class="email-header">
                                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/logo/spira-logo-bianco-thick.svg'); ?>"
                                    alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="130" height="41">
                            </td>
                        </tr>
                        <tr>
                            <td class="email-content">
                                <!-- <h1 class="email-title"><?php echo esc_html($title); ?></h1> -->
                                <?php echo $body_html; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="email-footer">
                                <?php if ($indirizzo): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($indirizzo); ?>"
                                        target="_blank" rel="noopener"><?php echo esc_html($indirizzo); ?></a><br>
                                <?php endif; ?>
                                <?php if ($email): ?>
                                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                <?php endif; ?>
                                <?php if ($email && $telefono): ?> &nbsp;—&nbsp; <?php endif; ?>
                                <?php if ($telefono): ?>
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $telefono)); ?>"><?php echo esc_html($telefono); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="email-subfooter">
                                &copy; <?php echo esc_html(date('Y')); ?> Studio Spira &nbsp;—&nbsp;
                                <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(wp_parse_url(home_url(), PHP_URL_HOST)); ?></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

    </html>
    <?php
    return ob_get_clean();
}

// admin-post.php runs outside the frontend request Polylang uses to detect
// the current language, so pll__() would silently fall back to the default
// language here — the visitor's language is captured in a hidden field
// instead and threaded through explicitly with pll_translate_string()
function sp_contatti_t($string, $lang)
{
    return pll_translate_string($string, $lang);
}

function sp_contatti_admin_email_body($nome, $cognome, $email, $telefono, $messaggio, $lang)
{
    $field = function ($label, $value_html) {
        return '<div class="field">'
            . '<span class="field-label">' . esc_html($label) . '</span>'
            . '<span class="field-value">' . $value_html . '</span>'
            . '</div>';
    };

    $body = $field(sp_contatti_t('Nome', $lang), esc_html($nome . ' ' . $cognome));
    $body .= $field(sp_contatti_t('Email', $lang), esc_html($email));

    if ($telefono) {
        $body .= $field(sp_contatti_t('Telefono', $lang), esc_html($telefono));
    }

    $body .= '<div class="message-box">'
        . '<span class="message-label">' . esc_html(sp_contatti_t('Messaggio', $lang)) . '</span>'
        . '<p class="message-text">' . nl2br(esc_html($messaggio)) . '</p>'
        . '</div>';

    return sp_contact_form_email_wrap(sp_contatti_t('Nuovo messaggio dal form contatti', $lang), $body);
}

function sp_contatti_confirmation_email_body($nome, $lang)
{
    $body = '<p class="greeting">' . sprintf(sp_contatti_t('Ciao %s,', $lang), '<strong>' . esc_html($nome) . '</strong>') . '</p>'
        . '<p class="text-muted">' . sp_contatti_t("Grazie per averci scritto. Abbiamo ricevuto il tuo messaggio e ti risponderemo il prima possibile.", $lang) . '</p>';

    return sp_contact_form_email_wrap(sp_contatti_t('Messaggio ricevuto', $lang), $body);
}

function sp_handle_contatti_submit()
{
    // redirect back to whichever language page the form was actually
    // submitted from, rather than hardcoding the Italian /contatti/ URL
    $redirect_base = wp_get_referer() ?: home_url('/contatti');

    if (!isset($_POST['sp_contatti_nonce']) || !wp_verify_nonce($_POST['sp_contatti_nonce'], 'sp_contatti_submit')) {
        wp_safe_redirect(add_query_arg('contatti', 'error', $redirect_base));
        exit;
    }

    $nome = sanitize_text_field($_POST['nome'] ?? '');
    $cognome = sanitize_text_field($_POST['cognome'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $telefono = sanitize_text_field($_POST['telefono'] ?? '');
    $messaggio = sanitize_textarea_field($_POST['messaggio'] ?? '');
    $privacy = !empty($_POST['privacy']);
    $token = $_POST['cf-turnstile-response'] ?? '';

    $lang = sanitize_key($_POST['sp_contatti_lang'] ?? '');
    if (!in_array($lang, pll_languages_list(), true)) {
        $lang = pll_default_language();
    }

    $valid = $nome && $cognome && is_email($email) && $messaggio && $privacy;

    if (!$valid || !sp_turnstile_verify($token)) {
        wp_safe_redirect(add_query_arg('contatti', 'error', $redirect_base));
        exit;
    }

    $recipient = get_option('sp_contatti_form_recipient');

    $admin_lang = pll_default_language();

    wp_mail(
        $recipient,
        sp_contatti_t('Nuovo messaggio dal form contatti', $admin_lang),
        sp_contatti_admin_email_body($nome, $cognome, $email, $telefono, $messaggio, $admin_lang),
        ['Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email]
    );

    wp_mail(
        $email,
        sp_contatti_t('Abbiamo ricevuto il tuo messaggio', $lang),
        sp_contatti_confirmation_email_body($nome, $lang),
        ['Content-Type: text/html; charset=UTF-8']
    );

    wp_safe_redirect(add_query_arg('contatti', 'success', $redirect_base));
    exit;
}
add_action('admin_post_sp_contatti_submit', 'sp_handle_contatti_submit');
add_action('admin_post_nopriv_sp_contatti_submit', 'sp_handle_contatti_submit');
