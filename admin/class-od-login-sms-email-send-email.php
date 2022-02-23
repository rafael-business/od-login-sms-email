<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://olivas.digital
 * @since      1.0.0
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/admin
 */

/**
 * The Email's functionality of the plugin.
 *
 * Defines Email's sending.
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/admin
 * @author     Olivas Digital <contato@olivasdigital.com.br>
 *
 * 
 */
class Od_Login_Sms_Email_Send_Email {

	/**
	 * From.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $from
	 */
	private $from;

	/**
	 * To.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $to
	 */
	private $to;

	/**
	 * Subject.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subject
	 */
	private $subject;

	/**
	 * Message.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $msg
	 */
	private $msg;

	/**
	 * Access Code.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $code
	 */
	private $code;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $to
	 * @param      int    	 $code
	 */
	public function __construct($to, $code) {

		$this->code = $code;
		$this->get_msg();

		$this->from 	= 'Mandala Comidas Especiais <mandala@mandalacomidas.com.br>';
		$this->to 		= $to;
		$this->subject 	= __('Access Code', 'od-login-sms-email');
		$this->msg 		= $this->msg;
	}

	public function get_msg() {

		$this->msg = '<div style="width: 230px; margin: 20px auto; text-align: center;"><img src="https://www.mandalacomidas.com.br/wp-content/themes/mandala/dist/img/emails/logo-mandala.png" alt="Mandala Comidas" /><br />Seu código de acesso é:<br /><div style="font-size: 40px;">'.$this->code.'</div></div>';
	}

	/**
	 * Send Email.
	 *
	 * @since    1.0.0
	 */
	public function send_email() {

		$sent = false;

		if ( $this->msg ) {

			$logger = wc_get_logger();
			$source = array( 'source' => 'od-login-sms-email' );
			$log 	= array();

			try {

				$headers[] = 'From: '. $this->from;
				$headers[] = 'Content-Type: text/html; charset=UTF-8';
				$sent = wp_mail( $this->to, $this->subject, $this->msg, $headers );

			} catch ( Exception $ex ) {

			    $log['exce'] = "Falha ao fazer o envio da mensagem. Exceção: ".$ex->getMessage()." - ".$ex->getTraceAsString();
			    $logger->error( $log['exce'], $source );
			}
		}

		return $sent;

	}

}
