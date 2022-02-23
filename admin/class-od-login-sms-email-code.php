<?php

/**
 * The Access Code functionality of the plugin.
 *
 * Defines Access Code creation.
 * 
 * @link       https://olivas.digital
 * @since      1.0.0
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/admin
 * @author     Olivas Digital <contato@olivasdigital.com.br>
 *
 */
class Od_Login_Sms_Email_Code {

	/**
	 * Access Code.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $code
	 */
	public $code;

	/**
	 * Requester.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $requester
	 */
	private $requester;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $to
	 */
	public function __construct( $to = null ) {

		$this->code 		= rand(10000, 99999);
		$this->requester 	= $to;
	}

	/**
	 * Create Access Code.
	 *
	 * @since    1.0.0
	 */
	public function create_access_code() {

		$created = false;

		if ( $this->code ) {

			try {

				/**
				 * Usando o gerador de Log do Woocommerce
				 */
			    $logger = wc_get_logger();
				$source = array( 'source' => 'od-login-sms-email' );

				$ac_post = array(
					'post_type'		=> 'access_code',
				    'post_title'    => $this->code,
				    'post_status'   => 'publish',
				    'post_author'   => 1
				);

				$created = wp_insert_post( $ac_post );

				if ( $created && !is_wp_error( $created ) ) {

					$exp = '5 minutes';

					$created_date = substr( get_the_date( 'c', $created ), 0, 19 );
					$exp_date = date( 'c', strtotime( $created_date . ' + ' . $exp ) );
					$exp_time = strtotime( get_the_date( 'c', $created ) . ' + ' . $exp );

				    add_post_meta( $created, 'access_code', 	$this->code );
				    add_post_meta( $created, 'requester', 		$this->requester );
				    add_post_meta( $created, 'exp_date', 		$exp_date );

				    $event = wp_schedule_single_event( $exp_time, 'delete_access_code', array( $created ) );
				    if ( !$event || is_wp_error( $event ) ) {

				    	$logger->error( 'Falha ao criar evento de exclusão do Access Code, ID: ' . $created, $source );
				    }
				}

			} catch ( Exception $ex ) {

			    $error = "Falha ao criar postagem. Exceção: ".$ex->getMessage()." - ".$ex->getTraceAsString();
			    $logger->error( $error, $source );
			}
		}

		return $created;

	}

	/**
	 * Delete Access Code post
	 * 
	 * @param  int 	$ac_id 		Id of Access Code
	 */
	public function call_delete_access_code( $ac_id ) {

		wp_delete_post( $ac_id );
	}

	/**
	 * Add columns to Access Code post type List
	 * 
	 * @param  [type] $columns [description]
	 * @return [type] $columns [description]
	 */
	public function access_code_columns( $columns ) {

		$columns['access_code'] = __( 'Access Code', 'od-login-sms-email' );
		$columns['requester'] = __( 'Requester', 'od-login-sms-email' );
		$columns['exp_date'] = __( 'Expiration Date', 'od-login-sms-email' );

		return $columns;
	}

	public function access_code_column_values( $column, $access_code_id ) {
 
	    switch ( $column ) {
	 	
	 		case 'access_code' :
	        case 'requester' :
	            echo get_post_meta( $access_code_id , $column , true );
	        break;

	        case 'exp_date' :
	        	_e( 'Will be deleted on:<br />', 'od-login-sms-email' );
	            echo date('d/m/Y \à\s H:i:s', strtotime( get_post_meta( $access_code_id , $column , true ) ));
	        break;
	    }
	}

}
