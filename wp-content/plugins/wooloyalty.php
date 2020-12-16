<?php
/**
 * Plugin Name:       Woocommerce Loyalty System
 * Description:       Вывод поля с бонусами за покупку в карточку товара WooCommerce.
 * Plugin URI:        https://github.com/MarkJobsky/wp-woo-bonus-field
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mark Jobsky
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain:       wooloyalty
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Product editor

add_action('woocommerce_product_options_general_product_data', 'wooloyalty_addbonuses');

function wooloyalty_addbonuses() {

    echo '<div class="options_group">';// Группировка полей

    woocommerce_wp_text_input( array(
        'id'                => '_product_bonuses',
        'label'             => __( 'Бонусы за покупку', 'woocommerce' ),
        'placeholder'       => '',
        'desc_tip'          => 'true',
        'custom_attributes' => array(),
        'description'       => __( 'Введите кол-во баллов за покупку', 'woocommerce' ),
    ) );

    echo '</div>';
}

// Save data

add_action( 'woocommerce_process_product_meta', 'save_bonus_count', 10, 1 );

function save_bonus_count( $post_id ){

    $text_field = isset( $_POST['_product_bonuses'] ) ? sanitize_text_field( $_POST['_product_bonuses'] ) : '';

    update_post_meta($post_id,'_product_bonuses', $text_field );

}

// OUTPUT BONUS COUNT IN SINGLE PRODUCT PAGE

function wooloyalty_woo_single_product() {
    global $product;
    $bonus_count =  get_post_meta( $product->get_id(), '_product_bonuses', true );
    if ($bonus_count > 0) {
    echo '<div class="bonus" style="font-size: 10pt">За эту покупку вы получите '.$bonus_count.' б.</div>';
    }
}

add_action( 'woocommerce_single_product_summary', 'wooloyalty_woo_single_product', 25 );
?>