<?php

class Credits_Coins_Manager_Options {

    private $version;

    private $options;

    private $js_configuration;

    function __construct($version, $options) {
        $this->version = $version;
        $this->options = $options;
        if(WP_DEBUG == false) {
            $this->js_configuration['js_path'] = 'js/prod/';
            $this->js_configuration['js_extension'] = $this->version . '.min.js';
        }else{
            $this->js_configuration['js_path'] = 'js/';
            $this->js_configuration['js_extension'] = 'js';
        }
    }

    public function register_scripts() {
        wp_register_script( 'credits-coins-admin-options-js', plugins_url( $this->js_configuration['js_path'] . 'credits-coins-admin-options.' . $this->js_configuration['js_extension'], __FILE__ ) );
        wp_register_script( 'jquery-validation-js', plugins_url( 'js/lib/jquery.validate.min.js', __FILE__ ) );
    }

    public function enqueue_scripts($hook) {
        if( 'settings_page_credits-coins-plugin-options' == $hook ){
            wp_enqueue_script('credits-coins-admin-options-js');
            wp_enqueue_script('jquery-validation-js');
        }
    }

    public function register_styles() {
        wp_register_style( 'credits-coins-admin-options-css', plugins_url( 'css/credits-coins-admin-options.css', __FILE__  ) );
    }

    public function enqueue_styles($hook) {
        if( 'settings_page_credits-coins-plugin-options' == $hook ) {
            wp_enqueue_style( 'credits-coins-admin-options-css', false, array(), $this->version );
        }
    }

    function add_plugin_options_page() {
        add_options_page(
            'Credits Coins Plugin Options',
            __('Credits Coins', 'credits-coins'),
            'manage_options',
            'credits-coins-plugin-options',
            array( $this, 'render_admin_options_page' )
        );
    }

    function render_admin_options_page() {
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php _e( 'Credits Coins Plugin Options', 'credits-coins' )?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'credits-coins-options' );
                do_settings_sections( 'credits-coins-options' );
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    function options_page_init() {
        register_setting(
            'credits-coins-options', // Option group
            'credits-coins-options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'credits-coins-options', // ID
            'Credits Coins Options', // Title
            array( $this, 'print_section_info' ), // Callback
            'credits-coins-options' // Page
        );

        add_settings_field(
            'new-user-default-credits', // ID
            'Default Credits for a new user', // Title
            array( $this, 'new_user_default_credits_callback' ), // Callback
            'credits-coins-options', // Page
            'credits-coins-options' // Section
        );

        add_settings_field(
            'post-types-values',
            __( 'Post Types with Credits', 'credits-coins' ),
            array( $this, 'post_types_values_callback' ),
            'credits-coins-options',
            'credits-coins-options'
        );

    }

    public function print_section_info()
    {
        _e( 'Enter your settings below:', 'credits-coins' );
    }

    function sanitize( $input ) {

        foreach ($input as $key => $value){
            $input[$key] =  sanitize_text_field($value);
        }

        $input['new-user-default-credits'] = intval( $input['new-user-default-credits'] );

        return $input;
    }

    public function new_user_default_credits_callback() {
        $value = ( isset( $this->options['new-user-default-credits'] ) && ( is_numeric( $this->options['new-user-default-credits']) ) ) ? $this->options['new-user-default-credits'] : 0;
        $description = '<p class="description">' . __('default credits assigned to a new user after registration', 'credits-coins') . '</p>';
        printf(
            '<input size="3" type="text" id="new-user-default-credits" name="credits-coins-options[new-user-default-credits]" value="%s" autocomplete="off"/>%s',
            $value,
            $description
        );
    }

    public function post_types_values_callback() {

        $post_types = $this->get_linking_post_types();

        $value = ( isset( $this->options['post-types-values'] ) ) ? $this->option_array_to_string( $this->options['post-types-values'] ) : '';

        $description = '<p class="description">' . __('Define which post types can be priced with credits', 'credits-coins') . '</p>';

        printf(
            '<input type="hidden" id="post-types-values" name="credits-coins-options[post-types-values]" value="%s" autocomplete="off"/>',
            $value
        );

        echo '<ul class="post-types-values-list">';

        $hide_message = ( count( $this->options['post-types-values'] ) ) ? 'class="hidden"' : '';

        echo '<li id="no-post-types-values-message" ' . $hide_message . '>' . __( 'No post type has been set to be valued with credits', 'credits-coin' ) . '</li>';

        if( count( $this->options['post-types-values'] ) ) {
            foreach(  $this->options['post-types-values']  as $key => $value ) {
                echo '<li class="post-types-values-item" data-value="' . $key . ',' . $value . '">' . $key . ' => ' . $value . ' ' . __( 'Credits', 'credits-coins' ) . ' - <a class="remove-post-type-value" href="#' . $key . ',' . $value . '">remove</a></li>';
            }
        }

        echo '</ul>';

        echo '<select id="new-post-type-value-name" name="new-post-type-value-name" autocomplete="off">';

        echo '<option value="0">' . __( 'select a post type', 'credits-coins' ) . '</option>';

        foreach( $post_types as $value ){
            $disabled = '';
            if( isset( $this->options['post-types-values'][$value] ) ) {
                $disabled = 'disabled';
            }
            $format = '<option value="%s" %s>%s</option>';
            printf( $format, $value, $disabled, $value );
        }

        echo '</select> ';

        echo __( 'Default Credits', 'credits-coins' ) . ' <input type="text" id="new-post-type-value-credit" name="new-post-type-value-credit" value="0" size="3" autocomplete="off" />';

        echo ' <input id="add-post-type-value" type="button" class="button button-primary" value="Add" />';

        echo $description;

    }

    function get_linking_post_types() {
        $linking_elements = get_post_types();
        unset($linking_elements['attachment']);
        unset($linking_elements['revision']);
        unset($linking_elements['nav_menu_item']);
        return $linking_elements;
    }


    /*
         * this function is ugly. now it is here
         * if you want if you need of you can please refactor it :)
         * it assume to parse a string like post,10;page,20 and produce
         * array{
         *  post => 10
         *  page => 20
         * }
         */
    private function option_array_to_string( $options = array() ) {
        $res = '';
        if( empty($options) ){
            return $res;
        }
        foreach ( $options as $key => $value ){
            if( ! empty( $res ) ) $res .= ';';
            $res .= $key.','.$value;
        }
        return $res;
    }

}