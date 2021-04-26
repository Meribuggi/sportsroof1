<?php
/**
 * Plugin Name: Remove Add to Cart
 * Plugin URI: http://localhost/sportroof/view-add-to-cart
 * Description: Remove add to cart button if User not logged in
 * Version: 1.0
 * Author: Dav Ammy
 */
 
 
	
function my_function_remove_addtocart_category(){
	if ( !is_user_logged_in() ){    
       remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' ); // for listing page
       remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 ); // single page
       
	}
}

add_action( 'wp', 'my_function_remove_addtocart_category' );

function btnmsg(){
        //
        if (!is_user_logged_in()) {
            $wc_ac_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
            $msg = __('Please log in to buy the product', 'wdmacspc');
            echo "<a href='$wc_ac_url' class='button alt wl8-custom-btn'>$msg</a>";
        }
}

add_action('woocommerce_single_product_summary', 'btnmsg', 10);