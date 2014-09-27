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


}