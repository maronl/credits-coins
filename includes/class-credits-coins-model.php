<?php
class Credits_Coins_Model {

    private static $_instance = null;

    private function __construct() { }
    private function  __clone() { }

    public static function getInstance() {
        if( !is_object(self::$_instance) )
            self::$_instance = new Credits_Coins_Model();
        return self::$_instance;
    }

    public function set_user_credits ( $user_id = null, $value = null ) {
        if( is_null($user_id) || is_null( $value ) ) {
            return false;
        }

        return update_user_meta( $user_id, 'credits-coins-user-credits', $value );
    }

    public function get_user_credits ( $user_id ) {
       return get_user_meta( $user_id, 'credits-coins-user-credits', true );
    }

    public function set_post_credits ( $post_id = null, $value = null ) {
        if( is_null( $post_id ) || is_null( $value ) ) {
            return false;
        }

        return update_post_meta( $post_id, 'credits-coins-post-value', $value );
    }

    public function get_post_credits ( $post_id ) {
        return get_post_meta( $post_id, 'credits-coins-post-value', true );
    }

    public function register_credits_movement( $args = array() ){
        /*
         * args accepetd are
         * maker_user_id
         * destination_user_id
         * delta_credits
         * tool_used
         * movement_description
         */

        $movement_description = '';

        extract($args);

        if( ! isset( $maker_user_id ) || is_null( $maker_user_id ) ) {
            return false;
        }
        if( ! isset( $destination_user_id ) || is_null( $destination_user_id ) ) {
            return false;
        }
        if( ! isset( $tool_used ) || is_null( $tool_used ) ) {
            return false;
        }
        if( ! isset( $delta_credits ) ||! is_numeric( $delta_credits ) ) {
            return false;
        }
        global $wpdb;

        $table_name = $wpdb->prefix . "credits_coins_movements";

        $rows_affected = $wpdb->insert( $table_name, array(
                'maker_user_id' => $maker_user_id,
                'destination_user_id' => $destination_user_id,
                'time' => current_time('mysql'),
                'value' => $delta_credits,
                'tools' => $tool_used,
                'description' => $movement_description)
        );

        return $rows_affected;

    }

    function get_user_credits_movements( $user = null, $limit = 15, $offset = 0) {
        global $wpdb;

        if( ! is_null( $user) ) {
            $latest_movements = $wpdb->get_results(
                "
                SELECT wp_credits_coins_movements.id, maker.user_login as maker_user, destination.user_login as destination_user, wp_credits_coins_movements.time, wp_credits_coins_movements.value, wp_credits_coins_movements.tools, wp_credits_coins_movements.description
                FROM wp_credits_coins_movements
                left join wp_users as maker on maker.id =  wp_credits_coins_movements.maker_user_id
                left join wp_users as destination on destination.id =  wp_credits_coins_movements.destination_user_id
                WHERE destination_user_id = " . $user . " ORDER BY id desc limit " . $offset . "," . $limit . "
			    ", ARRAY_A
            );

        }

        return $latest_movements;

    }

    function register_user_purchase( $user_id = null, $post_id = null, $value = null, $purchase_note = '' ) {

        if( ! isset( $user_id ) || is_null( $user_id ) ) {
            return false;
        }
        if( ! isset( $post_id ) || is_null( $post_id ) ) {
            return false;
        }
        if( ! isset( $value ) || is_null( $value ) ) {
            return false;
        }

        global $wpdb;

        $table_name = $wpdb->prefix . "credits_coins_purchases";

        $rows_affected = $wpdb->insert( $table_name, array(
                'user_id' => $user_id,
                'post_id' => $post_id,
                'time' => current_time('mysql'),
                'value' => $value,
                'note' => $purchase_note)
        );

        return $rows_affected;
    }

    function user_can_access_post( $user_id = null, $post_id = null ){
        global $wpdb;
        if( is_null( $user_id ) || is_null( $post_id ) ) {
            return false;
        }
        $check = $wpdb->get_row(
            $wpdb->prepare(
                "
                 SELECT * FROM " . $wpdb->prefix . "credits_coins_purchases
                 WHERE post_id = %d
                 AND user_id = %d
                ",
                $post_id, $user_id
            )
        );

        if( is_null( $check ) ){
            return false;
        }

        return $check;
    }

} 