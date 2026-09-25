<div class="row g-5 g-lg-0">

    <div class="contatti__main col-12 col-lg-7">

        <?php if (isset($_GET['contatti']) && $_GET['contatti'] === 'success') : ?>
            <p class="contatti__form-message contatti__form-message--success"><?php pll_e("Messaggio inviato, grazie! Ti risponderemo il prima possibile."); ?></p>
        <?php elseif (isset($_GET['contatti']) && $_GET['contatti'] === 'error') : ?>
            <p class="contatti__form-message contatti__form-message--error"><?php pll_e("Non è stato possibile inviare il messaggio. Controlla i campi obbligatori e riprova."); ?></p>
        <?php endif; ?>

        <form class="contatti__form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="sp_contatti_submit">
            <input type="hidden" name="sp_contatti_lang" value="<?php echo esc_attr(pll_current_language()); ?>">
            <?php wp_nonce_field('sp_contatti_submit', 'sp_contatti_nonce'); ?>
            <div class="row g-4">

                <div class="col-12 col-md-6">
                    <label class="form-label" for="contatti-nome"><?php pll_e('Nome'); ?> <span class="form-label__required">*</span></label>
                    <input type="text" id="contatti-nome" name="nome" required>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label" for="contatti-cognome"><?php pll_e('Cognome'); ?> <span class="form-label__required">*</span></label>
                    <input type="text" id="contatti-cognome" name="cognome" required>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label" for="contatti-email"><?php pll_e('Email'); ?> <span class="form-label__required">*</span></label>
                    <input type="email" id="contatti-email" name="email" required>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label" for="contatti-telefono"><?php pll_e('Telefono'); ?></label>
                    <input type="tel" id="contatti-telefono" name="telefono">
                </div>

                <div class="col-12">
                    <label class="form-label" for="contatti-messaggio"><?php pll_e('Messaggio'); ?> <span class="form-label__required">*</span></label>
                    <textarea id="contatti-messaggio" name="messaggio" rows="5" required></textarea>
                </div>

                <div class="col-12">
                    <label class="contatti__form-privacy">
                        <input type="checkbox" name="privacy" required>
                        <span>
                            <?php pll_e("Con l'invio del presente modulo acconsento al trattamento dei dati unicamente per la richiesta in oggetto. Consenso esplicito secondo il GDPR 679/2016. Leggi l'informativa sulla"); ?>
                            <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php pll_e('Privacy policy'); ?></a>
                        </span>
                    </label>
                </div>

                <div class="col-12">
                    <div class="cf-turnstile" data-sitekey="<?php echo esc_attr(get_option('sp_turnstile_site_key')); ?>"></div>
                </div>

                <div class="col-12 dark">
                    <button type="submit" class="sp-btn"><span class="sp-btn__label"><?php pll_e('Invia'); ?></span></button>
                </div>

            </div>
        </form>
    </div>

    <aside class="contatti__dati col-12 col-lg-4 offset-lg-1 order-first order-lg-last">
        <?php get_template_part('template-parts/footer-contacts', null, ['variant' => 'light']); ?>
    </aside>

</div>
