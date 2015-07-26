<?php
class WWLS_Points {
    private static $price_to_points_ratio = 100;
    
    public static function add_points_after_payment_complete($order_id) {
		global $wpdb;
		$user_id = get_current_user_id();
		$order = new WC_Order($order_id);
		
		$table_name = $wpdb->prefix . 'wwls_points';

		$old_points = get_user_meta($user_id,  'wwls_points', true );
		$new_points = $order->get_total() * self::$price_to_points_ratio;
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'id_user' => $user_id,
				'action' => 1,
				'amount' => $new_points,
				'description' => 'add_points_after_payment_complete'
			), 
			array( 
				'%s', 
				'%d',
				'%d',
				'%d',
				'%s'
			) 
		);
		
		update_user_meta( $user_id, 'wwls_points', $new_points + $old_points );
	}
	
	private function add($amount, $user_id) {
		
	}
	
	private function remove($used_points, $user_id = 0) {
	if (!$user_id) {
                $user_id = get_current_user_id();
            }	
            
            $old_points = get_user_meta($user_id,  'wwls_points', true );
            
            update_user_meta( $user_id, 'wwls_points', $old_points - $used_points );
	}
	
	public static function get($user_id = 0) {
            if (!$user_id) {
                $user_id = get_current_user_id();
            }

            $points = get_user_meta($user_id, 'wwls_points', true);

            return $points;
        }
        
        /**
         * 
         * @param type $amount - np 7.99
         */
        public static function pay($amount) {
            $points = (float) $amount * self::$price_to_points_ratio;
            self::remove($points);
        }
        
        public static function convertPointsToCurrency($points) {
            return floatval($points / self::$price_to_points_ratio);
        }
	
	
}

?>