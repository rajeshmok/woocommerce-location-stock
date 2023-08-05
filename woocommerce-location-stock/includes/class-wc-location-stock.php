<?php
class WC_Location_Stock {
    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
			
        return self::$instance;
    }

    private function __construct() {
        // Register activation and deactivation hooks
			
     register_activation_hook(__FILE__, function () {
    WC_Location_Stock_Install::install();
});
register_deactivation_hook(__FILE__, function () {
    WC_Location_Stock_Install::uninstall();
});
echo "is here";exit;
    }

    public function init() {
        // Plugin initialization code here
       
		 // Plugin initialization code here
    add_action('woocommerce_process_shop_order_meta', array($this, 'save_order_location'));
    add_filter('woocommerce_order_stock_reduced', array($this, 'reduce_order_stock'), 10, 3);
    add_filter('woocommerce_product_get_stock_quantity', array($this, 'get_stock_quantity'), 10, 2);

    // Add the settings page
    new WC_Location_Stock_Settings();
        // Add more hooks and filters as needed
    }

    // Implement plugin functions here
    public function save_order_location($order_id) {
        // Code to save the location selected by the admin for the order
		
		 $location = ''; // Initialize the location variable

    // Get the location selected by the admin from the order meta data
    if (isset($_POST['_location_selection'])) {
        $location = sanitize_text_field($_POST['_location_selection']);
    }

    // Save the location as order meta data
    if (!empty($location)) {
        update_post_meta($order_id, '_order_location', $location);
    }
    }

    public function reduce_order_stock($reduce_stock, $order_id, $product_id) {
        // Get the location from the order meta data
    $location = get_post_meta($order_id, '_order_location', true);

    if (!empty($location)) {
        // Reduce the stock quantity for the product and location
        // You need to implement your custom logic to handle the stock reduction based on the location
        // For example, you can use the WC_Location_Stock_DB class to update the stock in the custom table
        $new_stock_quantity = $this->get_stock_quantity_for_location($product_id, $location);

        // Update the stock for the product and location in the custom table
        WC_Location_Stock_DB::update_stock_quantity($product_id, $location, $new_stock_quantity);
    }

    // Always return true to allow WooCommerce to reduce stock for the default location
    return true;
    }

    public function get_stock_quantity($stock_quantity, $product) {
        // Code to get the stock quantity for the product and location from the custom table
        // Return the appropriate stock quantity based on the location
		 // Get the location from the order meta data (if available)
    $location = get_post_meta(get_the_ID(), '_order_location', true);

    if (!empty($location)) {
        // Get the stock quantity for the product and location from the custom table
        $stock_quantity = $this->get_stock_quantity_for_location($product->get_id(), $location);
    }

    return $stock_quantity;
    }
	
	private function get_stock_quantity_for_location($product_id, $location) {
    // Implement your custom logic to retrieve the stock quantity for the product and location from the custom table
    // For example, you can use the WC_Location_Stock_DB class to query the custom table
    $stock_quantity = WC_Location_Stock_DB::get_stock_quantity_for_location($product_id, $location);

    return $stock_quantity;
}
}

