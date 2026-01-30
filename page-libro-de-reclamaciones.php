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

    <section class="form__book__container contenedor">
        <?php echo do_shortcode('[contact-form-7 id="8d97d9a" title="Formulario del Libro de Reclamaciones"]'); ?>
    </section>
</main>

<script>
    /* Agrega la fecha en el libreo de reclamaciones de manera dinamica */
    document.addEventListener('DOMContentLoaded', function() {
        const fecha = new Date();
        const formato = fecha.toLocaleDateString('es-PE');

        document.getElementById('fecha-actual').textContent = formato;
        document.getElementById('fecha-envio').value = formato;
    });

    /* Cambios en los imputs */
    document.addEventListener('DOMContentLoaded', function() {

        const tipoDoc = document.querySelector('[name="tipo_doc_reclamante"]');
        const grupoPersona = document.querySelector('.grupo-persona');
        const grupoEmpresa = document.querySelector('.grupo-empresa');

        function toggleCampos() {
            const valor = tipoDoc.value;

            grupoPersona.style.display = 'none';
            grupoEmpresa.style.display = 'none';

            if (valor === 'DNI' || valor === 'CE' || valor === 'Pasaporte') {
                grupoPersona.style.display = 'flex';
            }

            if (valor === 'RUC') {
                grupoEmpresa.style.display = 'flex';
            }
        }

        tipoDoc.addEventListener('change', toggleCampos);
    });

    /* Contador de caracteres */
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('descripcion_reclamo');
        const contador = document.getElementById('contador-descripcion');
        const max = 6000;

        if (!textarea || !contador) return;

        function actualizarContador() {
            const longitud = textarea.value.length;
            contador.textContent = longitud;

            if (longitud >= max) {
                contador.parentElement.classList.add('limite');
            } else {
                contador.parentElement.classList.remove('limite');
            }
        }

        textarea.addEventListener('input', actualizarContador);
        actualizarContador();
    });
</script>

<?php get_footer(); ?>