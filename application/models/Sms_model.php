<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Twilio/autoload.php';
use Twilio\Rest\Client;

class Sms_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
	}

	/* * *********************************************************************
	 * * Function name : sendMessageFunction 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Message Function
	 * * Date : 08 APRIL 2021
	 * * **********************************************************************/
	public function sendMessageFunction($phone='',$message='',$sender_id='') {
		try {
			if(!empty($phone) && !empty($message) && !empty($sender_id)):
				//Please Enter Your Details
				$user='pltech'; //your username
				$password=SMSCOUNTRYPASSWORD; //your password
				$mobilenumbers=substr($phone,1); //enter Mobile numbers comma seperated
				$message = $message; //enter Your Message
				$senderid=$sender_id; //Your senderid
				
				$messagetype="N"; //Type Of Your Message
				$DReports="Y"; //Delivery Reports
				$url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
				$message = urlencode($message);
				$ch = curl_init();
				if (!$ch){die("Couldn't initialize a cURL handle");}
				$ret = curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt ($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt ($ch, CURLOPT_POSTFIELDS,
				"User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
				$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				//If you are behind proxy then please uncomment below line and provide your proxy ip with port.
				// $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
				$curlresponse = curl_exec($ch); // execute
				if(curl_errno($ch))
				return "ERROR";
				//echo 'curl error : '. curl_error($ch);
				if (empty($ret)) {
					return "some kind of an error happened";
				// some kind of an error happened
				//die(curl_error($ch));
				curl_close($ch); // close cURL handler
				} else {
				$info = curl_getinfo($ch);
				curl_close($ch); // close cURL handler
				return $curlresponse; //echo "Message Sent Succesfully" ;
				}
			endif;
		} catch (\Throwable $th) {
			return "FAIL";
		}
		
	}


	public function sendMessageDigitizebirdFunction($phone='',$message='',$senderid='')
	{
		try {
			if(!empty($phone) && !empty($message) && !empty($senderid)):
				
				//old api key $ApiKey 		= 'ybG+HgfvR2YzK/LOlwwBXU7YRhKu+LK5Vi6Mfg5N5AI=';
				$ApiKey 		= 'r8J3+a64Tni3MRp/0VDKEHPL2D4iu+Q/7LlLgL01f9c=';
				$ClientId 		= '3cb6faf0-b21c-4094-8409-cd3a0b3e03de';
				$CompanyId 		= '7';
				$message = urlencode($message);
				$url = "https://user.digitizebirdsms.com/api/v2/SendSMS?SenderId=$senderid&Is_Unicode=false&Is_Flash=true&Message=$message&MobileNumbers=$phone&ApiKey=$ApiKey&ClientId=$ClientId&CompanyId=$CompanyId";
				
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array( 'accept: text/plain' ),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				return $response; 
			endif;
		} catch (\Throwable $th) {
			return "FAIL";
		}
	}

	/* * *********************************************************************
	 * * Function name : sendMessageFromTwilio 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Message Function
	 * * Date : 08 APRIL 2021
	 * * **********************************************************************/
	public function sendMessageFromTwilio($phone='',$sent_message='') {
		try {
			$sid = 'AC6533fba73fa923d25ef9b45a2f6717db';
			$token = '2dfea9cf395b883235c9a103ff78e09d';
			
			$client = new Client($sid, $token);
			$phoneNumber = $phone;
			$twilioPurchasedNumber = "+13203616472";
			// Send a text message
			$message = $client->messages->create(
				$phoneNumber,
				[
					'from' => $twilioPurchasedNumber,
					'body' => $sent_message
				]
			);
			
			//print($sent_message." with sid = " . $message->sid ."\n\n");

			// Print the last 10 messages
			$messageList = $client->messages->read([],10);
			
			return true;
			// foreach ($messageList as $msg) {
			// 	print("ID:: ". $msg->sid . " | " . "From:: " . $msg->from . " | " . "TO:: " . $msg->to . " | "  .  " Status:: " . $msg->status . " | " . " Body:: ". $msg->body ."\n");
			// }
		} catch (\Throwable $th) {
			return "FAIL";
		}
	}//END  OF FUNCTION

	/***********************************************************************
	** Function name 	: sendForgotPasswordOtpSmsToUser
	** Developed By 	: Afsar Ali
	** Purpose  		: This is use for send Forgot Password Otp Sms To User
	** Date 			: 23 DEC 2022
	************************************************************************/
	function sendForgotPasswordOtpSmsToUser($mobileNumber='',$otp='',$country_code='+971') {  
		
		/// please use these function when twilio is working.

		// try {
		// 	//echo $country_code;die();
		// 	if($mobileNumber && $otp):
		// 		$message		=	"Your OTP is ".$otp.".";
		// 		$senderid		=	"DLZARBA";
		// 		if($country_code == '+971'):
		// 			$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
		// 		else:
		// 			if($country_code != ''){
		// 				$returnMessage	=	$this->sendMessageFromTwilio($mobileNumber,$message);
		// 			}else{
		// 				return false;
		// 			}
		// 		endif;
		// 		return $returnMessage;
		// 	endif;	
		// } catch (\Throwable $th) {
		// 	return false;
		// }

		$enableSMS = $this->common_model->getData('single','da_enablesms');
 
		// Finding country code and sending sms using sms country.
        if($enableSMS['smscountry'] == "enable"):

        	$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY as $key => $item):
				$SMSCOUNTRY[$key] = trim($item);
			endforeach;

            // checking country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY) ):
				if($mobileNumber && $otp):
					$message		=	"Your OTP is ".$otp.".";
					$senderid		=	"DLZARBA";
					$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
        endif;


        // Finding country code and sending sms using digitizebird.
        if($enableSMS['digitizebird'] == "enable"):

        	$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
			
			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY1 as $key => $item1):
				$SMSCOUNTRY1[$key] = trim($item1);
			endforeach;

            // checking country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY1) ):
				if($mobileNumber && $otp):
					$message		=	"Your OTP is ".$otp.".";
					$senderid 		= 'DLZRBIA';
					$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
        endif;

       		
		
	} //END OF FUNCTION

	/***********************************************************************
	** Function name 	: sendSuccessRgistrationSmsToUser
	** Developed By 	: Afsar Ali
	** Purpose  		: This is use for send success registration Sms To User
	** Date 			: 23 DEC 2022
	************************************************************************/
	function sendSuccessRgistrationSmsToUser($mobileNumber='',$users_name = '',$country_code='+971') {  
		


		$enableSMS = $this->common_model->getData('single','da_enablesms');
 
		// Finding country code and sending sms using sms country.
		if($enableSMS['smscountry'] == "enable"):

			$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY as $key => $item):
				$SMSCOUNTRY[$key] = trim($item);
			endforeach;

		    // checking country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY) ):
				if($mobileNumber && $users_name):
					$message		=	"Dear ".$users_name." , welcome to DealzArabia.Your Account has been created successfully. For more info, please visit https://dealzarabia.com/";
					$senderid		=	"DLZARBA";
					$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
		endif;


		// Finding country code and sending sms using digitizebird.
		if($enableSMS['digitizebird'] == "enable"):

			$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
			
			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY1 as $key => $item1):
				$SMSCOUNTRY1[$key] = trim($item1);
			endforeach;

		    // checking country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY1) ):
				if($mobileNumber && $users_name):
					$message		=	"Dear ".$users_name." , welcome to DealzArabia.Your Account has been created successfully. For more info, please visit https://dealzarabia.com/";
					// $senderid		=	"AD-DLZRBIA";
					$senderid		=	"DLZRBIA";
					$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
		endif;

	} //END OF FUNCTION

	/***********************************************************************
	** Function name 	: sendSuccessResetPasswordSmsToUser
	** Developed By 	: Afsar Ali
	** Purpose  		: This is use for send success reset password Sms To User
	** Date 			: 23 DEC 2022
	************************************************************************/
	function sendSuccessResetPasswordSmsToUser($mobileNumber='',$country_code='+971') {  
		
		$enableSMS = $this->common_model->getData('single','da_enablesms');
		
		// Finding country code and sending sms using sms country.
        if($enableSMS['smscountry'] == "enable"):

        	$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY as $key => $item):
				$SMSCOUNTRY[$key] = trim($item);
			endforeach;

            // checking country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY) ):
				if($mobileNumber):
					$message		=	"Your DealzArabia password has been reset successfully";
					$senderid		=	"DLZARBA";
					$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
        endif;

        // Finding country code and sending sms using digitizebird.
        if($enableSMS['digitizebird'] == "enable"):

        	$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
			
			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY1 as $key => $item1):
				$SMSCOUNTRY1[$key] = trim($item1);
			endforeach;

            // checking country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY1) ):
				if($mobileNumber):
					$message		=	"Your DealzArabia password has been reset successfully";
					$senderid		=	"DLZRBIA";
					$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
        endif;
	} //END OF FUNCTION

	/***********************************************************************
	** Function name 	: sendTicketDetails
	** Developed By 	: Dilip Halder
	** Purpose  		: This is use for send success reset password Sms To User
	** Date 			: 02 march 2023
	** Updatead By 		: Dilip Halder
	** Date 			: 15 march 2023
	************************************************************************/
	function sendTicketDetails($oid = ''){

		$tblName 				=	'da_orders';
		$shortField 			= 	array('_id'=> -1 );
		$whereCon['where']		= 	array('order_id'=>$oid);

		$orderData  =	$this->geneal_model->getordersList('single', $tblName, $whereCon,$shortField);

		// product id 
		$product_id = $orderData['order_details']['0']['product_id'];
		$tbl 					=	'da_products';
		$where 					=	['products_id' => (int)$product_id ];
		$productData		=	$this->geneal_model->getData($tbl, $where,[]);

		// user id 
		$UserId = $orderData['user_id'];

		$tbl 					=	'da_users';
		$where 					=	['users_id' => (int)$UserId];
		$UserData		=	$this->geneal_model->getData($tbl, $where,[]);

		$order_id  		  = $orderData['order_id'];
		$collection_code  = $orderData['collection_code'];
		$draw_date 		  = date('d M, Y',strtotime($productData[0]['draw_date']));
		if($UserData[0]['country_code']):
			$country_code     = $UserData[0]['country_code'];
		else:
			$country_code     = '+971';
		endif;

		$users_mobile     = $UserData[0]['users_mobile'];

		// collection_code ,order_id 
		// if($order_id && $collection_code && $draw_date ):
		// 	$message		=	"Your ticket number is $order_id Collection Code $collection_code Draw date $draw_date";
		// 	$senderid		=	"DLZARBA";
		// 	$mobileNumber   =   $country_code.$users_mobile;
		// 	$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
		// 	return $returnMessage;
		// endif;

		
		// Creating list of coupon code generated per order.
		$coupon_code = array();
		$coupon_code_donated = array();
		$pdfDownload_path = base_url('/').'order-details/download-invoice/'.$oid;

		foreach ($orderData['coupon_details'] as $key => $couponList):


			if($couponList['is_donated'] == 'N'  ):
				$coupon_code[] = $couponList['coupon_code'];
			else:
				$coupon_code_donated[] = $couponList['coupon_code'];
			endif;


		endforeach;
		
		$CouponCodeList =  implode( ' , ', $coupon_code); 
		$CouponCodeListDonated =  implode( ' , ', $coupon_code_donated);

		$enableSMS = $this->common_model->getData('single','da_enablesms');
		// collection_code ,order_id 
		foreach ($orderData['order_details'] as $key => $items):
			
			if($items['is_donated'] == "Y"):

				// Finding country code and sending sms using sms country.
		        if($enableSMS['smscountry'] == "enable"):

		        	$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY as $key => $item):
						$SMSCOUNTRY[$key] = trim($item);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY) ):
						if($order_id && $draw_date ):
							$message		=	"Your ticket number is ".$CouponCodeListDonated." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
							 // $message		=	"Your ticket number is $CouponCodeListDonated Draw date $draw_date" .'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZARBA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
							return $returnMessage;
						endif; 
					endif; 
		        endif;


		        // Finding country code and sending sms using digitizebird.
		        if($enableSMS['digitizebird'] == "enable"):

		        	$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
					
					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY1 as $key => $item1):
						$SMSCOUNTRY1[$key] = trim($item1);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY1) ):
						if($order_id && $draw_date ):
							$message		=	"Your ticket number is ".$CouponCodeListDonated." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
							 // $message		=	"Your ticket number is $CouponCodeListDonated Draw date $draw_date" .'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZRBIA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
							return $returnMessage;
						endif;
					endif; 
		        endif;
			endif;

			if($items['is_donated'] == "N"):

				// Finding country code and sending sms using sms country.
		        if($enableSMS['smscountry'] == "enable"):

		        	$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY as $key => $item):
						$SMSCOUNTRY[$key] = trim($item);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY) ):
						if($order_id && $collection_code && $draw_date ):
							$collection_code = base64_decode($collection_code); 

						 	$message		=	"Your ticket number is ".$CouponCodeList." Collection Code ".$collection_code." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
						 	 // $message		=	"Your ticket number is $CouponCodeList Collection Code $collection_code Draw date $draw_date".'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZARBA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
							return $returnMessage;
						endif;
					endif; 

		        endif;

		        // Finding country code and sending sms using digitizebird.
		        if($enableSMS['digitizebird'] == "enable"):

		        	$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
					
					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY1 as $key => $item1):
						$SMSCOUNTRY1[$key] = trim($item1);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY1) ):
						if($order_id && $collection_code && $draw_date ):
							$collection_code = base64_decode($collection_code); 

						 	$message		=	"Your ticket number is ".$CouponCodeList." Collection Code ".$collection_code." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
						 	 // $message		=	"Your ticket number is $CouponCodeList Collection Code $collection_code Draw date $draw_date".'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZRBIA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
							return $returnMessage;
						endif;
					endif; 
		        endif;

			endif;

			
		endforeach;


	}

	function sendQuickTicketDetails($ticket_order_id="",$mob = "",$couponData="",$ProductId="",$country_code="+971",$is_donated=""){

		// product id 
		$product_id = $ProductId;
		$tbl 					=	'da_products';
		// $where 					=	['products_id' => (int)$product_id ];
		// $productData		=	$this->geneal_model->getData($tbl, $where,[]);
		$where['where'] 		=	['products_id' => (int)$product_id ];//Updated by Afsar on 17-05-2023
		$productData		=	$this->geneal_model->getData2('single',$tbl, $where,[]);//Updated by Afsar on 17-05-2023

		$tbl1 					=	'da_ticket_coupons';
		$where['where'] 		=	['ticket_order_id' =>  $ticket_order_id ]; 
		$orderDetails			=	$this->geneal_model->getData2('single',$tbl1, $where,[]); 
		
		// Update on 17-05-2023 by Afsar ALi
		$tbl 					=	'da_prize';
		$where['where'] 		=	['product_id' => (int)$product_id ];
		$prizeData				=	$this->geneal_model->getData2('single',$tbl, $where,[]);
		// End

		$pdfDownload_path = base_url('/').'downloadQuickInvoice/'.$ticket_order_id;
		// -------------------------------------------
		// user id 
		//$UserId = $orderData['user_id'];
		
		// $order_id  		  = $orderData['order_id'];
		// $draw_date 		  = $productData[0]['draw_date'];
		//----------------------------------------------------
		//Comment by afsar ali on 17-05-2023
		//---------------------------------------------------

		$ade 				= $productData['adepoints'].' AED';
		$product_name 		= $productData['title'];
		$coupon_code		= implode(',',$couponData);

		$order_id  		  	= $ticket_order_id;
		$draw_date 			=  strtotime($productData['draw_date']);
		$draw_date 			=  date('d M, Y' ,$draw_date);

		$win				=	$prizeData['title'].'!';
		//------------------------------------------------
		// Updated by afsar ali on 17-05-2023
		//--------------------------------------------------

		// collection_code ,order_id 
		if($mob && $couponData && $draw_date ):
			


			$enableSMS = $this->common_model->getData('single','da_enablesms');

			// Finding country code and sending sms using sms country.
			if($enableSMS['smscountry'] == "enable"):

				$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

				// Removed extra space from country code ...
				foreach ($SMSCOUNTRY as $key => $item):
					$SMSCOUNTRY[$key] = trim($item);
				endforeach;

			    // checking country code exist or Not...
				if(in_array($country_code, $SMSCOUNTRY) ):
					
						$senderid		=	"DLZARBA";
						$mobileNumber   =   $country_code.$mob;

						if($orderDetails['isVoucher'] == "Y" &&  $is_donated == "Y" || $orderDetails['isVoucher'] == "Y" ):
							$message = "Thank you for purchasing Ticket ".$coupon_code." for the draw on ".$draw_date.". We wish you the best of luck in becoming the next winner of ".$win." Download Order pdf : https://dealzarabia.com/downloadQuickInvoice/".$order_id;
						else:
							$message = "Thank you for purchasing the ".$product_name." with Ticket ".$coupon_code." for the draw on ".$draw_date.". We wish you the best of luck in becoming the next winner of ".$win." Download Order pdf : https://dealzarabia.com/downloadQuickInvoice/".$order_id;
						endif;

						$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
						return $returnMessage;
					
				endif; 
			endif;

			// Finding country code and sending sms using digitizebird.
			if($enableSMS['digitizebird'] == "enable"):

				$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
				
				// Removed extra space from country code ...
				foreach ($SMSCOUNTRY1 as $key => $item1):
					$SMSCOUNTRY1[$key] = trim($item1);
				endforeach;

			    // checking country code exist or Not...
				if(in_array($country_code, $SMSCOUNTRY1) ):
					
						$senderid 		= 'DLZRBIA';

					    // Convert the number to a string to ensure all leading zeros are considered.
					    $numberAsString = (string) $mob;

					    // Use ltrim to remove leading zeros.
					    $mob1 = ltrim($numberAsString, '0');
						$mobileNumber   =   $country_code.$mob1;
						
						if($orderDetails['isVoucher'] == "Y" &&  $is_donated == "Y" || $orderDetails['isVoucher'] == "Y" ):
							$message = "Thank you for purchasing Ticket ".$coupon_code." for the draw on ".$draw_date.". We wish you the best of luck in becoming the next winner of ".$win." Download Order pdf : https://dealzarabia.com/downloadQuickInvoice/".$order_id;
						else:
							$message = "Thank you for purchasing the ".$product_name." with Ticket ".$coupon_code." for the draw on ".$draw_date.". We wish you the best of luck in becoming the next winner of ".$win." Download Order pdf : https://dealzarabia.com/downloadQuickInvoice/".$order_id;
						endif;

						$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
						return $returnMessage;
					
				endif; 
			endif;


		endif;
	}


	function sendQuickTicketDetails_test($ticket_order_id="",$mob = "",$couponData="",$ProductId="",$countryCode=""){

		// product id 
		$product_id = $ProductId;
		$tbl 					=	'da_products';
		$where['where'] 					=	['products_id' => (int)$product_id ];
		$productData		=	$this->geneal_model->getData2('single',$tbl, $where,[]);
		//Prize
		$tbl 					=	'da_prize';
		$where['where'] 					=	['product_id' => (int)$product_id ];
		$prizeData				=	$this->geneal_model->getData2('single',$tbl, $where,[]);

		// print_r($prizeData);die();
		$pdfDownload_path = base_url('/').'downloadQuickInvoice/'.$ticket_order_id;
		// user id 
		//$UserId = $orderData['user_id'];

		$ade 				= $productData['adepoints'].' AED';
		$product_name 		= $productData['title'];
		$coupon_code		= implode(',',$couponData);

		$order_id  		  	= "DZINV1002531";
		$draw_date 		  	= $productData['draw_date'];

		$win				=	$prizeData['title'].'!';
		

		// collection_code ,order_id 
		if($mob && $couponData && $draw_date ):
			// $message		=	"Your ticket number is ".implode(',',$couponData) . ", Draw date $draw_date. Thank You for purchasing. Download Order pdf : $pdfDownload_path ";
			// $message		=	"Your ticket number is ".implode(',',$couponData) . ", Draw date $draw_date. Thank You for purchasing.";
			$message = "Thank you for purchasing the ".$ade." ".$product_name." with Ticket ".$coupon_code." for the draw on ".$draw_date.". We wish you the best of luck in becoming the next winner of ".$win." Download Order pdf : https://dealzarabia.com/downloadQuickInvoice/DZINV1002531";
			echo $message;die();
			$senderid		=	"DLZARBA";
			if(!empty($countryCode)):
				$mobileNumber   =   $countryCode.$mob;
			else:
				$mobileNumber   =   '+971'.$mob;
			endif;

			$returnMessage	=	$this->sendMessageFunction('+971501292591',$message,$senderid);
			return $returnMessage;
		endif;
	}

	public function smsCountryTest($oid){
		// echo 'working';die();
		$tblName 				=	'da_orders';
		$shortField 			= 	array('_id'=> -1 );
		$whereCon['where']		= 	array('order_id'=>$oid);

		$orderData  =	$this->geneal_model->getordersList('single', $tblName, $whereCon,$shortField);
		// product id 
		$product_id = $orderData['order_details']['0']['product_id'];
		$tbl 					=	'da_products';
		$where 					=	['products_id' => (int)$product_id ];
		$productData		=	$this->geneal_model->getData($tbl, $where,[]);

		// user id 
		$UserId = $orderData['user_id'];

		$tbl 					=	'da_users';
		$where 					=	['users_id' => (int)$UserId];
		$UserData		=	$this->geneal_model->getData($tbl, $where,[]);
		echo '<pre>';print_r($UserData);die();
		$order_id  		  = $orderData['order_id'];
		$collection_code  = $orderData['collection_code'];
		$draw_date 		  = date('d M, Y',strtotime($productData[0]['draw_date']));

		if($UserData[0]['country_code']):
			$country_code     = $UserData[0]['country_code'];
		else:
			$country_code     = '+971';
		endif;


		$users_mobile     = $UserData[0]['users_mobile'];

		// collection_code ,order_id 
		// if($order_id && $collection_code && $draw_date ):
		// 	$message		=	"Your ticket number is $order_id Collection Code $collection_code Draw date $draw_date";
		// 	$senderid		=	"DLZARBA";
		// 	$mobileNumber   =   $country_code.$users_mobile;
		// 	$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
		// 	return $returnMessage;
		// endif;

		
		// Creating list of coupon code generated per order.
		$coupon_code = array();
		$coupon_code_donated = array();
		$pdfDownload_path = base_url('/').'downloadQuickInvoice/'.$oid;

		foreach ($orderData['coupon_details'] as $key => $couponList):


			if($couponList['is_donated'] == 'N'  ):
				$coupon_code[] = $couponList['coupon_code'];
			else:
				$coupon_code_donated[] = $couponList['coupon_code'];
			endif;


		endforeach;
		
		$CouponCodeList =  implode( ' , ', $coupon_code); 
		$CouponCodeListDonated =  implode( ' , ', $coupon_code_donated);

		$enableSMS = $this->common_model->getData('single','da_enablesms');
		// echo '<pre>';print_r($enableSMS);
		// print_r($orderData['order_details']);
		// die();
		// collection_code ,order_id 
		foreach ($orderData['order_details'] as $key => $items):
			if($items['is_donated'] == "Y"):
				// Finding country code and sending sms using sms country.
				echo $country_code;die();

		        if($enableSMS['smscountry'] == "enable"):
					// echo 'IN';die();
		        	$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY as $key => $item):
						$SMSCOUNTRY[$key] = trim($item);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY) ):
						if($order_id && $draw_date ):
							$message		=	"Your ticket number is ".$CouponCodeListDonated." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
							 // $message		=	"Your ticket number is $CouponCodeListDonated Draw date $draw_date" .'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZARBA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
							print_r($returnMessage);die();
							return $returnMessage;
						endif; 
					endif; 
		        endif;


		        // Finding country code and sending sms using digitizebird.
		        if($enableSMS['digitizebird'] == "enable"):

		        	$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
					
					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY1 as $key => $item1):
						$SMSCOUNTRY1[$key] = trim($item1);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY1) ):
						if($order_id && $draw_date ):
							$message		=	"Your ticket number is ".$CouponCodeListDonated." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
							 // $message		=	"Your ticket number is $CouponCodeListDonated Draw date $draw_date" .'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZRBIA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
							return $returnMessage;
						endif;
					endif; 
		        endif;
			endif;

			if($items['is_donated'] == "N"):

				// Finding country code and sending sms using sms country.
		        if($enableSMS['smscountry'] == "enable"):

		        	$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY as $key => $item):
						$SMSCOUNTRY[$key] = trim($item);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY) ):
						if($order_id && $collection_code && $draw_date ):
							$collection_code = base64_decode($collection_code); 

						 	$message		=	"Your ticket number is ".$CouponCodeList." Collection Code ".$collection_code." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
						 	 // $message		=	"Your ticket number is $CouponCodeList Collection Code $collection_code Draw date $draw_date".'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZARABA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
							return $returnMessage;
						endif;
					endif; 

		        endif;

		        // Finding country code and sending sms using digitizebird.
		        if($enableSMS['digitizebird'] == "enable"):

		        	$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
					
					// Removed extra space from country code ...
					foreach ($SMSCOUNTRY1 as $key => $item1):
						$SMSCOUNTRY1[$key] = trim($item1);
					endforeach;

		            // checking country code exist or Not...
					if(in_array($country_code, $SMSCOUNTRY1) ):
						if($order_id && $collection_code && $draw_date ):
							$collection_code = base64_decode($collection_code); 

						 	$message		=	"Your ticket number is ".$CouponCodeList." Collection Code ".$collection_code." Draw date ".$draw_date." Download Order pdf : ".$pdfDownload_path;
						 	 // $message		=	"Your ticket number is $CouponCodeList Collection Code $collection_code Draw date $draw_date".'<a href='.$pdfDownload_path.'> Download Order pdf </a>';
							$senderid		=	"DLZRBIA";
							$mobileNumber   =   $country_code.$users_mobile;
							$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
							return $returnMessage;
						endif;
					endif; 
		        endif;

			endif;

			
		endforeach;

	}

}	