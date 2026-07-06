    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php wp_nav_menu(['theme_location' => 'footer']); ?>
            Footer child theme
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
