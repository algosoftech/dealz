<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'sendgridmail/library/sendgrid-php.php';

use SendGrid\Mail\Mail;

class Emailsendgrid_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
		//$this->load->database();
	}
	/***********************************************************************
	** Function name : get_email_template_by_mail_type
	** Developed By : Tejaswi
	** Purpose  : This is get  email template by mail type.
	** Date : 08 APRIL 2021
	************************************************************************/
	function get_email_template_by_mail_type($type='') { 
	
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('email_template_id'=>$type));
		$result = $this->mongo_db->find_one('da_email_templates');
		if($result):
			return $result;
		else:	
			return false;
		endif;
	}

	##################################################################################################################################
	####################################	SENDGRID MAILER SECTION 		##############################################################
	##################################################################################################################################
	function sendForgotpasswordMailToUser($userData=array()) {  
		if(!empty($userData['users_email'])):
			$MTData				=	$this->get_email_template_by_mail_type(100000000000002); 
			if($MTData <> ""):  
				#.............................. message section ............................#
				$MHData 		= 	stripslashes($MTData['mail_header']);
	
				$MBData 		= 	str_replace('{USERNAME}', $userData['users_name'], stripslashes($MTData['mail_body']));
				
				$MBData         =   str_replace('{OTP}',$userData['users_otp'], $MBData);

				$MFData 		= 	stripslashes($MTData['mail_footer']);
	
				$MHtml	 		= 	stripslashes($MTData['html']);
				$MHtml	 		= 	str_replace('{MAIL-HEADER}', $MHData, $MHtml);	
				$MHtml	 		= 	str_replace('{MAIL-BODY}', $MBData, $MHtml);
				$MHtml	 		= 	str_replace('{MAIL-FOOTER}', $MFData, $MHtml);
				//echo $MHtml; die;

				$email = new Mail();
				$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
				$email->setSubject($MTData['subject']);
				$email->addTo($userData['users_email'], $userData['users_name']);
				//$email->addBcc("test@example.com", "Example User");
				//$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
				$email->addContent("text/html",$MHtml);

				//$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				$sendgrid = new \SendGrid(SENDGRID_KEY);
				$response = $sendgrid->send($email);
				return true;
				// try {
				//     $response = $sendgrid->send($email);
				//     print $response->statusCode() . "\n";
				//     //print_r($response->headers());
				//     print $response->body() . "\n";
				// } catch (Exception $e) {
				//     echo 'Caught exception: '.  $e->getMessage(). "\n";
				// }
			endif;
			return true;
		endif;
	}

	/***********************************************************************
	** Function name : sendRegistrationMailToUser
	** Developed By : Dilip Halder
	** Purpose  : This function is using to send verification otp..
	** Date : 24 Apirl 2023
	************************************************************************/
	function sendRegistrationMailToUser($userData=array(),$otp='') {  

		if(!empty($userData['email']) || !empty($userData['users_email']) ):

			$MTData				=	$this->get_email_template_by_mail_type(100000000000001); 

			if($MTData <> ""):  
				//$activeAccountlink  	=	base_url('activate-account/'.base64_encode($userData['users_email']));
				#.............................. message section ............................#
				$MHData 		= 	stripslashes($MTData['mail_header']);
	
				$MBData 		= 	str_replace('{USERNAME}', $userData['fname'], stripslashes($MTData['mail_body']));
				
				$MBData         =   str_replace('{OTP}',$otp, $MBData);

				$MFData 		= 	stripslashes($MTData['mail_footer']);
	
				$MHtml	 		= 	stripslashes($MTData['html']);
				$MHtml	 		= 	str_replace('{MAIL-HEADER}', $MHData, $MHtml);	
				$MHtml	 		= 	str_replace('{MAIL-BODY}', $MBData, $MHtml);
				$MHtml	 		= 	str_replace('{MAIL-FOOTER}', $MFData, $MHtml);
				// echo $MHtml; die;

				$email = new Mail();
				$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
				$email->setSubject($MTData['subject']);
				if($userData['email']):
				$email->addTo($userData['email'], $userData['fname']);
			else:
				$email->addTo($userData['users_email'], $userData['fname']);
			endif;
				//$email->addBcc("test@example.com", "Example User");
				//$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
				$email->addContent("text/html",$MHtml);

				//$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				$sendgrid = new \SendGrid(SENDGRID_KEY);
				$response = $sendgrid->send($email);
				return true;
			endif;
			return true;
		endif;
	}

	function sendSuccessRegistrationMailToUser($userData=array()) {  
		if(!empty($userData['users_email'])):
			$MTData				=	$this->get_email_template_by_mail_type(100000000000003); 
			if($MTData <> ""):  
				#.............................. message section ............................#
				$MHData 		= 	stripslashes($MTData['mail_header']);
	
				$MBData 		= 	str_replace('{USERNAME}', $userData['users_name'], stripslashes($MTData['mail_body']));

				$MFData 		= 	stripslashes($MTData['mail_footer']);
	
				$MHtml	 		= 	stripslashes($MTData['html']);
				$MHtml	 		= 	str_replace('{MAIL-HEADER}', $MHData, $MHtml);	
				$MHtml	 		= 	str_replace('{MAIL-BODY}', $MBData, $MHtml);
				$MHtml	 		= 	str_replace('{MAIL-FOOTER}', $MFData, $MHtml);
				//echo $MHtml; die;

				$email = new Mail();
				$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
				$email->setSubject($MTData['subject']);
				$email->addTo($userData['users_email'], $userData['users_name']);
				//$email->addBcc("test@example.com", "Example User");
				//$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
				$email->addContent("text/html",$MHtml);

				//$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				$sendgrid = new \SendGrid(SENDGRID_KEY);
				$response = $sendgrid->send($email);
				return true;
			endif;
			return true;
		endif;
	}

	function sendSuccessResetPasswordMailToUser($userData=array()) {  
		if(!empty($userData['users_email'])):
			$MTData				=	$this->get_email_template_by_mail_type(100000000000003); 
			if($MTData <> ""):  
				#.............................. message section ............................#
				$MHData 		= 	stripslashes($MTData['mail_header']);
	
				
				$MFData 		= 	stripslashes($MTData['mail_footer']);
	
				$MHtml	 		= 	stripslashes($MTData['html']);
				$MHtml	 		= 	str_replace('{MAIL-HEADER}', $MHData, $MHtml);	
				$MHtml	 		= 	str_replace('{MAIL-BODY}', $MBData, $MHtml);
				$MHtml	 		= 	str_replace('{MAIL-FOOTER}', $MFData, $MHtml);
				//echo $MHtml; die;

				$email = new Mail();
				$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
				$email->setSubject($MTData['subject']);
				$email->addTo($userData['users_email'], $userData['users_name']);
				//$email->addBcc("test@example.com", "Example User");
				//$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
				$email->addContent("text/html",$MHtml);

				//$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				$sendgrid = new \SendGrid(SENDGRID_KEY);
				$response = $sendgrid->send($email);
				return true;
			endif;
			return true;
		endif;
	}
	//Sent Order Email
	function sendOrderMailToUser($oid = '') {  
		if(!empty($oid)):
			$tblName 				=	'da_orders';
			$shortField 			= 	array('_id'=> -1 );
			$whereCon['where']		= 	array('order_id'=>$oid);

			$orderData  =	$this->geneal_model->getordersList('single', $tblName, $whereCon,$shortField);
			$name = explode('@',$orderData['user_email']);
			//$MHtml = $this->load->view('order_mail_template', $data);
			//print_r($MHtml);die();
			$html = '<!DOCTYPE html>
					<html lang="en">

					<head>
						<title>Dealzerbia | mailtemplate</title>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<style>
						a:link, a:active {
							color: #000 !important;
							text-decoration: none;
						}
						a{
							color:#000 !important;
						}
						@media screen and (min-device-width: 360px) and (max-device-width: 600px) {
							body {
								background-color: #fff !important;
								margin: 0px;
							}
						}
						</style>
					</head>
					<body style="background-color: #1e1e1e;color: #010f19 !important;">
						<div style="width: 533px;margin: auto;background-color: #fff;padding: 19px 15px 19px;">
							<div class="row" style="margin: 0px 35px;">
								<div style="background: #fff;padding: 5px 15px 5px;border: 1px solid #d4d4d4;border-radius: 10px;margin: 0px 18px;text-align: center;">
									<img src="https://dealzarabia.com/assets/img/jpeg_logo.jpg" alt="logo">
								</div>
							</div>';
				$html .= '<div class="main_content" style="margin:39px 0px;text-align:center;">
				<h1 style="font-family: "sans-serif;font-size: 27px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Invoice </h1>
				<p style="font-family: "sans-serif;font-size: 18px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Hello '.$name[0].', Your Order Details</p>
			</div>
			<div class="main_content" style="margin:39px 0px;    margin: 39px 54px;border: 1px solid #80808061;padding: 4px 16px 15px;border-radius: 5px;">
				<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 11px 0px 0px;">
				<div style="width: 315px;">	
				<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;">
						Order Id</p>
						</div>
					<div>
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight:600;">
						'.$orderData['order_id'].'</p>
						</div>	
				</div>
				<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 0px 0px;">
				    <div style="width: 315px;">
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;
					margin-right: 247px;margin-top: 0px"> Order Date</p>
					</div>
					<div>
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;font-weight:600;margin-top: 0px"> '.date('d M, Y', strtotime($orderData['created_at'])).'</p>
				</div>
				</div>
			</div>';

			foreach ($orderData['order_details'] as $key => $value) {
				$html .= ' <div class="row" style="display: flex;justify-content: space-between;margin: 11px 57px;border: 1px solid #80808061;padding: 13px;">
						<div class="product_box">
							<div class="img">
								<img src="'.base_url().$value['other']['image'].'" style="width:100px;">
							</div>
						</div>
						<div class="product" style="margin: 0px 20px;">
							<h1 style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom: 1px;"> '.stripslashes($value['product_name']).'</h1>
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;ine-height: 20px;"> Qty : <span style="font-weight: 600;">'.$value['quantity'].'</span></p>
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;line-height: 20px;"> '.stripslashes($value['other']['description']).'</p>
						</div>
						<div class="product_details">
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;font-weight:600;text-align: end;margin: 12px 0px;"> AED'.number_format($value['subtotal'],2).'</p>
						</div>
					</div>
					'; 
			}

			$html .= '<div class="main_content" style="margin:0px 0px;">
            <div class="row" >
			     <div style="text-align: center;">
                <h1 style="font-family: "Open Sans", sans-serif;font-size: 18px;color: #343333;">Payment Information </h1>
				</div>
                <div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;"> Inclusice of VAT</p>
						</div>
						<div>
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['inclusice_of_vat'],2).'</p>
						</div>
                    </div>
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div  style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Subtotal</p>
						</div>
						<div>
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['inclusice_of_vat'],2).'</p>
						</div>
                    </div>';

            if($orderData['payment_mode'] == 'Arabian Points'){
				$html .='<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
				<div style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Arabian Point</p>
                        </div>
						<div>
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
                    </div>
						</div>';
			}else{
				$html .='<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
				<div style=" width: 324px;">	
				<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Card </p>
				</div>
				<div>	
				<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
				</div>
				</div>';
			}

                $html .= '</div>
            </div>
        </div>';
		$html .=	'<div class="main_content" style="margin:0px 0px;">
		<div class="row" >
			<div style="text-align: center;font-family: sans-serif;">
				<h1 style="font-family: sans-serif;;font-size: 18px;color: #343333;">Coupon Information </h1>
			</div>
			<div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">';
		$flag = 0;
		$count = count($orderData['coupon_details']);
		if($count > 5){
			$flag = 1;
		}

		$newCount = ($flag == 0) ? $count : 5;

		for ($i=0; $i < $newCount; $i++) { 


			$tblName 				=	'da_products';
			$shortField 			= 	array('_id'=> -1 );
			$whereCon['where']		= 	array('products_id'=>(int)$orderData['coupon_details'][$i]['product_id']);
			$ProductDetails  =	$this->geneal_model->getData2('single', $tblName, $whereCon,$shortField);

			// echo "<pre>";
			// print_r($ProductDetails);
			// die();


		$html .=	'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:285px">
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Product Name</p>
					</div>
					<div>
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.substr(stripslashes($orderData['coupon_details'][$i]['product_title']),0,15).'</p>
					</div>
				</div>

				<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:267px">	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Coupon Code</p>
					</div>
					<div>	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.stripcslashes($orderData['coupon_details'][$i]['coupon_code']).'</p>
					</div>
				</div>
				<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:267px">	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Draw Date</p>
					</div>
					<div>	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.date('d M, Y',strtotime($ProductDetails['draw_date'].' '.$ProductDetails['draw_time'])).'</p>
					</div>
				</div>';
		}
		$html .= '</div> </div> </div>'; 	

		$html .='<div class="main_content" style="margin: -13px 41px 0px;text-align:center;">
            <div class="row" style="border-bottom: 1px solid #80808030;">
                <div style="text-aling:center;font-family:sans-serif;font-size: 26px; ">
                    <p style="color: #343333;font-family: "Open Sans", sans-serif;font-size: 26px; font-weight: 600;text-align: center;font-size: 19px;margin:0px;">Customer Support Service</p>
                </div>
            </div>
            <div class="row" style="display:inline-flex;justify-content: space-around;margin: 24px 0px;">
                <div class="" style="display: flex;flex-direction: row;justify-content: center; align-items: cente;color: #343333;margin-right: 38px;">
                    <img src="'.base_url().'assets/img/phone-solid.jpg" alt="" style="width: 20px;">
                    <a href="tel:045541927" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">045541927</a>
                </div>
                <div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;color: #343333;">
                    <img src="'.base_url().'assets/img/envelope-solid.jpg" alt="" style="width: 20px;">
                    <a href="mailto:info@dealzarabia.com" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">info@dealzarabia.com</a>
                </div>
            </div>
            <div class="row" style="display:blockjustify-content:center;margin: 0px 0px;color:#000;">
                <div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;">
                    <img src="'.base_url().'assets/img/globe-solid.jpg" alt="" style="width: 20px;height: 10%;">
                  <p style="margin:0px;color:#000;!important;line-height: 0px;">  <a href="https://dealzarabia.com/" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">https://dealzarabia.com/</a><p>
                </div>
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div style="background-color: #d82b2b;color: #fff;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #d82b2b;
			color: #fff;
			">
                <p style="margin:0px;padding: 12px 76px;background-color: #d12929;
				color: #fff;">Copyright© '.date('Y').'</p>
            </div>
            </div>
		</body>

		</html>';
		
		// echo $html;die();
			if($html <> ""):  
				#.............................. message section ............................#
				try {

					if($orderData['user_email']):

						$email = new Mail();
						$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
						$email->setSubject('Thank You,Please find the invoice.');
						$email->addTo($orderData['user_email'], $name[0]);
						$email->addContent("text/html",$html);
						
						$sendgrid = new \SendGrid(SENDGRID_KEY);
						$response = $sendgrid->send($email);
						return true;

					// else:
					// 	throw new Exception('Email id required');
					endif;
					
				} catch (Exception $e) {
					return false;
				}
				
			endif;
			return true;
		endif;
	} // End of function
//****************************************
//****************************************
//************* TEST ********************
//****************************************
//****************************************
	//Test order email
	function sendOrderMailToUser_TEST($oid = '') {  
		if(!empty($oid)):
			$tblName 				=	'da_orders';
			$shortField 			= 	array('_id'=> -1 );
			$whereCon['where']		= 	array('order_id'=>$oid);

			$orderData  =	$this->geneal_model->getordersList('single', $tblName, $whereCon,$shortField);
			$name = explode('@',$orderData['user_email']);
			//$MHtml = $this->load->view('order_mail_template', $data);
			//print_r($MHtml);die();
			$html = '<!DOCTYPE html>
					<html lang="en">

					<head>
						<title>Dealzerbia | mailtemplate</title>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<style>
						a:link, a:active {
							color: #000 !important;
							text-decoration: none;
						}
						a{
							color:#000 !important;
						}
						@media screen and (min-device-width: 360px) and (max-device-width: 600px) {
							body {
								background-color: #fff !important;
								margin: 0px;
							}
						}
						</style>
					</head>
					<body style="background-color: #1e1e1e;color: #010f19 !important;">
						<div style="width: 533px;margin: auto;background-color: #fff;padding: 19px 15px 19px;">
							<div class="row" style="margin: 0px 35px;">
								<div style="background: #fff;padding: 5px 15px 5px;border: 1px solid #d4d4d4;border-radius: 10px;margin: 0px 18px;text-align: center;">
									<img src="https://dealzarabia.com/assets/img/jpeg_logo.jpg" alt="logo">
								</div>
							</div>';
				$html .= '<div class="main_content" style="margin:39px 0px;text-align:center;">
				<h1 style="font-family: "sans-serif;font-size: 27px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Invoice </h1>
				<p style="font-family: "sans-serif;font-size: 18px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Hello '.$name[0].', Your Order Details</p>
			</div>
			<div class="main_content" style="margin:39px 0px;    margin: 39px 54px;border: 1px solid #80808061;padding: 4px 16px 15px;border-radius: 5px;">
				<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 11px 0px 0px;">
				<div style="width: 315px;">	
				<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;">
						Order Id</p>
						</div>
					<div>
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight:600;">
						'.$orderData['order_id'].'</p>
						</div>	
				</div>
				<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 0px 0px;">
				    <div style="width: 315px;">
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;
					margin-right: 247px;margin-top: 0px"> Order Date</p>
					</div>
					<div>
					<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;font-weight:600;margin-top: 0px"> '.date('d M, Y', strtotime($orderData['created_at'])).'</p>
				</div>
				</div>
			</div>';

			foreach ($orderData['order_details'] as $key => $value) {
				$html .= ' <div class="row" style="display: flex;justify-content: space-between;margin: 11px 57px;border: 1px solid #80808061;padding: 13px;">
						<div class="product_box">
							<div class="img">
								<img src="'.base_url().$value['other']['image'].'" style="width:100px;">
							</div>
						</div>
						<div class="product" style="margin: 0px 20px;">
							<h1 style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom: 1px;"> '.stripslashes($value['product_name']).'</h1>
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;ine-height: 20px;"> Qty : <span style="font-weight: 600;">'.$value['quantity'].'</span></p>
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;line-height: 20px;"> '.stripslashes($value['other']['description']).'</p>
						</div>
						<div class="product_details">
							<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;font-weight:600;text-align: end;margin: 12px 0px;"> AED'.number_format($value['subtotal'],2).'</p>
						</div>
					</div>
					'; 
			}

			$html .= '<div class="main_content" style="margin:0px 0px;">
            <div class="row" >
			     <div style="text-align: center;">
                <h1 style="font-family: "Open Sans", sans-serif;font-size: 18px;color: #343333;">Payment Information </h1>
				</div>
                <div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;"> Inclusice of VAT</p>
						</div>
						<div>
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['inclusice_of_vat'],2).'</p>
						</div>
                    </div>
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div  style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Subtotal</p>
						</div>
						<div>
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['subtotal'],2).'</p>
						</div>
                    </div>
                    <div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
					<div  style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> VAT</p>
                     </div>
					 <div>   
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED'.number_format($orderData['vat_amount'],2).'</p>
                    </div>
					
						</div>';

            if($orderData['payment_mode'] == 'Arabian Points'){
				$html .='<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
				<div style=" width: 324px;">
                        <p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Arabian Point</p>
                        </div>
						<div>
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
                    </div>
						</div>';
			}else{
				$html .='<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
				<div style=" width: 324px;">	
				<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Card </p>
				</div>
				<div>	
				<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
				</div>
				</div>';
			}

                $html .= '</div>
            </div>
        </div>';
		$html .=	'<div class="main_content" style="margin:0px 0px;">
		<div class="row" >
			<div style="text-align: center;font-family: sans-serif;">
				<h1 style="font-family: sans-serif;;font-size: 18px;color: #343333;">Coupon Information </h1>
			</div>
			<div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">';
		$flag = 0;
		$count = count($orderData['coupon_details']);
		if($count > 5){
			$flag = 1;
		}

		$newCount = ($flag == 0) ? $count : 5;

		for ($i=0; $i < $newCount; $i++) { 


			$tblName 				=	'da_products';
			$shortField 			= 	array('_id'=> -1 );
			$whereCon['where']		= 	array('products_id'=>(int)$orderData['coupon_details'][$i]['product_id']);
			$ProductDetails  =	$this->geneal_model->getData2('single', $tblName, $whereCon,$shortField);

			// echo "<pre>";
			// print_r($ProductDetails);
			// die();


		$html .=	'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:285px">
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Product Name</p>
					</div>
					<div>
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.substr(stripslashes($orderData['coupon_details'][$i]['product_title']),0,15).'</p>
					</div>
				</div>

				<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:267px">	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Coupon Code</p>
					</div>
					<div>	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.stripcslashes($orderData['coupon_details'][$i]['coupon_code']).'</p>
					</div>
				</div>
				<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
					<div style="width:267px">	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Draw Date</p>
					</div>
					<div>	
						<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.date('M d, Y h:i A',strtotime($ProductDetails['draw_date'].' '.$ProductDetails['draw_time'])).'</p>
					</div>
				</div>';
		}
		$html .= '</div> </div> </div>'; 	

		$html .='<div class="main_content" style="margin: -13px 41px 0px;text-align:center;">
            <div class="row" style="border-bottom: 1px solid #80808030;">
                <div style="text-aling:center;font-family:sans-serif;font-size: 26px; ">
                    <p style="color: #343333;font-family: "Open Sans", sans-serif;font-size: 26px; font-weight: 600;text-align: center;font-size: 19px;margin:0px;">Customer Support Service</p>
                </div>
            </div>
            <div class="row" style="display:inline-flex;justify-content: space-around;margin: 24px 0px;">
                <div class="" style="display: flex;flex-direction: row;justify-content: center; align-items: cente;color: #343333;margin-right: 38px;">
                    <img src="'.base_url().'assets/img/phone-solid.jpg" alt="" style="width: 20px;">
                    <a href="tel:045541927" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">045541927</a>
                </div>
                <div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;color: #343333;">
                    <img src="'.base_url().'assets/img/envelope-solid.jpg" alt="" style="width: 20px;">
                    <a href="mailto:info@dealzarabia.com" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">info@dealzarabia.com</a>
                </div>
            </div>
            <div class="row" style="display:blockjustify-content:center;margin: 0px 0px;color:#000;">
                <div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;">
                    <img src="'.base_url().'assets/img/globe-solid.jpg" alt="" style="width: 20px;height: 10%;">
                  <p style="margin:0px;color:#000;!important;line-height: 0px;">  <a href="https://dealzarabia.com/" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">https://dealzarabia.com/</a><p>
                </div>
            </div>
        </div>
        <div class="row" style="text-align: center;">
            <div style="background-color: #d82b2b;color: #fff;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #d82b2b;
			color: #fff;
			">
                <p style="margin:0px;padding: 12px 76px;background-color: #d12929;
				color: #fff;">Copyright© '.date('Y').'</p>
            </div>
            </div>
		</body>

		</html>';
		
		// echo $html;die();
			if($html <> ""):  
				#.............................. message section ............................#
				try {

					if($orderData['user_email']):

						$email = new Mail();
						$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
						$email->setSubject('Thank You,Please find the invoice.');
						$email->addTo($orderData['user_email'], $name[0]);
						$email->addContent("text/html",$html);
						
						$sendgrid = new \SendGrid(SENDGRID_KEY);
						$response = $sendgrid->send($email);
						return true;

					// else:
					// 	throw new Exception('Email id required');
					endif;
					
				} catch (Exception $e) {
					return false;
				}
				
			endif;
			return true;
		endif;
	}


	/* * *********************************************************************
	 * * Function name : sendQuickMailToUser
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for  Quick Mail To User
	 * * Date : 13 Apirl 2023
	 * * **********************************************************************/
	// function sendQuickMailToUser($UserData=array()) {  

	// 	if(!empty($UserData['order_users_email'])):
			
	// 		if($UserData <> ""):  
			
	// 			// product id 
	// 			$product_id = $UserData['product_id'];
	// 			$tbl 					=	'da_products';
	// 			$where 					=	['products_id' => (int)$product_id ];
	// 			$productData		=	$this->geneal_model->getData($tbl, $where,[]);
	// 			$ticket_order_id 	= 	$UserData['ticket_order_id'];
	// 			$pdfDownload_path = base_url('/').'downloadQuickInvoice/'.$ticket_order_id;

				
	// 			$draw_date 		  = $productData[0]['draw_date'];
	// 			$couponData = implode(',',$UserData['coupon_code']) ;
				
	// 			$subject = 'Quick Product purchase';
	// 			$message		=	"Your ticket number is $couponData , Draw date $draw_date. Thank You for purchasing. Download Order pdf : $pdfDownload_path ";

	// 			$users_name = $UserData['order_first_name']. ' ' . $UserData['order_last_name'];

	// 			$email = new Mail();
	// 			$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
	// 			$email->setSubject('Thank You for purchasing. Your order no is'.$UserData['ticket_order_id']);
	// 			$email->addTo($UserData['order_users_email'], $users_name);
	// 			// $email->addCc("afsarali509@gmail.com", "Quick Buy test");
	// 			//$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
	// 			$email->addContent("text/html",$message);

	// 			//$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
	// 			$sendgrid = new \SendGrid(SENDGRID_KEY);
	// 			$response = $sendgrid->send($email);
	// 			// return $response;
	// 			return true;
	// 			// try {
	// 			//     $response = $sendgrid->send($email);
	// 			//     print $response->statusCode() . "\n";
	// 			//     //print_r($response->headers());
	// 			//     print $response->body() . "\n";
	// 			// } catch (Exception $e) {
	// 			//     echo 'Caught exception: '.  $e->getMessage(). "\n";
	// 			// }

	// 		endif;
	// 		return true;
	// 	endif;
	// }
	/* * *********************************************************************
	 * * Function name : sendQuickMailToUser
	 * * Developed By : Afar Ali
	 * * Purpose  : This function used for  Quick Mail To User
	 * * Date : 17 MAY 2023
	 * * **********************************************************************/
	function sendQuickMailToUser($order_id = '') {  

		if($order_id != ''):
			$tbl1 				=	'da_ticket_orders';
			$where1['where'] 	=	['ticket_order_id' => $order_id ];
			$orderData			=	$this->geneal_model->getData2('single',$tbl1, $where1,[]);

			$tbl2 				=	'da_ticket_coupons';
			$where2['where'] 	=	['ticket_order_id' => $order_id ];
			$ticketCoupons		=	$this->geneal_model->getData2('single',$tbl2, $where2,[]);
			// print_r($ticketCoupons);die();


			if($orderData <> ""):  
				
				// product id 
				$product_id = $orderData['product_id'];
				$tbl3 				=	'da_products';
				$where3['where']		=	['products_id' => (int)$product_id ];
				$productData		=	$this->geneal_model->getData2('single',$tbl3, $where3,[]);
				// print_r($productData);die();
				
				$pdfDownload_path = base_url('/').'downloadQuickInvoice/'.$order_id;

				// htmal varriable
				$name 				=	$orderData['order_first_name'].' '.$orderData['order_last_name'];

				$draw_date 		  = $productData['draw_date'];
				$couponData = implode(',',$ticketCoupons['coupon_code']) ;
				// end
				$subject = 'Quick Product purchase';

				$html	=	'<!DOCTYPE html>
							<html lang="en">
								<head>
									<title>Dealzerbia | mailtemplate</title>
									<meta charset="utf-8">
									<meta name="viewport" content="width=device-width, initial-scale=1">
									<style>
										a:link, a:active {
											color: #000 !important;
											text-decoration: none;
										}
										a{
											color:#000 !important;
										}
										@media screen and (min-device-width: 360px) and (max-device-width: 600px) {
											body {
												background-color: #fff !important;
												margin: 0px;
											}
										}
									</style>
								</head>
								<body style="background-color: #1e1e1e;color: #010f19 !important;">
									<div style="width: 533px;margin: auto;background-color: #fff;padding: 19px 15px 19px;">
										<div class="row" style="margin: 0px 35px;">
											<div style="background: #fff;padding: 5px 15px 5px;border: 1px solid #d4d4d4;border-radius: 10px;margin: 0px 18px;text-align: center;">
												<img src="https://dealzarabia.com/assets/img/white-logo.png" alt="logo">
											</div>
										</div>';
				$html	.= 				'<div class="main_content" style="margin:39px 0px;text-align:center;">
											<h1 style="font-family: "sans-serif;font-size: 27px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Invoice </h1>
											<p style="font-family: "sans-serif;font-size: 18px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Hello '.$name.', Your Order Details</p>
										</div>
										<div class="main_content" style="margin:39px 0px;    margin: 39px 54px;border: 1px solid #80808061;padding: 4px 16px 15px;border-radius: 5px;">
											<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 11px 0px 0px;">
												<div style="width: 315px;">	
													<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;">Order Id</p>
												</div>
											<div>
												<p style=" margin-left: -35px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight:600;">'.$orderData['ticket_order_id'].'</p>
											</div>	
										</div>
										
										<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 0px 0px;">
				    						<div style="width: 315px;">
												<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;margin-right: 247px;margin-top: 0px"> Order Date</p>
											</div>
											<div>
												<p style=" margin-left: -45px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;font-weight:600;margin-top: 0px"> '.date('d M, Y', strtotime($orderData['created_at'])).'</p>
											</div>
										</div>

										<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 0px 0px;">
				    						<div style="width: 315px;">
												<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;margin-right: 247px;margin-top: 0px"> Draw Date</p>
											</div>
											<div>
												<p style=" margin-left: -45px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;font-weight:600;margin-top: 0px"> '.date('d M, Y', strtotime($productData['draw_date'])).'</p>
											</div>
										</div>
									</div>';
		
				$html	.= 			'<div class="row" style="display: flex;justify-content: space-between;margin: 11px 57px;border: 1px solid #80808061;padding: 13px;">
										<div class="product_box">
											<div class="img">
												<img src="'.base_url().$productData['product_image'].'" style="width:100px;">
											</div>
										</div>
										<div class="product" style="margin: 0px 20px;">
											<h1 style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom: 1px;"> '.stripslashes($productData['title']).'</h1>
											<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;ine-height: 20px;"> Qty : <span style="font-weight: 600;">'.$orderData['product_qty'].'</span></p>
											<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;line-height: 20px;"> '.stripslashes($productData['description']).'</p>
										</div>
										<div class="product_details">
											<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;font-weight:600;text-align: end;margin: 12px 0px;"> AED'.number_format($orderData['total_price'],2).'</p>
										</div>
									</div>'; 
		

				$html	.=				'<div class="main_content" style="margin:0px 0px;">
											<div class="row" >
												<div style="text-align: center;">
													<h1 style="font-family: "Open Sans", sans-serif;font-size: 18px;color: #343333;">Payment Information </h1>
												</div>
												<div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">
													
													<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
														<div  style=" width: 324px;">
															<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Subtotal</p>
														</div>
													<div>
														<p style="margin-left: -35px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
													</div>
												</div>
												';
        		if($orderData['payment_mode'] == 'Arabian Points'){
				$html	.=						'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
													<div style=" width: 324px;">
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Arabian Point</p>
													</div>
													<div>
														<p style="margin-left: -35px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
													</div>
												</div>';
				}else{
				$html	.=							'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
													<div style=" width: 324px;">	
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Card </p>
													</div>
													<div>	
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
													</div>
												</div>';
				}
				$html	.=						'</div>
										</div>
									</div>';
				$html .=				'<div class="main_content" style="margin:0px 0px;">
										<div class="row" >
											<div style="text-align: center;font-family: sans-serif;">
												<h1 style="font-family: sans-serif;;font-size: 18px;color: #343333;">Ticket Information </h1>
											</div>
											<div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">
											<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
												<div style="width:285px">
													<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Ticket No.</p>
												</div>
												<div>
													<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> Ticket Code</p>
												</div>
											</div>';
				$flag = 0;
				$count = count($ticketCoupons['coupon_code']);

				$j=1;
				for ($i=0; $i < $count; $i++) { 
				$html .=						'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
													<div style="width:267px">	
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> '. $j++.'.</p>
													</div>
													<div>	
														<p style="margin-left: 30px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.stripcslashes($ticketCoupons['coupon_code'][$i]).'</p>
													</div>
												</div>';
				}
				
				$html	.=					'</div> 
										</div> 
									</div>'; 	
				$html .= 			'<div class="row" style="text-align: center;">
										<div style="background-color: #d82b2b;color: #fff;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #d82b2b;color: #fff;">
											<a href="'.$pdfDownload_path.'"><p style="margin:0px;padding: 12px 76px;background-color: #d12929;color: #fff;">Download Invoice</p></a>
										</div>
									</div>';
				$html	.=			'<div class="main_content" style="margin: -13px 41px 0px;text-align:center;">
										<div class="row" style="border-bottom: 1px solid #80808030;">
											<div style="text-aling:center;font-family:sans-serif;font-size: 26px; ">
												<p style="color: #343333;font-family: "Open Sans", sans-serif;font-size: 26px; font-weight: 600;text-align: center;font-size: 19px;margin:0px;">Customer Support Service</p>
											</div>
										</div>
										<div class="row" style="display:inline-flex;justify-content: space-around;margin: 24px 0px;">
											<div class="" style="display: flex;flex-direction: row;justify-content: center; align-items: cente;color: #343333;margin-right: 38px;">
												<img src="'.base_url().'assets/img/phone-solid.jpg" alt="" style="width: 20px;">
												<a href="tel:045541927" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">045541927</a>
											</div>
											<div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;color: #343333;">
												<img src="'.base_url().'assets/img/envelope-solid.jpg" alt="" style="width: 20px;">
												<a href="mailto:info@dealzarabia.com" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">info@dealzarabia.com</a>
											</div>
										</div>
										<div class="row" style="display:blockjustify-content:center;margin: 0px 0px;color:#000;">
											<div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;">
												<img src="'.base_url().'assets/img/globe-solid.jpg" alt="" style="width: 20px;height: 10%;">
											<p style="margin:0px;color:#000;!important;line-height: 0px;">  <a href="https://dealzarabia.com/" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">https://dealzarabia.com/</a><p>
											</div>
										</div>
        							</div>
        							<div class="row" style="text-align: center;">
            							<div style="background-color: #d82b2b;color: #fff;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #d82b2b;color: #fff;">
                							<p style="margin:0px;padding: 12px 76px;background-color: #d12929;color: #fff;">Copyright© '.date('Y').'</p>
            							</div>
            						</div>
								</body>
							</html>';

				$users_name = $orderData['order_first_name']. ' ' . $orderData['order_last_name'];

				$email = new Mail();
				$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
				$email->setSubject('Thank You for purchasing. Your order no is'.$orderData['ticket_order_id']);
				$email->addTo($orderData['order_users_email'], $users_name);
				// $email->addCc("afsarali509@gmail.com", "Quick Buy test");
				//$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
				$email->addContent("text/html",$html);

				//$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				$sendgrid = new \SendGrid(SENDGRID_KEY);
				$response = $sendgrid->send($email);
				// return $response;
				return true;
				// try {
				//     $response = $sendgrid->send($email);
				//     print $response->statusCode() . "\n";
				//     //print_r($response->headers());
				//     print $response->body() . "\n";
				// } catch (Exception $e) {
				//     echo 'Caught exception: '.  $e->getMessage(). "\n";
				// }

			endif;
			return true;
		endif;
	}

	function sendQuickMailToUser_test($order_id = '') {  
		if($order_id != ''):
			$tbl1 				=	'da_ticket_orders';
			$where1['where'] 	=	['ticket_order_id' => $order_id ];
			$orderData			=	$this->geneal_model->getData2('single',$tbl1, $where1,[]);

			$tbl2 				=	'da_ticket_coupons';
			$where2['where'] 	=	['ticket_order_id' => $order_id ];
			$ticketCoupons		=	$this->geneal_model->getData2('single',$tbl2, $where2,[]);
			// print_r($ticketCoupons);die();


			if($orderData <> ""):  
				
				// product id 
				$product_id = $orderData['product_id'];
				$tbl3 				=	'da_products';
				$where3['where']		=	['products_id' => (int)$product_id ];
				$productData		=	$this->geneal_model->getData2('single',$tbl3, $where3,[]);
				// print_r($productData);die();
				
				$pdfDownload_path = base_url('/').'downloadQuickInvoice/'.$order_id;

				// htmal varriable
				$name 				=	$orderData['order_first_name'].' '.$orderData['order_last_name'];

				$draw_date 		  = $productData['draw_date'];
				$couponData = implode(',',$ticketCoupons['coupon_code']) ;
				// end
				$subject = 'Quick Product purchase';

				$html	=	'<!DOCTYPE html>
							<html lang="en">
								<head>
									<title>Dealzerbia | mailtemplate</title>
									<meta charset="utf-8">
									<meta name="viewport" content="width=device-width, initial-scale=1">
									<style>
										a:link, a:active {
											color: #000 !important;
											text-decoration: none;
										}
										a{
											color:#000 !important;
										}
										@media screen and (min-device-width: 360px) and (max-device-width: 600px) {
											body {
												background-color: #fff !important;
												margin: 0px;
											}
										}
									</style>
								</head>
								<body style="background-color: #1e1e1e;color: #010f19 !important;">
									<div style="width: 533px;margin: auto;background-color: #fff;padding: 19px 15px 19px;">
										<div class="row" style="margin: 0px 35px;">
											<div style="background: #fff;padding: 5px 15px 5px;border: 1px solid #d4d4d4;border-radius: 10px;margin: 0px 18px;text-align: center;">
												<img src="https://dealzarabia.com/assets/img/white-logo.png" alt="logo">
											</div>
										</div>';
				$html	.= 				'<div class="main_content" style="margin:39px 0px;text-align:center;">
											<h1 style="font-family: "sans-serif;font-size: 27px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Invoice </h1>
											<p style="font-family: "sans-serif;font-size: 18px;color: #343333;text-align: center;margin: 0px;line-height: 0px;"> Hello '.$name.', Your Order Details</p>
										</div>
										<div class="main_content" style="margin:39px 0px;    margin: 39px 54px;border: 1px solid #80808061;padding: 4px 16px 15px;border-radius: 5px;">
											<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 11px 0px 0px;">
												<div style="width: 315px;">	
													<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;margin-top: 0px;">Order Id</p>
												</div>
											<div>
												<p style=" margin-left: -35px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight:600;">'.$orderData['ticket_order_id'].'</p>
											</div>	
										</div>
										<div class="row" style="border-bottom: 1px solid #80808030;display: inline-flex;justify-content: space-between;margin: 0px 0px;">
				    						<div style="width: 315px;">
												<p style=" margin-left: 10px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;margin-right: 247px;margin-top: 0px"> Order Date</p>
											</div>
											<div>
												<p style=" margin-left: -35px !important;margin:0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;font-weight:600;margin-top: 0px"> '.date('d M, Y', strtotime($orderData['created_at'])).'</p>
											</div>
										</div>
									</div>';
		
				$html	.= 			'<div class="row" style="display: flex;justify-content: space-between;margin: 11px 57px;border: 1px solid #80808061;padding: 13px;">
										<div class="product_box">
											<div class="img">
												<img src="'.base_url().$productData['product_image'].'" style="width:100px;">
											</div>
										</div>
										<div class="product" style="margin: 0px 20px;">
											<h1 style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom: 1px;"> '.stripslashes($productData['title']).'</h1>
											<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin:0px;ine-height: 20px;"> Qty : <span style="font-weight: 600;">'.$orderData['product_qty'].'</span></p>
											<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin: 0px;line-height: 20px;"> '.stripslashes($productData['description']).'</p>
										</div>
										<div class="product_details">
											<p style="font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;font-weight:600;text-align: end;margin: 12px 0px;"> AED'.number_format($orderData['total_price'],2).'</p>
										</div>
									</div>'; 
		

				$html	.=				'<div class="main_content" style="margin:0px 0px;">
											<div class="row" >
												<div style="text-align: center;">
													<h1 style="font-family: "Open Sans", sans-serif;font-size: 18px;color: #343333;">Payment Information </h1>
												</div>
												<div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">
													
													<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
														<div  style=" width: 324px;">
															<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Subtotal</p>
														</div>
													<div>
														<p style="margin-left: -35px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
													</div>
												</div>
												';
        if($orderData['payment_mode'] == 'Arabian Points'){
				$html	.=						'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
													<div style=" width: 324px;">
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Arabian Point</p>
													</div>
													<div>
														<p style="margin-left: -35px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
													</div>
												</div>';
		}else{
			$html	.=							'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px 0px;border-bottom: 1px solid #80808030;">
													<div style=" width: 324px;">	
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;width: 324px;margin-top: 0px;"> Paid Using Card </p>
													</div>
													<div>	
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;margin-top: 0px;"> AED '.number_format($orderData['total_price'],2).'</p>
													</div>
												</div>';
		}
			$html	.=						'</div>
										</div>
									</div>';
			$html .=				'<div class="main_content" style="margin:0px 0px;">
										<div class="row" >
											<div style="text-align: center;font-family: sans-serif;">
												<h1 style="font-family: sans-serif;;font-size: 18px;color: #343333;">Ticket Information </h1>
											</div>
											<div class="main_content" style="margin: 29px 54px;border: 1px solid #80808061;padding: 4px 16px;border-radius: 5px;">
											<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
												<div style="width:285px">
													<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> Ticket No.</p>
												</div>
												<div>
													<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> Ticket Code</p>
												</div>
											</div>';
$flag = 0;
$count = count($ticketCoupons['coupon_code']);

$j=1;
for ($i=0; $i < $count; $i++) { 
				$html .=						'<div class="row" style="display: inline-flex;justify-content: space-between;margin: 0px;border-bottom: 1px solid #80808030;">
													<div style="width:267px">	
														<p style="margin-left: 10px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;"> '. $j++.'.</p>
													</div>
													<div>	
														<p style="margin-left: 30px !important;margin: 0px;font-family: "Open Sans", sans-serif;font-size: 16px;color: #343333;margin-bottom:7px;font-weight: 600;"> '.stripcslashes($ticketCoupons['coupon_code'][$i]).'</p>
													</div>
												</div>';
}
				
				$html	.=					'</div> 
										</div> 
									</div>'; 	
				$html .= 			'<div class="row" style="text-align: center;">
										<div style="background-color: #d82b2b;color: #fff;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #d82b2b;color: #fff;">
											<a href="'.$pdfDownload_path.'"><p style="margin:0px;padding: 12px 76px;background-color: #d12929;color: #fff;">Download Invoice</p></a>
										</div>
									</div>';
				$html	.=			'<div class="main_content" style="margin: -13px 41px 0px;text-align:center;">
										<div class="row" style="border-bottom: 1px solid #80808030;">
											<div style="text-aling:center;font-family:sans-serif;font-size: 26px; ">
												<p style="color: #343333;font-family: "Open Sans", sans-serif;font-size: 26px; font-weight: 600;text-align: center;font-size: 19px;margin:0px;">Customer Support Service</p>
											</div>
										</div>
										<div class="row" style="display:inline-flex;justify-content: space-around;margin: 24px 0px;">
											<div class="" style="display: flex;flex-direction: row;justify-content: center; align-items: cente;color: #343333;margin-right: 38px;">
												<img src="'.base_url().'assets/img/phone-solid.jpg" alt="" style="width: 20px;">
												<a href="tel:045541927" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">045541927</a>
											</div>
											<div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;color: #343333;">
												<img src="'.base_url().'assets/img/envelope-solid.jpg" alt="" style="width: 20px;">
												<a href="mailto:info@dealzarabia.com" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color: #343333 !important;">info@dealzarabia.com</a>
											</div>
										</div>
										<div class="row" style="display:blockjustify-content:center;margin: 0px 0px;color:#000;">
											<div class="" style="display:inline-flex;flex-direction: row;justify-content: center; align-items: center;">
												<img src="'.base_url().'assets/img/globe-solid.jpg" alt="" style="width: 20px;height: 10%;">
											<p style="margin:0px;color:#000;!important;line-height: 0px;">  <a href="https://dealzarabia.com/" style=" margin-left: 10px !important;margin:0px;font-family: sans-serif;font-size: 16px;color:#000;">https://dealzarabia.com/</a><p>
											</div>
										</div>
        							</div>
        							<div class="row" style="text-align: center;">
            							<div style="background-color: #d82b2b;color: #fff;font-family: "Open Sans", sans-serif;font-size: 16px; font-weight: 400;text-align: center;width:50%;background-color: #d82b2b;color: #fff;">
                							<p style="margin:0px;padding: 12px 76px;background-color: #d12929;color: #fff;">Copyright© '.date('Y').'</p>
            							</div>
            						</div>
								</body>
							</html>';
			

				
				$users_name = $orderData['order_first_name']. ' ' . $orderData['order_last_name'];

				$email = new Mail();
				$email->setFrom(MAIL_FROM_MAIL, MAIL_SITE_FULL_NAME);
				$email->setSubject('Thank You for purchasing. Your order no '.$orderData['ticket_order_id']);
				$email->addTo('afsarali509@gmail.com', $users_name);
				// $email->addCc("afsarali509@gmail.com", "Quick Buy test");
				//$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
				$email->addContent("text/html",$html);

				//$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				$sendgrid = new \SendGrid(SENDGRID_KEY);
				$response = $sendgrid->send($email);
				return true;
				// try {
				//     $response = $sendgrid->send($email);
				//     print $response->statusCode() . "\n";
				//     //print_r($response->headers());
				//     print $response->body() . "\n";
				// } catch (Exception $e) {
				//     echo 'Caught exception: '.  $e->getMessage(). "\n";
				// }

			endif;
			return true;
		endif;
	}

}	
?>