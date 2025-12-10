<?php get_header(); ?>

<?php
$args = array(
    'post_type' => 'servicios',
    'orderby' => 'title',
    'order' => 'ASC',
);

$query = new WP_Query($args);
?>

<main class="seccion pd-l-1 p-t">
    <section class="contenedor hero bg-azul-cielo">
        <div class="left text-white g-0">
            <?php if (get_field('titulo')) { ?>
                <h1 class="tiny-lh"><?php the_field('titulo'); ?></h1>
            <?php } ?>

            <?php if (get_field('descripcion')) { ?>
                <p class="p-hero"><?php the_field('descripcion'); ?></p>
            <?php } ?>
        </div>

        <div class="right">
            <?php
            $imagen = get_field('imagen');
            if ($imagen) { ?>
                <?php echo wp_get_attachment_image($imagen, 'full', false, array('class' => 'imagen-hero')); ?>
            <?php } ?>
        </div>
    </section>

    <!-- Listado de Servicios -->
    <section class="contenedor servicios">

        <ul class="listado-grid">
            <?php if ($query->have_posts()): ?>
                <?php
                $i = 0; 
                ?>
                <?php while ($query->have_posts()): $query->the_post(); ?>
                    <?php
                    $image_id = get_field('imagen');
                    $image_html = $image_id ? wp_get_attachment_image($image_id, 'full', false, ['class' => 'imagen']) : '';

                    $li_class = 'text-white';
                    if ($i % 2 === 0) {
                        $h3_text_class = 'text-white';
                        $h3_bg_class   = 'bg-p';
                    } else {
                        $h3_text_class = 'text-primary';
                        $h3_bg_class   = 'bg-turqueza';
                    }
                    ?>
                    <li class="card <?php echo $li_class; ?>">
                        <div class="content-img">
                            <?php echo $image_html; ?>
                            <?php the_field('descripcion'); ?>
                        </div>

                        <h3 class="<?php echo $h3_bg_class; ?> mb-0 <?php echo $h3_text_class; ?>">
                            <?php the_title(); ?>
                        </h3>
                    </li>
                    <?php $i++; ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </ul>

        <p class="sin-servicios"> </p>
    </section>
    <!-- Fin Listado de Servicios -->
</main>

<?php get_footer(); ?>