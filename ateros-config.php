<?php

/*
* This is the custom Wordpress configuration for Ateros Textile
*/


/*
 * Custom widgets
 */

function widgetWelcomeContents()
{
    echo "Commencez dès maintenant à ajouter vos produits !";

}

function addWidgets()
{

    wp_add_dashboard_widget(
        'wpexplorer_dashboard_widget',
        'Bienvenue sur Ateros Textile',
        'widgetWelcomeContents'
    );
}

add_action('wp_dashboard_setup', 'addWidgets');

/*
 * Custom menus
 */

function textilePageContent()
{
    ?>
    <div class="wrap">
        <h1>Ateros Textile</h1>
    </div>
    <?php
}

function registerTextileSidebarButton()
{
    add_menu_page(
        'Ateros Textile',
        'Ateros Textile',
        'manage_options',
        'ateros-textile',
        'textilePageContent',
        'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIzLjAuNiwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAzOTYgNTY4IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAzOTYgNTY4OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+Cgkuc3Qwe2ZpbGw6IzQzNDM0Mzt9Cjwvc3R5bGU+CjxnPgoJPHBhdGggY2xhc3M9InN0MCIgZD0iTTEzNi44LDEyNy41djYxLjNoLTM0LjF2NTcuMWgzNC4ydjE0MS45YzAsMjkuNSw3LjMsNTIuNCwyMS45LDY4LjhjMTQuNiwxNi4zLDM2LjMsMjQuNSw2NS4xLDI0LjUKCQljMTIuOCwwLDI1LjUtMiwzOC4xLTUuOXMyMy43LTkuNCwzMy4zLTE2LjVMMjY4LDQwOGMtOS42LDUuMy0xOC41LDgtMjYuNyw4Yy0xNC45LDAtMjIuNC05LjctMjIuNC0yOS4zVjI0NS45aDQ5LjZsOC41LTU3LjFoLTU4LjEKCQl2LTcwLjlMMTM2LjgsMTI3LjV6Ii8+CjwvZz4KPC9zdmc+Cg==',
        2
    );
}

add_action('admin_menu', 'registerTextileSidebarButton');

/*
 * Plugin auto-activation
 */

function activate_all_plugins()
{
    activate_plugins(['woocommerce-3.7.1/woocommerce.php']);
}

add_action('admin_init', 'activate_all_plugins');

/*
 * Dashboard wigdets removal
 */

remove_action('welcome_panel', 'wp_welcome_panel');
function remove_dashboard_widgets()
{
    global $wp_meta_boxes;

    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');


/*
 * Custom Woocommerce fields
 */

function addDesignerLinkOption()
{
    $args = array(
        'id' => 'designer-link',
        'label' => 'Lien Ateros Designer',
        'class' => 'custom-field',
        'desc_tip' => true,
        'description' => 'Créez votre design sur Ateros Designer, puis collez le lien ici.',
    );
    woocommerce_wp_text_input($args);
}

add_action('woocommerce_product_options_general_product_data', 'addDesignerLinkOption');

function saveDesignerLinkOption($post_id)
{
    $product = wc_get_product($post_id);
    $link = isset($_POST['designer-link']) ? $_POST['designer-link'] : '';
    $product->update_meta_data('designer-link', sanitize_text_field($link));
    $product->save();
}

add_action('woocommerce_process_product_meta', 'saveDesignerLinkOption');