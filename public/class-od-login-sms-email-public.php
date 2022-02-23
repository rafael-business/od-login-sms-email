<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://olivas.digital
 * @since      1.0.0
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/public
 * @author     Olivas Digital <contato@olivasdigital.com.br>
 */
class Od_Login_Sms_Email_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/od-login-sms-email-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/od-login-sms-email-public.js', array( 'jquery' ), $this->version, false );

		wp_register_script( 'od-login-sms-email-ajax', esc_url( add_query_arg( array( 'od_login_sms_email_js' => 1 ), site_url() ) ) );
  		wp_enqueue_script( 'od-login-sms-email-ajax' );

	}

	/**
	 * Display login sms/email buttons
	 */
	public function buttons() {

		$sms = get_option('od_login_sms_email_sms_icon');
		$sms_img = $sms ? $sms : plugin_dir_url( __FILE__ ).'img/sms.png';

		$email = get_option('od_login_sms_email_email_icon');
		$email_img = $email ? $email : plugin_dir_url( __FILE__ ).'img/o-email.png';

		include('partials/od-login-sms-email-buttons.php');
	}

	/**
	 * JS Vars
	 */
	public function js_vars() {

		if ( !isset( $_GET[ 'od_login_sms_email_js' ] ) ) return;

		$nonce = wp_create_nonce('od_login_sms_email_nonce');

		$variaveis_javascript = array(
			'od_login_sms_email_nonce' => $nonce, 
			'xhr_url'             => admin_url('admin-ajax.php')
		);

		$new_array = array();
		foreach( $variaveis_javascript as $var => $value ) $new_array[] = esc_js( $var ) . " : '" . esc_js( $value ) . "'";

		header("Content-type: application/x-javascript");
		printf('var %s = {%s};', 'od_login_sms_email_js', implode( ',', $new_array ) );
		exit;
	}

	/**
	 * 
	 */
	public function send_sms() {

		if( ! wp_verify_nonce( $_POST['od_login_sms_email_nonce'], 'od_login_sms_email_nonce' ) ) {

			echo '401';
			die();
		}

		if( ! isset( $_POST['to'] ) ) {

			echo '401';
			die();
		}

		$to = $_POST['to'];

		$sender = new Od_Login_Sms_Email_Send_SMS($to);
		$sender->send_sms();
		
		exit;
	}

	/**
	 * 
	 */
	public function sms_flow() {

		$this->validate_nonce();

		if ( !isset( $_POST['to'] ) || empty( $_POST['to'] ) ) {

        	$response = json_encode([ 
            	'type' 	=> 'error', 
            	'msg' 	=> __( 'Empty field', 'od-login-sms-email' ) 
        	]);
        	echo $response;
			die();
		}

		$to = $_POST['to'];
		$user = $this->get_user_by_billing_phone($to);

		if ( $user ) {

			$creator = new Od_Login_Sms_Email_Code($user->user_email);
			$created = $creator->create_access_code();

			if ( $created && !is_wp_error( $created ) ) {

				$sender = new Od_Login_Sms_Email_Send_SMS($to, $creator->code);
				$sent 	= $sender->send_sms();

				if ( $sent ) {

					$response = json_encode([ 
		        		'type'	=> 'success', 
		            	'msg' 	=> __( 'Enter or paste the passcode sent into your phone. It expires in 5 minutes.', 'od-login-sms-email' ) 
		        	]);
				} else {

					$response = json_encode([ 
		        		'type'	=> 'error', 
		            	'msg' 	=> __( 'Failed to send SMS', 'od-login-sms-email' ) 
		        	]);
				}
			}
		} else {

        	$response = json_encode([ 
        		'type'	=>'error', 
            	'msg' 	=> __( 'No or more than one user(s) found with this number.', 'od-login-sms-email' ) 
        	]);
		}

		echo $response;
		exit;
	}

	/**
	 * Get user by billing_phone
	 * 
	 * @param  string $phone Number in Woocommerce
	 * @return object $user  WP_User in success or null in failure or duplication
	 */
	public function get_user_by_billing_phone( $phone ) {

		$phone_mod = preg_replace('/[^0-9]/', '', $phone);

		$args = array (
	        'meta_query' => array(
	        	'relation'	  => 'OR',
	            array(
	                'key'     => 'billing_phone',
	                'value'   => $phone,
	                'compare' => '='
	           	),
	           	array(
	                'key'     => 'billing_phone',
	                'value'   => $phone_mod,
	                'compare' => '='
	           	)
	        )
	    );

		$wp_user_query = new WP_User_Query( $args );
		$users = $wp_user_query->get_results();
		$user = null;

		if ( $users && !is_wp_error( $users ) ) {

			$user = $users && 1 == count( $users ) ? $users[0] : null;
		}

		return $user;
	}

	/**
	 * 
	 */
	public function email_flow() {

		$this->validate_nonce();

		if ( !isset( $_POST['to'] ) || empty( $_POST['to'] ) ) {

        	$response = json_encode([ 
            	'type' 	=> 'error', 
            	'msg' 	=> __( 'Empty field', 'od-login-sms-email' ) 
        	]);
        	echo $response;
			die();
		}

		$to = $_POST['to'];
		$user = get_user_by( 'email', $to );

		if ( $user && !is_wp_error( $user ) ) {

			$creator = new Od_Login_Sms_Email_Code($to);
			$created = $creator->create_access_code();

			if ( $created && !is_wp_error( $created ) ) {

				$sender = new Od_Login_Sms_Email_Send_Email($to, $creator->code);
				$sent 	= $sender->send_email();

				if ( $sent ) {

					$response = json_encode([ 
		        		'type'	=> 'success', 
		            	'msg' 	=> __( 'Enter or paste the passcode sent into your email. It expires in 5 minutes.', 'od-login-sms-email' ) 
		        	]);
				} else {

					$response = json_encode([ 
		        		'type'	=> 'error', 
		            	'msg' 	=> __( 'Failed to send email', 'od-login-sms-email' ) 
		        	]);
				}
			}
		} else {

        	$response = json_encode([ 
        		'type'	=>'error', 
            	'msg' 	=> __( 'Invalid Email', 'od-login-sms-email' ) 
        	]);
		}

		echo $response;
		exit;
	}

	/**
	 * [get_access_codes description]
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function get_access_codes( $code ) {

		$args = array(
		    'post_type'  => 'access_code',
		    'meta_query' => array(
		        array(
		            'key'   => 'access_code',
		            'value' => $code,
		        )
		    )
		);

		$access_codes = get_posts( $args );
		return $access_codes;
	}

	/**
	 * [validate_code description]
	 * @return [type] [description]
	 */
	public function validate_code() {

		$this->validate_nonce();

		if ( !isset( $_POST['code'] ) || empty( $_POST['code'] ) ) {

        	$response = json_encode([ 
            	'type' 	=> 'error', 
            	'msg' 	=> __( 'Empty field', 'od-login-sms-email' ) 
        	]);
        	echo $response;
			die();
		}

		$code = $_POST['code'];
		$access_codes = $this->get_access_codes( $code );

		if ( $access_codes && 1 == count( $access_codes ) ) {

			$access_code = $access_codes[0];
			$ID = $access_code->ID;
			$requester = get_post_meta( $ID, 'requester', true );
			$user = $requester ? get_user_by( 'email', $requester ) : null;

			if ( $user && !is_wp_error( $user ) ) {

				wp_delete_post( $ID );

				wp_clear_auth_cookie();
		        wp_set_current_user ( $user->ID );
		        wp_set_auth_cookie  ( $user->ID );

		        do_action( 'wp_login', $user->user_login, $user );

				$response = json_encode([ 
	            	'type' 	=> 'success', 
	            	'msg' 	=> sprintf(__( '\ö/<br />Welcome <b>%s</b>. You are being redirected.', 'od-login-sms-email' ), $user->display_name) 
	        	]);

	        	echo $response;
				exit;
			}
		} else {

			$response = json_encode([ 
            	'type' 	=> 'error', 
            	'msg' 	=> __( 'Invalid or incorrect Access Code. Try again.<br /><a href="#" id="restart">Restart</a>', 'od-login-sms-email' ) 
        	]);

        	echo $response;
			exit;
		}
	}

	/**
	 * [validate_nonce description]
	 * @return [type] [description]
	 */
	public function validate_nonce() {

		if ( !wp_verify_nonce( $_POST['od_login_sms_email_nonce'], 'od_login_sms_email_nonce' ) ) {

        	$response = json_encode([ 
        		'type' 	=> 'error', 
            	'msg' 	=> __( 'Invalid nonce', 'od-login-sms-email' ) 
        	]);
        	echo $response;
			die();
		}
	}

}
