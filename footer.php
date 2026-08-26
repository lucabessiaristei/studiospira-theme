    </div><!-- #content -->

    <footer id="colophon" class="site-footer">

        <div class="footer-cta bg-accent">
            <div class="container px-4 py-a d-flex flex-wrap align-items-center justify-content-center justify-content-lg-between gap-4 text-center text-lg-start">
                <h3 class="mb-0"><?php pll_e('Hai un edificio storico da restaurare?'); ?></h3>
                <a href="<?php echo esc_url(home_url('/contatti')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Parlaci del tuo edificio'); ?></span></a>
            </div>
        </div>

        <div class="footer-main dark bg-bg text-weiss">
            <div class="container px-4 py-5 pt-md-a d-flex flex-column gap-5 gap-md-a">

                <div class="row gx-0 gy-5 w-100">

                    <div class="col-12 col-lg-3">
                        <?php echo sp_logo('white'); ?>
                    </div>

                    <div class="col-12 col-lg-3">
                        <?php wp_nav_menu([
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'depth'          => 1,
                        ]); ?>
                    </div>

                    <div class="col-12 col-lg-3">
                        <?php get_template_part('template-parts/footer-contacts', null, ['variant' => 'dark']); ?>
                    </div>

                    <?php
                    $footer_legal_lines = array_filter([
                        get_option('sp_footer_registro_imprese'),
                        get_option('sp_footer_capitale_sociale'),
                        get_option('sp_footer_piva'),
                    ]);
                    ?>
                    <div class="col-12 col-lg-3 d-flex flex-column align-items-start align-items-lg-end gap-5 text-lg-end">
                        <?php if ($footer_legal_lines) : ?>
                            <p class="footer-fiscal fs-body-small text-weiss-75">
                                <?php echo implode('<br>', array_map('esc_html', $footer_legal_lines)); ?>
                            </p>
                        <?php endif; ?>

                        <?php $footer_languages = function_exists('pll_the_languages') ? pll_the_languages(['raw' => 1]) : []; ?>
                        <?php if ($footer_languages) : ?>
                            <div class="footer-lang d-flex flex-column-reverse gap-2">
                                <?php foreach ($footer_languages as $lang) : ?>
                                    <a href="<?php echo esc_url($lang['url']); ?>"
                                       class="sp-btn ghost sp-small"
                                       <?php echo $lang['current_lang'] ? 'aria-current="true"' : ''; ?>>
                                        <?php echo esc_html($lang['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="footer-bottom fs-body-small w-100 d-flex flex-column flex-lg-row justify-content-lg-center gap-4 gap-lg-d text-weiss-75">
                    <p class="mb-0">&copy; <?php echo esc_html(date('Y')); ?> Spira</p>

                    <nav class="footer-policies d-flex flex-wrap gap-4">
                        <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php pll_e('Privacy Policy'); ?></a>
                        <a href="<?php echo esc_url(home_url('/cookie-policy')); ?>"><?php pll_e('Cookie Policy'); ?></a>
                    </nav>

                    <p class="footer-credits mb-0">Design e Sviluppo <a href="/">Filippo Viciani</a> & <a href="/">Luca Bessi Aristei</a></p>
                </div>

            </div>
        </div>

    </footer>

</div><!-- #page -->

<?php get_template_part('template-parts/lightbox'); ?>

<?php wp_footer(); ?>
</body>
</html>
