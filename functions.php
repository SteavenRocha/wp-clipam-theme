<?php

function clipam_setup()
{
    // Imagenes Destacadas
    add_theme_support('post-thumbnails');

    // Titulos de las paginas -> SEO
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'clipam_setup');

function clipam_menus()
{
    register_nav_menus(array(
        'menu-principal' => __('Menú Principal', 'clipam'),
        'menu-legales' => __('Menú Legales', 'clipam')
    ));
}

add_action('init', 'clipam_menus');

function clipam_filtrar_menu_paginas_no_publicadas($items, $args) {

    foreach ($items as $key => $item) {

        if ($item->type === 'post_type') {

            $post = get_post($item->object_id);

            if (!$post || $post->post_status !== 'publish') {
                unset($items[$key]);
            }
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'clipam_filtrar_menu_paginas_no_publicadas', 20, 2);

// Ocultar "Entradas" del menú de administración
function quitar_menu_entradas()
{
    remove_menu_page('edit.php');
}

add_action('admin_menu', 'quitar_menu_entradas');

function clipam_scripts_styles()
{
    wp_enqueue_style('style', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0.0');
    // wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0.0'); // estilos ubicado en la carpeta raiz
}

add_action('wp_enqueue_scripts', 'clipam_scripts_styles');

/* JS */
function clipam_scripts_js()
{
    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', [], null, true);
}

add_action('wp_enqueue_scripts', 'clipam_scripts_js');

/* SWIPER */
function enqueue_swiper_assets()
{
    wp_enqueue_style('swiper-css', get_template_directory_uri() . '/assets/vendor/css/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/assets/vendor/js/swiper-bundle.min.js', [], null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');

/* MICROMODAL */
function enqueue_modal_assets()
{
    wp_enqueue_script('micromodal', get_template_directory_uri() . '/assets/vendor/js/micromodal.min.js', [], null, true);
    wp_add_inline_script('micromodal', 'MicroModal.init();');
}

add_action('wp_enqueue_scripts', 'enqueue_modal_assets');

// PAGINACIÓN ESPECIALIDADES
function cargar_especialidades_ajax()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $especialidad_id = isset($_POST['especialidad_id']) ? intval($_POST['especialidad_id']) : 0;

    $args = array(
        'post_type'      => 'especialidades',
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'posts_per_page' => 8,
        'paged'          => $paged
    );

    // Si se seleccionó una especialidad específica
    if ($especialidad_id) {
        $args['p'] = $especialidad_id;
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $image_html = '';

            if (has_post_thumbnail()) {
                $image_html = get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'imagen']);
            } else {
                $fallback = get_template_directory_uri() . '/assets/img/img-fallback.jpg';
                $image_html = '<img src="' . esc_url($fallback) . '" alt="Imagen por defecto" class="imagen">';
            }

            $staff_page = get_page_by_path('staff-medico');
            $staff_url = $staff_page ? get_permalink($staff_page->ID) : '#';

            $especialidades_page = get_page_by_path('especialidades');
            $boton = $especialidades_page ? get_field('boton_especialidades', $especialidades_page->ID) : '';
            $boton_html = '';

            if ($boton) {
                $boton_html = '<a href="' . esc_url($staff_url . '?especialidad=' . get_the_ID()) . '" 
                           class="btn estilo_3" 
                           data-especialidad-id="' . get_the_ID() . '">
                            ' . esc_html($boton['texto']) . '
                        </a>';
            }

            $html .= '<li class="card">
                        <div class="contenido text-white">
                            ' . $image_html . '
                            <div class="bg-contenido">
                                <div>
                                    <h3>' . get_the_title() . '</h3>
                                    ' . get_field('descripcion') . '
                                </div>
                                ' . $boton_html . '
                            </div>
                        </div>
                    </li>';
        endwhile;
    endif;

    wp_reset_postdata();

    wp_send_json([
        'html' => $html,
        'maxPages' => $query->max_num_pages
    ]);
}

// Para usuarios logueados y no logueados
add_action('wp_ajax_nopriv_cargar_especialidades', 'cargar_especialidades_ajax');
add_action('wp_ajax_cargar_especialidades', 'cargar_especialidades_ajax');

// PAGINACIÓN DOCTORES
function cargar_doctores_ajax()
{
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
    $especialidad = isset($_POST['especialidad']) ? sanitize_text_field($_POST['especialidad']) : '';

    $meta_query = array();
    $tax_query = array();

    // Filtro por nombre (búsqueda en título)
    $search = '';
    if (!empty($nombre)) {
        $search = $nombre;
    }

    // Filtro por especialidad (relación con CPT)
    if (!empty($especialidad)) {
        $tax_query[] = array(
            'relation' => 'OR',
            array(
                'key' => 'especialidad', // campo ACF (relación)
                'value' => '"' . $especialidad . '"',
                'compare' => 'LIKE'
            ),
        );
    }

    $args = array(
        'post_type'      => 'staff_medico',
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        's'              => $search,
        'posts_per_page' => 8,
        'paged'          => $paged,
        'meta_query'     => $meta_query,
    );

    if (!empty($tax_query)) {
        $args['meta_query'] = $tax_query;
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $image_html = '';

            if (has_post_thumbnail()) {
                $image_html = get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'imagen modal-imagen-doctor']);
            } else {
                $fallback = get_template_directory_uri() . '/assets/img/med-fallback.jpg';
                $image_html = '<img src="' . esc_url($fallback) . '" alt="Imagen por defecto" class="imagen modal-imagen-doctor">';
            }

            $staff_page = get_page_by_path('staff-medico');
            $titulo_boton = $staff_page ? get_field('titulo_boton', $staff_page->ID) : '';

            // Especialidades (relación con CPT)
            $especialidades_html = '';
            $especialidades = get_field('especialidad');
            if ($especialidades) {
                $spans = [];
                foreach ((array)$especialidades as $esp_id) {
                    $spans[] = '<span class="pill">' . esc_html(get_the_title($esp_id)) . '</span>';
                }
                $especialidades_html = implode(' ', $spans);
            }

            // Documentos
            $docs = [];
            $docs_html = '';
            if (have_rows('documentos')) {
                while (have_rows('documentos')) : the_row();
                    $cmp = get_sub_field('cmp');
                    $rne = get_sub_field('rne');

                    if (!empty($cmp)) {
                        $docs[] = '<strong>CMP:</strong> ' . esc_html($cmp);
                    }

                    if (!empty($rne)) {
                        $docs[] = '<strong>RNE:</strong> ' . esc_html($rne);
                    }
                endwhile;
                if (!empty($docs)) {
                    $docs_html = '<p class="documentos">' . implode(' / ', $docs) . '</p>';
                }
            }

            $detalles = get_field('detalles');
            $data_detalles = !empty($detalles) ? esc_attr(json_encode($detalles)) : '[]';

            $html .= '<li class="card doctores">
                        <div class="contenido text-white">
                            ' . $image_html . '
                            <div class="bg-contenido">
                                <div class="pill-contenedor">' . $especialidades_html . '</div>
                                <div class="cuerpo">
                                    <h4>' . get_the_title() . '</h4>
                                    ' . $docs_html . '
                                </div>
                                <span class="titulo-btn"
                                    data-nombre="' . esc_attr(get_the_title()) . '"
                                    data-especialidades="' . esc_attr($especialidades_html) . '"
                                    data-documentos="' . esc_attr($docs_html) . '"
                                    data-imagen="' . esc_attr($image_html) . '"
                                    data-detalles="' . $data_detalles . '"
                                >' . esc_html($titulo_boton) . '</span>
                            </div>
                        </div>
                    </li>';
        endwhile;
    endif;

    wp_reset_postdata();

    wp_send_json([
        'html'     => $html,
        'maxPages' => $query->max_num_pages
    ]);
}

// Para usuarios logueados y no logueados
add_action('wp_ajax_nopriv_cargar_doctores', 'cargar_doctores_ajax');
add_action('wp_ajax_cargar_doctores', 'cargar_doctores_ajax');

// Shortcode para el formulario de libro de reclamaciones
add_shortcode('acf_clipam_data', function () {

    $data = [
        'ruc'          => get_field('ruc', 'informacion-general'),
        'razon_social' => get_field('razon_social', 'informacion-general'),
        'direccion'    => get_field('direccion', 'informacion-general'),
    ];

    return wp_json_encode($data);
});

add_filter('wpcf7_form_tag', function ($tag) {

    $json = do_shortcode('[acf_clipam_data]');
    $data = json_decode($json, true);

    if (!is_array($data)) {
        return $tag;
    }

    switch ($tag['name']) {

        case 'ruc-responsable':
            $tag['values'] = [$data['ruc']];
            $tag['raw_values'] = [$data['ruc']];
            break;

        case 'razon-social-responsable':
            $tag['values'] = [$data['razon_social']];
            $tag['raw_values'] = [$data['razon_social']];
            break;

        case 'direccion-responsable':
            $tag['values'] = [$data['direccion']];
            $tag['raw_values'] = [$data['direccion']];
            break;
    }

    return $tag;
});

/* Insertar divs padres a legales */
function clipam_wrap_tables_only_legales($content) {

    if (is_admin()) {
        return $content;
    }

    if (!is_singular('legales')) {
        return $content;
    }

    if (strpos($content, 'table-scroll') !== false) {
        return $content;
    }

    $content = preg_replace(
        '/<table([^>]*)>/i',
        '<div class="table-scroll"><table$1>',
        $content
    );

    $content = preg_replace(
        '/<\/table>/i',
        '</table></div>',
        $content
    );

    return $content;
}
add_filter('the_content', 'clipam_wrap_tables_only_legales', 20);

/* Obtener ID de un video de Youtube de cualqueir URL valida */
function get_youtube_id($url)
{
    preg_match(
        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
        $url,
        $match
    );

    return $match[1] ?? false;
}

/* Cambio de logo wp-admin */
function custom_login_logo()
{
?>
    <style type="text/css">
        #login h1 a {
            background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/img/LOGO_Y_VARIABLES-0001_small.png');
            background-size: contain;
            width: 100%;
            height: 80px;
        }
    </style>
<?php
}

add_filter('login_headerurl', function () {
    return home_url();
});

add_filter('login_headertext', function () {
    return get_bloginfo('name');
});

add_action('login_enqueue_scripts', 'custom_login_logo');
