<?php get_header(); ?>

<main class="single-legales seccion p-t">

    <section class="contenedor contenido-principal">
        <?php
        while (have_posts()): the_post();
        ?>
            <div class="contenido_WYSIWYG">
                <?php
                the_content();
                ?>
            </div>
        <?php
        endwhile;
        ?>
    </section>
</main>

<?php get_footer(); ?>