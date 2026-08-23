<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

    <header id="masthead" class="site-header fixed-top">
        <div class="container h-100 d-flex justify-content-between py-3 px-4 align-items-center">
            <div class="site-branding h-100">
                <?php if (is_front_page()) : ?>
                    <?php echo sp_logo('white'); ?>
                    <?php echo sp_logo('black'); ?>
                <?php else : ?>
                    <?php echo sp_logo('black'); ?>
                <?php endif; ?>
            </div>

            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu">
                <span class="visually-hidden"><?php pll_e('Menu'); ?></span>
                <span class="menu-toggle__icon" aria-hidden="true"><span></span><span></span><span></span></span>
            </button>

            <nav class="main-navigation" aria-label="Primary navigation">
                <div class="main-navigation__inner container px-4">
                <?php wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                ]); ?>

                <div class="main-navigation__meta dark text-weiss">
                    <?php $header_languages = function_exists('pll_the_languages') ? pll_the_languages(['raw' => 1]) : []; ?>
                    <?php if ($header_languages) : ?>
                        <div class="main-navigation__lang d-flex gap-2">
                            <?php foreach ($header_languages as $lang) : ?>
                                <a href="<?php echo esc_url($lang['url']); ?>"
                                   class="sp-btn ghost sp-small"
                                   <?php echo $lang['current_lang'] ? 'aria-current="true"' : ''; ?>>
                                    <?php echo esc_html($lang['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php get_template_part('template-parts/footer-contacts', null, [
                        'variant' => 'dark',
                        'fields'  => ['email', 'telefono'],
                    ]); ?>
                </div>
                </div>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content">
        
