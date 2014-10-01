<?php
/**
 * Bootstrap the plugin unit testing environment.
 *
 * Edit 'active_plugins' setting below to point to your main plugin file.
 *
 * @package wordpress-plugin-tests
 */
// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = array(
    'active_plugins' => array( 'credits-coins/credits-coins.php' ),
);
// If the develop repo location is defined (as WP_DEVELOP_DIR), use that
// location. Otherwise, we'll just assume that this plugin is installed in a
// WordPress develop SVN checkout.
//if(! defined( 'WP_DEVELOP_DIR' ) )  {
    require 'C:/xampp/htdocs/wp-plugin-dev/wordpress' . '/../tests/wp-tests/tests/phpunit/includes/bootstrap.php';
    //require '/tmp/wordpress-tests/tests/phpunit/includes/bootstrap.php';
//} else {
//    require '../../../../tests/phpunit/includes/bootstrap.php';
//}