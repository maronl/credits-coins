<?php

class Credits_Coins_Manager_Admin {

    private $version;

    private $options;

    private $data_model;

    private $js_configuration;

    function __construct( $version, $options, $data_model ) {
        $this->version = $version;
        $this->options = $options;
        $this->data_model = $data_model;
        $this->js_configuration = array();
        if(WP_DEBUG == false) {
            $this->js_configuration['js_path'] = 'js/prod/';
            $this->js_configuration['js_extension'] = $this->version . '.min.js';
        }else{
            $this->js_configuration['js_path'] = 'js/';
            $this->js_configuration['js_extension'] = 'js';
        }
    }

    public function init_db_schema()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'liveshoutbox';

        $charset_collate = '';

        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . 'credits_coins_movements';

        $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
                  id bigint(20) NOT NULL AUTO_INCREMENT,
                  maker_user_id bigint(20) NOT NULL,
                  destination_user_id bigint(20) NOT NULL,
                  time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  value int(11) NOT NULL DEFAULT '0',
                  tools varchar(10) NOT NULL DEFAULT '',
                  description longtext NOT NULL,
                  UNIQUE KEY id (id),
                  KEY maker_user_id (maker_user_id),
                  KEY destination_user_id (destination_user_id)
                ) $charset_collate;";

        dbDelta($sql);

        $table_name = $wpdb->prefix . 'credits_coins_purchases';

        $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
                  id bigint(20) NOT NULL AUTO_INCREMENT,
                  user_id bigint(20) NOT NULL,
                  post_id bigint(20) NOT NULL,
                  time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  value int(11) NOT NULL DEFAULT '0',
                  note longtext NOT NULL,
                  UNIQUE KEY id (id),
                  UNIQUE KEY unique_purchase (user_id,post_id),
                  KEY user_id (user_id),
                  KEY post_id (post_id)
                ) $charset_collate;";

        dbDelta($sql);

        add_option('credits_coins_db_version', $this->version);

    }

    public function register_scripts() {
        wp_register_script( 'credits-coins-admin-user-profile-js', plugins_url( $this->js_configuration['js_path'] . 'credits-coins-admin-user-profile.' . $this->js_configuration['js_extension'], __FILE__ ) );
    }

    public function enqueue_scripts($hook) {
        $enabling_hooks = array( 'profile.php', 'user-edit.php' );
        if( in_array( $hook, $enabling_hooks ) ){
            wp_enqueue_script('credits-coins-admin-user-profile-js');
        }
    }

    function set_default_credits_after_registration( $user_id ) {
        $value = ( isset( $this->options['new-user-default-credits'] ) && ( is_numeric( $this->options['new-user-default-credits']) ) ) ? $this->options['new-user-default-credits'] : 0;
        $this->data_model->set_user_credits( $user_id, $value );
    }

    function modify_admin_users_columns( $columns ){
        $columns['credits_coins_users_credits'] = 'Credits';
        return $columns;
    }

    function modify_admin_user_columns_content( $val, $column_name, $user_id ) {
        if($column_name == 'credits_coins_users_credits'){
            $user_credit = $this->data_model->get_user_credits( $user_id );
            if( empty ( $user_credit ) ) $user_credit = 0;
            return $user_credit;
        }
        return '';
    }

    function show_extra_profile_fields( $user ) {
        $nonce = wp_create_nonce( 'credits-coins-movements' );?>

        <h3>Crediti utente</h3>

        <table class="form-table">

            <tr>
                <th><label for="credits-coins-user-credits">Credito</label></th>

                <td>
                    <input type="text" name="credits-coins-user-credits" id="credits-coins-user-credits" value="<?php echo esc_attr( get_user_meta( $user->ID, 'credits-coins-user-credits',true) ); ?>" class="regular-text" />
                    <a id="btn-visualizza-movimenti" href="<?php echo $user->ID ?>" class="button">Visualizza ultimi 15 movimenti</a>
                        <a id="btn-scarica-movimenti" href="<?php echo plugin_dir_url( __FILE__  ) . "export-credits-movements.php?_wpnonce=" . $nonce . "&user_id=" . $user->ID; ?>">Scarica tutti i movimenti in formato .cvs</a> <br />
                    <span class="description">Crediti disponibili dell'utente. modificare con cura :)</span>
                </td>
            </tr>

        </table>
        <div id="wrapper-latest-recharges"></div>

    <?php }

    function save_extra_profile_fields($user_id) {

        if ( ! current_user_can('edit_user', $user_id) )
            return false;

        $oldCredits = $this->data_model->get_user_credits( $user_id );
        if (!$oldCredits)
            $oldCredits = 0;
        $newCredits = $_POST['credits-coins-user-credits'];
        $delta_credits = $newCredits - $oldCredits;

        if ($delta_credits != 0) {
            $maker_user_id = get_current_user_id();
            $destination_user_id = $user_id;
            $tool_used = 'wp-admin';
            $movement_description = __( 'defined new credits payoff using wp-admin', 'credits-coins' );
            $args = compact('maker_user_id','destination_user_id','delta_credits','tool_used','movement_description');
            $this->data_model->register_credits_movement($args);
        }

        $this->data_model->set_user_credits( $user_id, $newCredits );

    }

    function ajax_get_json_user_credits_movements() {
        $user = null;
        $limit = 15;
        $offset = 0;
        if( isset($_POST['user']) ) $user =  esc_sql( $_POST['user'] );
        if( isset($_POST['limit']) ) $limit = esc_sql( $_POST['limit'] );
        if( isset($_POST['offset']) ) $offset= esc_sql( $_POST['offset'] );

        if($user){
            $user_credits_movements = $this->data_model->get_user_credits_movements( $user, $limit, $offset );
            $res['status'] = 'ok';
            $res['data'] = array();
            if ( $user_credits_movements ) {
                $res['data'] =  $user_credits_movements;
            }

        }else{
            $res['status'] = 'ko';
            $res['message'] = 'No valid user';
        }

        $res_json = json_encode($res);
        die($res_json);

    }

    function ajax_buy_post() {

        $post_id = null;
        if( isset($_POST['post_id']) ) $post_id =  esc_sql( $_POST['post_id'] );

        if( ! is_user_logged_in() || is_null( $post_id ) ) {
            die( json_encode( array(
                'status' => 0,
                'msg' => __('Ops ... To buy a post before do login. Thanks!', 'credits-coins')
            ) ) );
        }

        check_ajax_referer( 'credits-coins-ajax', 'security' );

        $user_id = get_current_user_id();
        $post_value = $this->data_model->get_post_credits( $post_id );
        $user_credit = $this->data_model->get_user_credits( $user_id );
        if( $user_credit < $post_value ) {
            die( json_encode( array(
                'status' => 0,
                'msg' => __('It seems you don\'t have enough credits to buy this post. Please buy new credits to proceed with the order', 'credits-coins')
            ) ) );
        }

        $args = array(
            'maker_user_id' => $user_id,
            'destination_user_id' => $user_id,
            'delta_credits' => -($post_value),
            'tool_used' => 'credits-coins-plugin',
            'movement_description' => 'buy post ' . $post_id,
        );

        if( ! $this->data_model->register_user_purchase( $user_id, $post_id, $post_value ) ) {
            die( json_encode( array(
                'status' => 0,
                'msg' => __('Ops ... it was not possible complete the order. Please try again!', 'credits-coins')
            ) ) );
        }

        $this->data_model->set_user_credits( $user_id,  ( $user_credit - $post_value ));
        $this->data_model->register_credits_movement( $args );
        die( json_encode( array(
            'status' => 1,
            'msg' => 'Good! Your order has been completed successfully. you will redirected to the post page with the entire content in a few seconds.'
        ) ) );

    }

    function is_credits_coins_metabox_enabled( $post_type ) {
        if ( isset($this->options['post-types-values'][$post_type]) ){
            return true;
        }else{
            return false;
        }
    }
    
    function add_meta_box_credits_coins() {

        global $post_type;
        if( $this->is_credits_coins_metabox_enabled( $post_type ) ) {
            add_meta_box(
                'creditd_coins',
                __("Credits", 'credits-coins'),
                array($this, 'render_meta_box_credits_coins'),
                $post_type, 'side'
            );
        }

    }

    function render_meta_box_credits_coins( $post ) {
        global $pagenow; //post-new.php
        $current_credit_value = $this->data_model->get_post_credits( $post->ID );
        if( empty($current_credit_value) && $pagenow !== 'post-new.php' ) {
            $current_credit_value = 0;
        }elseif( empty($current_credit_value) && $pagenow === 'post-new.php' ) {
            $current_credit_value = $this->options['post-types-values'][$post->post_type];
        }
        ?>

        <input type="number" id="post-type-value" name="post-type-value" size="4" value="<?php echo $current_credit_value; ?>" />
        <p><?php _e( 'Assign a value in Credits for this resource', 'credits-coins' ); ?></p>

    <?php
    }

    function save_meta_box_credits_coin($post_id) {

        $new_value = 0;

        if( isset( $_POST['post-type-value'] ) && is_numeric( $_POST['post-type-value'] ) && $_POST['post-type-value'] > 0 ){
            $new_value = $_POST['post-type-value'];
        }

        $this->data_model->set_post_credits( $post_id, $new_value );

    }
    /*
     * CREATE TABLE IF NOT EXISTS wp_credits_coins_movements (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  maker_user_id bigint(20) NOT NULL,
  destination_user_id bigint(20) NOT NULL,
  time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  value int(11) NOT NULL DEFAULT '0',
  tools varchar(10) NOT NULL DEFAULT '',
  description longtext NOT NULL,
  UNIQUE KEY id (id),
  KEY maker_user_id (maker_user_id),
  KEY destination_user_id (destination_user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS wp_credits_coins_purchases (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  post_id bigint(20) NOT NULL,
  time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  value int(11) NOT NULL DEFAULT '0',
  note longtext NOT NULL,
  UNIQUE KEY id (id),
  UNIQUE KEY unique_purchase (user_id,post_id),
  KEY user_id (user_id),
  KEY post_id (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

     */
}