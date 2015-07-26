<?php
require_once 'class.WWLS_Points.php';

class WWLS_Setup {
	protected static $instance;

    public static function init() {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }
    
    public static function on_activation() {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        self::create_db_table();
    }

    public static function on_deactivation() {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        
        self::on_uninstall();

    }

    public static function on_uninstall() {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'wwls_points';
        $sql = "DROP TABLE IF EXISTS $table_name";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		remove_option( 'wwls_db_version');

    }
    
    private function create_db_table() {
    	global $wpdb;
		global $wwls_db_version;
	
		$table_name = $wpdb->prefix . 'wwls_points';
		 
		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			id_user mediumint(5) NOT NULL,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			action mediumint(5) NOT NULL,
			amount mediumint(5) NOT NULL,
			description text NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
		add_option( 'wwls_db_version', $wwls_db_version );
    }

    public function __construct() {
        add_action('woocommerce_order_status_completed', array('WWLS_Points', 'add_points_after_payment_complete'), 10, 1);
    }
}
?>