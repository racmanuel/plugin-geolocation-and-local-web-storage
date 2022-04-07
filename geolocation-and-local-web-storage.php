<?php
/*
 * Plugin Name: GeoLocation and Local Web Storage - JS
 * Description: Plugin to show how use the GeoLocation API and the Local Web Storage in a WordPress Shortcode and plugin, and save the the location in a custom post type.
 * Version: 1.0
 * Author: Manuel Ramirez Coronel
 * Author URI: https://github.com/racmanuel
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

function wp_enqueue_scripts_styles()
{
    wp_enqueue_script('location', plugins_url('/js/custom.js', __FILE__), array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'wp_enqueue_scripts_styles');

/**
 * Make a Custom Meta Box for Add Custom Fields
 */
function ubicacion_custom_meta_box()
{
    add_meta_box(
        'ubicaciones', // Unique ID
        'Ubicaciones', // Box title
        'ubicacion_content_of_custom_meta_box', // Content callback, must be of type callable
        'ubicaciones' // Post type
    );
}
add_action('add_meta_boxes', 'ubicacion_custom_meta_box');

/**
 * Callback of ubicacion_custom_meta_box()
 */
function ubicacion_content_of_custom_meta_box($post)
{
    /**
     * Get the values of Custom Fields and puts in the inputs if exists
     */
    $latitude = get_post_meta($post->ID, 'latitud', true);
    $longitud = get_post_meta($post->ID, 'longitud', true);
    ?>
        <!-- Print the content of the Custom Meta box -->
        <h4>Datos de Ubicacion</h4>
        <label for="latitud">Latitud</label>
        <input type="text" name="latitud" id="latitud" value="<?php echo $latitude; ?>"></input>
        <label for="longitud">Longitud</label>
        <input type="text" name="longitud" id="longitud" value="<?php echo $longitud; ?>"></input>
        <hr>
        <h4>Ubicacion en Google Maps</h4>
    <?php
    echo "<iframe src='https://maps.google.com/maps?q={$latitude},{$longitud}&z=15&output=embed' width='360' height='270' frameborder='0' style='border:0'></iframe>";
}

/**
 * Save the Custom Fields inside of Ubicaciones Meta Boxes
 */
function ubicacion_save_custom_fields($post_id)
{
    if (array_key_exists('latitud', $_POST)) {
        update_post_meta(
            $post_id,
            'latitud',
            $_POST['latitud']
        );
    }
    if (array_key_exists('longitud', $_POST)) {
        update_post_meta(
            $post_id,
            'longitud',
            $_POST['longitud']
        );
    }
}
add_action('save_post', 'ubicacion_save_custom_fields');

/**
 * Make a Shorcode for Front-End Form to get location and save in Custom Post Type: Ubicaciones
 */
function get_current_location()
{
    ?>
<div>
    <form id="myForm" name="myform" action="<?php echo esc_attr(admin_url('admin-post.php')); ?>" method="POST">
        <input type="hidden" name="action" value="save_my_custom_form" />
        <label for="latitud">Latitud</label>
        <input type="text" name="f-latitud" id="latitud"></input>
        <label for="longitud">Longitud</label>
        <input type="text" name="f-longitud" id="longitud"></input>
        <input type="submit" value="Enviar" />
    </form>
    <button id="get_location">Obtener Ubicacion</button>
</div>
<?php
/**
     * If you need the variable in PHP you can use the next:
     * $latitude = $_GET['latitude'];
     * $longitud = $_GET['longitude'];
     */

}
add_shortcode('location', 'get_current_location');

add_action('admin_post_nopriv_save_my_custom_form', 'process_form');
add_action('admin_post_save_my_custom_form', 'process_form');
/**
 * Proceess to save the form data in a Custom Post Type: Ubicaciones
 */
function process_form()
{
    /** Get the Variables*/
    $latitude = $_POST['f-latitud'];
    $longitud = $_POST['f-longitud'];

    /** Make the Title of the Custom Post Type: Ubicaciones */
    $title = 'Ubicacion: '. $latitude . ' ' . $longitud;
    
    $my_post = array(
        'post_title' => $title,
        'post_status' => 'publish',
        'post_type' => 'ubicaciones',
        'meta_input' => array(
         'latitud' => $latitude,
         'longitud' => $longitud,
    ));
    wp_insert_post( $my_post );
    wp_redirect(home_url());
}

/**
 * Add a Custom Post Type
 */
function cptui_register_my_cpts() {

	/**
	 * Post Type: Ubicaciones.
	 */

	$labels = [
		"name" => __( "Ubicaciones", "storefront" ),
		"singular_name" => __( "Ubicación", "storefront" ),
		"menu_name" => __( "Ubicaciones", "storefront" ),
		"all_items" => __( "Todas las Ubicaciones", "storefront" ),
		"add_new" => __( "Añadir nueva", "storefront" ),
		"add_new_item" => __( "Añadir nueva Ubicación", "storefront" ),
		"edit_item" => __( "Editar Ubicación", "storefront" ),
		"new_item" => __( "Nueva Ubicación", "storefront" ),
		"view_item" => __( "Ver Ubicación", "storefront" ),
		"view_items" => __( "Ver Ubicaciones", "storefront" ),
		"search_items" => __( "Buscar Ubicaciones", "storefront" ),
		"not_found" => __( "No se han encontrado Ubicaciones", "storefront" ),
		"not_found_in_trash" => __( "No se han encontrado Ubicaciones en la papelera", "storefront" ),
		"parent" => __( "Ubicación superior:", "storefront" ),
		"featured_image" => __( "Imagen destacada para Ubicación", "storefront" ),
		"set_featured_image" => __( "Establece una imagen destacada para Ubicación", "storefront" ),
		"remove_featured_image" => __( "Eliminar la imagen destacada de Ubicación", "storefront" ),
		"use_featured_image" => __( "Usar como imagen destacada de Ubicación", "storefront" ),
		"archives" => __( "Archivos de Ubicación", "storefront" ),
		"insert_into_item" => __( "Insertar en Ubicación", "storefront" ),
		"uploaded_to_this_item" => __( "Subir a Ubicación", "storefront" ),
		"filter_items_list" => __( "Filtrar la lista de Ubicaciones", "storefront" ),
		"items_list_navigation" => __( "Navegación de la lista de Ubicaciones", "storefront" ),
		"items_list" => __( "Lista de Ubicaciones", "storefront" ),
		"attributes" => __( "Atributos de Ubicaciones", "storefront" ),
		"name_admin_bar" => __( "Ubicación", "storefront" ),
		"item_published" => __( "Ubicación publicado", "storefront" ),
		"item_published_privately" => __( "Ubicación publicado como privado.", "storefront" ),
		"item_reverted_to_draft" => __( "Ubicación devuelto a borrador.", "storefront" ),
		"item_scheduled" => __( "Ubicación programado", "storefront" ),
		"item_updated" => __( "Ubicación actualizado.", "storefront" ),
		"parent_item_colon" => __( "Ubicación superior:", "storefront" ),
	];

	$args = [
		"label" => __( "Ubicaciones", "storefront" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"can_export" => true,
		"rewrite" => [ "slug" => "ubicaciones", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-admin-site",
		"supports" => false,
		"show_in_graphql" => false,
	];

	register_post_type( "ubicaciones", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );
