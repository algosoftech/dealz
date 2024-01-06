<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
//require(__DIR__.'/../Twilio/autoload.php');

require_once 'twilio/autoload.php';

use Twilio\Rest\Client;

class Twilio_sms extends CI_Model
{
	public function __construct()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		parent::__construct(); 
	}

	/* * *********************************************************************
	 * * Function name : sendMessageFunction 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Message Function
	 * * Date : 08 APRIL 2021
	 * * **********************************************************************/
	public function sendMessageFunction($phone='',$message='',$senderid='') {
		// try {
			//echo 'working';die();
			$sid = 'AC6533fba73fa923d25ef9b45a2f6717db';
			$token = '2dfea9cf395b883235c9a103ff78e09d';
			$client = new Client($sid, $token);

			// Specify the phone numbers in [E.164 format](https://www.twilio.com/docs/glossary/what-e164) (e.g., +16175551212)
			// This parameter determines the destination phone number for your SMS message. Format this number with a '+' and a country code
			$phoneNumber = "+918700144841";

			// This must be a Twilio phone number that you own, formatted with a '+' and country code
			$twilioPurchasedNumber = "+13203616472";

			// Send a text message
			$message = $client->messages->create(
				$phoneNumber,
				[
					'from' => $twilioPurchasedNumber,
					'body' => "Testting Twilio sms by Afsar Ali."
				]
			);
			print("Message sent successfully with sid = " . $message->sid ."\n\n");

			// Print the last 10 messages
			$messageList = $client->messages->read([],10);
			foreach ($messageList as $msg) {
				print("ID:: ". $msg->sid . " | " . "From:: " . $msg->from . " | " . "TO:: " . $msg->to . " | "  .  " Status:: " . $msg->status . " | " . " Body:: ". $msg->body ."\n");
			}
		// } catch (\Throwable $th) {
		// 	return "FAIL";
		// }
		
	}
	/* * *********************************************************************
	 * * Function name : sendMessageFunction 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Message Function
	 * * Date : 08 APRIL 2021
	 * * **********************************************************************/
	function test_sms() {
		//echo 'working1';die();
		$this->sendMessageFunction();
	}

}	