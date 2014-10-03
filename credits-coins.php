<?php

/**
 * The file responsible for starting the Credits Coins plugin
 *
 * The Online Magazine is a WordPress plugin that enable WordPress within the elements necessary
 * to manage efficiently an online magazine. online magazine is composed by issues delivered periodically.
 * Each issue contains article grouped by category/rubric.
 * *
 * @wordpress-plugin
 * Plugin Name: Credits Coins
 * Plugin URI: http://
 * Description: The Online Magazine is a plugin that enable WordPress within the elements necessary to manage efficiently an online magazine. online magazine is composed by issues delivered periodically. Each issue contains article grouped by category/rubric.
 * Version: 1.0.0
 * Author: Luca Maroni
 * Author URI: http://maronl.it
 * Text Domain: credits-coins
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, then abort execution.
if (!defined('WPINC')) {
    die;
}

/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-credits-coins-manager.php';

echo "load plugin";

/**
 * Instantiates the Credits Coins Manager class and then
 * calls its run method officially starting up the plugin.
 */
function run_credits_coins_manager()
{

    $onlimag = new Credits_Coins_Manager();
    $onlimag->run();

}

// Call the above function to begin execution of the plugin.
run_credits_coins_manager();