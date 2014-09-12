<?php

class Credits_Coins_Theme_Functions {

    function __construct() { }

    public static function  define_theme_functions() {

	   if( ! function_exists( 'cc_user_can_access_post' ) ) {
            function cc_user_can_access_post( $user_id, $post_id ) {
                $cc_data_model = Credits_Coins_Model::getInstance();
                return $cc_data_model->user_can_access_post( $user_id, $post_id );
            }
        }

        if( ! function_exists( 'cc_register_user_purchase' ) ) {
            function cc_register_user_purchase( $user_id, $post_id, $value, $purchase_note = '' ) {
                $cc_data_model = Credits_Coins_Model::getInstance();
                return $cc_data_model->register_user_purchase( $user_id, $post_id, $value, $purchase_note );
            }
        }

    }
} 