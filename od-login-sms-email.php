<?php

/**
 * Olivas Digital - Login via SMS/E-mail - bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://olivas.digital
 * @since             1.0.0
 * @package           Od_Login_Sms_Email
 *
 * @wordpress-plugin
 * Plugin Name:       Olivas Digital - Login via SMS/E-mail
 * Plugin URI:        https://olivas.digital/plugin/od-login-sms-email
 * Description:       Login via SMS/E-mail com integração Zenvia.
 * Version:           1.0.0
 * Author:            Olivas Digital
 * Author URI:        https://olivas.digital
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       od-login-sms-email
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OD_LOGIN_SMS_EMAIL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-od-login-sms-email-activator.php
 */
function activate_od_login_sms_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-od-login-sms-email-activator.php';
	Od_Login_Sms_Email_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-od-login-sms-email-deactivator.php
 */
function deactivate_od_login_sms_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-od-login-sms-email-deactivator.php';
	Od_Login_Sms_Email_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_od_login_sms_email' );
register_deactivation_hook( __FILE__, 'deactivate_od_login_sms_email' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-od-login-sms-email.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_od_login_sms_email() {

	$plugin = new Od_Login_Sms_Email();
	$plugin->run();

}
run_od_login_sms_email();
