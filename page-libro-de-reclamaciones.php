<?php get_header(); ?>

<main class="seccion p-t">
    <section class="contenedor hero bg-crema">
        <div class="left text-primary g-0">
            <?php if (get_field('titulo_book')) { ?>
                <h1 class="tiny-lh"><?php the_field('titulo_book'); ?></h1>
            <?php } ?>

            <?php if (get_field('descripcion_book')) { ?>
                <p class="p-hero"><?php the_field('descripcion_book'); ?></p>
            <?php } ?>
        </div>

        <div class="right">
            <?php
            $imagen = get_field('imagen_book');
            if ($imagen) { ?>
                <?php echo wp_get_attachment_image($imagen, 'full', false, array('class' => 'imagen-hero')); ?>
            <?php } ?>
        </div>
    </section>

    <section class="contenedor">
        <?php echo do_shortcode('[contact-form-7 id="8d97d9a" title="Formulario del Libro de Reclamaciones"]'); ?>
    </section>
</main>

<?php get_footer(); ?>