<?php 
function wc_location_stock_get_locations() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'location_stock';

    // Write SQL query to get a distinct list of locations from the custom table
    $query = "SELECT DISTINCT location FROM $table_name";

    // Execute the query and get the locations
    $locations = $wpdb->get_col($query);

    return $locations;
}
function wc_location_stock_is_location_enabled($product_id) {
    // Implement logic to check if the location feature is enabled for the product
    // You might use product meta or any other criteria to determine this
    // For example, you could add a checkbox meta field for products to enable location-based stock management.
    // Here, we are assuming a custom meta field '_enable_location_stock' for demonstration purposes.
    return (bool) get_post_meta($product_id, '_enable_location_stock', true);
}?>