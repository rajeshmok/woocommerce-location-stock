<?php
class WC_Location_Stock_Frontend {
    public function __construct() {
        // Frontend hooks and actions
        add_action('woocommerce_single_product_summary', array($this, 'display_location_stock'), 5);
        // Add more hooks and filters as needed
    }

    // Implement frontend functions here
   public function display_location_stock() {
    global $product;

    // Display stock information on the product page
    $location_stock_location_1 = get_post_meta($product->get_id(), 'location_stock_location_1', true);
    $location_stock_location_2 = get_post_meta($product->get_id(), 'location_stock_location_2', true);

    ?>
    <div class="location-stock">
        <p>Location 1 Stock: <?php echo esc_html($location_stock_location_1); ?></p>
        <p>Location 2 Stock: <?php echo esc_html($location_stock_location_2); ?></p>
    </div>
    <?php
}

}
