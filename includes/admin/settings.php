<?php

namespace WPVQV\Admin;

class Settings
{

    private static $instance;

    public static function init()
    {
        if (null === self::$instance) {
            self::$instance = new Settings();
        }

        return self::$instance;
    }

    public function get_default_settings(){
        
       
        $default_settings = [
            'enable_quick_view' => '1',
            'quick_view_btn_label' => __('Quick View','quick-view-popup-woo'),
            'quick_view_btn_layout' => 'icon_with_label', // icon_with_label, icon, label
            'quick_view_btn_position'   => 'next_add_to_cart', // next_add_to_cart

            // quick view popup content
            'quick_view_popup_content' => [
                'product_title' => [
                    'enable' => '1',
                    'priority' => '1'
                ],
                'product_price' => [
                    'enable' => '1',
                    'priority' => '2'
                ],
                'product_rating' => [
                    'enable' => '1',
                    'priority' => '3'
                ],
                'product_excerpt' => [
                    'enable' => '1',
                    'priority' => '4'
                ],
                'product_add_to_cart' => [
                    'enable' => '1',
                    'priority' => '5'
                ],
                'product_meta' => [
                    'enable' => '1',
                    'priority' => '6'
                ],
                'product_description' => [
                    'enable' => '0',
                    'priority' => '7'
                ],
            ],

            // quick view popup view details button
            'view_details_btn_label' => __('View Details','quick-view-popup-woo'),
            
            // quick view product image
            'quick_view_media' => 'with_product_gallery', // with_product_gallery, product_image, none

            // popup animation
            'popup_animation' => 'zoom-in', // move-horizontal, newspaper, move-from-top, zoom-out, 3d-unfold, zoom-in, with-fade

        ];

        return $default_settings;
    }

    /**
     * Get settings
     * @return array
     */
    protected function get_settings(){
            
        $settings = get_option('wpvqv_settings', $this->get_default_settings());

        // need to merge with default settings
        $settings = wp_parse_args($settings, $this->get_default_settings());

        $settings = apply_filters('wpvqv_settings', $settings);

        return $settings;
    }

    /**
     * Get setting value
     * @param string $key
     * @return string
     */
    public function get($key){
        $settings = $this->get_settings();
        return isset($settings[$key]) ? $settings[$key] : '';
    }
}
