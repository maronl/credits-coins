<?php

class Credits_Coins_Manager_Public {

    private $version;

    private $options;

    private $data_model;

    function __construct( $version, $options, $data_model ) {
        $this->version = $version;
        $this->options = $options;
        $this->data_model = $data_model;
    }

    function the_content_filter( $content ) {
        if ( is_single() && ! $this->data_model->user_can_access_post( get_current_user_id(), get_the_ID() )){
            $content = "STOP!!";
        }
        return $content;
    }
}