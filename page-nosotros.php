<?php get_header(); ?>

<?php
// Query CPT Noticias
$args = array(
    'post_type'      => 'noticias',
    'post_status'    => 'publish',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$query = new WP_Query($args);

?>

<main class="seccion pb-0 nosotros p-t">
    <section class="contenedor hero-media">
        <?php
        $video_url = get_field('url_video');
        $video = get_field('video_group');
        $imagen = get_field('imagen_hero');
        ?>

        <?php if ($video_url): ?>
            <?php
            $youtube_id = get_youtube_id($video_url);
            ?>

            <?php if ($youtube_id): ?>
                <div class="hero__container hero-video--youtube">
                    <div class="video-preview"
                        role="button"
                        tabindex="0"
                        data-youtube="<?php echo esc_attr($youtube_id); ?>">

                        <!-- <img
                            src="https://img.youtube.com/vi/<?php echo esc_attr($youtube_id); ?>/hqdefault.jpg"
                            alt="Miniatura del video"
                            class="video-poster"> -->

                        <img
                            src="https://img.youtube.com/vi/<?php echo esc_attr($youtube_id); ?>/maxresdefault.jpg"
                            onload="if(this.naturalWidth <= 120) { this.src='https://img.youtube.com/vi/<?php echo esc_attr($youtube_id); ?>/hqdefault.jpg'; }"
                            class="video-poster"
                            alt="Miniatura del video">

                        <div class="video-overlay">
                            <span class="video-play">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="m10.65 15.75l4.875-3.125q.35-.225.35-.625t-.35-.625L10.65 8.25q-.375-.25-.763-.038t-.387.663v6.25q0 .45.388.663t.762-.038M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22" />
                                </svg>
                            </span>
                            <p class="video-text">Haz click para reproducir</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


        <?php elseif ($video['video'] && $video['video_poster']): ?>
            <?php
            $video_url_file = $video['video']['url'];
            $poster = $video['video_poster'];
            ?>

            <div class="hero__container hero-video--file">
                <div class="video-preview"
                    role="button"
                    tabindex="0"
                    data-video="<?php echo esc_url($video_url_file); ?>">

                    <?php
                    if ($poster) {
                        echo wp_get_attachment_image($poster, 'full', false, [
                            'class' => 'video-poster'
                        ]);
                    }
                    ?>

                    <div class="video-overlay">
                        <span class="video-play">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m10.65 15.75l4.875-3.125q.35-.225.35-.625t-.35-.625L10.65 8.25q-.375-.25-.763-.038t-.387.663v6.25q0 .45.388.663t.762-.038M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22" />
                            </svg>
                        </span>
                        <p class="video-text">Haz click para reproducir</p>
                    </div>
                </div>
            </div>

        <?php elseif ($imagen): ?>
            <div class="hero__container">
                <?php echo wp_get_attachment_image($imagen, 'full', false, [
                    'class' => 'imagen-hero'
                ]); ?>
            </div>

        <?php endif; ?>
    </section>

    <section class="contenedor cantainer historia seccion-pg-t">
        <div class="contenido text-primary">
            <?php if (get_field('titulo_historia')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_historia'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_historia')) { ?>
                <p><?php the_field('descripcion_historia'); ?></p>
            <?php } ?>
        </div>

        <?php
        $historia_img = get_field('imagen_historia');
        if ($historia_img):
            echo wp_get_attachment_image($historia_img, 'full', false, array('class' => 'side-img'));
        endif;
        ?>
    </section>

    <section class="contenedor cantainer seccion-pg-t">
        <div class="contenido text-primary mision">
            <?php if (get_field('titulo_mision')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_mision'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_mision')) { ?>
                <p><?php the_field('descripcion_mision'); ?></p>
            <?php } ?>
        </div>

        <div class="contenido text-primary vision">
            <?php if (get_field('titulo_vision')) { ?>
                <h2 class="mg-b-4 tiny-lh"><?php the_field('titulo_vision'); ?></h2>
            <?php } ?>

            <?php if (get_field('descripcion_vision')) { ?>
                <p><?php the_field('descripcion_vision'); ?></p>
            <?php } ?>
        </div>

    </section>

    <section class="contenedor seccion-pg">
        <?php if (get_field('frase')) { ?>
            <h3 class="text-primary frase"><?php echo nl2br(esc_html(get_field('frase'))); ?></h3>
        <?php } ?>
    </section>

    <section class="bg-crema seccion-pg text-primary">
        <div class="noticias contenedor">
            <div class="w-7">
                <?php if (get_field('titulo_noticias')) { ?>
                    <h2 class="tiny-lh"><?php the_field('titulo_noticias'); ?></h2>
                <?php } ?>

                <?php if (get_field('descripcion_noticias')) { ?>
                    <p><?php the_field('descripcion_noticias'); ?></p>
                <?php } ?>
            </div>

            <?php
            if ($query->have_posts()): ?>
                <div class="swiper-container">
                    <div class="swiper noticias-swiper">
                        <div class="swiper-wrapper">
                            <?php while ($query->have_posts()): $query->the_post(); ?>
                                <a class="swiper-slide noticias-slide" href="<?php the_permalink(); ?>">

                                    <?php
                                    if (has_post_thumbnail()) {
                                        echo get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'imagen-slider'));
                                    } else {
                                        $fallback = get_template_directory_uri() . '/assets/img/img-fallback.jpg';
                                        echo '<img src="' . esc_url($fallback) . '" alt="Imagen por defecto" class="imagen-slider">';
                                    }
                                    ?>

                                    <div class="text-white">
                                        <div class="noticias-detalles">
                                            <h3 class="tiny-lh mg-b-05"><?php the_title(); ?></h3>
                                            <div class="meta-noticia">
                                                <span class="autor">por: <strong><?php the_author(); ?></strong></span> -
                                                <span class="fecha"><?php echo get_the_date('d M Y'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Controles Swiper -->
                    <?php
                    $icono_izquierda = get_field('icono_izquierda', 'informacion-general');
                    $icono_derecha = get_field('icono_derecha', 'informacion-general');
                    ?>

                    <div class="controles">
                        <div class="swiper-button-prev">
                            <svg width="65" height="38" viewBox="0 0 65 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.1973 23.0055C-0.830125 20.9719 -0.729141 16.3982 2.50003 14.5339L26.5 0.677406C29.8334 -1.24709 34 1.15849 34 5.00748V32.7204C34 36.5693 29.8334 38.9749 26.5 37.0505L2.50003 23.194L2.1973 23.0055ZM4.00003 17.1315C2.6667 17.9013 2.6667 19.8266 4.00003 20.5964L28 34.4528C29.3333 35.2224 31 34.2599 31 32.7204V5.00748L30.9952 4.86491C30.8993 3.45638 29.3937 2.58732 28.126 3.20866L28 3.27506L4.00003 17.1315Z" fill="#103799" />
                                <path d="M34 16.7278H63.5C64.3284 16.7278 65 17.3994 65 18.2278V18.2278C65 19.0562 64.3284 19.7278 63.5 19.7278H34V16.7278Z" fill="#103799" />
                            </svg>
                        </div>

                        <div class="swiper-pagination"></div>

                        <div class="swiper-button-next">
                            <svg width="65" height="38" viewBox="0 0 65 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M62.8027 14.7223C65.8301 16.7559 65.7291 21.3295 62.5 23.1939L38.5 37.0504C35.1666 38.9749 31 36.5693 31 32.7203V5.00741C31 1.15844 35.1666 -1.24716 38.5 0.677331L62.5 14.5338L62.8027 14.7223ZM61 20.5963C62.3333 19.8265 62.3333 17.9012 61 17.1314L37 3.27499C35.6667 2.50539 34 3.46791 34 5.00741V32.7203L34.0048 32.8629C34.1007 34.2714 35.6063 35.1405 36.874 34.5191L37 34.4527L61 20.5963Z" fill="#103799" />
                                <path d="M31 21H1.5C0.671573 21 0 20.3284 0 19.5V19.5C0 18.6716 0.671573 18 1.5 18H31V21Z" fill="#103799" />
                            </svg>
                        </div>
                    </div>
                </div>
            <?php endif;

            wp_reset_postdata();
            ?>
        </div>
    </section>
</main>

<!-- Modal -->
<div class="modal micromodal-slide modal-video" id="modal-video" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <header class="modal__header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="modal__close icono" data-micromodal-close>
                    <path fill="white" d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12L5.293 6.707a1 1 0 0 1 0-1.414" />
                </svg>
            </header>
            <main class="modal__content" id="modal-content">
            </main>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<script>
    /* MODAL */
    document.addEventListener("DOMContentLoaded", function() {

        const preview = document.querySelector('.video-preview');
        const modalContent = document.getElementById('modal-content');

        if (!preview || !modalContent) return;

        preview.addEventListener('click', function() {
            // YouTube
            if (this.dataset.youtube) {
                const videoId = this.dataset.youtube;

                modalContent.innerHTML = `
                <iframe
                    src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0"
                    frameborder="0"
                    allow="autoplay; encrypted-media"
                    allowfullscreen>
                </iframe>
            `;
            }
            // Video MP4
            if (this.dataset.video) {
                const videoUrl = this.dataset.video;

                modalContent.innerHTML = `
                <video controls autoplay playsinline style="width:100%; height:auto;">
                    <source src="${videoUrl}" type="video/mp4">
                    Tu navegador no soporta video HTML5
                </video>
            `;
            }

            MicroModal.show('modal-video', {
                disableScroll: true,
                awaitOpenAnimation: true,
                onClose: () => {
                    modalContent.innerHTML = '';
                }
            });
        });
    });

    /* SWIPER NOTICIAS */
    const swiper = new Swiper('.swiper', {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 30,
        watchOverflow: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'fraction',
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 25,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });
</script>