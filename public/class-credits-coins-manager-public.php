<?php

class Credits_Coins_Manager_Public {

    private $version;

    private $options;

    private $data_model;

    private $js_configuration;

    function __construct( $version, $options, $data_model ) {
        $this->version = $version;
        $this->options = $options;
        $this->data_model = $data_model;
        if(WP_DEBUG == false) {
            $this->js_configuration['js_path'] = 'js/prod/';
            $this->js_configuration['js_extension'] = $this->version . '.min.js';
        }else{
            $this->js_configuration['js_path'] = 'js/';
            $this->js_configuration['js_extension'] = 'js';
        }
    }

    public function register_scripts() {
        wp_register_script( 'credits-coins-public-js', plugins_url( $this->js_configuration['js_path'] . 'credits-coins-public.' . $this->js_configuration['js_extension'], __FILE__ ), array( 'jquery' ) );
        wp_localize_script( 'credits-coins-public-js', 'ajax_credits_coins', array(
            'url' => '/wp-admin/admin-ajax.php',
            'security' => wp_create_nonce( "credits-coins-ajax" ),
        ) );

    }

    public function enqueue_scripts($hook) {
        wp_enqueue_script('credits-coins-public-js');
    }

    function the_content_filter( $content ) {
        if ( is_single() && ! $this->data_model->user_can_access_post( get_current_user_id(), get_the_ID() )){
            $check_preview = strpos( $content, '<!--om-preview-->' );
            if( $check_preview === false ) {
                $content = '';
            }else{
                $content = substr($content, 0, $check_preview);
            }
            ob_start();
            if( file_exists( get_template_directory() . '/cc-buy-post.php' ) ){
                include get_template_directory() . '/
            }else {
                include 'partials/buy-post.php';
            }
            $out = ob_get_contents();
            ob_end_clean();
            $content .=  $out;
        }
        return $content;
    }
}