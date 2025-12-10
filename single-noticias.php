<?php get_header(); ?>

<main class="single-noticia seccion p-t">

    <div class="contenedor con-sidebar">
        <section class="contenido-principal">
            <?php
            while (have_posts()): the_post();
            ?>
                <h1><?php the_title(); ?></h1>

                <?php
                if (has_post_thumbnail()) {
                    echo get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'imagen-single-noticia'));
                }
                ?>

                <?php
                $icono_autor = get_field('icono_autor', 'informacion-general');
                $icono_fecha = get_field('icono_fecha', 'informacion-general');
                $icono_hora  = get_field('icono_hora', 'informacion-general');
                ?>

                <div class="meta-noticia">
                    <span class="autor">
                        <?php if ($icono_autor): ?>
                            <img src="<?php echo esc_url($icono_autor); ?>" alt="" class="icono icono-meta">
                        <?php endif; ?>
                        <?php the_author(); ?>
                    </span>

                    <span class="fecha">
                        <?php if ($icono_fecha): ?>
                            <img src="<?php echo esc_url($icono_fecha); ?>" alt="" class="icono icono-meta">
                        <?php endif; ?>
                        <?php echo get_the_date('d M Y'); ?>
                    </span>

                    <span class="hora">
                        <?php if ($icono_hora): ?>
                            <img src="<?php echo esc_url($icono_hora); ?>" alt="" class="icono icono-meta">
                        <?php endif; ?>
                        <?php echo get_the_time('H:i'); ?>
                    </span>
                </div>

                <hr>

                <div class="contenido_WYSIWYG">
                    <?php
                    the_content();
                    ?>
                </div>
            <?php
            endwhile;
            ?>
        </section>

        <aside class="sidebar-noticias">
            <?php
            $nosotros_page = get_page_by_path('nosotros');
            $titulo_sidebar = $nosotros_page ? get_field('titulo_sidebar_noticias', $nosotros_page->ID) : '';
            ?>

            <h3><?php echo esc_html($titulo_sidebar) ?></h3>

            <ul class="lista-ultimas-noticias">
                <?php
                $args = array(
                    'post_type'      => 'noticias',
                    'posts_per_page' => 3,
                    'post__not_in'   => array(get_the_ID()),
                );
                $ultimas = new WP_Query($args);

                if ($ultimas->have_posts()):
                    while ($ultimas->have_posts()): $ultimas->the_post();
                ?>
                        <li class="item-noticia">
                            <a href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'miniatura-noticia')); ?>
                                <?php else: ?>
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/img-fallback.jpg"
                                        alt="<?php the_title_attribute(); ?>"
                                        class="miniatura-noticia">
                                <?php endif; ?>

                                <div class="noticias-detalles miniatura">
                                    <h4 class="tiny-lh mg-b-05"><?php the_title(); ?></h4>
                                    <div class="meta-noticia">
                                        <span class="autor">por: <strong><?php the_author(); ?></strong></span>
                                        <span class="fecha"><?php echo get_the_date('d M Y'); ?></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                    echo '<p>No hay noticias recientes.</p>';
                endif;
                ?>
            </ul>
        </aside>
    </div>

</main>

<?php get_footer(); ?>