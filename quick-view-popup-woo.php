<?php
/**
* Plugin Name: Quick View Popup for Woo
* Description: Provide customers a quick product preview without visiting the product page with Quick View for WooCommerce.
* Version: 0.1
* Author: Anand Upadhyay
* Author URI: https://profiles.wordpress.org/anandau14/
* License: GPLv3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: quick-view-popup-woo
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define.
define('WPVQV_VERSION', '0.1');
define('WPVQV_MIN_PHP', '7.4');
define('WPVQV_MIN_WP', '5.6');
define('WPVQV_FILE', __FILE__);
define('WPVQV_BASE', plugin_basename(WPVQV_FILE));
define('WPVQV_PATH', plugin_dir_path(WPVQV_FILE));
define('WPVQV_URL', plugin_dir_url(WPVQV_FILE));

define('WPVQV_MIN',( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min');


// check if woocommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	// add admin notice
	add_action('admin_notices', function(){
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php echo esc_html__( 'Quick View Popup for WooCommerce requires WooCommerce to be installed and active.', 'quick-view-popup-woo' ); ?></p>
		</div>
		<?php
	});
}else{
	require_once WPVQV_PATH . 'vendor/autoload.php';
	require_once WPVQV_PATH . 'includes/init.php';
}