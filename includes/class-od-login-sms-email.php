<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://olivas.digital
 * @since      1.0.0
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/includes
 * @author     Olivas Digital <contato@olivasdigital.com.br>
 */
class Od_Login_Sms_Email {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Od_Login_Sms_Email_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'OD_LOGIN_SMS_EMAIL_VERSION' ) ) {
			$this->version = OD_LOGIN_SMS_EMAIL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'od-login-sms-email';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Od_Login_Sms_Email_Loader. Orchestrates the hooks of the plugin.
	 * - Od_Login_Sms_Email_i18n. Defines internationalization functionality.
	 * - Od_Login_Sms_Email_Admin. Defines all hooks for the admin area.
	 * - Od_Login_Sms_Email_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-od-login-sms-email-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-od-login-sms-email-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-od-login-sms-email-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-od-login-sms-email-public.php';

		if ( !is_plugin_active( 'od-woo-sms/od-woo-sms.php' ) ) {

			/**
			 * The class responsible for Zenvia.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/zenvia/autoload.php';
		}

		/**
		 * The class responsible for send SMS's.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-od-login-sms-email-send-sms.php';

		/**
		 * The class responsible for send Email's.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-od-login-sms-email-send-email.php';

		/**
		 * The class responsible for manage Access Codes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-od-login-sms-email-code.php';

		$this->loader = new Od_Login_Sms_Email_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Od_Login_Sms_Email_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Od_Login_Sms_Email_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Od_Login_Sms_Email_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', 			$plugin_admin, 'config_page' );
		$this->loader->add_action( 'admin_init', 			$plugin_admin, 'register_configs' );
		$this->loader->add_action( 'init', 					$plugin_admin, 'register_access_code' );
		$this->loader->add_action( 'add_meta_boxes', 		$plugin_admin, 'register_meta_box' );
		$this->loader->add_action( 'save_post', 			$plugin_admin, 'save_meta_box' );

		$access_code = new Od_Login_Sms_Email_Code();
		$this->loader->add_action( 'delete_access_code', 					 $access_code, 'call_delete_access_code' );
		$this->loader->add_filter( 'manage_access_code_posts_columns', 		 $access_code, 'access_code_columns' );
		$this->loader->add_action( 'manage_access_code_posts_custom_column', $access_code, 'access_code_column_values', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Od_Login_Sms_Email_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', 						 $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', 						 $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'login_enqueue_scripts', 					 $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'login_enqueue_scripts', 					 $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'login_form', 		 						 $plugin_public, 'buttons' );
		$this->loader->add_action( 'woocommerce_login_form', 					 $plugin_public, 'buttons' );
		$this->loader->add_action( 'template_redirect', 						 $plugin_public, 'js_vars' );
		$this->loader->add_action( 'wp_ajax_nopriv_od_login_sms_email_send_sms', $plugin_public, 'send_sms');
		$this->loader->add_action( 'wp_ajax_od_login_sms_email_send_sms', 		 $plugin_public, 'send_sms');
		$this->loader->add_action( 'wp_ajax_nopriv_email_flow', 				 $plugin_public, 'email_flow');
		$this->loader->add_action( 'wp_ajax_email_flow', 		 				 $plugin_public, 'email_flow');
		$this->loader->add_action( 'wp_ajax_nopriv_sms_flow', 				 	 $plugin_public, 'sms_flow');
		$this->loader->add_action( 'wp_ajax_sms_flow', 		 				 	 $plugin_public, 'sms_flow');
		$this->loader->add_action( 'wp_ajax_nopriv_validate_code', 				 $plugin_public, 'validate_code');
		$this->loader->add_action( 'wp_ajax_validate_code', 		 			 $plugin_public, 'validate_code');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Od_Login_Sms_Email_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
