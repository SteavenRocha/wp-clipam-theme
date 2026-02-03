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
                    <input type="text" id="search-input" placeholder="<?php echo esc_html($buscador['nombre']['texto_interno_nombre']); ?>" />
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
    <section class="contenedor doctores" id="doctores">

        <button id="ver-todas" class="back-btn" style="display:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="128" viewBox="0 0 12 24">
                <path fill="currentColor" fill-rule="evenodd" d="m3.343 12l7.071 7.071L9 20.485l-7.778-7.778a1 1 0 0 1 0-1.414L9 3.515l1.414 1.414z" />
            </svg>
            <span>Atrás</span>
        </button>

        <ul id="listado-doctores" class="listado-grid"> </ul> <!-- Renderizado de los Doctores -->

        <p id="sin-doctores" class="sin-doctores"></p> <!-- Renderizado descripción sin Doctores -->

        <div id="paginacion" class="paginacion">
            <button id="prev-page" title="Atrás">
                <svg width="65" height="38" viewBox="0 0 65 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.1973 23.0055C-0.830125 20.9719 -0.729141 16.3982 2.50003 14.5339L26.5 0.677406C29.8334 -1.24709 34 1.15849 34 5.00748V32.7204C34 36.5693 29.8334 38.9749 26.5 37.0505L2.50003 23.194L2.1973 23.0055ZM4.00003 17.1315C2.6667 17.9013 2.6667 19.8266 4.00003 20.5964L28 34.4528C29.3333 35.2224 31 34.2599 31 32.7204V5.00748L30.9952 4.86491C30.8993 3.45638 29.3937 2.58732 28.126 3.20866L28 3.27506L4.00003 17.1315Z" fill="#103799" />
                    <path d="M34 16.7278H63.5C64.3284 16.7278 65 17.3994 65 18.2278V18.2278C65 19.0562 64.3284 19.7278 63.5 19.7278H34V16.7278Z" fill="#103799" />
                </svg>
            </button>
            <div>
                <span id="current-page"></span> /
                <span id="total-page"></span>
            </div>
            <button id="next-page" title="Siguiente">
                <svg width="65" height="38" viewBox="0 0 65 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M62.8027 14.7223C65.8301 16.7559 65.7291 21.3295 62.5 23.1939L38.5 37.0504C35.1666 38.9749 31 36.5693 31 32.7203V5.00741C31 1.15844 35.1666 -1.24716 38.5 0.677331L62.5 14.5338L62.8027 14.7223ZM61 20.5963C62.3333 19.8265 62.3333 17.9012 61 17.1314L37 3.27499C35.6667 2.50539 34 3.46791 34 5.00741V32.7203L34.0048 32.8629C34.1007 34.2714 35.6063 35.1405 36.874 34.5191L37 34.4527L61 20.5963Z" fill="#103799" />
                    <path d="M31 21H1.5C0.671573 21 0 20.3284 0 19.5V19.5C0 18.6716 0.671573 18 1.5 18H31V21Z" fill="#103799" />
                </svg>
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="modal__close icono" data-micromodal-close>
                    <path fill="var(--primario-azul)" d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12L5.293 6.707a1 1 0 0 1 0-1.414" />
                </svg>
            </header>
            <main class="modal__content text-primary" id="modal-doctor-content">
                <div id="modal-doctor-img"></div>

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
        const verTodasBtn = document.getElementById("ver-todas");

        if (especialidadParam) {
            const searchSelect = document.getElementById("search-select");
            if (searchSelect) {
                searchSelect.value = especialidadParam;
            }
        }

        // --- Variables de paginación ---
        let paged = 1;
        let maxPages = 1;
        let isFirstLoad = true;
        const vieneDesdeURL = Boolean(especialidadParam);

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
            const hayFiltros = nombre !== "" || especialidad !== "";
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

                    verTodasBtn.style.display = hayFiltros ? "inline-flex" : "none";

                    currentPage.textContent = paged;
                    totalPage.textContent = maxPages;
                    prevBtn.disabled = paged <= 1;
                    nextBtn.disabled = paged >= maxPages;

                    if (!isFirstLoad || vieneDesdeURL) {
                        scrollToDoctores();
                    }

                    isFirstLoad = false;
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

        verTodasBtn.addEventListener("click", () => {
            searchInput.value = "";
            searchSelect.value = "";
            paged = 1;
            cargarDoctores();
        });

        // --- Carga inicial ---
        cargarDoctores();
    });

    function scrollToDoctores() {
        const section = document.getElementById('doctores');
        if (!section) return;

        section.scrollIntoView({
            block: 'start'
        });
    }

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
            document.getElementById("modal-doctor-img").innerHTML = imagen;
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