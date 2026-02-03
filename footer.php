<footer class="footer">
    <div class="bg-footer">
        <div class="contenedor contenido-footer text-white big-lh">

            <a class="column-logo" href="<?php echo esc_url(home_url('/')); ?>">
                <!-- Logo -->
                <?php
                $logo_footer = get_field('logo', 'informacion-general');
                if ($logo_footer):
                    echo wp_get_attachment_image($logo_footer, 'full', false, array('class' => 'logo-footer'));
                endif;
                ?>
            </a>

            <!-- TITULOS DE LOS ENLACES -->
            <?php $titulos = get_field('titulos__detalles', 'informacion-general'); ?>

            <div class="column-grid">
                <!-- Enlaces -->
                <div class="column column-1">
                    <h4><?php echo esc_html($titulos['titulo_enlaces']); ?></h4>

                    <!-- Navegacion -->
                    <?php

                    $args = array(
                        'theme_location' => 'menu-principal',
                        'container' => 'nav',
                        'container_class' => 'menu-principal'
                    );

                    wp_nav_menu($args);

                    ?>
                </div>

                <!-- Legal -->
                <div class="column column-2">
                    <h4><?php echo esc_html($titulos['titulo_legal']); ?></h4>

                    <!-- Navegacion -->
                    <?php

                    $args = array(
                        'theme_location' => 'menu-legales',
                        'container' => 'nav',
                        'container_class' => 'menu-principal'
                    );

                    wp_nav_menu($args);

                    ?>
                </div>

                <!-- Contactanos -->
                <div class="column column-3">
                    <h4>
                        <?php echo esc_html($titulos['contactanos']['titulo_contactanos']); ?>
                    </h4>

                    <div class="row mg-b-4">
                        <p class="small-lh">
                            <?php echo nl2br(esc_html($titulos['contactanos']['whatsapp_detalles']['descripcion'])); ?>
                        </p>

                        <a class="info"
                            href="<?php echo esc_url(get_field('enlace_whatsApp', 'informacion-general')); ?>"
                            target="_blank"
                            rel="noopener noreferrer">

                            <img class="icono"
                                src="<?php echo esc_url($titulos['contactanos']['whatsapp_detalles']['icono']['value']['url']); ?>"
                                alt="<?php echo esc_attr($titulos['contactanos']['whatsapp_detalles']['icono']['value']['alt']); ?>">

                            <p class="text-bold-big"><?php echo esc_html(get_field('telefono', 'informacion-general')); ?></p>
                        </a>
                    </div>

                    <div class="row">
                        <p class="small-lh">
                            <?php echo nl2br(esc_html($titulos['contactanos']['direccion_detalles']['descripcion'])); ?>
                        </p>

                        <a class="info"
                            href="<?php echo esc_url(get_field('enlace_google_maps', 'informacion-general')); ?>"
                            target="_blank"
                            rel="noopener noreferrer">

                            <img class="icono"
                                src="<?php echo esc_url($titulos['contactanos']['direccion_detalles']['icono']['value']['url']); ?>"
                                alt="<?php echo esc_attr($titulos['contactanos']['direccion_detalles']['icono']['value']['alt']); ?>">

                            <p class="text-bold-big"><?php echo esc_html(get_field('direccion', 'informacion-general')); ?></p>
                        </a>
                    </div>
                </div>

                <!-- Redes Sociales / Libro de reclamaciones -->
                <div class="column column-4">
                    <h4><?php echo esc_html($titulos['titulo_redes_sociales']); ?></h4>

                    <div class="redes-sociales">
                        <?php
                        // Definimos las redes que tienes en ACF
                        $redes = ['facebook', 'linkedin', 'instagram', 'youtube', 'x'];

                        foreach ($redes as $red) {
                            $social = get_field($red, 'informacion-general');

                            if (!empty($social['url']) && !empty($social['icono']['value']['url'])): ?>
                                <a href="<?php echo esc_url($social['url']); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="red-social <?php echo esc_attr($red); ?>">
                                    <img class="icono"
                                        src="<?php echo esc_url($social['icono']['value']['url']); ?>"
                                        alt="<?php echo esc_attr($social['icono']['value']['alt']); ?>">
                                </a>
                        <?php endif;
                        }
                        ?>
                    </div>

                    <!-- Libro de reclamaciones -->
                    <div class="book-container">
                        <?php
                        $post_footer = get_field('post_footer', 'informacion-general');
                        $libro_de_reclamaciones = $post_footer['libro_de_reclamaciones'];
                        ?>

                        <?php
                        if ($libro_de_reclamaciones):
                            foreach ($libro_de_reclamaciones as $book):
                                $url = $book['url_formulario'];
                        ?>
                                <a href="<?php echo esc_url($url); ?>" rel="noopener" class="book">
                                    <?php echo wp_get_attachment_image($book['imagen_formulario'], 'medium', false, ['class' => 'book-img']); ?>
                                </a>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-post-footer">
        <p class="contenedor text-primary copyright">© <?php echo date('Y'); ?> <?php echo (esc_html($post_footer['copyright'])); ?></p>
    </div>
</footer>

<!-- Botón flotante WhatsApp -->
<?php
$icono_whatsapp = get_field('icono_whatsapp', 'informacion-general');
$url_whatsapp = get_field('url_whatsapp', 'informacion-general');
$texto_whatsapp = get_field('texto_whatsapp', 'informacion-general');
?>

<a href="<?php echo esc_url($url_whatsapp); ?>" target="_blank" class="btn-whatsapp" data-title="<?php echo esc_html($texto_whatsapp); ?>">
    <img src="<?php echo esc_url($icono_whatsapp['value']['url']); ?>" alt="<?php echo esc_attr($icono_whatsapp['value']['alt']); ?>">
</a>

<?php wp_footer(); ?>

</body>

</html>

<script>
    /* Scroll Header */
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.header');
        if (window.scrollY > 50) {
            header.classList.add('scroll');
        } else {
            header.classList.remove('scroll');
        }
    });

    /* Menu Hmaburguesa */
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('.menu-principal');
        const overlay = document.querySelector('.menu-overlay');
        const body = document.body;

        toggle.addEventListener('click', function() {
            const isActive = toggle.classList.toggle('active');
            menu.classList.toggle('active', isActive);

            if (window.innerWidth <= 768) {
                body.classList.toggle('no-scroll', isActive);
            }

            if (window.innerWidth > 768 && window.innerWidth <= 1024) {
                overlay.classList.toggle('active', isActive);
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                body.classList.remove('no-scroll');
                menu.classList.remove('active');
                toggle.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        overlay.addEventListener('click', function() {
            toggle.classList.remove('active');
            menu.classList.remove('active');
            overlay.classList.remove('active');
        });
    });

    /* Menu desplegable */
    document.addEventListener('DOMContentLoaded', function() {
        const boton = document.querySelector('.toggle-desplegable');
        const menu = document.querySelector('.desplegable');

        if (boton && menu) {
            boton.addEventListener('click', (e) => {
                e.stopPropagation();
                const activo = menu.classList.toggle('activo');
                boton.classList.toggle('activo', activo);
            });

            document.addEventListener('click', (e) => {
                if (!boton.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('activo');
                    boton.classList.remove('activo');
                }
            });

            const links = menu.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.remove('activo');
                    boton.classList.remove('activo');
                });
            });
        }
    });
</script>