<?php
class WC_Location_Stock_DB {
    // Implement database functions here
	
	public function __construct() {
        // Admin hooks and actions
    }
	
	public static function get_stock_quantity_for_location($product_id, $location) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'location_stock';

    // Write SQL query to retrieve stock quantity for the given product and location
    $query = $wpdb->prepare(
        "SELECT stock_quantity FROM $table_name WHERE product_id = %d AND location = %s",
        $product_id,
        $location
    );

    // Execute the query and get the stock quantity
    $stock_quantity = $wpdb->get_var($query);

    // Return the stock quantity (0 if not found)
    return absint($stock_quantity);
}

public static function update_stock_quantity($product_id, $location, $new_stock_quantity) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'location_stock';

    // Check if the record already exists for the product and location
    $existing_stock_quantity = self::get_stock_quantity_for_location($product_id, $location);

    if ($existing_stock_quantity > 0) {
        // Update the existing record
        $wpdb->update(
            $table_name,
            array('stock_quantity' => $new_stock_quantity),
            array('product_id' => $product_id, 'location' => $location),
            array('%d'),
            array('%d', '%s')
        );
    } else {
        // Insert a new record
        $wpdb->insert(
            $table_name,
            array('product_id' => $product_id, 'location' => $location, 'stock_quantity' => $new_stock_quantity),
            array('%d', '%s', '%d')
        );
    }
}

	
}
