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
 * The SMS's functionality of the plugin.
 *
 * Defines SMS's sending.
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/admin
 * @author     Olivas Digital <contato@olivasdigital.com.br>
 *
 * 
 */
class Od_Login_Sms_Email_Send_SMS {

	/**
	 * Account.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $account
	 */
	private $account;

	/**
	 * Password.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $password
	 */
	private $password;

	/**
	 * Web Service URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ws
	 */
	private $ws;

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
	 * Message.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $msg
	 */
	private $msg;

	/**
	 * Unique id.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $id
	 */
	private $id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $email_id
	 * @param      string    $order_id
	 */
	public function __construct($to, $code) {

		$this->account 	= get_option('od_login_sms_email_account');
		$this->password = get_option('od_login_sms_email_password');
		$this->ws 		= 'https://api-rest.zenvia.com';
		$this->from 	= 'MANDALA COMIDAS';
		$this->msg 		= 'Seu código de acesso é: ' . $code;
		$this->id 		= rand(100, 999);

		$this->sms_to($to);

	}

	public function sms_to($to) {

		$to = str_replace('(', '', $to);
		$to = str_replace(')', '', $to);
		$to = str_replace('-', '', $to);
		$to = str_replace(' ', '', $to);
		$to = '55' . $to;
		$this->to = $to;
	}

	/**
	 * Send SMS.
	 *
	 * @since    1.0.0
	 */
	public function send_sms() {

		$sent = false;

		if ( $this->msg ) {

			$smsFacade = new SmsFacade($this->account, $this->password, $this->ws);

			$sms = new Sms();
			$sms->setFrom($this->from);
			$sms->setTo($this->to);
			$sms->setMsg($this->msg);
			$sms->setId($this->id);
			$sms->setCallbackOption(Sms::CALLBACK_NONE);

			$date = new DateTime();
			$date->setTimeZone(new DateTimeZone('America/Sao_Paulo'));
			$schedule = $date->format("Y-m-d\TH:i:s");

			$sms->setSchedule($schedule);

			$logger = wc_get_logger();
			$source = array( 'source' => 'od-login-sms-email' );
			$log 	= array();

			try{

			    $response = $smsFacade->send($sms);
			    $sent = $response->getStatusCode() == '00' ? true : false;

			    $log['erro'] = "Mensagem não pôde ser enviada.";

			    if ( !$sent ) {

			       $logger->error( $log['erro'], $source );
			    }

			}
			catch(Exception $ex){

			    $log['exce'] = "Falha ao fazer o envio da mensagem. Exceção: ".$ex->getMessage()." - ".$ex->getTraceAsString();
			    $logger->error( $log['exce'], $source );
			}
		}

		return $sent;
	}

}
