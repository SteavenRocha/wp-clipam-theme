<?php get_header(); ?>

<main class="seccion pb-0 p-t">

    <section class="contenedor">
        <!-- Slider desde el Repeater -->
        <?php if (have_rows('slider')): ?>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php while (have_rows('slider')): the_row(); ?>
                        <div class="swiper-slide contenedor-slide">
                            <?php
                            $banner = get_sub_field('banner');
                            $titulo = get_sub_field('titulo');
                            $desc = get_sub_field('descripcion');
                            $boton = get_sub_field('boton');
                            ?>

                            <?php if ($banner):
                                $img_desktop = $banner['banner_desktop'];
                                $img_mobile  = $banner['banner_mobile'];
                            ?>
                                <picture>
                                    <?php if ($img_mobile): ?>
                                        <source media="(max-width: 768px)"
                                            srcset="<?php echo esc_url(wp_get_attachment_image_url($img_mobile, 'full')); ?>">
                                    <?php endif; ?>

                                    <?php if ($img_desktop): ?>
                                        <source media="(min-width: 769px)"
                                            srcset="<?php echo esc_url(wp_get_attachment_image_url($img_desktop, 'full')); ?>">
                                    <?php endif; ?>

                                    <?php echo wp_get_attachment_image($img_desktop, 'full', false, array('class' => 'imagen-hero')); ?>
                                </picture>
                            <?php endif; ?>

                            <div class="left text-primary w-4 g-3">
                                <div>
                                    <?php if ($titulo): ?>
                                        <h1 class="tiny-lh"><?php echo nl2br(esc_html($titulo)); ?></h1>
                                    <?php endif; ?>

                                    <?php if ($desc): ?>
                                        <p class="p-hero"><?php echo esc_html($desc); ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($boton): ?>
                                    <a href="<?php echo esc_url($boton['enlace']); ?>"
                                        class="btn big-btn <?php echo esc_attr($boton['estilo']); ?>">
                                        <?php echo esc_html($boton['texto']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Controles Swiper -->
                <!-- <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div> -->
                <div class="autoplay-progress">
                    <svg viewBox="0 0 48 48">
                        <circle cx="24" cy="24" r="20"></circle>
                    </svg>
                    <span></span>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php endif; ?>
    </section>

    <section class="contenedor convenios seccion-mg">
        <h3 class="text-primary text-font-headings mg-b-0 tiny-lh">
            <?php echo esc_html(get_field('titulo_convenios')); ?>
        </h3>

        <?php if (have_rows('marcas')): ?>
            <div class="carousel-wrapper">
                <div class="group">
                    <?php
                    // Primer grupo
                    while (have_rows('marcas')): the_row();
                        $logo = get_sub_field('imagen');
                        $url = get_sub_field('url');
                        $title = get_post_meta($logo, '_wp_attachment_image_alt', true);
                        if ($logo):
                            if ($url):
                                echo '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" title="' . $title . '">';
                                echo wp_get_attachment_image($logo, 'full', false, array('class' => 'imagen-slider'));
                                echo '</a>';
                            else:
                                echo wp_get_attachment_image($logo, 'full', false, array('class' => 'imagen-slider'));
                            endif;
                        endif;
                    endwhile;

                    // Reiniciamos y pintamos duplicado
                    reset_rows();

                    // Segundo grupo (aria-hidden)
                    while (have_rows('marcas')): the_row();
                        $logo = get_sub_field('imagen');
                        $url = get_sub_field('url');
                        $title = get_post_meta($logo, '_wp_attachment_image_alt', true);
                        if ($logo):
                            if ($url):
                                echo '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" title="' . $title . '" aria-hidden="true">';
                                echo wp_get_attachment_image($logo, 'full', false, array('class' => 'imagen-slider'));
                                echo '</a>';
                            else:
                                echo wp_get_attachment_image($logo, 'full', false, array('class' => 'imagen-slider', 'aria-hidden' => 'true'));
                            endif;
                        endif;
                    endwhile;
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <section class="contenedor center seccion-mg">
        <h2 class="text-primary text-font-headings mg-b-8 tiny-lh text-center">
            <?php echo esc_html(get_field('titulo_cards')); ?>
        </h2>

        <?php if (have_rows('cards')): ?>
            <div class="cards-wrapper text-primary">
                <?php
                $bg_classes = ['bg-lavanda', 'bg-turqueza', 'bg-crema', 'bg-azul-cielo'];
                $i = 0;
                while (have_rows('cards')): the_row();
                    $card_title       = get_sub_field('titulo');
                    $card_description = get_sub_field('descripcion');
                    $card_boton       = get_sub_field('boton');

                    $bg_class = $bg_classes[$i % count($bg_classes)];
                    $i++;
                ?>
                    <div class="card__ <?php echo esc_attr($bg_class); ?>">
                        <div>
                            <?php if ($card_title): ?>
                                <h3 class="card-title"><?php echo esc_html($card_title); ?></h3>
                            <?php endif; ?>

                            <?php if ($card_description): ?>
                                <p class="card-description"><?php echo esc_html($card_description); ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if ($card_boton):
                            $btn_url = esc_url($card_boton['enlace']);
                            $btn_text = esc_html($card_boton['texto']);
                            $btn_style = esc_attr($card_boton['estilo']);
                        ?>
                            <a href="<?php echo $btn_url; ?>" class="btn <?php echo $btn_style; ?>">
                                <?php echo $btn_text; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="bg-crema seccion-mg seccion-pg mb-0" id="contactanos">
        <div class="contenedor text-primary center">
            <h2 class="text-font-headings mg-b-8 tiny-lh">
                <?php echo esc_html(get_field('titulo_contactanos')); ?>
            </h2>

            <div class="halft">
                <div class="textos">
                    <h2 class="text-font-headings">
                        <?php echo nl2br(esc_html(get_field('subtitulo_contactanos'))); ?>
                    </h2>
                    <p>
                        <?php echo nl2br(esc_html(get_field('descripcion_contactanos'))); ?>
                    </p>
                </div>

                <?php echo do_shortcode('[contact-form-7 id="ccb18e4" title="Formulario de Contáctanos"]'); ?>

            </div>
        </div>
    </section>
</main>

<?php
$icono_cerrar = get_field('icono_cerrar_modal');
?>
<!-- Modal -->
<div class="modal micromodal-slide modal-1" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <header class="modal__header">
                <img src="<?php echo esc_url($icono_cerrar); ?>" alt="" class="modal__close icono" aria-label="Cerrar" data-micromodal-close>
            </header>
            <main class="modal__content" id="modal-content">

                <?php
                $modal_img = get_field('imagen_modal');
                if ($modal_img):
                    echo wp_get_attachment_image($modal_img, 'full', false, array('class' => 'modal-img'));
                endif;
                ?>
            </main>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<script>
    const progressCircle = document.querySelector(".autoplay-progress svg");
    const progressContent = document.querySelector(".autoplay-progress span");

    const swiper = new Swiper('.swiper', {
        loop: true,
        spaceBetween: 20,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        /*  navigation: {
             nextEl: '.swiper-button-next',
             prevEl: '.swiper-button-prev',
         }, */
        on: {
            autoplayTimeLeft(s, time, progress) {
                progressCircle.style.setProperty("--progress", 1 - progress);
                progressContent.textContent = `${Math.ceil(time / 1000)}s`;
            }
        }
    });

    // Formulario
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll("#nombre, #correo, #telefono, #asunto, #mensaje");

        inputs.forEach(input => {
            const label = input.closest(".form-group").querySelector(".floating-label");

            function toggleLabel() {
                if (input.value.trim() !== "") {
                    label.classList.add("active");
                } else {
                    label.classList.remove("active");
                }
            }

            toggleLabel();

            input.addEventListener("input", toggleLabel);
            input.addEventListener("focus", () => label.classList.add("active"));
            input.addEventListener("blur", toggleLabel);
        });
    });

    /* MODAL */
    document.addEventListener("DOMContentLoaded", function() {
        const estadoModal = "<?php echo esc_js(get_field('estado')); ?>";

        if (estadoModal === "activado") {
            const modalMostrado = sessionStorage.getItem("modalMostrado");

            if (!modalMostrado) {
                MicroModal.show('modal-1', {
                    disableScroll: true,
                    awaitOpenAnimation: true,
                    awaitCloseAnimation: false
                });

                sessionStorage.setItem("modalMostrado", "true");
            }
        }
    });

</script>