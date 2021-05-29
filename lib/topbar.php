<div class="header-top htp_style_one">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="htop-contact">
                    <?php $urban_charity_mail = get_theme_mod( 'topbar_email', 'support@urbancharity.com' ); ?>
                    <?php $urban_charity_phone = get_theme_mod( 'topbar_phone', '+1386-263-3623' ); ?>
                    <?php $urban_charity_donate_button = get_theme_mod( 'donate_button_text', 'Donate Button' ); ?>
                    <?php $urban_charity_donate_button_url = get_theme_mod( 'donate_button_url', '#' ); ?>
                    <ul>
                        <?php if ($urban_charity_phone): ?> 
                        <li>
                            <span>
                                <i class="icofont icofont-ui-cell-phone"></i> 
                                <?php esc_html_e('Call us', 'urban-charity'); ?> 
                                <?php print esc_html($urban_charity_phone);?>
                            </span>
                        </li>
                        <?php endif ?>
                        
                        <?php if ($urban_charity_mail): ?> 
                            <li>
                                <span>
                                    <i class="icofont icofont-world"></i>
                                    <?php print esc_html($urban_charity_mail);?>
                                </span>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                <div class="htop-donate-lng">
                    <div class="htop-donate-btn"> 
                        <ul>   
                            <?php if ($urban_charity_donate_button_url): ?> 
                                <li>
                                    <a class="charity-dashboard skip-link" href="<?php echo esc_url($urban_charity_donate_button_url) ?>">
                                        <?php print esc_html($urban_charity_donate_button);?>
                                    </a>
                                </li>
                             <?php endif ?> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>