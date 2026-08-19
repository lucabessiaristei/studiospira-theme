    </div><!-- #content -->

    <footer id="colophon" class="site-footer">

        <div class="footer-cta bg-accent">
            <div class="container px-4 py-5 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h3 class="mb-0"><?php pll_e('Hai un edificio storico da restaurare?'); ?></h3>
                <a href="<?php echo esc_url(home_url('/contatti')); ?>" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Parlaci del tuo edificio'); ?></span></a>
            </div>
        </div>

        <div class="footer-main bg-bg dark text-weiss">
            <div class="container px-4 pt-a pb-5 d-flex flex-column gap-a align-items-center">

                <div class="w-100 row row-cols-1 row-cols-md-4 g-4">

                    <div class="col">
                        <?php echo sp_logo('white'); ?>
                    </div>

                    <div class="col">
                        <?php wp_nav_menu([
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'depth'          => 1,
                        ]); ?>
                    </div>

                    <div class="col">
                        <?php get_template_part('template-parts/footer-contacts', null, ['variant' => 'dark']); ?>
                    </div>

                    <?php
                    $footer_legal_lines = array_filter([
                        get_option('sp_footer_registro_imprese'),
                        get_option('sp_footer_capitale_sociale'),
                        get_option('sp_footer_piva'),
                    ]);
                    ?>
                    <div class="col col d-flex flex-column gap-5 align-items-md-end text-md-end">
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
                                       class="sp-btn ghost"
                                       <?php echo $lang['current_lang'] ? 'aria-current="true"' : ''; ?>>
                                        <?php echo esc_html($lang['name']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="footer-bottom d-flex flex-wrap align-items-center gap-4 gap-md-d fs-body-small text-weiss-75">
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
