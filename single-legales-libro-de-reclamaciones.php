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

<!-- Modal Mensaje Emergente -->
<div class="modal micromodal-slide" id="modal-contactanos" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-contactanos-title">
            <header class="modal__header">
            </header>
            <main class="modal__content text-primary" id="modal-contactanos-content">
            </main>
        </div>
    </div>
</div>

<script>
    /* Agrega la fecha en el libreo de reclamaciones de manera dinamica */
    document.addEventListener('DOMContentLoaded', function() {
        const fecha = new Date();
        const formato = fecha.toLocaleDateString('es-PE');

        document.getElementById('fecha-actual').textContent = formato;
        document.getElementById('fecha-envio').value = formato;
    });

    document.addEventListener('DOMContentLoaded', function() {

        function setValueIfExists(name, value) {
            const input = document.querySelector(`[name="${name}"]`);
            if (input) input.value = value;
        }

        function getInput(name) {
            return document.querySelector(`[name="${name}"]`);
        }

        function estaSincronizado(prefix) {
            const selectRelacion = getInput('afectado_mismo_reclamante');
            return prefix === 'afectado' && selectRelacion && selectRelacion.value === 'Si';
        }

        /* ================================
           TOGGLE PERSONA / EMPRESA
        ================================== */

        function configurarToggle(selectName, personaClass, empresaClass, prefix) {

            const select = getInput(selectName);
            const grupoPersona = document.querySelector(personaClass);
            const grupoEmpresa = document.querySelector(empresaClass);

            if (!select) return;

            function toggle() {

                const valor = select.value;

                grupoPersona.style.display = 'none';
                grupoEmpresa.style.display = 'none';

                if (!estaSincronizado(prefix)) {
                    setValueIfExists(`razon_social_${prefix}`, '');
                    setValueIfExists(`ape_paterno_${prefix}`, '');
                    setValueIfExists(`ape_materno_${prefix}`, '');
                    setValueIfExists(`nombres_${prefix}`, '');
                }

                if (['DNI', 'CE', 'Pasaporte'].includes(valor)) {

                    grupoPersona.style.display = 'flex';

                    if (!estaSincronizado(prefix)) {
                        setValueIfExists(`razon_social_${prefix}`, '-');
                    }
                }

                if (valor === 'RUC') {

                    grupoEmpresa.style.display = 'flex';

                    if (!estaSincronizado(prefix)) {
                        setValueIfExists(`ape_paterno_${prefix}`, '-');
                        setValueIfExists(`ape_materno_${prefix}`, '-');
                        setValueIfExists(`nombres_${prefix}`, '-');
                    }
                }
            }

            select.addEventListener('change', toggle);
            toggle();
        }

        configurarToggle(
            'tipo_doc_reclamante',
            '.grupo-persona-reclamante',
            '.grupo-empresa-reclamante',
            'reclamante'
        );

        configurarToggle(
            'tipo_doc_afectado',
            '.grupo-persona-afectado',
            '.grupo-empresa-afectado',
            'afectado'
        );

        /* ================================
           SINCRONIZAR AFECTADO = RECLAMANTE
        ================================== */

        const selectRelacion = getInput('afectado_mismo_reclamante');

        if (!selectRelacion) return;

        const campos = [
            ['numero_doc_reclamante', 'numero_doc_afectado'],
            ['ape_paterno_reclamante', 'ape_paterno_afectado'],
            ['ape_materno_reclamante', 'ape_materno_afectado'],
            ['nombres_reclamante', 'nombres_afectado'],
            ['razon_social_reclamante', 'razon_social_afectado'],
            ['celular_reclamante', 'celular_afectado'],
            ['email_reclamante', 'email_afectado'],
            ['departamento_reclamante', 'departamento_afectado'],
            ['provincia_reclamante', 'provincia_afectado'],
            ['distrito_reclamante', 'distrito_afectado'],
            ['direccion_reclamante', 'direccion_afectado'],
            ['tipo_doc_reclamante', 'tipo_doc_afectado']
        ];

        function copiarValores() {

            campos.forEach(([from, to]) => {

                const origen = getInput(from);
                const destino = getInput(to);

                if (!origen || !destino) return;

                destino.value = origen.value;

                if (destino.tagName === 'SELECT') {
                    destino.dispatchEvent(new Event('change'));
                    destino.style.pointerEvents = 'none';
                    destino.style.backgroundColor = '#F3F4F6';
                } else {
                    destino.readOnly = true;
                }
            });
        }

        function limpiarValores() {

            campos.forEach(([_, to]) => {

                const destino = getInput(to);
                if (!destino) return;

                destino.value = '';

                if (destino.tagName === 'SELECT') {
                    destino.style.pointerEvents = 'auto';
                    destino.style.backgroundColor = 'white';
                    destino.dispatchEvent(new Event('change'));
                } else {
                    destino.readOnly = false;
                }
            });
        }

        function toggleSync() {
            if (selectRelacion.value === 'Si') {
                copiarValores();
            } else {
                limpiarValores();
            }
        }

        selectRelacion.addEventListener('change', toggleSync);

        campos.forEach(([from]) => {
            const origen = getInput(from);
            if (origen) {
                origen.addEventListener('input', () => {
                    if (selectRelacion.value === 'Si') copiarValores();
                });
                origen.addEventListener('change', () => {
                    if (selectRelacion.value === 'Si') copiarValores();
                });
            }
        });

        toggleSync();

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

    document.addEventListener('DOMContentLoaded', function() {

        const input = document.querySelector('input[name="archivo_adjunto"]');
        if (!input) return;

        const uploadWrapper = input.closest('.custom-file-upload');
        if (!uploadWrapper) return;

        const fileNamesSpan = uploadWrapper.querySelector('.file-names');

        input.multiple = true;
        input.name = 'archivo_adjunto[]';

        const MAX_FILES = 4;
        const MAX_FILE_SIZE = 3 * 1024 * 1024;
        const MAX_TOTAL_SIZE = 12 * 1024 * 1024;

        function showCf7Error(message) {
            clearCf7Error();

            input.setAttribute('aria-invalid', 'true');

            const span = document.createElement('span');
            span.className = 'wpcf7-not-valid-tip';
            span.setAttribute('aria-hidden', 'true');
            span.textContent = message;

            uploadWrapper.insertAdjacentElement('afterend', span);
        }

        function clearCf7Error() {
            input.removeAttribute('aria-invalid');

            const tip = uploadWrapper.parentNode.querySelector('.wpcf7-not-valid-tip');
            if (tip) tip.remove();
        }

        function resetFileNames() {
            input.value = '';
            fileNamesSpan.textContent = 'Ningún archivo seleccionado';
        }

        function renderFileNames(files) {
            fileNamesSpan.innerHTML = '';

            Array.from(files).forEach(file => {
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);

                const item = document.createElement('div');
                item.textContent = `${file.name} (${sizeMB} MB)`;

                fileNamesSpan.appendChild(item);
            });
        }

        resetFileNames();

        input.addEventListener('click', function() {
            clearCf7Error();
        })

        input.addEventListener('change', function() {

            clearCf7Error();

            if (!this.files || this.files.length === 0) {
                resetFileNames();
                return;
            }

            if (this.files.length > MAX_FILES) {
                showCf7Error(`Solo se permiten máximo ${MAX_FILES} archivos.`);
                resetFileNames();
                return;
            }

            let totalSize = 0;

            for (let file of this.files) {
                totalSize += file.size;

                if (file.size > MAX_FILE_SIZE) {
                    showCf7Error(`El archivo "${file.name}" supera los 3MB.`);
                    resetFileNames();
                    return;
                }
            }

            if (totalSize > MAX_TOTAL_SIZE) {
                showCf7Error(`El tamaño total no debe superar los 12MB.`);
                resetFileNames();
                return;
            }

            renderFileNames(this.files);
        });

        document.addEventListener('wpcf7mailsent', function(event) {
            if (event.target.querySelector('input[name="archivo_adjunto[]"]')) {
                resetFileNames();
            }
        });
    });
</script>

<?php get_footer(); ?>