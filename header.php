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

    <header id="masthead" class="site-header">
        <div class="container-xxl d-flex justify-content-between">
            <div class="site-branding">
                <?php echo sp_logo('white'); ?>
            </div>

            <nav class="main-navigation">
                <?php wp_nav_menu(['theme_location' => 'primary']); ?>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content">
        
