<div class="single-post">

    <?php if ( has_post_thumbnail() ){ ?>
        <div class="post-thumbnail">
            <?php if( is_single() ){ ?>
                <?php the_post_thumbnail('charity-large', array('class' => 'img-fluid')); ?>
            <?php } else { ?>
                <a class="skip-link" href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('charity-large', array('class' => 'img-fluid')); ?>
                </a>            
            <?php }?>
        </div>
    <?php }  ?>

    <div class="blog-post-content">
        <?php the_title( sprintf( '<h2 class="title"><a href="%s" class="skip-link">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        <?php if ( get_theme_mod( 'blog_author', true ) || get_theme_mod( 'blog_category', true ) ): ?>
            <ul class="blog-post-meta">   
                <?php if ( get_theme_mod( 'blog_date', true ) ) { ?>
                    <li>
                        <div class="meta">
                            <i class="fa fa-calendar"></i> <a class="skip-link" href="<?php the_permalink(); ?>"><time datetime="<?php echo esc_html(get_the_date()); ?>"><?php echo esc_html(get_the_date()); ?></time></a>
                        </div>
                    </li>
                <?php } ?>
                
                <?php if ( get_theme_mod( 'blog_author', true ) ): ?>
                    <li>
                        <div class="meta">
                            <i class="fa fa-user"></i><a class="skip-link" href="<?php echo ' '.esc_url(get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php echo esc_html(get_the_author_meta('display_name')); ?></a>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'blog_category', true ) ): ?>
                    <li>
                        <div class="meta">
                            <i class="fa fa-book"></i><?php echo wp_kses_post(get_the_category_list(', ')); ?>
                        </div>
                    </li>
                <?php endif; ?>                      
            </ul>
        <?php endif; ?>
       
        <?php
            if (is_single()){
                the_content( 
                    sprintf( 
                        __( 'Continue reading%s', 'urban-charity' ), 
                        '<span>'.get_the_title().'</span>' 
                    ) 
                );
            }else {
                get_template_part( 'post-format/entry-content' );
            }
        ?>  
    </div>

    <?php
        if (is_single()) {
            get_template_part( 'post-format/entry-content-blog' );
        }
    ?>
    
</div><!--/#post-->


