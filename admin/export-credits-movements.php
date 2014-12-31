<?php

// This include gives us all the WordPress functionality
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

function credits_coins_csv_export($data) {

    $date = new DateTime();
    $ts = $date->format( 'Y-m-d-H-i-s' );
    $filename = "credits-movements-" . $ts . ".csv";

    header( 'Content-Type: text/csv' );
    header( 'Content-Disposition: attachment;filename='.$filename);

    $fp = fopen('php://output', 'w');
    $hrow = $data[0];

    fputcsv($fp, array_keys($hrow));

    foreach ($data as $row) {
        fputcsv($fp, $row);
    }

    fclose($fp);
}

global $wpdb;
//check nonce, user_logged, user_id
$user_id = null;
$security = null;

if( isset( $_REQUEST['user_id'] ) ){
    $user_id = $_REQUEST['user_id'];
}
if( isset( $_REQUEST['_wpnonce'] ) ){
    $security= $_REQUEST['_wpnonce'];
}

if ( ! wp_verify_nonce( $security, 'credits-coins-movements' ) ) {

    die( 'Ops ... Are you cheating!' );

}

if ( ! current_user_can( 'manage_options' ) ) {

    die( 'Ops ... It seems you do not have the right permission for this!' );

}

if ( empty( $user_id ) ) {

    die( 'Ops ... user ID missing!' );

}

// OK proceed extracting data
$sql = "
    SELECT * FROM " . $wpdb->base_prefix . "credits_coins_movements as m
    WHERE m.destination_user_id = %d
    ";
$users = $wpdb->get_results( $wpdb->prepare( $sql, $user_id ), ARRAY_A );
credits_coins_csv_export($users);
?>