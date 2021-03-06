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
