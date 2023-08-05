<?php
class WC_Location_Stock_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page() {
        // Add a new menu item under WooCommerce menu
        add_submenu_page(
            'woocommerce',
            __('Location Stock Settings', 'woocommerce-location-stock'),
            __('Location Stock Settings', 'woocommerce-location-stock'),
            'manage_options',
            'wc_location_stock_settings',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page() {
        // Render the settings page content here
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('wc_location_stock_options');
                    do_settings_sections('wc_location_stock_settings');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {
        // Register and add settings
        register_setting(
            'wc_location_stock_options', // Option group
            'wc_location_stock_settings', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'wc_location_stock_settings_section', // ID
            __('General Settings', 'woocommerce-location-stock'), // Title
            array($this, 'print_section_info'), // Callback
            'wc_location_stock_settings' // Page
        );

        add_settings_field(
            'your_setting_id', // ID
            __('Your Setting Label', 'woocommerce-location-stock'), // Title
            array($this, 'your_setting_callback'), // Callback
            'wc_location_stock_settings', // Page
            'wc_location_stock_settings_section' // Section
        );

        // Add more settings fields as needed
    }

    public function sanitize($input) {
        // Sanitize the input data here if necessary
        return $input;
    }

    public function print_section_info() {
        // Print section info here if needed
    }

    public function your_setting_callback() {
        // Render your setting field here
        $options = get_option('wc_location_stock_settings');
        $value = isset($options['your_setting_id']) ? $options['your_setting_id'] : '';
        ?>
        <input type="text" name="wc_location_stock_settings[your_setting_id]" value="<?php echo esc_attr($value); ?>" />
        <?php
    }

    // Add more callback functions for additional settings fields if needed
}
