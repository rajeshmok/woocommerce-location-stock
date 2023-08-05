<?php
class WC_Location_Stock_Admin {
    public function __construct() {
        // Admin hooks and actions
        add_filter('woocommerce_product_data_tabs', array($this, 'add_location_stock_tab'));
        add_action('woocommerce_product_data_panels', array($this, 'display_location_stock_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'save_location_stock_fields'));
    }

    // Implement admin functions here
    public function add_location_stock_tab($tabs) {
        // Code to add a custom tab for managing location stock
		  $tabs['location_stock'] = array(
        'label' => __('Location Stock', 'woocommerce-location-stock'),
        'target' => 'location_stock_data',
        'class' => array('show_if_simple', 'show_if_variable'),
    );
    return $tabs;
    }

    public function display_location_stock_fields() {
        // Code to display fields for managing location stock
		 global $product_object;

    // Display fields for managing location stock on the custom tab
    ?>
    <div id="location_stock_data" class="panel woocommerce_options_panel">
        <!-- Your fields here -->
        <p>Location 1 Stock: <input type="number" name="location_stock_location_1" value="<?php echo esc_attr(get_post_meta($product_object->get_id(), 'location_stock_location_1', true)); ?>"></p>
        <p>Location 2 Stock: <input type="number" name="location_stock_location_2" value="<?php echo esc_attr(get_post_meta($product_object->get_id(), 'location_stock_location_2', true)); ?>"></p>
    </div>
    <?php
    }

    public function save_location_stock_fields($product_id) {
		// Save location stock data when the product is updated
    if (isset($_POST['location_stock_location_1'])) {
        $location_stock_location_1 = sanitize_text_field($_POST['location_stock_location_1']);
        update_post_meta($product_id, 'location_stock_location_1', $location_stock_location_1);
    }

    if (isset($_POST['location_stock_location_2'])) {
        $location_stock_location_2 = sanitize_text_field($_POST['location_stock_location_2']);
        update_post_meta($product_id, 'location_stock_location_2', $location_stock_location_2);
    }
        // Code to save location stock data when the product is updated
    }
}
