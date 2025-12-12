<?php get_header(); ?>

<?php
$args = array(
    'post_type' => 'especialidades',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page' => -1
);

$query = new WP_Query($args);
$titulos = [];

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $titulos[] = [
            'id'    => get_the_ID(),
            'title' => get_the_title()
        ];
    }
    wp_reset_postdata();
}
?>

<main class="seccion pd-l-1 p-t">

    <section class="contenedor hero bg-turqueza">
        <div class="left text-primary">
            <div>
                <?php if (get_field('titulo')) { ?>
                    <h1 class="tiny-lh"><?php the_field('titulo'); ?></h1>
                <?php } ?>

                <?php if (get_field('descripcion')) { ?>
                    <p class="p-hero"><?php the_field('descripcion'); ?></p>
                <?php } ?>
            </div>

            <!-- Buscador -->
            <?php
            $buscador = get_field('buscador');
            ?>

            <div class="buscador">
                <div class="mg-b-2">
                    <label for="search-input"><?php echo esc_html($buscador['nombre']['titulo_buscador_medico']); ?></label>
                    <input type="text" id="search-input" placeholder="<?php echo esc_html($buscador['nombre']['texto_interno_nombre']); ?>"/>
                </div>

                <div class="form-group">
                    <label for="search-select"><?php echo esc_html($buscador['especialidad']['titulo_buscador_espe']); ?></label>

                    <select id="search-select">
                        <option value="" disabled selected><?php echo esc_html($buscador['especialidad']['texto_interno']); ?></option>

                        <?php foreach ($titulos as $item) : ?>
                            <option value="<?php echo esc_attr($item['id']); ?>">
                                <?php echo esc_html($item['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="right">
            <?php
            $imagen = get_field('imagen');
            if ($imagen) { ?>
                <?php echo wp_get_attachment_image($imagen, 'full', false, array('class' => 'imagen-hero')); ?>
            <?php } ?>
        </div>
    </section>

    <!-- Listado de Doctores -->
    <section class="contenedor doctores">

        <ul id="listado-doctores" class="listado-grid"> </ul> <!-- Renderizado de los Doctores -->

        <p id="sin-doctores" class="sin-doctores"></p> <!-- Renderizado descripción sin Doctores -->

        <?php
        $icono_izquierda = get_field('icono_izquierda', 'informacion-general');
        $icono_derecha = get_field('icono_derecha', 'informacion-general');
        $icono_cerrar = get_field('icono_cerrar_modal');
        ?>
        <div id="paginacion" class="paginacion">
            <button id="prev-page" title="Atrás">
                <?php
                if ($icono_izquierda) : ?>
                    <img src="<?php echo esc_url($icono_izquierda); ?>" alt="" aria-hidden="true" class="icono">
                <?php endif; ?>
            </button>
            <div>
                <span id="current-page"></span> /
                <span id="total-page"></span>
            </div>
            <button id="next-page" title="Siguiente">
                <?php
                if ($icono_derecha) : ?>
                    <img src="<?php echo esc_url($icono_derecha); ?>" alt="" aria-hidden="true" class="icono">
                <?php endif; ?>
            </button>
        </div>
    </section>
    <!-- Fin Listado de Doctores -->

</main>

<!-- Modal -->
<div class="modal micromodal-slide" id="modal-doctor" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-doctor-title">
            <header class="modal__header">
                <img src="<?php echo esc_url($icono_cerrar); ?>" alt="" class="modal__close icono" aria-label="Cerrar" data-micromodal-close>
            </header>
            <main class="modal__content text-primary" id="modal-doctor-content">
                <img id="modal-doctor-img" alt="" class="modal-imagen-doctor">

                <div class="modal__content__cuerpo">
                    <div class="modal__content__encabezado">
                        <h3 id="modal-doctor-title" class="mg-b-0"></h3>
                        <div id="modal-doctor-documentos"></div>
                        <div id="modal-doctor-especialidades" class="pill-contenedor"></div>
                    </div>

                    <div id="modal-doctor-detalles" class="detalles"></div>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Leer parámetro de especialidad desde la URL ---
        const params = new URLSearchParams(window.location.search);
        const especialidadParam = params.get("especialidad");

        if (especialidadParam) {
            const searchSelect = document.getElementById("search-select");
            if (searchSelect) {
                searchSelect.value = especialidadParam;
            }
        }

        // --- Variables de paginación ---
        let paged = 1;
        let maxPages = 1;

        const listado = document.getElementById("listado-doctores");
        const vacio = document.getElementById("sin-doctores");
        const mensaje = "<?php echo esc_js(get_field('descripcion_sin_staff_medico', get_page_by_path('staff-medico')->ID)); ?>";
        const paginacion = document.getElementById("paginacion");
        const currentPage = document.getElementById("current-page");
        const totalPage = document.getElementById("total-page");
        const prevBtn = document.getElementById("prev-page");
        const nextBtn = document.getElementById("next-page");
        const searchInput = document.getElementById("search-input");
        const searchSelect = document.getElementById("search-select");

        // --- Cargar Doctores AJAX ---
        function cargarDoctores() {
            const nombre = searchInput.value.trim();
            const especialidad = searchSelect.value;
            const data = new FormData();

            data.append("action", "cargar_doctores");
            data.append("paged", paged);
            data.append("nombre", nombre);
            data.append("especialidad", especialidad);

            fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                    method: "POST",
                    body: data,
                })
                .then((res) => res.json())
                .then((data) => {
                    const html = data.html;
                    maxPages = data.maxPages;

                    if (!html || html.trim() === "") {
                        listado.style.display = "none";
                        vacio.innerHTML = mensaje;
                        vacio.style.display = "flex";
                        paginacion.style.display = "none";
                    } else {
                        listado.innerHTML = html;
                        listado.style.display = "grid";
                        vacio.style.display = "none";
                        paginacion.style.display = maxPages > 1 ? "flex" : "none";
                    }

                    currentPage.textContent = paged;
                    totalPage.textContent = maxPages;
                    prevBtn.disabled = paged <= 1;
                    nextBtn.disabled = paged >= maxPages;
                });
        }

        // --- Eventos ---
        prevBtn.addEventListener("click", () => {
            if (paged > 1) {
                paged--;
                cargarDoctores();
            }
        });

        nextBtn.addEventListener("click", () => {
            if (paged < maxPages) {
                paged++;
                cargarDoctores();
            }
        });

        // --- Filtros ---
        searchInput.addEventListener("input", () => {
            paged = 1;
            cargarDoctores();
        });

        searchSelect.addEventListener("change", () => {
            paged = 1;
            cargarDoctores();
        });

        // --- Carga inicial ---
        cargarDoctores();
    });

    // --- MODAL DOCTOR ---
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("titulo-btn")) {
            const btn = e.target;

            const nombre = btn.dataset.nombre;
            const especialidades = btn.dataset.especialidades;
            const documentos = btn.dataset.documentos;
            const imagen = btn.dataset.imagen;
            const alt = btn.dataset.alt;
            const detalles = JSON.parse(btn.dataset.detalles || "[]");

            document.getElementById("modal-doctor-title").textContent = nombre;
            document.getElementById("modal-doctor-img").src = imagen;
            document.getElementById("modal-doctor-img").alt = alt;
            document.getElementById("modal-doctor-especialidades").innerHTML = especialidades;
            document.getElementById("modal-doctor-documentos").innerHTML = documentos;

            const detallesContainer = document.getElementById("modal-doctor-detalles");
            if (detalles.length) {
                detallesContainer.innerHTML = detalles
                    .map(
                        (d) => `
                <div class="detalle">
                    <h4 class="mg-b-05">${d.titulo}</h4>
                    <p>${d.descripcion}</p>
                </div>
            `
                    )
                    .join("");
            } else {
                detallesContainer.innerHTML = "<p>No hay detalles disponibles</p>";
            }

            MicroModal.show("modal-doctor", {
                disableScroll: true,
                awaitOpenAnimation: true,
                awaitCloseAnimation: false,
            });
        }
    });
</script>

<?php get_footer(); ?>