<?php
class WC_Location_Stock_Install {
	
	
	
    public static function install() {
        global $wpdb;
	echo "comming here also"; exit;
        // Create custom table to store location stock data
    echo    $table_name = $wpdb->prefix . 'location_stock';
        $charset_collate = $wpdb->get_charset_collate();

    echo    $sql = "CREATE TABLE $table_name (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            product_id BIGINT(20) NOT NULL,
            location VARCHAR(255) NOT NULL,
            stock_quantity INT NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
exit;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function uninstall() {
        // Code to remove custom table on plugin uninstallation
        global $wpdb;
        $table_name = $wpdb->prefix . 'location_stock';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}
