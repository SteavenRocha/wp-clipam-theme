<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--  <title>Document</title> -->
    <?php wp_head(); ?>
</head>

<body>
    <header class="header">
        <div class="contenedor barra-navegacion">
            <div class="logo">
                <!-- Logo -->
                <?php
                $logo_header = get_field('logo_header', 'informacion-general');
                if ($logo_header):
                    echo wp_get_attachment_image($logo_header, 'full', false, array('class' => 'logo-header'));
                endif;
                ?>
            </div>

            <!-- Navegacion -->
            <div class="menu-principal-container">
                <!-- Botón hamburguesa -->
                <div class="menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <?php

                $args = array(
                    'theme_location' => 'menu-principal',
                    'container' => 'nav',
                    'container_class' => 'menu-principal'
                );

                wp_nav_menu($args);

                ?>

                <div class="extra-menu-item">
                    <?php
                    $boton_intranet_grupo = get_field('boton_intranet_grupo', 'informacion-general');
                    $boton_intranet = $boton_intranet_grupo['boton_intranet'];

                    $texto_btn_intranet = $boton_intranet['texto_boton_intranet'];
                    $estilo_btn_intranet = $boton_intranet['estilo_boton_intranet'];
                    ?>

                    <button class="btn tiny-btn <?php echo esc_attr($estilo_btn_intranet); ?> toggle-desplegable">
                        <?php echo esc_html($texto_btn_intranet); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 48 48">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M36 18L24 30L12 18" />
                        </svg>
                    </button>

                    <?php if (!empty($boton_intranet_grupo) && !empty($boton_intranet_grupo['sub_botones'])) : ?>
                        <div class="desplegable <?php echo esc_attr($estilo_btn_intranet); ?>">
                            <?php foreach ($boton_intranet_grupo['sub_botones'] as $sub) :
                                $texto_subBtn = isset($sub['texto_subBoton']) ? $sub['texto_subBoton'] : '';
                                $url_subBtn   = isset($sub['url_subBoton']) ? $sub['url_subBoton'] : '#';
                            ?>
                                <a href="<?php echo esc_url($url_subBtn); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html($texto_subBtn); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </header>

    <div class="menu-overlay"></div>