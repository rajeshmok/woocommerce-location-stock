<?php
/*
Plugin Name: WooCommerce Location Stock
Description: Manage stock for a product in multiple locations.
Version: 1.0
Author: Hasmukh Bhalgama
Author URI: Paradiseinfosoft
Text Domain: woocommerce-location-stock
Domain Path: /languages
*/

// Define constants


// Include the necessary files

//require_once plugin_dir_path(__FILE__) . 'includes/class-wc-location-stock-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wc-location-stock.php';
//require_once plugin_dir_path(__FILE__) . 'includes/class-wc-location-stock-admin.php';
//require_once plugin_dir_path(__FILE__) . 'includes/class-wc-location-stock-frontend.php';
//require_once plugin_dir_path(__FILE__) . 'includes/class-wc-location-stock-db.php';
//require_once plugin_dir_path(__FILE__) . 'includes/wc-location-stock-helpers.php';
//require_once plugin_dir_path(__FILE__) . 'includes/wc-location-stock-install.php';


//add_filter('manage_edit-product_columns', 'custom_add_product_column');
function custom_add_product_column($columns) {
    $columns['store1'] = __('Store 1 Stock', 'textdomain');
	 $columns['store2'] = __('Store 2 Stock', 'textdomain');
    return $columns;
}


// Populate custom column with data
//add_action('manage_product_posts_custom_column', 'custom_render_product_column', 10, 2);
function custom_render_product_column($column, $post_id) {
    if ($column === 'store1') {
        // Get your custom data for each product here
        // For example, you can fetch the value of a custom field using get_post_meta()

        // Replace 'custom_field_name' with the actual name of your custom field
        $custom_field_value = get_post_meta($post_id, 'location_stock_location_1', true);

        // Output the custom data in the custom column
        echo $custom_field_value;
    }
	if ($column === 'store2') {
        // Get your custom data for each product here
        // For example, you can fetch the value of a custom field using get_post_meta()

        // Replace 'custom_field_name' with the actual name of your custom field
        $custom_field_value = get_post_meta($post_id, 'location_stock_location_2', true);

        // Output the custom data in the custom column
        echo $custom_field_value;
    }
}
class WC_Location_Stock_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page() {
        // Add a new menu item under WooCommerce menu
        add_submenu_page(
            'woocommerce',
            __('Location Store Settings', 'woocommerce-location-stock'),
            __('Location Store Settings', 'woocommerce-location-stock'),
            'manage_options',
            'wc_location_stock_settings',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page() {
        // Render the settings page content here
        ?>
         <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'my_plugin_settings_group' );
            do_settings_sections( 'my_plugin_settings' ); // Make sure this matches the ID you used in the settings tab
            ?>
            <div id="store_names_container">
                <?php echo '<pre>';
                $store_names = get_option( 'my_plugin_store_names' );
				 $store_names = $store_names['my_plugin_store_names'];
			
                if ( ! empty( $store_names ) ) {
                   // $store_names = explode( ',', $store_names );
                    foreach ( $store_names as $store_name ) {
						echo '<div style="margin-bottom:15px;display:flex;align-items: center;">';
                        echo '<h4>Store name :</h4><input style="height:40px" type="text" name="my_plugin_store_names[]" value="' . esc_attr( $store_name ) . '" class="regular-text">';                      echo '<span class="button remove" style="display:flex;align-items: center;" >Remove</span></div>';
                    }
                }
                ?>
            </div>
            <button type="button" id="add_store_name">Add Store</button>
            <?php submit_button(); ?>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#add_store_name').on('click', function() {
            $('<div style="margin-bottom:15px;display:flex;align-items: center;"><h4>Store name :</h4><input type="text" name="my_plugin_store_names[]" class="regular-text"></div>').appendTo('#store_names_container');
        });
		$(document).on('click','.remove',function(e){
	e.preventDefault();
	console.log('comming');
	$(this).parent().remove();
	})
		
    });
    </script>
        <?php
    }

    public function page_init() {
     register_setting( 'my_plugin_settings_group', 'my_plugin_store_names', array( $this, 'sanitize' ) );
        // Add more settings fields as needed
    }

  public function sanitize($input) {
        // Sanitize the input data here if necessary
        // Sanitize the input fields before saving
        $sanitized_data = array();
	
		
        if (isset($_REQUEST['my_plugin_store_names']) && is_array($_REQUEST['my_plugin_store_names'])) {
            $sanitized_data['my_plugin_store_names'] = array_map('sanitize_text_field', $_REQUEST['my_plugin_store_names']);
			//print_r($sanitized_data);
			//update_option('my_plugin_store_names',$sanitized_data);
			//exit;
        }

        // Add more fields as needed

        return $sanitized_data;
    }


    public function print_section_info() {
        // Print section info here if needed
    }

    public function your_setting_callback() {
        // Render your setting field here
        $options = get_option('wc_location_stock_settings');
		print_r($options);
        $value = isset($options['your_setting_id']) ? $options['your_setting_id'] : '';
        ?>
        <input type="text" name="wc_location_stock_settings[your_setting_id]" value="<?php echo esc_attr($value); ?>" />
        <?php
    }

    // Add more callback functions for additional settings fields if needed
}

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
        <?php $store_names = get_option( 'my_plugin_store_names' );
		if($store_names){
				 $store_names = $store_names['my_plugin_store_names'];
		foreach($store_names as $store){
			$sname = str_replace(" ","_",$store);
			 $sname ='location_stock_location_'.$sname
			?>
             <p><strong><?php echo $store;?> Stock:</strong><br /> <input type="number" name="<?php echo $sname;?>" value="<?php echo esc_attr(get_post_meta($product_object->get_id(), $sname, true)); ?>"></p>
            <?php
		}
		}
        ?>
      
    </div>
    <?php
    }

    public function save_location_stock_fields($product_id) {
		// Save location stock data when the product is updated
  
	
	$store_names = get_option( 'my_plugin_store_names' );
		if($store_names){
				 $store_names = $store_names['my_plugin_store_names'];
		foreach($store_names as $store){
			 $sname = str_replace(" ","_",$store);
			 $siname = "location_stock_location_".$sname;
		

			 if (isset($_POST[$siname])) {
				
       $location_stock_location_1 = sanitize_text_field($_POST[$siname]);
        update_post_meta($product_id, $siname, $location_stock_location_1);
    }
	
		}
		}

  
        // Code to save location stock data when the product is updated
    }
}
add_action('woocommerce_admin_order_data_after_order_details', 'add_custom_store_dropdown_to_order');
function add_custom_store_dropdown_to_order($order) {
	$custom_store="";
	if(isset($_REQUEST['post'])){
	  $custom_store = get_post_meta($_REQUEST['post'], 'custom_store', true);
	}
	
    echo '<p class="form-field form-field-wide">
            <label for="custom_store">' . __('Select Store', 'textdomain') . '</label>
            <select name="custom_store" id="custom_store">
			<option value="">online</option>
			';
			
			$store_names = get_option( 'my_plugin_store_names' );
		if($store_names){
				 $store_names = $store_names['my_plugin_store_names'];
				 
		foreach($store_names as $store){
			if($custom_store==$store){
			
			echo '<option value="'.$store.'" selected="selected">' .$store. '</option>';
			}else{
				echo '<option value="'.$store.'">' .$store. '</option>';
			}
		}
		}
              
            echo '</select>
        </p>';
}
add_action('woocommerce_process_shop_order_meta', 'save_custom_store_selection');
function save_custom_store_selection($order_id) {
    if (isset($_POST['custom_store'])) {
        $custom_store = sanitize_text_field($_POST['custom_store']);
        update_post_meta($order_id, 'custom_store', $custom_store);
    }
}
// Update product stock after order is placed
// Update product stock after order is placed
add_action('woocommerce_new_order', 'update_product_stock_after_order', 10, 1);
function update_product_stock_after_order($order_id) {
	
	/*
	
    $order = wc_get_order($order_id);

    // Get products in the order
    $items = $order->get_items();

    // Get custom store selection for the order
     $custom_store = get_post_meta($order_id, 'custom_store', true);

    foreach ($items as $item_id => $item_data) {
       $product_id = $item_data->get_product_id();
        $quantity = $item_data->get_quantity();

        // Get the stock quantity from custom field (using ACF) based on selected store
         $stock_quantity_store1 = get_post_meta($product_id,'location_stock_location_1',true);
	
		 $stock_quantity_store2 = get_post_meta($product_id,'location_stock_location_2',true);

     
            // Calculate new stock quantity
           

            // Update stock in custom field based on selected store
            //update_field('stock_quantity_' . $custom_store, $new_stock_quantity, $product_id);

            // Update WooCommerce stock status (optional)
          if($quantity > 0){
				if($custom_store=="store1"){
					 $new_stock_quantity = $stock_quantity_store1 - $quantity;
                    update_post_meta($product_id,'location_stock_location_1', $new_stock_quantity);
				}
				if($custom_store=="store2"){
					 $new_stock_quantity = $stock_quantity_store2 - $quantity;
					  update_post_meta($product_id,'location_stock_location_2', $new_stock_quantity);
				}
		  }
         
       
    }
	*/
}



add_action('woocommerce_before_save_order_items', 'custom_function_after_order_items_saved', 10, 2);
function custom_function_after_order_items_saved($order_id, $items) {
	 $order = wc_get_order($order_id);
	  $items = $order->get_items();
	$custom_store = get_post_meta($order_id, 'custom_store', true);

		  if($custom_store==""){
			$custom_store = $_REQUEST['custom_store'];
		}
		


	  if(isset($_REQUEST['items'])){
     $encoded_data = $_REQUEST['items'];
	$decoded_data = urldecode($encoded_data);
	
// Parse the data into an associative array
parse_str($decoded_data, $parsed_data);
	//print_r($parsed_data);

 foreach ($items as $item_id => $item_data) {
	
      $product_id = $item_data->get_product_id();
      $quantity = $item_data->get_quantity();
	  $nqty = $parsed_data['order_item_qty'][$item_id];
		
		
		$store_names = get_option( 'my_plugin_store_names' );
		
		if($store_names){
				 $store_names = $store_names['my_plugin_store_names'];
		foreach($store_names as $store){
			if($store==$custom_store){
			 $sname = str_replace(" ","_",$store);
		 $siname = "location_stock_location_".$sname;
		
		 $stock_quantity_store =  get_post_meta($product_id,$siname,true);
		
			
			
			 $new_stock_quantity = $stock_quantity_store + $quantity;
				
				 $new_stock_quantity = $new_stock_quantity - $nqty;
		
               update_post_meta($product_id, $siname, $new_stock_quantity);
			
			}
		}
		
		}
	
		
	
		
	 }
	  }else{
		
	 foreach ($items as $item_id => $item_data) {
		
	$newqty = $_REQUEST['order_item_qty'][$item_id];
	
    $product_id = $item_data->get_product_id();
    $quantity = $item_data->get_quantity();
	
	
		
		$store_names = get_option( 'my_plugin_store_names' );
		
		if($store_names){
				 $store_names = $store_names['my_plugin_store_names'];
		foreach($store_names as $store){
			if($store==$custom_store){
			 $sname = str_replace(" ","_",$store);
		 $siname = "location_stock_location_".$sname;
				
			 $stock_quantity_store =  get_post_meta($product_id,$siname,true);
		    if($newqty!=$quantity){
			$new_stock_quantity = $stock_quantity_store + $quantity;
			$new_stock_quantity = $new_stock_quantity - $newqty;
				update_post_meta($product_id, $siname, $new_stock_quantity);
			}else{
				if($_REQUEST['save']=="Create"){
				$new_stock_quantity = $stock_quantity_store - $quantity;
                update_post_meta($product_id, $siname, $new_stock_quantity);
				}
			}
			}
		}
		
		}
		
		
	
	 }
	 
	  }
    // Your custom code here
	
	
// Now you have the data in an array

	
	
}

add_filter('woocommerce_admin_order_item_quantity', 'update_order_item_quantity_on_admin_save', 10, 2);
function update_order_item_quantity_on_admin_save($item_quantity, $item_id) {/*
    $order = wc_get_order($item_id);
    $order_id = $order->get_id();
    $custom_store = get_post_meta($order_id, 'custom_store', true);

    $product_id = $order->get_product_id($item_id);
    
    $stock_quantity_store1 = get_post_meta($product_id,'location_stock_location_1',true);
		 $stock_quantity_store2 = get_post_meta($product_id,'location_stock_location_2',true);
   
	
		 
		  $original_quantity = wc_get_order_item_meta($item_id, '_qty', true);
				if($custom_store=="store1"){
					echo "oqty=". $new_stock_quantity = $stock_quantity_store1 - $original_quantity;
					echo "nqty=".  $new_stock_quantity = $stock_quantity_store1 + $item_quantity;
                update_post_meta($product_id, 'location_stock_location_1', $new_stock_quantity);
				}
				if($custom_store=="store2"){
					echo "oqty=". $new_stock_quantity = $stock_quantity_store2 - $original_quantity;
					echo "nqty=".  $new_stock_quantity = $stock_quantity_store2 + $item_quantity;
					  update_post_meta($product_id, 'location_stock_location_2', $new_stock_quantity);
				}
		
	

    return $item_quantity;*/
}


// Instantiate the plugin class


// Instantiate the plugin class


// Initialize the plugin
function wc_location_stock_init() {
	$setting = new WC_Location_Stock_Settings();
	//$your_plugin_instance = new Your_Plugin_Class();
	$setting = new WC_Location_Stock_Admin();
	
    //WC_Location_Stock::get_instance()->init();
	
}
add_action('init', 'wc_location_stock_init');


// Enqueue custom JavaScript
add_action( 'admin_enqueue_scripts', 'enqueue_custom_admin_scripts' );
function enqueue_custom_admin_scripts() {
    global $pagenow;

    if ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
        $post = get_post( $post_id );

        // Check if this is a WooCommerce order post type
        if ( 'shop_order' === $post->post_type ) {
            wp_enqueue_script( 'my-custom-admin-script', plugin_dir_url( __FILE__ ) . 'js/custom-admin.js', array( 'jquery' ), '1.0', true );
        }
    }
}

// Disable auto-saving for WooCommerce orders
add_action( 'admin_print_scripts', 'disable_auto_save_for_orders' );
function disable_auto_save_for_orders() {
    global $pagenow;

    if ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
        $post = get_post( $post_id );

        // Check if this is a WooCommerce order post type
        if ( 'shop_order' === $post->post_type ) {
            ?>
            <script>
            $(document).ready(function($) {
                // Disable auto-save for WooCommerce orders
                $('.edit-order-item').click(function(){
					console.log('comming here');
					$('.save_order').prop('disabled', true);
					})
            });
            </script>
            <?php
        }
    }
}
