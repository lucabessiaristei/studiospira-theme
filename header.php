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
        <div class="h-100 d-flex justify-content-between py-3 px-4 align-items-center">
            <div class="site-branding h-100">
                <?php if (is_front_page()) : ?>
                    <?php echo sp_logo('white'); ?>
                    <?php echo sp_logo('black'); ?>
                <?php else : ?>
                    <?php echo sp_logo('black'); ?>
                <?php endif; ?>
            </div>

            <nav class="main-navigation">
                <?php wp_nav_menu(['theme_location' => 'primary']); ?>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content">
        
