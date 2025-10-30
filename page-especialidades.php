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

<main class="contenedor seccion pd-l-1 p-t">
    <section class="hero bg-crema">
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
                <label for="search"><?php echo esc_html($buscador['titulo_buscador_espe']); ?></label>

                <select id="search">
                    <option value="" disabled selected><?php echo esc_html($buscador['texto_interno']); ?></option>
                    <?php foreach ($titulos as $item) : ?>
                        <option value="<?php echo esc_attr($item['id']); ?>">
                            <?php echo esc_html($item['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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

    <!-- Listado de Especialidades -->
    <section class="especialidades">

        <ul id="listado-especialidades" class="listado-grid"> </ul> <!-- Renderizado de las especialidades -->

        <p id="sin-especialidades" class="sin-especialidades"></p> <!-- Renderizado descripción sin especialidades -->

        <?php
        $icono_izquierda = get_field('icono_izquierda', 'informacion-general');
        $icono_derecha = get_field('icono_derecha', 'informacion-general');
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
    <!-- Fin Listado de Especialidades -->
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Hero Select ---
        const select = document.getElementById("search");

        select.addEventListener("change", () => {
            paged = 1;
            cargarEspecialidades();
        });

        // --- Paginación y AJAX ---
        let paged = 1;
        let maxPages = 1;

        const listado = document.getElementById("listado-especialidades");
        const vacio = document.getElementById("sin-especialidades");
        const mensaje = "<?php echo esc_js(get_field('descripcion_sin_especialidades', get_page_by_path('especialidades')->ID)); ?>";
        const paginacion = document.getElementById("paginacion");
        const currentPage = document.getElementById("current-page");
        const totalPage = document.getElementById("total-page");
        const prevBtn = document.getElementById("prev-page");
        const nextBtn = document.getElementById("next-page");

        function cargarEspecialidades() {
            const especialidadID = select.value;

            const data = new FormData();
            data.append('action', 'cargar_especialidades');
            data.append('paged', paged);
            data.append('especialidad_id', especialidadID);

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(data => {
                    const html = data.html;
                    maxPages = data.maxPages;

                    if (!html || html.trim() === '') {
                        listado.style.display = "none";
                        vacio.innerHTML = mensaje;
                        vacio.style.display = "flex";
                        paginacion.style.display = "none";
                    } else {
                        listado.innerHTML = html;
                        listado.style.display = "grid";
                        vacio.style.display = "none";

                        if (especialidadID) {
                            paginacion.style.display = "none";
                        } else {
                            paginacion.style.display = "flex";
                        }
                    }

                    currentPage.textContent = paged;
                    totalPage.textContent = maxPages;
                    prevBtn.disabled = paged <= 1;
                    nextBtn.disabled = paged >= maxPages;
                });
        }

        prevBtn.addEventListener("click", () => {
            if (paged > 1) {
                paged--;
                cargarEspecialidades();
            }
        });

        nextBtn.addEventListener("click", () => {
            if (paged < maxPages) {
                paged++;
                cargarEspecialidades();
            }
        });

        cargarEspecialidades();
    });
</script>

<?php get_footer(); ?>