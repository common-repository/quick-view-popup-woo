<?php

namespace WPVQV\Frontend;

use WPVQV\Admin\Settings;

class Module
{
    public function __construct() {

        // Enqueue scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Button position
        $this->quick_view_button_position();

        // Popup content action
        $this->popup_content_action();

        // AJAX
        add_action('wp_ajax_wpvqv_quick_view', array($this, 'quick_view_popup'));
        add_action('wp_ajax_nopriv_wpvqv_quick_view', array($this, 'quick_view_popup'));
        add_action( 'wp_footer', array( $this, 'include_woocommerce_photoswipe' ) );

        // Popup product image
        add_action('wpvqv/shop-page/render-product-image', [$this, 'popup_product_image']);
        
        // Add WoocCommerce theme support to our theme
        add_action( 'after_setup_theme', array($this,'add_woocommerce_support') );

        add_action('wp_footer', array($this, 'popup_html'));

        add_action('script_loader_tag', array($this, 'add_module_type'), 10, 2);
    }

    // Enqueue scripts
    public function enqueue_scripts(){
        if (( is_shop() || is_product_category() ) ) {
        
            wp_enqueue_style('wpvqv-frontend-style', WPVQV_URL . 'assets/css/frontend.css', [], WPVQV_VERSION);
            wp_enqueue_script('wpvqv-frontend', WPVQV_URL . 'assets/js/frontend.js', ['jquery'], '0.1', true);
            wp_localize_script('wpvqv-frontend', 'wpvqv', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('wpvqv_quick_view_nonce'),
            ));

            wp_enqueue_script('wc-add-to-cart-variation');
            wp_enqueue_script('wc-single-product');
            wp_enqueue_script( 'flexslider' );
            wp_enqueue_script( 'zoom' );
            wp_enqueue_script( 'photoswipe' );
            wp_enqueue_script( 'photoswipe-ui-default' );
            wp_enqueue_style( 'photoswipe-default-skin' );
            
        }
    }

    // add module type
    public function add_module_type($tag, $handle) {
        if ('wpvqv-frontend' !== $handle) {
            return $tag;
        }
        return str_replace(' src', ' type="module" src', $tag);
    }

    // Add WoocCommerce theme support to our theme
    function add_woocommerce_support() {
        add_theme_support( 'woocommerce' );
        // To enable gallery features add WooCommerce Product zoom effect, lightbox and slider support to our theme
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    // Include photoswipe
    public function include_woocommerce_photoswipe() {
        wc_get_template( 'single-product/photoswipe.php' );
    }

    function popup_html(){
        ?>
            <div class="ldcv lg wpvqv-ld-cover-popup">     
                <div class="base">    
                    <div class="wpvqv-inner-content"> 
                    </div> 
                </div>
            </div>
        <?php
    }
    
    // Create quick view button
    public function quick_view_button() {
        if ( ! ( is_shop() || is_product_category() ) ) {
            return;
        }
    
        $label = Settings::init()->get('quick_view_btn_label');
        $button_display_mode = Settings::init()->get('quick_view_btn_layout');


        ?>
        <a href="#" class="wpvqv-quick-view-btn button" data-product_id="<?php the_ID(); ?>"><?php 
            if ($button_display_mode === 'icon' || $button_display_mode === 'icon_with_label') 
                {
                    ?>
                        <svg width="100px" height="100px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" >
                            <path d="M15.0007 12C15.0007 13.6569 13.6576 15 12.0007 15C10.3439 15 9.00073 13.6569 9.00073 12C9.00073 10.3431 10.3439 9 12.0007 9C13.6576 9 15.0007 10.3431 15.0007 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.0012 5C7.52354 5 3.73326 7.94288 2.45898 12C3.73324 16.0571 7.52354 19 12.0012 19C16.4788 19 20.2691 16.0571 21.5434 12C20.2691 7.94291 16.4788 5 12.0012 5Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php 
                } 
            if ($button_display_mode === 'icon_with_label' || $button_display_mode === 'label') { ?>
                <?php echo esc_html($label); 
                } ?>
        </a>
      <?php
      
    }
    
    // Create View Details button
    public function view_details_button_popup() {
        $label = Settings::init()->get('view_details_btn_label');
        
        ?>
        <div class="wpvqv-view-details-wrapper">
            <a href="<?php  echo esc_url(get_permalink()); ?>" class="view-details-btn">
                <?php echo esc_html($label); ?>
            </a>
        </div>
        <?php
    
    }

    // Button position
    public function quick_view_button_position() {

        $button_position = Settings::init()->get('quick_view_btn_position');
        $actions = [
            'next_add_to_cart' => [
                'hook' => 'woocommerce_after_shop_loop_item',
                'priority' => 20,
            ],
        ];
    
        if (isset($actions[$button_position])) {
            add_action($actions[$button_position]['hook'], [$this, 'quick_view_button'], $actions[$button_position]['priority']);

        }else{
            add_action('woocommerce_after_shop_loop_item', [$this, 'quick_view_button']);}
    }

    // Popup content action
    public function popup_content_action(){
        $selected_actions = Settings::init()->get('quick_view_popup_content');
        $content = [
            'product_title' => [
                'hook' => 'woocommerce_template_single_title',
                'priority' => 20,
               
            ],
            'product_price' => [
                'hook' => 'woocommerce_template_single_price',
                'priority' => 30,
               
            ],
            'product_rating' => [
                'hook' => 'woocommerce_template_single_rating',
                'priority' => 40,
                
            ],
            'product_excerpt' => [
                'hook' => 'woocommerce_template_single_excerpt',
                'priority' => 50,
                
            ],
            'product_add_to_cart' => [
                'hook' => 'woocommerce_template_single_add_to_cart',
                'priority' => 60,
                
            ],
            'product_meta' => [
                'hook' => 'woocommerce_template_single_meta',
                'priority' => 70,
                
            ],
            'product_description'=> [
                'hook' => 'the_content',
                'priority' => 80,
               
            ],
        ];
    
        foreach ($content as $key => $value) {
            if (isset($selected_actions[$key]) && $selected_actions[$key]['enable'] === '1') {
                add_action('wpvqv/shop-page/render-product-content', $value['hook'], $value['priority']);
            }
        }
    
        // Add the "View Details" button after the "Add to Cart" button
        add_action('wpvqv/shop-page/render-product-content', array($this, 'view_details_button_popup'), 65); // Adjust the priority to match the position of "Add to Cart"

    }
    
    // AJAX callback function
    public function quick_view_popup(){
        global $post, $product;
        // Check AJAX nonce
        check_ajax_referer('wpvqv_quick_view_nonce', 'wpvcp_security');

        $post = get_post((int)$_POST['product_id']);
        $product = wc_get_product($post);
       
        if ($product) {
            wc_get_template('includes/frontend/template/popup-template.php',
                array(
                    'post' => $post,
                ),
                'woocommerce-quick-view',
                WPVQV_PATH
            );
        } 

        wp_die();
    }

    // Popup product image
    public function popup_product_image() {
        
        $popup_image_layout = Settings::init()->get('quick_view_media');
        
        if ($popup_image_layout === 'with_product_gallery') {
            ?>
            <div class="popup-product-images">
                <?php
                   wc_get_template( 'single-product/product-image.php' );
                ?>
            </div>
            <?php 
        }
        if($popup_image_layout === 'product_image'){
            ?>
            <div class="popup-product-images">
                <?php
                    remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
                    wc_get_template( 'single-product/product-image.php' );
                ?>
            </div>
            <?php
        }
        if($popup_image_layout === 'none'){
            return;
        }
    }
}
