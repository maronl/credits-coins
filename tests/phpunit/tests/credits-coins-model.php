<?php

class Tests_Secure_Attachments extends WP_UnitTestCase
{
    function setUp()
    {
        parent::setUp();

        $creditsCoinsOptions = array(
            'new-user-default-credits' => '10',
            'single-credit-value' => '1',
            'single-credit-currency' => 'Eur',
            'credits-by-group-values' => '10,8;20,15;50,30'
        );

        add_option('credits-coins-options', $creditsCoinsOptions);

        // force DB schema creation - tobedone in a smarter way
        $data_model = credits_coins_model::getinstance();
        $admin = new Credits_Coins_Manager_Admin( '1.0.0', $creditsCoinsOptions, $data_model );
        $admin->init_db_schema();
    }

    function tearDown()
    {
        parent::tearDown();
        delete_option('credits-coins-options');
    }

    function test_default_plugin_options_values()
    {
        $creditsCoinsOptions = get_option('credits-coins-options');
        $this->assertCount(4, $creditsCoinsOptions, 'le opzioni indicate nel record credits-coins-options devono essere 4');
        $this->assertEquals('10', $creditsCoinsOptions['new-user-default-credits']);
        $this->assertEquals('1', $creditsCoinsOptions['single-credit-value']);
        $this->assertEquals('Eur', $creditsCoinsOptions['single-credit-currency']);
        $this->assertEquals('10,8;20,15;50,30', $creditsCoinsOptions['credits-by-group-values']);
    }

    function test_method_set_credit_user()
    {
        $data_model = credits_coins_model::getinstance();
        $this->assertequals(false, $data_model->set_user_credits());
        $user_id = 9999999999999;
        $this->assertequals(false, $data_model->set_user_credits($user_id, 22));
        $user_id = 1;
        $this->assertequals(false, $data_model->set_user_credits($user_id));
        $this->assertequals(true, $data_model->set_user_credits($user_id, 100));
        $this->assertequals(100, $data_model->get_user_credits($user_id));

    }

    function test_method_set_post_credits()
    {
        $data_model = credits_coins_model::getinstance();
        $this->assertequals(false, $data_model->set_post_credits());
        $post_id = 9999999999999;
        $this->assertequals(false, $data_model->set_post_credits($post_id, 10));
        $post_id = $this->factory->post->create();
        $this->assertequals(false, $data_model->set_post_credits($post_id));
        $this->assertequals(true, $data_model->set_post_credits($post_id, 19));
        $this->assertequals(19, $data_model->get_post_credits($post_id));

    }


    function test_method_register_credits_movement()
    {
        $data_model = credits_coins_model::getinstance();
        $user_id = 1;

        $args = array(
            'maker_user_id' => $user_id,
            'destination_user_id' => $user_id,
            'delta_credits' => 100,
            'tool_used' => 'wp_admin',
            'movement_description' => 'phpunit testing :)'
        );
        $this->assertEquals(1, $data_model->register_credits_movement($args));
        $this->assertCount(1, $data_model->get_user_credits_movements(1));

    }


    function test_method_get_user_credits_movements()
    {
        $data_model = credits_coins_model::getinstance();
        $user_id = 1;
        $this->assertCount(0, $data_model->get_user_credits_movements($user_id));

    }

    function test_method_register_user_purchase()
    {
        $data_model = credits_coins_model::getinstance();
        $user_id = 1;
        $post_id = 1;
        $post_value = 10;
        $purchase_note = 'phpunit testing purchase';
        $this->assertEquals( false, $data_model->register_user_purchase( $user_id ) );
        $this->assertEquals( false, $data_model->register_user_purchase( $user_id, $post_id ) );
        $this->assertEquals( 1, $data_model->register_user_purchase( $user_id, $post_id, $post_value ) );
        $this->assertCount( 1, $data_model->get_user_purchases( $user_id ) );
        $this->assertEquals( false, $data_model->register_user_purchase( $user_id, $post_id, $post_value, $purchase_note ) );
        $this->assertCount( 1, $data_model->get_user_purchases( $user_id ) );

    }

    function test_method_user_can_access_post()
    {
        $data_model = credits_coins_model::getinstance();
        $user_id = 1;
        $post_id = 1;
        $post_value = 10;
        $purchase_note = 'phpunit testing purchase';
        $this->assertEquals( false, $data_model->user_can_access_post( $user_id, $post_id ) );
        $this->assertEquals( 1, $data_model->register_user_purchase( $user_id, $post_id, $post_value, $purchase_note ) );
        $number_informaton_purchase = 6;
        $this->assertCount( $number_informaton_purchase, $data_model->user_can_access_post( $user_id, $post_id ) );

    }

}