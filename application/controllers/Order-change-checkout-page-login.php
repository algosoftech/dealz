<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Order extends My_Head {
public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model('geneal_model');
		$this->lang->load('statictext','front');
	} 
	/***********************************************************************
	** Function name 	: index
	** Developed By 	: RAVI NEGI
	** Purpose 			: This function used for placed order
	** Date 			: 04 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 		
	public function index()
	{  
		$data  = array();
		$data['page']			=	'Checkout';
		$finalPrice = 0;
		$data['sufficient_point_error'] =  '';
		$data['post_sufficient_point_error'] =  '';

		$where               =      ['users_id' => (int)$this->session->userdata('DZL_USERID') ];
		$user_data           =      $this->geneal_model->getOnlyOneData('da_users', $where);
		$where2              =      ['user_id' => (int)$this->session->userdata('DZL_USERID') ];
		$user_address        =      $this->geneal_model->getData('da_diliveryAddress', $where2,[]);
		$data['ToatlPoints'] =      $user_data['availableArabianPoints'];
		$data['dilivertAddress'] = 	$user_address;
		
		if($this->session->userdata('DZL_USERID')){
			$wcon['where']		=	[ 'user_id' => (int)$this->session->userdata('DZL_USERID') ];
			$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
			foreach($cartItems as $CA):
				$finalPrice += $CA['qty'] * $CA['price'];
				//check Stock
				$stockCheck = $this->geneal_model->getStock($CA['id'], $CA['qty']);
			endforeach;
			$data['finalprice'] = $finalPrice;
			$data['shipping']   = 0;
			$data['finaltotal'] = $finalPrice + $data['shipping'];

			if($data['finaltotal'] > $data['ToatlPoints']){
			    $data['sufficient_point_error'] =  "You don't have sufficient Arabian points";
			}
		}else{
			redirect('login');
		}

		if($this->input->post('placeOrder') == 'YES'){
			if($stockCheck == 0){
				$this->session->set_flashdata('error', lang('OUTOFSTOCK'));
		        redirect('user-cart');
			}elseif ($stockCheck <> 1) {
				$this->session->set_flashdata('error', lang('PRO_QTY'));
		        redirect('user-cart');
			}
			if($this->input->post('payment_method') == 'arabianpoint'){

				if($data['finaltotal'] > $data['ToatlPoints']){
				    //$this->session->set_flashdata('sufficient_point_error', 'You have not sufficient Arabian points');
				    //redirect('checkout');
				    $data['post_sufficient_point_error'] =  "You don't have sufficient Arabian points";
				}else{
					$shippingAddress					=	explode('_____', $this->input->post('address'));
					/* Order Place Table */
			        $ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
			        $ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
			        $ORparam["user_id"] 				=	(int)$this->session->userdata('DZL_USERID');
			        $ORparam["user_type"] 				=	$this->session->userdata('DZL_USERSTYPE');
			        $ORparam["user_email"] 				=	$this->session->userdata('DZL_USEREMAIL');
			        $ORparam["user_phone"] 				=	$this->session->userdata('DZL_USERMOBILE');
			        $ORparam["shipping_address"] 		=	$shippingAddress[1];//$this->input->post('address');
			        $ORparam["shipping_charge"] 		=	(float)$this->input->post('shipping_charge');
			        $ORparam["inclusice_of_vat"] 		=	(float)$this->input->post('inclusice_of_vat');
			        $ORparam["subtotal"] 				=	(float)$this->input->post('subtotal');
			        $ORparam["vat_amount"] 				=	(float)$this->input->post('vat_amount');
			        $ORparam["total_price"] 			=	(float)$data['finaltotal'];
				    $ORparam["payment_mode"] 			=	'Arabian Points';
				    $ORparam["payment_from"] 			=	'Web';
				    $ORparam["order_status"] 			=	"Process";
				    $ORparam["creation_ip"] 			=	$this->input->ip_address();
				    $ORparam["created_at"] 				=	date('Y-m-d H:i');
				    $orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);

				    if($orderInsertID):
				        foreach($cartItems as $CA):	
							$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
							$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
							$ORDparam["order_id"]			=	$ORparam["order_id"];
							$ORDparam["user_id"]			=	(int)$CA['user_id'];
							$ORDparam["product_id"] 		=	(int)$CA['id'];
							$ORDparam["product_name"] 		=	$CA['name'];
							$ORDparam["quantity"] 		    =	(int)$CA['qty'];
							$ORDparam["price"] 		        =	(float)$CA['price'];
							$ORDparam["tax"] 		        =	(float)0;
							$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
							$ORDparam["is_donated"] 		=	$CA['is_donated'];
							$ORDparam["other"] 		        =	array(
																		'image' 		=>	$CA['other']->image,
																		'description' 	=>	$CA['other']->description,
																		'aed'			=>	$CA['other']->aed
																	);
							$this->geneal_model->addData('da_orders_details', $ORDparam);
						endforeach;
					endif;
					redirect('order-success/'.$ORparam["order_id"]);
			    	/* End */
				}
			}else{
				$shippingAddress					=	explode('_____', $this->input->post('address'));
				$this->session->set_userdata(array(
													'payment_shipping_address_id' => $shippingAddress[0],
													'payment_shipping_address' => $shippingAddress[1],
													'payment_shipping_charge' => $this->input->post('shipping_charge'),
													'payment_inclusice_of_vat' => $this->input->post('inclusice_of_vat'),
													'payment_subtotal' => $this->input->post('subtotal'),
													'payment_vat_amount' => $this->input->post('vat_amount'),
													'payment_total_price' => $data['finaltotal']
													));
				redirect('payment');
			}
		}

		$this->load->view('checkout', $data);
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: payment
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for payment
	** Date 			: 08 JULY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 		
	public function payment()
	{  
		$this->load->library('ccvalidation');
		$data  = array();
		$data['page']			=	'Payment';
		$finalPrice = 0;
		$data['error']							=	'';
		$data['card_number_error'] 				=	"";
   		$data['expiry_date_error'] 				=	"";
   		$data['cvv_number_error'] 				=	"";

		$where               =      ['users_id' => (int)$this->session->userdata('DZL_USERID') ];
		$user_data           =      $this->geneal_model->getOnlyOneData('da_users', $where);
		$where2              =      ['user_id' => (int)$this->session->userdata('DZL_USERID') ];
		$user_address        =      $this->geneal_model->getData('da_diliveryAddress', $where2,[]);
		$data['ToatlPoints'] =      $user_data['availableArabianPoints'];
		$data['dilivertAddress'] = 	$user_address;
		
		if($this->session->userdata('DZL_USERID')){
			$wcon['where']		=	[ 'user_id' => (int)$this->session->userdata('DZL_USERID') ];
			$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
			foreach($cartItems as $CA):
				$finalPrice += $CA['qty'] * $CA['price'];

				//check Stock
				$stockCheck = $this->geneal_model->getStock($CA['id'], $CA['qty']);
			endforeach;
			$data['finalprice'] = $finalPrice;
			$data['shipping']   = 0;
			$data['finaltotal'] = $finalPrice + $data['shipping'];
		}else{
			redirect('login');
		}

		if($this->input->post('makePayment') == 'YES'){	
			if($stockCheck == 0){
				$this->session->set_flashdata('error', lang('OUTOFSTOCK'));
		        redirect('user-cart');
			}elseif ($stockCheck <> 1) {
				$this->session->set_flashdata('error', lang('PRO_QTY'));
		        redirect('user-cart');
			}else{
				$error 								=	'NO';

				$this->form_validation->set_rules('cardholder_name','Card Holder Name','trim|required');
	       		$this->form_validation->set_rules('card_number','Card Number','trim|required');
	       		$this->form_validation->set_rules('expiry_date','Expiry Date','trim|required');
	       		$this->form_validation->set_rules('cvv_number','CVV Number','trim|required');

	       		if($this->input->post('card_number')):
					$cardValiData 		= 	$this->ccvalidation->validateCreditcardNumber(str_replace(' ','',$this->input->post('card_number')));
					if($cardValiData['status'] === 'false'):
						$error 							=	'YES';
						$data['card_number_error'] 		=	"Provide valid card number";
					endif;
				endif;
				if($this->input->post('expiry_date')):
					$expiry_date 		=	explode('/',trim($this->input->post('expiry_date')));
					$expValiData 		= 	$this->ccvalidation->validateCreditCardExpirationDate(trim($expiry_date[0]),trim($expiry_date[1]));
					if($expValiData === 'false'):
						$error 							=	'YES';
						$data['expiry_date_error'] 		=	"Provide valid expiry date";
					endif;
				endif;
				if($this->input->post('card_number') && $this->input->post('cvv_number')):
					$cvcValiData 		= 	$this->ccvalidation->validateCVV(str_replace(' ','',$this->input->post('card_number')),trim($this->input->post('cvv_number')));
					if($cvcValiData === 'false'):
						$error 							=	'YES';
						$data['cvv_number_error'] 		=	"Provide valid CVV";
					endif;
				endif;

				if($this->form_validation->run() && $error == 'NO'): //echo '<pre>'; print_r($_POST); die;
					/* Order Place Table */
			        $ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
			        $ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
			        $ORparam["user_id"] 				=	(int)$this->session->userdata('DZL_USERID');
			        $ORparam["user_type"] 				=	$this->session->userdata('DZL_USERSTYPE');
			        $ORparam["user_email"] 				=	$this->session->userdata('DZL_USEREMAIL');
			        $ORparam["user_phone"] 				=	$this->session->userdata('DZL_USERMOBILE');
			        $ORparam["shipping_address"] 		=	$this->session->userdata('payment_shipping_address');
			        $ORparam["shipping_charge"] 		=	(float)$this->session->userdata('payment_shipping_charge');
			        $ORparam["inclusice_of_vat"] 		=	(float)$this->session->userdata('payment_inclusice_of_vat');
			        $ORparam["subtotal"] 				=	(float)$this->session->userdata('payment_subtotal');
			        $ORparam["vat_amount"] 				=	(float)$this->session->userdata('payment_vat_amount');
			        $ORparam["total_price"] 			=	(float)$this->session->userdata('payment_total_price');
				    $ORparam["payment_mode"] 			=	'Stripe';
				    $ORparam["payment_from"] 			=	'Web';
				    $ORparam["order_status"] 			=	"Initialize";
				    $ORparam["creation_ip"] 			=	$this->input->ip_address();
				    $ORparam["created_at"] 				=	date('Y-m-d H:i');
				    $orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);

				    if($orderInsertID):
				        foreach($cartItems as $CA):	
							$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
							$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
							$ORDparam["order_id"]			=	$ORparam["order_id"];
							$ORDparam["user_id"]			=	(int)$CA['user_id'];
							$ORDparam["product_id"] 		=	(int)$CA['id'];
							$ORDparam["product_name"] 		=	$CA['name'];
							$ORDparam["quantity"] 		    =	(int)$CA['qty'];
							$ORDparam["price"] 		        =	(float)$CA['price'];
							$ORDparam["tax"] 		        =	(float)0;
							$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
							$ORDparam["is_donated"] 		=	$CA['is_donated'];
							$ORDparam["other"] 		        =	array(
																		'image' 		=>	$CA['other']->image,
																		'description' 	=>	$CA['other']->description,
																		'aed'			=>	$CA['other']->aed
																	);
							$this->geneal_model->addData('da_orders_details', $ORDparam);
						endforeach;

						$deliveryAddress			=	$this->geneal_model->getDataByParticularField('da_diliveryAddress','id',$this->session->userdata('payment_shipping_address_id'));

						$expiryDetails 				=	explode('/', $this->input->post('expiry_date'));

						$paymentData['card'] 		=	array('card' => array('number' => trim(stripslashes($this->input->post('card_number'))),
														      'exp_month' 	=> 	trim($expiryDetails[0]),
														      'exp_year' 	=> 	trim($expiryDetails[1]),
														      'cvc' 		=> 	trim(stripslashes($this->input->post('cvv_number')))
															  )
															 );	
						$paymentData['name'] 		=	$this->session->userdata('DZL_USERNAME');
						$paymentData['address'] 	=	[
													      'line1' => $this->session->userdata('payment_shipping_address'),
													      'postal_code' => $deliveryAddress['pincode'],
													      'city' => $deliveryAddress['city'],
													      'state' => $deliveryAddress['area'],
													      'country' => $deliveryAddress['country'],
													    ];	
						$paymentData['email'] 		=	$this->session->userdata('DZL_USEREMAIL');
						$paymentData['amount'] 		=	number_format($this->session->userdata('payment_total_price'),2,".","");
						$paymentData['currency'] 	=	'AED';
						$paymentData['order_id'] 	=	$ORparam["order_id"];

						$stripeReturnData 			=	$this->makeStripePayment($paymentData);
						if($stripeReturnData['status'] == 'success' && $stripeReturnData['stripeChargeId']):

							//update order status
							$updateStatus['stripe_token'] 		=	$stripeReturnData['stripeToken'];
							$updateStatus['customer_id'] 		=	$stripeReturnData['customerId'];
							$updateStatus['capture_amount'] 	=	$stripeReturnData['captureAmount'];
							$updateStatus['stripe_charge_id'] 	=	$stripeReturnData['stripeChargeId'];
							$updateStatus['order_status'] 		=	"Process";
							$this->geneal_model->editData('da_orders', $updateStatus, 'order_id', $ORparam["order_id"]);

							$this->session->set_userdata(array(
																'payment_stripe_token' => $stripeReturnData['stripeToken'],
																'payment_stripe_charge_id' => $stripeReturnData['stripeChargeId'],
																'payment_customer_id' => $stripeReturnData['customerId']
																));

							redirect('order-success/'.$ORparam["order_id"]);
						endif;
					endif;
				endif;
			}
		}

		$this->load->view('payment', $data);
	} // END OF FUNCTION

	/***********************************************************************
	** Function name : makeStripePayment
	** Developed By : Manoj Kumar
	** Purpose  : This function used for make Stripe Payment
	** Date : 08 JULY 2022
	************************************************************************/
	public function makeStripePayment($paymentData=array())
	{  
		include APPPATH . 'third_party/stripe/Stripe.php';
		error_reporting(0); 
		$params = array(
			"payment_mode"   	=> 	STRIPE_PAYMENT_MODE,
			"private_live_key" 	=> 	STRIPE_LIVE_SK,
			"public_live_key"  	=> 	STRIPE_LIVE_PK,
			"private_test_key" 	=> 	STRIPE_TEST_SK,
			"public_test_key" 	=> 	STRIPE_TEST_PK
		);
		if($params['payment_mode'] == "test"){
			Stripe::setApiKey($params['private_test_key']);
			$pubkey = $params['public_test_key'];
		}else{   
			Stripe::setApiKey($params['private_live_key']);
			$pubkey = $params['public_live_key'];
		}  
		$returnArray 				=	array();		
		$tokenData 					=	$this->createStripeToken($paymentData['card']);
		if($tokenData['status'] == 'success'){
			$paymentData['stripeToken'] 	=	$tokenData['stripeToken'];
			$returnArray['stripeToken']		=	$paymentData['stripeToken'];
			$customerData 					=	$this->createStripeCustomer($paymentData['stripeToken'],$paymentData['email']);

			if($customerData['status'] == 'success'){
				$paymentData['customerId'] 		=	$customerData['customerId'];
				$returnArray['customerId']		=	$paymentData['customerId'];
				$captureData 					=	$this->createStripeCharge($paymentData['customerId'],$paymentData['amount'],$paymentData['currency'],$paymentData['order_id']);
				 
				if($captureData['status'] == 'success'){
					$returnArray['captureAmount']		=	$captureData['captureAmount'];
					$returnArray['stripeChargeId']		=	$captureData['stripeChargeId'];
					$returnArray['status']				=	'success';
					$returnArray['message']				=	$captureData['message'];
				} else {
					$returnArray['status']		=	'error';
					$returnArray['message']		=	$captureData['message'];
				}
			} else {
				$returnArray['status']		=	'error';
				$returnArray['message']		=	$customerData['message'];
			}
		} else {
			$returnArray['status']	=	'error';
			$returnArray['message']	=	$tokenData['message'];
		} 
		return $returnArray;
	}

	/***********************************************************************
	** Function name : createStripeToken
	** Developed By : Manoj Kumar
	** Purpose  : This function used for create Stripe Token
	** Date : 08 JULY 2022
	************************************************************************/
	public function createStripeToken($paymentData=array())
	{  	
		$returnArray 			=	array();
		$params = array(
			"payment_mode"   	=> 	STRIPE_PAYMENT_MODE,
			"private_live_key" 	=> 	STRIPE_LIVE_SK,
			"public_live_key"  	=> 	STRIPE_LIVE_PK,
			"private_test_key" 	=> 	STRIPE_TEST_SK,
			"public_test_key" 	=> 	STRIPE_TEST_PK
		);
		if($params['payment_mode'] == "test"){
			$privateKey = $params['private_test_key'];
		}else{   
			$privateKey = $params['private_live_key'];
		}  
		if($paymentData){
			try { 
				$tokenData 	= Stripe_Token::create($paymentData,$privateKey);
				if($tokenData['error']):
					$returnArray['status']		=	'error';
					$returnArray['message']		=	$tokenData['error']['message'];
				else:
					$returnArray['status']		=	'success';
					$returnArray['message']		=	'Payment successfully.';
				endif;
			} catch (Exception $e) { 
				$returnArray['status']			=	'error';
				$returnArray['message']			=	$tokenData['error']['message'];
			} 
			if($returnArray['status'] == 'success'){
				$returnArray['stripeToken']		=	$tokenData['id'];
			}
		} else {
			$returnArray['status']				=	'error';
			$returnArray['message']				=	'Sonething wrong. Please try after sometime';
		}
		return $returnArray;
	}

	/***********************************************************************
	** Function name : createStripeCustomer
	** Developed By : Manoj Kumar
	** Purpose  : This function used for create Stripe Customer
	** Date : 08 JULY 2022
	************************************************************************/
	public function createStripeCustomer($stripeToken='',$userEmail='')
	{  
		$returnArray 			=	array();
		$params = array(
			"payment_mode"   	=> 	STRIPE_PAYMENT_MODE,
			"private_live_key" 	=> 	STRIPE_LIVE_SK,
			"public_live_key"  	=> 	STRIPE_LIVE_PK,
			"private_test_key" 	=> 	STRIPE_TEST_SK,
			"public_test_key" 	=> 	STRIPE_TEST_PK
		);
		if($params['payment_mode'] == "test"){
			$privateKey = $params['private_test_key'];
		}else{   
			$privateKey = $params['private_live_key'];
		}  
		if($stripeToken && $userEmail){
			try {
				$customer = Stripe_Customer::create(array('card'=>$stripeToken,'email'=>strip_tags(trim($userEmail)),"name"=>"Manoj",
															'address' => [
														      'line1' => '510 Townsend St',
														      'postal_code' => '98140',
														      'city' => 'San Francisco',
														      'state' => 'CA',
														      'country' => 'US',
														    ],),$privateKey);
				if($customer['error']):
					$returnArray['status']		=	'error';
					$returnArray['message']		=	$customer['error']['message'];
				else:
					$returnArray['status']		=	'success';
					$returnArray['message']		=	'Payment successfully.';
				endif;
			} catch (Exception $e) {
				$returnArray['status']			=	'error';
				$returnArray['message']			=	$customer['error']['message'];
			}
			if($returnArray['status'] == 'success'){
				$returnArray['customerId']		=	$customer['id'];
			}
		} else{
			$returnArray['status']				=	'error';
			$returnArray['message']				=	'Sonething wrong. Please try after sometime';
		}
		return $returnArray;
	}

	/***********************************************************************
	** Function name : createStripeCharge
	** Developed By : Manoj Kumar
	** Purpose  : This function used for create Stripe Charge
	** Date : 08 JULY 2022
	************************************************************************/
	public function createStripeCharge($customerId='',$amount='',$currency='',$order_id='')
	{  
		$returnArray 			=	array();
		$params = array(
			"payment_mode"   	=> 	STRIPE_PAYMENT_MODE,
			"private_live_key" 	=> 	STRIPE_LIVE_SK,
			"public_live_key"  	=> 	STRIPE_LIVE_PK,
			"private_test_key" 	=> 	STRIPE_TEST_SK,
			"public_test_key" 	=> 	STRIPE_TEST_PK
		);
		if($params['payment_mode'] == "test"){
			$privateKey = $params['private_test_key'];
		}else{   
			$privateKey = $params['private_live_key'];
		} 
		if($customerId && $amount && $currency && $order_id){
			$amount_cents 	= 	str_replace(".","",$amount); // Amount
			$description 	= 	"Payment for orderId #".$order_id;
			try {
				$autohCapture = Stripe_Charge::create(array("amount"=>$amount_cents,
															"currency"=>$currency,
															'capture' => true,
															"customer"=>$customerId,
															"description"=>$description
															),$privateKey			  
													  );
				if($autohCapture['error']):
					$returnArray['status']			=	'error';
					$returnArray['message']			=	$autohCapture['error']['message'];
				else:
					$returnArray['status']			=	'success';
					$returnArray['message']			=	'Payment successfully.';
				endif;
			} catch (Exception $e) {
				$returnArray['status']				=	'error';
				$returnArray['message']				=	$autohCapture['error']['message'];
			}
			if($returnArray['status'] == 'success'){ 
				$returnArray['captureAmount']		=	($autohCapture['amount']/100);
				$returnArray['stripeChargeId']		=	$autohCapture['id'];
			}
		} else{
			$returnArray['status']					=	'error';
			$returnArray['message']					=	'Sonething wrong. Please try after sometime';
		}
		return $returnArray;
	}

	/***********************************************************************
	** Function name 	: index
	** Developed By 	: RAVI NEGI
	** Purpose 			: This function used for placed order
	** Date 			: 04 MAY 2022
	** Updated By		: Afsar Ali
	** Updated Date 	: 05 MAY 2022
	************************************************************************/ 
	public function success($oid='')
	{  
		$data  = array();
		$data['finalPrice'] = 0;
		$data['order_id'] = $oid;
		$productIdPrice   = array();

		if(empty($this->cart->contents())):
			redirect('home');
		endif;

		//Get current order of user.
		$wcon							=	[ 'order_id' => $oid ];
		$data['orderData'] 				=	$this->geneal_model->getData2('single', 'da_orders', $wcon);
		
		//Get current order details of user.
		$wcon2['where']					=	[ 'order_id' => $oid ];
		$data['orderDetails']         	=	$this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);

		//update order status
		$updateStatus 					=	[ 'order_status' => 'Success' ];
		$updateorderstatus 				= 	$this->geneal_model->editData('da_orders', $updateStatus, 'order_id', $oid);

		//Generate coupon 
		foreach($data['orderDetails'] as $CA):
			$data['finalPrice'] += $CA['quantity'] * $CA['price'];
			$this->geneal_model->updateStock($CA['product_id'],$CA['quantity']);

			$productIdPrice[$CA['product_id']] 		=	($CA['quantity'] * $CA['price']);

			//Start Create Coupons for simple product
			for($i=0; $i < $CA['quantity']; $i++){
				A:
				$whereCon['coupon_code']	=	'';
				$whereCon['coupon_code']	=	strtoupper(uniqid(16));
				$check 	= 	$this->geneal_model->checkDuplicate('da_coupons',$whereCon);
				if($check == 0){
					$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
					$couponData['users_id']			= 	(int)$this->session->userdata('DZL_USERID');
					$couponData['users_email']		= 	$this->session->userdata('DZL_USEREMAIL');
					$couponData['order_id']			= 	$oid;
					$couponData['product_id']		= 	$CA['product_id'];
					$couponData['product_title']	= 	$CA['product_name'];
					$couponData['is_donated'] 		=	'N';
					$couponData['coupon_status'] 	=	'Live';
					$couponData['coupon_code'] 		= 	$whereCon['coupon_code'];
					$couponData['coupon_type'] 		= 	'Simple';
					$couponData['created_at']		=	date('Y-m-d H:i');

					$this->geneal_model->addData('da_coupons',$couponData);
				}else{
					goto A;
				}
			}
			//End Create Coupons 

			//Start Create Coupons for donate product
			if($CA['is_donated'] == 'Y'):
				for($i=0; $i < $CA['quantity']; $i++){
					B:
					$whereCon['coupon_code']	=	'';
					$whereCon['coupon_code']	=	strtoupper(uniqid(16));
					$check 	= 	$this->geneal_model->checkDuplicate('da_coupons',$whereCon);
					if($check == 0){
						$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
						$couponData['users_id']			= 	(int)$this->session->userdata('DZL_USERID');
						$couponData['users_email']		= 	$this->session->userdata('DZL_USEREMAIL');
						$couponData['order_id']			= 	$oid;
						$couponData['product_id']		= 	$CA['product_id'];
						$couponData['product_title']	= 	$CA['product_name'];
						$couponData['is_donated'] 		=	'Y';
						$couponData['coupon_status'] 	=	'Live';
						$couponData['coupon_code'] 		= 	$whereCon['coupon_code'];
						$couponData['coupon_type'] 		= 	'Donated';
						$couponData['created_at']		=	date('Y-m-d H:i');

						$this->geneal_model->addData('da_coupons',$couponData);
					}else{
						goto B;
					}
				}
			endif;
			//End Create Coupons
		endforeach;

		$wcon['where'] 					=	array('order_id' => $oid);
		$data['couponDetails']			=	$this->geneal_model->getData2('multiple', 'da_coupons', $wcon);

		// Deduct the purchesed points and get available arabian points of user.
		$currentBal 					= 	$this->geneal_model->debitPoints($data['finalPrice']); 

		/* Load Balance Table -- after buy product*/
	    $Buyparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
		$Buyparam["user_id_cred"] 		=	(int)$this->session->userdata('DZL_USERID');
		$Buyparam["user_id_deb"]		=	(int)$this->session->userdata('DZL_USERID');
		$Buyparam["arabian_points"] 	=	(float)$data['finalPrice'];
	    $Buyparam["record_type"] 		=	'Debit';
	    $Buyparam["arabian_points_from"]=	'Purchase';
	    $Buyparam["creation_ip"] 		=	$this->input->ip_address();
	    $Buyparam["created_at"] 		=	date('Y-m-d H:i');
	    $Buyparam["created_by"] 		=	(int)$this->session->userdata('DZL_USERSTYPE');
	    $Buyparam["status"] 			=	"A";
	    
	    $this->geneal_model->addData('da_loadBalance', $Buyparam);
	    /* End */
	    
		$membershipData = $this->geneal_model->getMembership((int)$this->session->userdata('DZL_TOTALPOINTS'));
		if($membershipData):
			$cashback 			=	$data['finalPrice'] * $membershipData['benifit'] /100;
			$data['cashback'] 	= 	$cashback;
			if($cashback):
				$insertCashback = array(
					'cashback_id'	=>	(int)$this->geneal_model->getNextSequence('da_cashback'),
					'user_id'		=>	(int)$this->session->userdata('DZL_USERID'),
					'order_id'		=>	(int)$data['order_id'],
					'cashback'		=>	(float)$cashback,
					'created_at'	=>	date('Y-m-d H:i'),
				);
				$this->geneal_model->addData('da_cashback',$insertCashback);

				// Credit the purchesed points and get available arabian points of user.
				$this->geneal_model->creaditPoints($cashback); 

				/* Load Balance Table -- after buy product*/
			    $Cashbparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
				$Cashbparam["user_id_cred"] 		=	(int)$this->session->userdata('DZL_USERID');
				$Cashbparam["user_id_deb"]			=	(int)$this->session->userdata('DZL_USERID');
				$Cashbparam["arabian_points"] 		=	(float)$cashback;
			    $Cashbparam["record_type"] 			=	'Credit';
			    $Cashbparam["arabian_points_from"] 	=	'Membership Cashback';
			    $Cashbparam["creation_ip"] 			=	$this->input->ip_address();
			    $Cashbparam["created_at"] 			=	date('Y-m-d H:i');
			    $Cashbparam["created_by"] 			=	(int)$this->session->userdata('DZL_USERSTYPE');
			    $Cashbparam["status"] 				=	"A";
			    
			    $this->geneal_model->addData('da_loadBalance', $Cashbparam);
			    /* End */
			endif;
		endif;

		if($this->session->userdata('SHARED_USER_ID') && $this->session->userdata('SHARED_USER_REFERRAL_CODE') && $this->session->userdata('SHARED_PRODUCT_ID')):
			if(isset($productIdPrice[$this->session->userdata('SHARED_PRODUCT_ID')])):

				$prowhere['where']	=	array('products_id'=>(int)$this->session->userdata('SHARED_PRODUCT_ID'));
				$prodData			=	$this->common_model->getData('single','da_products',$prowhere);

				$sharewhere['where']=	array('users_id'=>(int)$this->session->userdata('SHARED_USER_ID'),'products_id'=>(int)$this->session->userdata('SHARED_PRODUCT_ID'));
				$shareCount			=	$this->common_model->getData('count','da_product_share',$sharewhere,'','0','0');

				if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit']):

					$param['share_id']					=	(int)$this->common_model->getNextSequence('da_product_share');
					$param['users_id']					=	(int)$this->session->userdata('SHARED_USER_ID');
					$param['products_id']				=	(int)$this->session->userdata('SHARED_PRODUCT_ID');
					$param['creation_date']				=   date('Y-m-d H:i');
					$param['creation_ip']				=   $this->input->ip_address();
					$this->common_model->addData('da_product_share',$param);

					$productCartAmount  		=	$productIdPrice[$this->session->userdata('SHARED_PRODUCT_ID')];
					//First label referal amount Credit
					// $ref1tbl 					=	'referral_percentage';
					// $ref1where 					=	['referral_lebel' => (int)1 ];
					// $referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
					$ref1tbl 					=	'da_products';
					$ref1where 					=	['products_id' => (int)$this->session->userdata('SHARED_PRODUCT_ID') ];
					$referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
					if($referal1Data && $referal1Data['share_percentage_first'] > 0):
						$referal1Amount  		=	(($productCartAmount*$referal1Data['share_percentage_first'])/100);

						/* Referal Product Table -- after buy product*/
					    $ref1Amtparam["referral_id"]			=	(int)$this->geneal_model->getNextSequence('referral_product');
						$ref1Amtparam["referral_user_code"] 	=	(int)$this->session->userdata('SHARED_USER_REFERRAL_CODE');
						$ref1Amtparam["referral_from_id"] 		=	(int)$this->session->userdata('SHARED_USER_ID');
						$ref1Amtparam["referral_to_id"]			=	(int)$this->session->userdata('DZL_USERID');
					    $ref1Amtparam["referral_percent"] 		=	(float)$referal1Data['share_percentage_first'];
					    $ref1Amtparam["referral_amount"] 		=	(float)$referal1Amount;
					    $ref1Amtparam["referral_cart_amount"] 	=	(float)$productCartAmount;
					    $ref1Amtparam["referral_product_id"] 	=	(int)$this->session->userdata('SHARED_PRODUCT_ID');
					    $ref1Amtparam["creation_ip"] 			=	$this->input->ip_address();
					    $ref1Amtparam["created_at"] 			=	date('Y-m-d H:i');
					    $ref1Amtparam["created_by"] 			=	(int)$this->session->userdata('DZL_USERSTYPE');
					    $ref1Amtparam["status"] 				=	"A";
					    
					    $this->geneal_model->addData('referral_product', $ref1Amtparam);
					    /* End */

					    /* Load Balance Table -- after buy product*/
					    $ref1param["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
						$ref1param["user_id_cred"] 			=	(int)$this->session->userdata('SHARED_USER_ID');
						$ref1param["user_id_deb"]			=	(int)$this->session->userdata('SHARED_USER_ID');
						$ref1param["arabian_points"] 		=	(float)$referal1Amount;
					    $ref1param["record_type"] 			=	'Credit';
					    $ref1param["arabian_points_from"] 	=	'Referral';
					    $ref1param["creation_ip"] 			=	$this->input->ip_address();
					    $ref1param["created_at"] 			=	date('Y-m-d H:i');
					    $ref1param["created_by"] 			=	(int)$this->session->userdata('DZL_USERSTYPE');
					    $ref1param["status"] 				=	"A";
					    
					    $this->geneal_model->addData('da_loadBalance', $ref1param);
					    /* End */
					endif;

					//Second label referal amount Credit
					$ref2checktbl 				=	'referral_product';
					$ref2checkwhere 			=	['referral_to_id' => (int)$this->session->userdata('SHARED_USER_ID'), 'referral_product_id' => (int)$this->session->userdata('SHARED_PRODUCT_ID')];
					$referal2checkData			=	$this->geneal_model->getOnlyOneData($ref2checktbl, $ref2checkwhere);
					if($referal2checkData):
						//$ref2tbl 					=	'referral_percentage';
						//$ref2where 					=	['referral_lebel' => (int)2 ];
						//$referal2Data				=	$this->geneal_model->getOnlyOneData($ref2tbl, $ref2where);

						$ref2tbl 					=	'da_products';
						$ref2where 					=	['products_id' => (int)$this->session->userdata('SHARED_PRODUCT_ID') ];
						$referal2Data				=	$this->geneal_model->getOnlyOneData($ref2tbl, $ref2where);
						if($referal2Data && $referal2Data['share_percentage_second'] > 0):
							$referal2Amount  		=	(($productCartAmount*$referal2Data['share_percentage_second'])/100);

						    /* Load Balance Table -- after buy product*/
						    $ref1param["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
							$ref1param["user_id_cred"] 			=	(int)$referal2Data['referral_from_id'];
							$ref1param["user_id_deb"]			=	(int)$referal2Data['referral_from_id'];
							$ref1param["arabian_points"] 		=	(float)$referal2Amount;
						    $ref1param["record_type"] 			=	'Credit';
						    $ref1param["arabian_points_from"] 	=	'Referral';
						    $ref1param["creation_ip"] 			=	$this->input->ip_address();
						    $ref1param["created_at"] 			=	date('Y-m-d H:i');
						    $ref1param["created_by"] 			=	(int)$this->session->userdata('DZL_USERSTYPE');
						    $ref1param["status"] 				=	"A";
						    
						    $this->geneal_model->addData('da_loadBalance', $ref1param);
						    /* End */
						endif;
					endif;
				endif;
			endif;
		endif;

		//Delete cart items.
		$this->geneal_model->deleteData('da_cartItems', 'user_id', $this->session->userdata('DZL_USERID')); 
		$this->cart->destroy();
		$this->session->unset_userdata(array('SHARED_USER_ID','SHARED_USER_REFERRAL_CODE', 'SHARED_PRODUCT_ID',
											 'payment_shipping_address_id', 'payment_shipping_address','payment_shipping_charge',
										     'payment_inclusice_of_vat','payment_subtotal', 'payment_vat_amount','payment_total_price',
										 	 'payment_stripe_token','payment_stripe_charge_id','payment_customer_id'));

		$this->load->view('success', $data);
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: orderList
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for show order list
	** Date 			: 10 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function orderList()
	{  
		$data 					=	array();
		$data['page']			=	'My Order';
		$this->load->library("pagination");
		
		$tblName 				=	'da_orders';
		$shortField 			= 	array('_id'=> -1 );
		$whereCon['where']		= 	array('user_id'=>(int)$this->session->userdata('DZL_USERID'));

		$totalPage 				=	$this->geneal_model->getData2('count',$tblName,$whereCon,$shortField,'0','0');
		$config 				= 	['base_url'=>base_url('order-list'),'per_page'=>10,'total_rows'=> $totalPage];

		$this->pagination->initialize($config);
		$data['orderData']  =	$this->geneal_model->getData2('multiple', $tblName, $whereCon,$shortField,$this->uri->segment(2),$config['per_page']);

		$this->load->view('order_list',$data);
	} 	//END OF FUNCTION

	/***********************************************************************
	** Function name 	: orderDetails
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for show order list
	** Date 			: 10 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function orderDetails($id='')
	{  
		$id 					=	manojDecript($id);
		$data 					=	array();
		$data['page']			=	'My Order';
		$this->load->library("pagination");
		
		$ORDwhere['order_id'] 	=	$id;

		$data['orderData'] 		= 	$this->geneal_model->getOnlyOneData('da_orders', $ORDwhere);

		$ORDDwhere['order_id'] 	=	$id;
		$ORDDorder['_id'] 		=	'ASC';

		$data['orderDetails'] 	= 	$this->geneal_model->getData('da_orders_details', $ORDDwhere, $ORDDorder);

		$this->load->view('order_details',$data);
	} 	//END OF FUNCTION

}
