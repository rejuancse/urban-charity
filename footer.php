<footer>
    <div class="footer-area" style="background-image: url(<?php echo esc_url( get_theme_mod('footer_bg', '')); ?>)">
        <div class="container">
            <div class="footer-inner">
                <div class="row">
                    <?php dynamic_sidebar('bottom1'); ?>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center footer-two-wrap">
                        <?php get_template_part('lib/social-link'); ?>

                        <p class="copyright">
                            <?php echo wp_kses_post(balanceTags( get_theme_mod( 'copyright_text', '2020 Urban Charity. All Rights Reserved.') )); ?>
                        </p> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</div>
</body>
</html>
