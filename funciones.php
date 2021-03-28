<?php
/*
Plugin Name: Funciones para WordPress
Plugin URI: https://twitter.com/
Description: Plugin para liberar de funciones el fichero <code>functions.php</code>, con las principales funciones, como Google Fonts y personalización del logo. Versión estándar y base, a partir de esta puedes añadir y configurar todas las funciones dependiendo del sitio web al que las apliques.
Version: 1.0
Author: Tu nombre
Author URI: https://tupaginaweb.com/
License: GPLv2 o posterior
*/

/**           **
 ** SEGURIDAD **
 **           */  
function agregar_cabeceras_seguridad() {
header( 'Strict-Transport-Security: "max-age=31536000" env=HTTPS' );
header( 'X-XSS-Protection: 1;mode=block' );
header( 'X-Content-Type-Options: nosniff' );
header( 'X-Frame-Options: SAMEORIGIN' );
header( 'Referrer-Policy: no-referrer-when-downgrade' );
header( "Content-Security-Policy default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';" ); 
}
add_action( 'send_headers', 'agregar_cabeceras_seguridad' );

/* Añadir Font Awesome // Add Font Awesome */
add_action( 'wp_enqueue_scripts', 'custom_load_font_awesome' );
/**
 * Enqueue Font Awesome.
 */
function custom_load_font_awesome() {
    wp_enqueue_style( 'font-awesome-free', '//use.fontawesome.com/releases/v5.15.1/css/all.css' );
}

/* Añadir Google Fonts // Add Google Fonts */

function bps_enqueue_font() { 
wp_enqueue_style('google-font-pdisplay', 'https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap', array()); /* Playfair Display */
wp_enqueue_style('google-font-mulish', 'https://fonts.googleapis.com/css2?family=Mulish:wght@500&display=swap', array()); /* Mulish */
wp_enqueue_style('google-font-raleway', 'https://fonts.googleapis.com/css2?family=Raleway:wght@400&display=swap', array()); /* Raleway */

add_action( 'wp_enqueue_scripts', 'bps_enqueue_font' );

/**     **
 ** WPO **
 **     */

/* Desactivar self pingbacks // Deactivate self pingbacks */
function no_self_pings( &$links ) {
  foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);
}

add_action( 'pre_ping', 'no_self_pings' );

/* Precarga de DNS externas  // Prefetch dns */
function dns_prefetch() {
echo '<meta http-equiv="x-dns-prefetch-control" content="on">
<link rel="dns-prefetch" href="//fonts.googleapis.com" />
<link rel="dns-prefetch" href="//fonts.gstatic.com" />
<link rel="dns-prefetch" href="//ajax.googleapis.com" />
<link rel="dns-prefetch" href="//apis.google.com" />
<link rel="dns-prefetch" href="//google-analytics.com" />
<link rel="dns-prefetch" href="//www.google-analytics.com" />
<link rel="dns-prefetch" href="//ssl.google-analytics.com" />
<link rel="dns-prefetch" href="//youtube.com" />
<link rel="dns-prefetch" href="//api.pinterest.com" />
<link rel="dns-prefetch" href="//connect.facebook.net" />
<link rel="dns-prefetch" href="//platform.twitter.com" />
<link rel="dns-prefetch" href="//syndication.twitter.com" />
<link rel="dns-prefetch" href="//syndication.twitter.com" />
<link rel="dns-prefetch" href="//platform.instagram.com" />
<link rel="dns-prefetch" href="//s.gravatar.com" />
<link rel="dns-prefetch" href="//s0.wp.com" />
<link rel="dns-prefetch" href="//stats.wp.com" />';
}
add_action('wp_head', 'dns_prefetch', 0);

/* Defer parsing of JS YouTube (https://ayudawp.com/defer-parsing-javascript-youtube/) Needs to be fixed */ /*
function init() {
    var vidDefer = document.getElementsByTagName('iframe');
  for (var i=0; i<vidDefer.length; i++) {
    if(vidDefer[i].getAttribute('data-src')) {
      vidDefer[i].setAttribute('src',vidDefer[i].getAttribute('data-src'));
} } }
window.onload = init; */

/* Personalizacion login // Login personalisation*/
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/logo.png);
        height: 65px;
        width: 320px;
        background-size: 320px 65px;
        background-repeat: no-repeat;
        padding-bottom: 20px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'My website - Subtitle';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/login.css' );
    wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/js/login.js' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

/* Botones compartir propios // Sharing buttons */ 
function pd_social_share() {
if(is_singular('post')) {
echo '<div class="social-share">

<a style="text-decoration:none;" class="fontawesome social-icon facebook" href="http://www.facebook.com/sharer.php?u='.get_permalink().'" target="_blank"></a>

<a style="text-decoration:none;" class="fontawesome social-icon twitter" href="https://twitter.com/share?url='.get_permalink().'&text='.get_the_title().'&&via=user" target="_blank"></a>

<a style="text-decoration:none;" class="fontawesome social-icon whatsapp" href="whatsapp://send?text='.get_the_title().' '.get_permalink().'" target="_blank"></a>

<a style="text-decoration:none;" class="fontawesome social-icon telegram" href="https://telegram.me/share/url?url='.get_the_permalink().'&text='.get_the_title().'"></a>

<a style="text-decoration:none;" class="fontawesome generic-icon email" href="mailto:?Subject=Acabo de descubrir Website&amp;Body= https://yoururl.com" target="blank"></a>

</div>';
}}

/* 
 ***** WooCommerce *****
*/

/* Avisar a cliente conectado si ya compró productos */
add_action( 'woocommerce_after_shop_loop_item', 'ya_comprado', 30 );
function ya_comprado() {
global $product;
if ( ! is_user_logged_in() ) return;
$current_user = wp_get_current_user();
if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->get_id() ) ) echo '<div class="user-bought">&hearts; ¡Hola ' . $current_user->first_name . ', esto ya lo compraste anteriormente! ¿Quieres comprarlo de nuevo?</div>';
}

/* Mensaje personalizado si el cliente selecciona Portugal */
// Parte 1 - Creamos el mensaje y lo ponemos sobre la parte de facturación
add_action( 'woocommerce_before_checkout_billing_form', 'mensaje_portugal' );

function mensaje_portugal() {
echo '<div class="shipping-notice woocommerce-info" style="display:none">Los pedidos a Portugal tardan de 3 a 5 días laborales a partir del pedido.</div>';
}

// Parte 2 - Mostramos u ocultamos el mensaje según el país
add_action( 'woocommerce_after_checkout_form', 'mostrar_mensaje_portugal' );
function mostrar_mensaje_portugal(){
?>
<script>
jQuery(document).ready(function($){
// Pon aquí el código de país para el que se mostrará el mensaje
var countryCode = 'PT';
$('select#billing_country').change(function(){
selectedCountry = $('select#billing_country').val();
if( selectedCountry == countryCode ){
$('.shipping-notice').show();
}
else {
$('.shipping-notice').hide();
}
});
});
</script>
<?php
}

/* Enlazar a las pestañas de WooCommerce */
add_action( 'woocommerce_single_product_summary', 'scroll_tabs_products', 21 );
function scroll_tabs_products() {
global $post, $product; 
// ENLACE A LA PESTAÑA DE DESCRIPCIÓN
if ( $post->post_content ) {
echo '<p><a class="ir-a-la-tab" href="#tab-description">' . __( 'Ver más', 'woocommerce' ) . ' &rarr;</a></p>';
}

// ENLACE A LA PESTAÑA DE INFORMACIÓN ADICIONAL
if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
echo '<p><a class="ir-a-la-tab" href="#tab-additional_information">' . __( 'Información adicional', 'woocommerce' ) . ' &rarr;</a></p>';
}

// ENLACE A LA PESTAÑA DE VALORACIONES
if ( comments_open() ) {
echo '<p><a class="ir-a-la-tab" href="#tab-reviews">' . __( 'Ver valoraciones', 'woocommerce' ) . ' &rarr;</a></p>';
}

// ENLACE A PESTAÑA PERSONALIZADA
if ( $post->post_content ) { echo '<p><a class="ir-a-la-tab" href="#tab-personalizada">' . __( 'Instrucciones', 'woocommerce' ) . ' &rarr;</a></p>'; }
?>
<script>
jQuery(document).ready(function($){
$('a.ir-a-la-tab').click(function(e){
e.preventDefault();
var tabhash = $(this).attr("href");
var tabli = 'li.' + tabhash.substring(1);
var tabpanel = '.panel' + tabhash;
$(".wc-tabs li").each(function() {
if ( $(this).hasClass("active") ) {
$(this).removeClass("active");
}
});
$(tabli).addClass("active");
$(".woocommerce-tabs .panel").css("display","none");
$(tabpanel).css("display","block");
$('html,body').animate({scrollTop:$(tabpanel).offset().top}, 750);
});
});
</script>
<?php
}

/* Quitar precio de los resultados de Google */
add_filter( 'woocommerce_structured_data_product_offer', '__return_empty_array' );

/* Errores junto al campo con el error */
add_filter( 'woocommerce_form_field', 'etiqueta_error_pago', 10, 4 );
function etiqueta_error_pago( $field, $key, $args, $value ) {
if ( strpos( $field, '</label>' ) !== false && $args['required'] ) {
$error = '<span class="error" style="display:none">';
$error .= sprintf( __( '%s es un campo obligatorio.', 'woocommerce' ), $args['label'] );
$error .= '</span>';
$field = substr_replace( $field, $error, strpos( $field, '</label>' ), 0);
}
return $field;
}

/* Añadir descuento según volumen de compra */
add_action( 'woocommerce_before_calculate_totals', 'precio_segun_cantidad', 9999 );
function precio_segun_cantidad( $cart ) {
if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;

// Define aquí las reglas de descuento y los umbrales
$umbral1 = 11; // Cambiar precio si hay > 10 productos
$descuento1 = 0.05; // Rebaja del 5% si hay > 10 productos
$umbral2 = 21; // Cambiar precio si hay > 20 productos
$descuento2 = 0.1; // Rebaja del 10% si hay > 20 productos

foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
if ( $cart_item['quantity'] >= $umbral1 && $cart_item['quantity'] < $umbral2 ) {
$price = round( $cart_item['data']->get_price() * ( 1 - $descuento1 ), 2 );
$cart_item['data']->set_price( $price );
} elseif ( $cart_item['quantity'] >= $umbral2 ) {
$price = round( $cart_item['data']->get_price() * ( 1 - $descuento2 ), 2 );
$cart_item['data']->set_price( $price );
}
}
}

/* Mostrar productos de venta cruzada en la pagina de agradecimiento */
add_action( 'woocommerce_thankyou', 'ventacruzada_agradecimiento' );
function ventacruzada_agradecimiento() {
echo '<h2>¿Has visto ya estos otros productos?</h2>';
echo do_shortcode( '[products ids="136,137,138"]' );
}

/* Parte 1 - Producto de WooCommerce */
add_filter( 'woocommerce_get_price_html', 'descuento_clientes_producto', 9999, 2 );
function descuento_clientes( $price_html, $product ) {
    // SOLO EN LA TIENDA
    if ( is_admin() ) return $price_html;
    // SOLO SI NO HAY PRECIO
    if ( '' === $product->get_price() ) return $price_html;
    // SI EL CLIENTE ESTÁ CONECTADO APLICAR 20% DE DESCUENTO   
    if ( wc_current_user_has_role( 'customer' ) ) {
        $orig_price = wc_get_price_to_display( $product );
        $price_html = wc_price( $orig_price * 0.80 );
    }
    return $price_html;
}
/* Parte 2 - Carrito y finalizar compra */
add_action( 'woocommerce_before_calculate_totals', 'descuento_clientes_carrito', 9999 );
function descuento_clientes_carrito( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;
    // SI EL CLIENTE NO ESTA CONECTADO NO SE APLICA DESCUENTO
    if ( ! wc_current_user_has_role( 'customer' ) ) return;
    // LOOP POR PRODUCTOS DEL CARRITO Y APLICAR DESCUENTO 20%
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];
        $price = $product->get_price();
        $cart_item['data']->set_price( $price * 0.80 );
    }
}

/* Añadir PDF a emails de nuevo pedido y procesando  */
add_filter( 'woocommerce_email_attachments', 'archivo_emails_woo', 10, 4 );
function archivo_emails_woo( $attachments, $email_id, $order, $email ) {
    $email_ids = array( 'new_order', 'customer_processing_order' ); 
    if ( in_array ( $email_id, $email_ids ) ) {
        $upload_dir = wp_upload_dir();
        $attachments[] = $upload_dir['basedir'] . "/2020/10/catalogo-ofertas.pdf"; // Change url path
    }
    return $attachments;
} 

// List of all variables of $email_ids
/* // 'cancelled_order' // pedido cancelado
'customer_processing_order' // pedido procesándose
'customer_invoice' // factura del cliente
'customer_new_account' // nueva cuenta de cliente
'customer_note' // notas al cliente
'customer_on_hold_order' // pedido en espera
'customer_refunded_order' // reembolso
'customer_reset_password' // restablecer contraseña
'failed_order' // pedido fallido
'new_order' // nuevo pedido
*/

/* Descarga por defecto en todas las cuentas */
add_filter( 'woocommerce_customer_get_downloadable_products', 'descarga_gratis', 9999, 1 );
 
function descarga_gratis( $downloads ) {
   $downloads[] = array(
      'product_name' => 'Descripción de la descarga',
      'download_name' => 'Etiqueta del botón',
      'download_url' => 'https://web.es/archivo.pdf',
   );
   return $downloads;
}

/* Campos solo lectura en editar cuenta */
add_filter( 'woocommerce_billing_fields', 'readonly_billing_account_fields', 25, 1 );
function readonly_billing_account_fields ( $billing_fields ) {
    // Solo la dirección de facturación para usuarios conectados
    if( is_user_logged_in() && is_account_page() ){

        $readonly = ['readonly' => 'readonly'];

        $billing_fields['billing_first_name']['custom_attributes'] = $readonly;
        $billing_fields['billing_last_name']['custom_attributes'] = $readonly;
        $billing_fields['billing_email']['custom_attributes'] = $readonly;
    }
    return $billing_fields;
}

/* Shortcode para mostrar valoraciones de WooCommerce */
//Aquí definimos el nombre del shortcode y la función que invoca
add_shortcode( 'valoraciones_producto', 'shortcode_valoraciones' );
 
function shortcode_valoraciones( $atts ) {
    
   if ( empty( $atts ) ) return '';
 
   if ( ! isset( $atts['id'] ) ) return '';
//Aquí definimos que hay que indicar el ID del producto del que queremos mostrar valoraciones       
   $comments = get_comments( 'post_id=' . $atts['id'] );
    
   if ( ! $comments ) return '';
//Generamos el HTML para mostrar las valoraciones al estilo Woo, con avatares, estrellitas y todo    
   $html .= '<div class="woocommerce-tabs"><div id="reviews"><ol class="commentlist">';
    
   foreach ( $comments as $comment ) {   
      $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
      $html .= '<li class="review">';
      $html .= get_avatar( $comment, '60' );
      $html .= '<div class="comment-text">';
      if ( $rating ) $html .= wc_get_rating_html( $rating );
      $html .= '<p class="meta"><strong class="woocommerce-review__author">';
      $html .= get_comment_author( $comment );
      $html .= '</strong></p>';
      $html .= '<div class="description">';
      $html .= $comment->comment_content;
      $html .= '</div></div>';
      $html .= '</li>';
   }
    
   $html .= '</ol></div></div>';
    
   return $html;
}
// [valoraciones_producto id=888]

/* Distinto nombre a Mi cuenta si el usuario está desconectado */
add_filter( 'wp_nav_menu_items', 'dynamic_label_change', 10, 2 ); 
 
function dynamic_label_change( $items, $args ) { 
   if ( ! is_user_logged_in() ) { 
      $items = str_replace( "Mi cuenta", "Acceder/Registrarse", $items ); 
   } 
   return $items; 
}

/* Productos WooCommerce sin pestañas */
function woocommerce_output_product_data_tabs() {
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
if ( empty( $product_tabs ) ) return;
echo '<div class="woocommerce-tabs wc-tabs-wrapper">';
foreach ( $product_tabs as $key => $product_tab ) {
?>
<div id="tab-<?php echo esc_attr( $key ); ?>">
<?php
if ( isset( $product_tab['callback'] ) ) {
call_user_func( $product_tab['callback'], $key, $product_tab );
}
?>
</div>
<?php 
}
echo '</div>';
}

/* Desactivar estilos y scripts de WooCommerce */
add_action('wp_enqueue_scripts', 'ayudawp_quitar_scripts_woocommerce', 99);
function ayudawp_quitar_scripts_woocommerce() {
if(function_exists('is_woocommerce')) {
if(!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() && !is_product() && !is_product_category() && !is_shop()) {
//Estilos
wp_dequeue_style('woocommerce-general');
wp_dequeue_style('woocommerce-layout');
wp_dequeue_style('woocommerce-smallscreen');
wp_dequeue_style('woocommerce_frontend_styles');
wp_dequeue_style('woocommerce_fancybox_styles');
wp_dequeue_style('woocommerce_chosen_styles');
wp_dequeue_style('woocommerce_prettyPhoto_css');
//Scripts
wp_dequeue_script('wc_price_slider');
wp_dequeue_script('wc-single-product');
wp_dequeue_script('wc-add-to-cart');
wp_dequeue_script('wc-checkout');
wp_dequeue_script('wc-add-to-cart-variation');
wp_dequeue_script('wc-single-product');
wp_dequeue_script('wc-cart');
wp_dequeue_script('wc-chosen');
wp_dequeue_script('woocommerce');
wp_dequeue_script('prettyPhoto');
wp_dequeue_script('prettyPhoto-init');
wp_dequeue_script('jquery-blockui');
wp_dequeue_script('jquery-placeholder');
wp_dequeue_script('fancybox');
wp_dequeue_script('jqueryui');
}
}
}