<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://olivas.digital
 * @since      1.0.0
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/includes
 * @author     Olivas Digital <contato@olivasdigital.com.br>
 */
class Od_Login_Sms_Email_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'od-login-sms-email',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
