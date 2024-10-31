<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }
?>
<div class="wpvqv-popup-wrapper">
    <?php
    do_action('wpvqv/shop-page/render-product-image');
    ?>
    <div class="popup-content">
        <?php
        do_action('wpvqv/shop-page/render-product-content', $post);
        ?>
    </div>
</div>
<button class="wpvqv-popup-close-btn" >
    <svg width="24px" height="24px" class="wpvqv-popup-close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="wpvcp-close-btn-icon mfp-close">
        <path d="M7 7L17 17M7 17L17 7" stroke="#292929" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
</button>