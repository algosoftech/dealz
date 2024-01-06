<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quickbuy extends CI_Controller {
public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model(array('geneal_model','common_model','emailsendgrid_model','sms_model'));
		$this->load->helper(array('common_helper'));
		$this->lang->load('statictext','front');
	} 
/***********************************************************************
** Function name 	: index
** Developed By 	: Dilip Halder
** Purpose 			: This function used for index
** Date 			: 27 September 2023
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
	public function checkValiduser($id='',$sharedDetails='')
	{  
		$data 					=	array();

		if($sharedDetails):
			$sharedDatas    	= 	base64_decode($sharedDetails);
			$sharedData    		= 	explode('_',$sharedDatas);

			$datatime 			=	strtotime(date('Y-m-d  H:i:s'));
			$sharedate			=	date('Y-m-d H:i:s',$sharedData[2]);
			$expairon			=	strtotime(date('Y-m-d H:i:s',strtotime($sharedate.'+5 hour')));

			//echo $sharedDatas;die();	
			if($sharedDetails <> '' && $datatime < $expairon):

				$param['seq_id']					=	(int)$this->common_model->getNextSequence('da_deep_link');
				$param['SHARED_USER_ID']			=	(int)$sharedData[0];
				$param['SHARED_USER_REFERRAL_CODE']	=	$sharedData[1];
				$param['SHARED_PRODUCT_ID']			=	(int)manojDecript($id);
				$param['BUY_USER_ID']				=	(int)$this->session->userdata('DZL_USERID');
				$param['created_at']				=	date('Y-m-d H:i');

				$prowhere['where']	=	array('products_id'=>(int)$param['SHARED_PRODUCT_ID']);
				$prodData			=	$this->common_model->getData('single','da_products',$prowhere);

				$sharewhere['where']=	array('users_id'=>(int)$param['SHARED_USER_ID'],'products_id'=>(int)$param['SHARED_PRODUCT_ID']);
				$shareCount			=	$this->common_model->getData('count','da_product_share',$sharewhere,'','0','0');

				$checkDuplicatewhere['where']	=	array('BUY_USER_ID'=>(int)$param['BUY_USER_ID'],'SHARED_PRODUCT_ID'=>(int)$param['SHARED_PRODUCT_ID']);
				$checkDuplicate					=	$this->common_model->getData('count','da_deep_link',$checkDuplicatewhere,'0','0');

				if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit'] && $checkDuplicate == 0):
					$this->geneal_model->addData('da_deep_link',$param);

					$this->session->set_userdata('SHARED_USER_ID', $sharedData[0]);
					$this->session->set_userdata('SHARED_USER_REFERRAL_CODE', $sharedData[1]);
					$this->session->set_userdata('SHARED_PRODUCT_ID', manojDecript($id));
				endif;
			endif;
			//echo base_url('quick-buy/'.(int)manojDecript($id));die();
			redirect(base_url('quick-buy/'.(int)manojDecript($id)));
		endif;

		$data 					=	array();

		$tbl 					=	'da_products';
		//$where 					=	['products_id' => (int)manojDecript($id) ];
		if(is_numeric($id)):
			$where1 					=	['products_id' => (int)$id, 'stock' => [ '$gt' => 0], 'status' => 'A' ];
		else:
			$where1 					=	['title_slug' => $id, 'stock' => [ '$gt' => 0], 'status' => 'A' ];
		endif;
		$data['products']		=	$this->geneal_model->getOnlyOneData($tbl, $where1);

		if(!$data['products']):
			redirect(base_url());
		endif;

		
		$tbl 					  =	'da_prize';
		$where 					  =	['product_id' => (int)$data['products']['products_id'] ];
		$data['prize']			  =	$this->geneal_model->getOnlyOneData($tbl, $where);
		$data['countryCodeData']  =   countryCodeList();

		$data['page']			=	'Product | '.$data['products']['title'];
	
		$this->load->view('mobile-quickbuy-check-valiuser',$data);
	}


	/* * *********************************************************************
	 * * Function name : verifyUser
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to verify user mobile number.
	 * * Date : 05 October 2023
	 * * **********************************************************************/
	public function verifyUser()
	{

		$country_code   = $this->input->post('country_code');
		$mobile_no 		= $this->input->post('users_mobile');
		$otp            = (int)rand(1000,9999);

		// Getting all cancelled order details
		$where['where'] 	=	array( 'country_code' => $country_code ,'users_mobile' => (int)$mobile_no);
		$tblName 			=	'da_quick_users';
		$quickUser 			=	$this->common_model->getData('single',$tblName, $where);
		

		if($quickUser):
			$whereCon 		= array('sequence_id' => (int)$quickUser['sequence_id'] , 'country_code' => $country_code ,'users_mobile' => (int)$mobile_no);
			$updateParams 	= array( 'otp' => $otp , 'is_verify' => 'N');	
			$updatedstatus 	= $this->geneal_model->editSingleData('da_quick_users',$updateParams,$whereCon);
		else:
			$user["sequence_id"]	= (int)$this->geneal_model->getNextSequence('da_quick_users');
			$user['first_name'] 	= $this->input->post('first_name');
			$user['last_name'] 		= $this->input->post('last_name');
			$user['country_code'] 	= $this->input->post('country_code');
			$user['users_mobile'] 	= (int)$this->input->post('users_mobile');
			$user['users_email'] 	= $this->input->post('users_email');
			$user['otp'] 			= $otp;
			$user['is_verify'] 		= 'N';
			$user["status"] 		= "A";
		    $user["creation_ip"] 	= $this->input->ip_address();
		    $user["created_at"] 	= date('Y-m-d H:i');
			
			//create new user.
			$orderInsertID 			= $this->geneal_model->addData('da_quick_users', $user);
		endif;

		$MOBILENUMBER = $country_code.$mobile_no;
 		
 		$this->session->set_userdata('Quick_country_code',$country_code);
 		$this->session->set_userdata('Quick_users_mobile',$mobile_no);


		$this->sms_model->sendForgotPasswordOtpSmsToUser($MOBILENUMBER,$otp,$country_code);
		echo "sms sent";

	}

	/* * *********************************************************************
	 * * Function name : verifyUserOTP
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to verify user OTP.
	 * * Date : 05 October 2023
	 * * **********************************************************************/
	public function verifyUserOTP()
	{
				
		$country_code   = $this->session->userdata('Quick_country_code');
		$mobile_no   	= $this->session->userdata('Quick_users_mobile');
		$otp            = $this->input->post('otp');

		 // Getting all cancelled order details
		$where['where'] 	=	array( 'country_code' => $country_code ,'users_mobile' => (int)$mobile_no ,'otp' => (int)$otp );
		$tblName 			=	'da_quick_users';
		$quickUser 			=	$this->common_model->getData('single',$tblName, $where);

		// echo "<pre>";print_r($quickUser); die();

		if($quickUser):
			$whereCon 		= array('sequence_id' => (int)$quickUser['sequence_id'] , 'country_code' => $country_code ,'users_mobile' => (int)$mobile_no);
			$updateParams 	= array( 'otp' => '','is_verify' => 'Y');	
			$updatedstatus 	= $this->geneal_model->editSingleData('da_quick_users',$updateParams,$whereCon);
			$result['status'] = 'success';
			$result['message'] = lang('OTP_VERIFIED');
			echo json_encode($result);die();
		else:
			$updateParams 	= array('is_verify' => 'N');	
			$updatedstatus 	= $this->geneal_model->editSingleData('da_quick_users',$updateParams,$whereCon);
			$result['status'] = 'failed';
			$result['message'] = lang('WRONG_OTP');
			echo json_encode($result);die();
		endif;
	}

	/* * *********************************************************************
	 * * Function name : purchase
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to verify user OTP.
	 * * Date          : 23 October 2023
	 * * **********************************************************************/
	public function checkout()
	{ 		

		// Getting user details .
		$quantity			= $this->input->post('quantity');
		$first_name			= $this->input->post('first_name');
		$last_name			= $this->input->post('last_name');

		if(!empty($this->input->post('is_donated'))):
			$is_donated			=  'Y';
		else:
			$is_donated			=  'N';
		endif;


		$country_code		= $this->session->userdata('Quick_country_code');
		$users_mobile		= $this->session->userdata('Quick_users_mobile');
		$products_id		= $this->session->userdata('Quick_products_id');
		$adepoints			= $this->session->userdata('Quick_adepoints');
		$title				= $this->session->userdata('Quick_title');

		$Quick_buy			= $this->session->set_userdata('Quick_buy','Y');

		$this->form_validation->set_rules('quantity', 'Description', 'trim|required');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$error = 'NO';


		
		if($this->form_validation->run() && $error == 'NO' || $this->input->post('placeOrder') == 'YES' ): 
		
			if($quantity && $first_name && $last_name):
				$is_donated			= $this->session->set_userdata('Quick_is_donated',$is_donated);
				$quantity 			= $this->session->set_userdata('quantity',$quantity );
				$first_name 		= $this->session->set_userdata('first_name' ,$first_name );
				$last_name 			= $this->session->set_userdata('last_name'	,$last_name );
			endif;

			$quantity			= $this->session->userdata('quantity');
			$first_name			= $this->session->userdata('first_name');
			$last_name			= $this->session->userdata('last_name');
			$is_donated			= $this->session->userdata('Quick_is_donated');


			//Otp verified checked
			$whereCon['where'] 	 =  array( 'is_verify' => 'Y' , 'country_code' => $country_code ,'users_mobile' => (int)$users_mobile);
			$ISvarified          =  $this->geneal_model->getData2('count','da_quick_users', $whereCon);

			if($ISvarified  == 0 ):
				$this->session->set_flashdata('error', lang('OTP_NOT_VARIFIED'));
	    		redirect('/');
			else:
				$USRUpdate['first_name']	= $this->session->userdata('first_name');
				$USRUpdate['last_name']		= $this->session->userdata('last_name');

				$UpdatewhereCon['where'] 	=  array( 'is_verify' => 'Y' , 'country_code' => $country_code ,'users_mobile' => (int)$users_mobile);
				$this->geneal_model->editDataByMultipleCondition('da_quick_users',$USRUpdate,$UpdatewhereCon['where']);
			endif;

			$data  = array();
			$data['page']			=	'Checkout';
			$finalPrice = 0;
			$data['sufficient_point_error'] =  '';
			$data['post_sufficient_point_error'] =  '';
			$data['product_is_in_donate'] 		 =  'Y';

			$where               =      array('country_code' => $country_code , 'users_mobile' => (int)$users_mobile);
			$user_data           =      $this->geneal_model->getOnlyOneData('da_users', $where);

			if(!empty($user_data)):
				$this->session->set_userdata('DZL_USERID',$user_data['users_id']);
				$this->session->set_userdata('DZL_USERSTYPE',$user_data['users_type']);
				$this->session->set_userdata('DZL_USEREMAIL',$user_data['users_email']);
				$this->session->set_userdata('DZL_USERMOBILE',$user_data['users_mobile']);
			endif;

			$where2              =      ['user_id' => (int)$this->session->userdata('DZL_USERID') ];
			$user_address        =      $this->geneal_model->getData('da_diliveryAddress', $where2,[]);
			$data['ToatlPoints'] 	 =  $user_data['availableArabianPoints']?$user_data['availableArabianPoints']:'0';
			$data['dilivertAddress'] = 	$user_address;
			$data['enabledPayment'] =	$this->geneal_model->getData2('single', 'da_paymentmode');
	 		

			if($adepoints && $quantity && $title && $is_donated ):

				$finalPrice = $quantity*$adepoints;
				$data['finaltotal'] = $finalPrice;


				// Check Product availability
				$productID 			= $this->session->userdata('Quick_products_id');
				$productQty 		= $this->session->userdata('quantity');
				$productIsDonated 	= $this->session->userdata('Quick_is_donated');
				$Plateform 			= 'mobile-web';
				$CouponGenerate 	= '';
				$result 			=  $this->common_model->CheckAvailableTickets($productID,$productQty ,$productIsDonated,$Plateform,$CouponGenerate);

				if($is_donated == 'N'):
					$data['product_is_in_donate'] =  'N';
				endif;

				if($data['product_is_in_donate'] == 'N'):
					$data['shipping']   = 0;//SHIPPING_CHARGE;
				else:
					$data['shipping']   = 0;
				endif;
				

				if(($data['finaltotal']+$data['shipping']) > $data['ToatlPoints']){
					// echo 'here';die();
				    $data['sufficient_point_error'] =  "You don't have sufficient Arabian points";
				    $data['post_sufficient_point_error'] =  "You don't have sufficient Arabian points";
				}


				if($this->input->post('placeOrder') === 'YES'):
						// payment capturing code.
						if($this->session->userdata('DZL_USERID') && $this->input->post('payment_method') === 'arabianpoint'):
							if($data['finaltotal']+$data['shipping'] > $data['ToatlPoints']):
							    $data['post_sufficient_point_error'] =  "You don't have sufficient Arabian points";
							else:
								$productCount          				=	$quantity;
								/* Order Place Table */
						        $ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
						        $ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
						        $ORparam["user_id"] 				=	(int)$this->session->userdata('DZL_USERID');
						        $ORparam["user_type"] 				=	$this->session->userdata('DZL_USERSTYPE');
						        $ORparam["user_email"] 				=	$this->session->userdata('DZL_USEREMAIL');
						        $ORparam["user_phone"] 				=	$this->session->userdata('DZL_USERMOBILE');
						        $ORparam["product_is_donate"] 		=	$this->input->post('product_is_donate');
						        if($this->input->post('product_is_donate') == 'N'):
						        	$ORparam["shipping_method"] 		=	$this->input->post('shipping_method');
						      

								    $collection_points  				=	explode('_____',$this->input->post('collection_points'));

								    $tblName1 					=	'da_emirate_collection_point';
									$where1['where'] 			=	array( 'status' => 'A','collection_point_id' => (int)$collection_points[2]);
									$order1 					=	['collection_point_id' => 'ASC'];
									$data1						=	$this->geneal_model->getData2('single',$tblName1,$where1,$order1);

						        	$ORparam["emirate_id"] 				=	$collection_points[0];
						        	$ORparam["emirate_name"] 			=	$data1['emirate_name'];

									$ORparam["area_id"] 				=	$collection_points[4];
									$ORparam["area_name"] 				=	$data1['area_name'];

						        	$ORparam["collection_point_id"] 	=	$collection_points[2];
						        	$ORparam["collection_point_name"] 	=	$data1['collection_point_name'];

								    $ORparam["shipping_address"]	=	'';

							    else:
							    	$ORparam["shipping_method"] 	=	'';

							    	$ORparam["emirate_id"] 				=	'';
						        	$ORparam["emirate_name"] 			=	'';

									$ORparam["area_id"] 				=	'';
									$ORparam["area_name"] 				=	'';

						        	$ORparam["collection_point_id"] 	=	'';
						        	$ORparam["collection_point_name"] 	=	'';

							    	$ORparam["shipping_address"] 	=	'';
							    endif;

								$ORparam["product_count"] 			=	(int)$productCount;
						        $ORparam["shipping_charge"] 		=	(float)$this->input->post('shipping_charge');
						        $ORparam["inclusice_of_vat"] 		=	(float)$this->input->post('inclusice_of_vat');
						        $ORparam["subtotal"] 				=	(float)$this->input->post('subtotal');
						        $ORparam["vat_amount"] 				=	(float)$this->input->post('vat_amount');
						        $ORparam["total_price"] 			=	(float)$ORparam["inclusice_of_vat"];
						        $ORparam["availableArabianPoints"] 	=	(float)$user_data["availableArabianPoints"];
						        $ORparam["end_balance"] 			=	(float)$user_data["availableArabianPoints"] - (float)$ORparam["inclusice_of_vat"] ;
							    $ORparam["payment_mode"] 			=	'Arabian Points';
							    $ORparam["payment_from"] 			=	'Web';
							    $ORparam["order_status"] 			=	"Process";
							    $ORparam["creation_ip"] 			=	$this->input->ip_address();
							    $ORparam["created_at"] 				=	date('Y-m-d H:i');
								
							    $orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);

							    if($orderInsertID):

										//Manage Inventory
										if($this->input->post('product_is_donate') == 'N'):
											$whereinv['where']		=	array(
																		'products_id'			=>	(int)$products_id,
																		'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
																	);
											$INVcheck	=	$this->geneal_model->getData2('single','da_inventory',$whereinv);

											if($INVcheck <> ''):
												$orqty = $INVcheck['order_request_qty'] + (int)$quantity;
												$INVUpdate['order_request_qty']		=	$orqty;
												$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$whereinv['where']);
											else:
												$INVparam['products_id']				= 	(int)$products_id;
												$INVparam['qty']						=	(int)0;
												$INVparam['available_qty']				=	(int)0;
												$INVparam['order_request_qty']			=	(int)$quantity;
												$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

												$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
												
												$INVparam['creation_ip']				=	currentIp();
												$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
												$INVparam['status']						=	'A';
												$this->geneal_model->addData('da_inventory', $INVparam);
											endif;
										endif;


										$prodDet['where']		=	array('products_id'			=>	(int)$products_id);
										$productDetails			=	$this->geneal_model->getData2('single','da_products',$prodDet);


										//END
										$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
										$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
										$ORDparam["order_id"]			=	$ORparam["order_id"];
										$ORDparam["user_id"]			=	(int)$this->session->userdata('DZL_USERID');
										$ORDparam["product_id"] 		=	(int)$products_id;
										$ORDparam["product_name"] 		=	$title;
										$ORDparam["quantity"] 		    =	(int)$quantity;
										if($productDetails['color']):
											$ORDparam["color"] 		    =	$productDetails['color'];
										endif;
										if($productDetails['size']):
											$ORDparam["size"] 		    =	$productDetails['size'];
										endif;
										$ORDparam["price"] 		        =	(float)$productDetails['adepoints'];
										$ORDparam["tax"] 		        =	(float)0;
										$ORDparam["subtotal"] 		    =	(float)$quantity*$productDetails['adepoints'];
										$ORDparam["is_donated"] 		=	$is_donated;
										$ORDparam["other"] 		        =	array(
																					'image' 		=>	$productDetails['product_image'],
																					'description' 	=>	$productDetails['description'],
																					'aed'			=>  $productDetails['adepoints']
																				);
										$ORDparam["current_ip"] 		=	$CA['current_ip'];
										$ORDparam["rowid"] 				=	$CA['rowid'];
										$ORDparam["curprodrowid"] 		=	$CA['curprodrowid'];
										$this->geneal_model->addData('da_orders_details', $ORDparam);


								endif;

								$this->session->set_userdata(array('payment_method' => $this->input->post('payment_method'),'payment_total_price' => $this->input->post('inclusice_of_vat')));
								$this->session->set_userdata('CURRENT_ORDER_ID',$ORparam["order_id"]);
								redirect('order-success/');
							endif;
						
						else:





							$shippingAddress					=	explode('_____', $this->input->post('address'));
							if($this->input->post('product_is_donate') == 'N'):
					        	$curr_shipping_method			=	$this->input->post('shipping_method');
					     
							    $curr_shipping_address_id 		=	'';
							    $curr_shipping_address 			=	'';

							     $collection_points  			=	explode('_____',$this->input->post('collection_points'));

							    $curr_emirate_id 				=	$collection_points[0];
					        	$curr_emirate_name 				=	$collection_points[1];
								
								$curr_area_id 					=	$collection_points[4];
					        	$curr_area_name 				=	$collection_points[5];

					        	$curr_collection_point_id 		=	$collection_points[2];
					        	$curr_collection_point_name 	=	$collection_points[3];
						    else:
						    	$curr_shipping_method 			=	'';
						    	$curr_shipping_address_id 		=	'';
							    $curr_shipping_address 			=	'';

							    $curr_emirate_id 				=	'';
					        	$curr_emirate_name 				=	'';
								
							    $curr_emirate_id 				=	'';
					        	$curr_emirate_name 				=	'';

					        	$curr_collection_point_id 		=	'';
					        	$curr_collection_point_name 	=	'';
						    endif;
							$this->session->set_userdata(array(
																'payment_product_is_donate' => $this->input->post('product_is_donate'),
																'payment_shipping_method' => $curr_shipping_method,
																'payment_shipping_address_id' => $curr_shipping_address_id,
																'payment_shipping_address' => $curr_shipping_address,

																'payment_emirate_id' => $curr_emirate_id,
																'payment_emirate_name' => $curr_emirate_name,

																'payment_area_id' => $curr_area_id,
																'payment_area_name' => $curr_area_name,

																'payment_collection_point_id' => $curr_collection_point_id,
																'payment_collection_point_name' => $curr_collection_point_name,

																'payment_shipping_charge' => $this->input->post('shipping_charge'),
																'payment_inclusice_of_vat' => $this->input->post('inclusice_of_vat'),
																'payment_subtotal' => $this->input->post('subtotal'),
																'payment_vat_amount' => $this->input->post('vat_amount'),
																'payment_total_price' => $this->input->post('inclusice_of_vat'),
																'payment_method' => $this->input->post('payment_method')
																));

							if($this->input->post('payment_method') == 'stripe'):
								redirect('payment');
							elseif($this->input->post('payment_method') == 'telr'):
								redirect('telrpayment');
							elseif($this->input->post('payment_method') == 'Noon'):
								redirect('noonpayment');
							endif;

						endif;


				endif;
			endif;	
				
	 	
		 	$collectionPoints   		=	array();
			$tblName1 					=	'da_emirate';
			$where1['where'] 			=	array( 'status' => 'A');
			$order1 					=	['emirate_name' => 'ASC'];
			$data1						=	$this->geneal_model->getData2('multiple',$tblName1,$where1,$order1);
			if($data1):
				foreach($data1 as $info1):
					$tblName2 					=	'da_emirate_collection_point';
					$where2['where'] 			=	array( 'emirate_id'=>(int)$info1['emirate_id'], 'status' => 'A');
					$order2 					=	['collection_point_name' => 'ASC'];
					$data2						=	$this->geneal_model->getData2('multiple',$tblName2,$where2,$order2);
					if($data2):
						$info1['collectionPoint']	=	$data2;
					endif;
					array_push($collectionPoints,$info1);
				endforeach;
			endif;


			$data['collectionList']    	=   $collectionPoints;
			// echo '<pre>';print_r($data);die();
		$this->load->view('checkout', $data);

		
		else:
			$this->session->set_flashdata('error', lang('ERROR'));
	    	redirect('/');
		endif;
	} // END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : purchase
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to verify user OTP.
	 * * Date          : 12 October 2023
	 * * **********************************************************************/
	public function purchase()
	{
				
		// $productName     = $this->input->post('productName');
		// $price     		 = $this->input->post('price');
		// $quantity     	 = $this->input->post('quantity');
		// $first_name      = $this->input->post('first_name');
		// $last_name       = $this->input->post('last_name');
		// $country_code    = $this->input->post('country_code');
		// $users_mobile    = $this->input->post('users_mobile');


		$productName     = "Stereo Mobile Headphone_____100000000000196";
		$price     		 = "10";
		$quantity     	 = "1";
		$first_name      = "Dilip";
		$last_name       = "Halder";
		$country_code    = "+91";
		$users_mobile    = "8700144841";
	}

	/* * *********************************************************************
	 * * Function name : quickcampaign
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to verify user OTP.
	 * * Date          : 12 October 2023
	 * * **********************************************************************/
	public function quickcampaign()
	{
		
		$data 					 =	array();
		$data['page']			 =	'Quick Buy';

		// Campaign list code start here. 
		$tbl 					=	'da_products';
		$wcon['where']          =  	array( 'stock'=> array('$gt'=> 0,),
										  'clossingSoon' => 'N',
										  'status' => 'A',
										  'remarks'=> array('$ne' => 'lotto-products')
										);
		$shortField 					=	['seq_order' => 1];

		$ProductList		=	$this->geneal_model->getProductWithPrizeDetails('multiple', $tbl, $wcon, $shortField);

		// Campaign filtering by validdate and drawdate wise.
		if($ProductList):

					$ourCampaigns = [];
					foreach($ProductList as $info2):
						$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':0';
						$drawDate2 			= 	$info2['draw_date'].' '.$info2['draw_time'].':0';
						$today2 			= 	date('Y-m-d H:i:s');

						if(strtotime($valid2) > strtotime($today2) && strtotime($drawDate2) > strtotime($today2)):
						
							array_push($ourCampaigns,$info2);

						endif;
					endforeach;
				endif;
				
				$data['products'] = $ourCampaigns;

		// echo "<pre>"; print_r($data['products']); die();
		$useragent=$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-quickbuy',$data);
		else:
			$this->load->view('quickbuy',$data);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : quickbuyticket
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to show single quick campaign.
	 * * Date          : 21 December 2023
	 * * **********************************************************************/
	public function quickbuyticket()
	{
		$data 					 =	array();
		$data['page']			 =	'Quick Buy Tickets';


		$USERID = $this->session->userdata('DZL_USERID');
		$pid    = base64_decode($this->input->get('pid'));
		$type   = $this->input->get('type');

		// Campaign list code start here. 
		$tableName 				=	'da_products';
		$whereCon['where']      =  	array( 'stock'=> array('$gt'=> 0),'clossingSoon' => 'N','status' => 'A','products_id' => (int)$pid );
		$shortField 			=	array('seq_order' => 1);
		$ProductList			=	$this->common_model->getData('single', $tableName, $whereCon, $shortField);
		$data['products'] 		= $ProductList;

		$wconUserCon['where']  =  array('users_id' => (int)$USERID);
		$userResult			   =  $this->geneal_model->getData2('single', 'da_users', $wconUserCon);
		$data['availableArabianPoints']= $userResult['availableArabianPoints'];
		$data['countryCodeData']=   countryCodeList();

		if($this->input->post('SaveChanges')):
			$error					=	'NO';


			$this->form_validation->set_rules('quantity', 'Quantity', 'trim|required');
			$this->form_validation->set_rules('available_balance', 'Available alance', 'trim|required');
			$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
			$this->form_validation->set_rules('users_mobile', 'Mobile No.', 'trim|required');
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('type', 'Type', 'trim|required');
			
			if($this->form_validation->run() && $error == 'NO'): 
				$productData 				= 	 explode('_____', $this->input->post('product_id'));
				$param['quantity']			= 	 $this->input->post('quantity');
				$param['available_balance']	= 	 $this->input->post('available_balance');
				$param['country_code']		= 	 $this->input->post('country_code');
				$param['users_mobile']		= 	 $this->input->post('users_mobile');
				$param['first_name']		= 	 $this->input->post('first_name');
				$param['last_name']			= 	 $this->input->post('last_name');
				$param['type']				= 	 $this->input->post('type');
				$param['total_price']		=	 (float)$ProductList['adepoints'] *$this->input->post('quantity');
				$param['is_donated']		=	 $this->input->post('is_donated')?'Y':'N';
				$param['product_id']		=	 (int)$pid;
				$param['users_id']			=	 (int)$USERID;
				$this->generatequickorder($param);

			endif;


			
			die();



			$error					=	'NO';
			$quantity 			= $this->input->post('quantity');
			$country_code 		= $this->input->post('country_code');
			$mobile  			= $this->input->post('mobile');
			$available_balance  = $this->input->post('available_balance');
			$first_name  		= $this->input->post('first_name');
			$last_name  		= $this->input->post('last_name');
			$type  				= $this->input->post('type');

			die();
			 
		endif;





		// echo "<pre>";print_r($data);die();

		$user_agent		  =$_SERVER['HTTP_USER_AGENT'];
		// if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
		// 	// $this->load->view('mobile-quick-buy-ticket',$data);
		// else:
			// $this->load->view('quick-buy-ticket',$data);
		// endif;
		
		$this->load->view('mobile-quick-buy-ticket',$data);

	}


	public function quickorders($pageno='')
	{	
		$data 			 =	array();
		$data['page']	 =	'Quick Orders';

		$userId = $this->session->userdata('DZL_USERID');
		// $userId = 100000000013118;

		$tblName 					=	'da_ticket_orders';
		
		$searchfield = $this->input->post('searchfield');
		if(!empty($searchfield)):
		$data['searchfield'] = $searchfield;
		 $whereCondition['like']    =   array(0 => 'order_users_mobile' , '1' => $searchfield);	
		endif;
		 $whereCondition['where']   =   array('user_id' => (int)$userId ,'order_status' => 'Success' );
		$shortField 				=	array('sequence_id' => -1 );
		$Orderlist 					= 	$this->common_model->getData('multiple',$tblName,$whereCondition,$shortField);

		if($Orderlist):
			$ticket_order_id = array();
			foreach ($Orderlist as $key => $items):
				 $ticket_order_id[$key] = $items['ticket_order_id'];
			endforeach;

			$tableName 		   		=   'da_ticket_coupons';
			$wcon['where']  		=   array( 'users_id' => (int)$userId);
			$wcon['where_in']		= 	array(array(0 => 'ticket_order_id' , '1' => $ticket_order_id));	
			$shortField 			=	array('coupon_id' => -1 );

			$totalRows 					= $this->common_model->getDataByNewQuery('','count',$tableName,$wcon,$shortField);
			// User Defined data  For Pagination Start.
			$itemsPerPage = (isset($PerPage)) ?  $PerPage : 10; // undefined added default value 1.

			// Total pages count .
			$totalPages  =  floor($totalRows / $itemsPerPage);
			$page 		 = (!empty($pageno)) ?  $pageno : 1; ;

			if($page == 1):
				$searchStart = 0;
			elseif($page == 2):
				$searchStart = $itemsPerPage-1;
			else:
				$searchStart = ($page*$itemsPerPage)-1;
			endif;

			// echo "item per page = ".$itemsPerPage .'<br>'; 
			// echo " pageno = ".$page .'<br>'; 
			// echo " searchStart = ".$searchStart .'<br>'; 
			// die();

			// User Defined data  For Pagination End.
			$userOrderList = $this->common_model->getDataByNewQuery('','multiple',$tableName,$wcon,$shortField,$itemsPerPage,$searchStart);
			// $userOrderList = $this->common_model->getDataByNewQuery('','multiple',$tableName,$wcon,$shortField);

			// echo "<pre>";print_r($userOrderList);die();

			$baseUrl 	=  base_url('quick-orders');
		    $data['pagination']			=	Pagination($baseUrl,$page,$totalPages ,$itemsPerPage,$totalRows);

			foreach ($userOrderList as $key => $item) :
				unset($userOrderList[$key]['draw_date']);
				unset($userOrderList[$key]['draw_time']);
				$userOrderList[$key]['status'] =  $Orderlist[$key]['status']?$Orderlist[$key]['status']:'';
				// $userOrderList[$key]['draw_date'] =  array($item['draw_date']);
				// $userOrderList[$key]['draw_time'] =  array($item['draw_time']);
			endforeach;

		endif;

		$data['orderData'] = $userOrderList;
 		
 		$useragent =$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-quickorders',$data);
		else:
			$this->load->view('quickorders',$data);
		endif;

	}

	public function quicksms($OrderID ='')
	{
		
		$tbaleName 			= "da_ticket_coupons";
		$whereCon['where']  = array('ticket_order_id' => $OrderID );
		$couponData         =  $this->common_model->getData('single',$tbaleName , $whereCon);

		if($couponData['order_users_mobile']):
			$this->sms_model->sendQuickTicketDetails($couponData['ticket_order_id'],$couponData['order_users_mobile'],$couponData['coupon_code'],$couponData['product_id'],$couponData['order_users_country_code'],$couponData['is_donated']);
		endif;

		if($couponData['order_users_email']):
			$this->emailsendgrid_model->sendQuickMailToUser($couponData['ticket_order_id']);
		endif;
		$this->session->set_flashdata('success','sms sent');
		return redirect('quick-orders');

	}

	public function download_invoice($id=""){
		$oid = base64_decode($id);
		
		$this->load->library('Mpdf');
		$tblName 				=	'da_orders';
		$shortField 			= 	array('_id'=> -1 );
		$whereCon['where']		= 	array('order_id'=>$oid);
	
		// $orderData  =	$this->geneal_model->getordersList('single', $tblName, $whereCon,$shortField);
			
		

		$tbl 					=	'da_ticket_coupons';
		$wcon['where']          =   array('ticket_order_id' => $oid);

		$order 					=	['coupon_id' => -1];
		$userOrderList	=	$this->geneal_model->GetQuickTicketHistory('multiple', $tbl, $wcon,$order);


		$where2 					=	['users_id' => (int)$userOrderList[0]['users_id'] ];
		$userData					=	$this->geneal_model->getOnlyOneData('da_users', $where2);

		//$name = explode('@',$data['orderData']['user_email']);
		$data['orderData'] = $userOrderList;
		$data['retailerData'] = $userData;
		

		// $this->load->view('web_api/order_pdf_template', $data);
		$this->load->view('web_api/generate_quick_ticket', $data);
		
		return true;
		// $this->session->set_flashdata('success','Receipt downloaded');
		// return redirect('quick-orders');
	}

	public function DownlodeOrderPDF($file='')
	{  
		if (file_exists($this->config->item("root_path")."/assets/orderpdf/".$file)) {
			header('Content-Description: File Transfer');
			// We'll be outputting a PDF
			header('Content-type: application/pdf');
			// It will be called downloaded.pdf
			header('Content-Disposition: attachment; filename="'.$file.'"');
			// The PDF source is in original.pdf
			readfile($this->config->item("root_path")."/assets/orderpdf/".$file);
			
			@unlink($this->config->item("root_path")."/assets/orderpdf/".$file);
			exit;
		}
	}


	public function quicksummeryreport()
	{	
		$data 			 =	array();
		$data['page']	 =	'Quick Orders Summery';

			$userId = $this->session->userdata('DZL_USERID');
			// $userId = 100000000013118;
			$product_title =  $from_date = $this->input->post('product_title');
		  	if($this->input->post('start_date')):
		  		$start_date = date('Y-m-d 00:01' ,strtotime($this->input->post('start_date')) );
		  	endif;

		  	if($this->input->post('end_date')):
		      	$end_date = date('Y-m-d 23:59' ,strtotime($this->input->post('end_date')));
		  	endif;
	      	
	      	if(!empty($product_title)):
	      		$data['product_title'] = $product_title;
	      	endif;
	      	if(!empty($start_date)):
	      		$data['start_date'] = $start_date;
	      	endif;
	      	if(!empty($end_date)):
	      		$data['end_date'] = $end_date;
	      	endif;

			if($product_title):
				$wcon['like']    =   array(0 => 'product_title' , '1' => $product_title);	
			endif;

			if(!empty($start_date)):
	      		$wcon['where_gte'] =   array(array(0 => 'created_at' , '1' => $start_date));	
	      	endif;

	      	if(!empty($end_date)):
	      		$wcon['where_lte'] =   array(array(0 => 'created_at' , '1' => $end_date));	
	      	endif;
			$wcon['where'] 			=   array('user_id' => (int)$userId , 'status' => array('$ne'=> 'CL') );
				
				$wconUserCon['where']  =  array('users_id' => (int)$userId);
				$userResult			   =  $this->geneal_model->getData2('single', 'da_users', $wconUserCon);
				$data['totalArabianPoints'] 			= $userResult['totalArabianPoints'];
				$data['availableArabianPoints'] 		= $userResult['availableArabianPoints'];


				$tbl 				=	'da_ticket_orders';
			 	$shortField 		=	array('sequence_id' => -1 );
				// $totalRows 			= $this->common_model->getData('count',$tbl,$wcon ,$shortField);
				// // User Defined data  For Pagination Start.
				// $itemsPerPage = (isset($PerPage)) ?  $PerPage : 10; // undefined added default value 1.
				// // Total pages count .
				// $totalPages  =  floor($totalRows / $itemsPerPage);
				// $page 		 = (!empty($pageno)) ?  $pageno : 1; ;
				// if($page == 1):
				// 	$searchStart = 0;
				// elseif($page == 2):
				// 	$searchStart = $itemsPerPage-1;
				// else:
				// 	$searchStart = ($page*$itemsPerPage)-1;
				// endif;

				// $orderList 			= $this->common_model->getData('multiple',$tbl,$wcon ,$shortField,$itemsPerPage,$searchStart);
				$orderList 			= $this->common_model->getData('multiple',$tbl,$wcon ,$shortField);
				
 				
 				$productId 				= array();
				//Getting product IDs
				foreach($orderList as $key => $items):
				  if(!in_array($items['product_id'],$productId)):
					$productId[] = $items['product_id'];
				  endif;
				endforeach;

				$productImage = array();
				$products = array();

				foreach ($productId as $key => $pid):
					// Gettting product image from product collection.
					$tableName 		  = 'da_products' ;
					$where['where']   = array( 'products_id' => (int)$pid);
					$product    	  = $this->common_model->getDataByNewQuery(array('product_image'),'single',$tableName,$where);


					$product_image = $product['product_image'] ;

					// Creating Data package as per product id.....
					$sum = 0;
					$sales_count = 0;
					$total_price = 0;
					foreach($orderList as $key => $items):
						$total_price += $items['total_price'];
							if($items['product_id'] == $pid):
								if($sum == 0):
								   $data1['product_is_donate']  = array();
								endif;
								$sum 									       = $sum + $items['total_price'];
								$sales_count  						   		   = $sales_count+(int)$items['product_qty'];
							 	$data1['_id']       				       		   = $items['product_title'];
							    $data1['price']			  		       		   = $items['total_price'];
							    $data1['product_image']			       		   = array($product_image);
								$data1['sales_count']  						   = $sales_count;
							    $data1['sales'] 			  		       		   = $sum;
							    $data1['product_is_donate'][]       	   		   = $items['product_is_donate'];
							    $availableArabianPoints[]  					   = $items['availableArabianPoints'];
							    $end_balance[] 			   				       = $items['end_balance'];
							endif;
					endforeach;

				    $data1['availableArabianPoints']   =   reset($end_balance);
				    $data1['end_balance']   =   end($end_balance);
					array_push($products, $data1);
				endforeach;

				$data['total_sales'] = $total_price?$total_price:0;
				$data['products'] = $products;

				if($this->input->post('product_title') || $this->input->post('start_date')  || $this->input->post('end_date') ):
					$availableArabianPoints = $data['products'][0]['availableArabianPoints'];
					$data['totalArabianPoints'] 	  =	$availableArabianPoints?$availableArabianPoints: 0;
					$end_balance = $data['products'][0]['end_balance'];
					$data['availableArabianPoints']   =	$end_balance?$end_balance:0;
				endif;
 		$useragent =$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-summeryreport',$data);
		else:
			$this->load->view('summeryreport',$data);
		endif;

	}


	function generatequickorder($param=''){
		
		// Validate Produc
		$where 			=	array('products_id' => (int)$param['product_id']);
		$tblName 		=	'da_products';
		$productData 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
 		 
	 	$pid    = $this->input->get('pid');
		$type   = $this->input->get('type');

		$url = base_url('quick-buy-ticket/?pid='.$pid.'&type='.$type);
		if(empty($productData)):
			$this->session->set_flashdata('error', lang('Product_not_found'));
    		redirect($url);die();

		elseif($productData['stock'] < $this->input->post('product_qty') ):
			$this->session->set_flashdata('error', lang('PRO_QTY'));
    		redirect($url);die();
		endif;
 
		// Validate product availablity
		$validUpto 		=	strtotime(date('Y-m-d H:i:s',strtotime($productData['validuptodate'].' '.$productData['validuptotime'])));
		$currDate 		=	strtotime(date('Y-m-d H:i:s'));
				
		if($validUpto < $currDate){
			$this->session->set_flashdata('error', lang('PRODUCT_EXPIRE'));
    		redirect($url);die();
		}
		
		//END
		$where 			=	array('users_id' => (int)$param['users_id']);
		$tblName 		=	'da_users';
		$sellerDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

		//Check user exits or not.
		$wcon['where']			=	[ 'users_mobile' => (int)$this->input->post('users_mobile') ,'status' => "A" ];
		$Userdata 		=	$this->geneal_model->getData2('count', 'da_quick_users', $wcon);
		
		if($Userdata== 0 ):
			$user["sequence_id"]	= (int)$this->geneal_model->getNextSequence('da_quick_users');
			$user['first_name'] 	= $this->input->post('first_name');
			$user['last_name'] 		= $this->input->post('last_name');
			$user['country_code'] 	= $this->input->post('country_code');
			$user['users_mobile'] 	= (int)$this->input->post('users_mobile');
			$user['users_email'] 	= $this->input->post('users_email');
			$user["status"] 		=	"A";
		    $user["creation_ip"] 	=	$this->input->ip_address();
		    $user["created_at"] 	=	date('Y-m-d H:i');
			
			//create new user.
			$orderInsertID 			= $this->geneal_model->addData('da_quick_users', $user);
		endif;
    			
		if(!empty($sellerDetails)):
			
			//Get current Ticket order sequence from admin panel.
			$tblName = 'da_tickets_sequence';
			$whereCon2['where']		 			= 	array('product_id' => (int)$param['product_id'] , 'status' => 'A');	
			$shortField 						= 	array('tickets_seq_id'=>'DESC');
			$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

			$tblName = 'da_quickcoupons_totallist';
			$whereCon3['where']		 			= 	array('product_id' => (int)$param['product_id'] ,'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] );	
			$shortField3 						= 	array('coupon_id'=>'DESC');
			$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

			$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
					
			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
			endif;

			if($this->input->post('product_is_donate') == 'Y'):
				$check_availblity = $this->input->post('product_qty') * 2;
			else:
				$check_availblity = $this->input->post('product_qty');
			endif;
					
			$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  

			if($left_ticket < 0):
				$this->session->set_flashdata('error', lang('TICKET_FULL'));
    			redirect($url);die();
			endif;

			if((int)$this->input->post('product_qty') >1):
				if((float)$this->input->post('vat_amount') + (float)$this->input->post('subtotal') != (float)$this->input->post('total_price')):
					$this->session->set_flashdata('error', lang('PRICE_ERROR'));
    				redirect($url);die();
				endif;
			endif;

			if($sellerDetails['availableArabianPoints'] >= (float)$this->input->post('total_price')):

					if(!empty($sellerDetails)):
							if($sellerDetails['status'] == 'A'):
								
								$tableName  		= 'da_prize';
								$shortField  		= array('product_id' => -1);
								$whereCon['where']  = array('product_id' => (int)$param['product_id']);
								$prizeData  		= $this->common_model->getData('single',$tableName,$whereCon,$shortField);

								//Order param start here. 
								$ORparam["sequence_id"]		    		=	(int)$this->geneal_model->getNextSequence('da_ticket_orders');
						        $ORparam["ticket_order_id"]		        =	$this->geneal_model->getNextQuickBuyOrderId();
						        $ORparam["user_id"] 					=	(int)$param['users_id'];
						        $ORparam["user_type"] 					=	$sellerDetails['users_type']; //$this->input->post('user_type');
					        	$ORparam["user_email"] 					=   $sellerDetails['users_email'];	//$this->input->post('user_email');
							 	$ORparam["user_phone"] 					=	$sellerDetails['users_mobile'];	//$this->input->post('user_phone');
						     	$ORparam["product_id"] 					=	(int)$param['product_id'];
						     	$ORparam["product_title"] 				=	$productData['title'];
						     	$ORparam["product_qty"] 				=	$param['quantity'];;
						     	$ORparam["product_color"] 				=	$param['product_color'];
						     	$ORparam["product_size"] 				=	$param['product_size'];
						     	$ORparam["prize_title"] 				=	$prizeData['title'];
						        $ORparam["vat_amount"] 					=	(float)$param['vat_amount'];
						        $ORparam["subtotal"] 					=	(float)$param['subtotal'];
						        $ORparam["total_price"] 				=	(float)$param['total_price'];;
						        $ORparam["availableArabianPoints"] 		=	(float)$sellerDetails["availableArabianPoints"];
								$ORparam["end_balance"] 				=	(float)$sellerDetails["availableArabianPoints"] - (float)$ORparam["total_price"] ;
							    $ORparam["payment_mode"] 				=	'Arabian Points';
							    $ORparam["payment_from"] 				=	'Quick';
						        $ORparam["product_is_donate"] 			=	$param['is_donated']?$param['is_donated']:'N'; //$this->input->post('product_is_donate');
							    $ORparam["order_status"] 				=	"Success";
							    $ORparam["device_type"] 				=	'';
				   				$ORparam["app_version"] 				=	'';
						     	$ORparam["order_first_name"] 			=	$param['first_name'];
						     	$ORparam["order_last_name"] 			=	$param['last_name'];
						     	$ORparam["order_users_country_code"] 	=	$param['country_code']?$param['country_code']:"+971";
						     	$ORparam["order_users_mobile"] 			=	$param['users_mobile'];
						     	$ORparam["order_users_email"] 			=	$param['users_email'];
						     	$ORparam["SMS"] 						=	$param['SMS']?$param['SMS']:'Y';
						     	$ORparam["mail"] 						=	$param['mail'];
							    $ORparam["creation_ip"] 				=	$this->input->ip_address();
							    $ORparam["created_at"] 					=	date('Y-m-d H:i');

							    //Saving order details for Ticket
							    $orderInsertID 							=	$this->geneal_model->addData('da_ticket_orders', $ORparam);
						    	$result['successData']					=	$this->successPaymentByArabiyanPoints($ORparam);
						    	
						    	$this->session->set_flashdata('success', lang('ORDER_SUCCESS'));
    							redirect('quick-buy');die();

							endif;
					else:
						$this->session->set_flashdata('error', lang('USER_ID_INCORRECT'));
    					redirect($url);die();
					endif;
			else:
					$this->session->set_flashdata('error', lang('LOW_BALANCE'));
    				redirect($url);die();
			endif;
		endif;

	}


	public function successPaymentByArabiyanPoints($ORparam=array())
	{	
		//Get current order of user.
		$wcon['where']					=	[ 'ticket_order_id' => $ORparam["ticket_order_id"]];
		$data['orderData'] 				=	$this->geneal_model->getData2('single', 'da_ticket_orders', $wcon);
		
		$results = array();
		$couponList = array();

		$tblName 					=	'da_products';
		$where['where'] 			= 	array( 'products_id'=> $ORparam['product_id'] ,'status' => 'A');
		$Sortdata					=	array('category_id'=> 'DESC');
		$productDetails				=	$this->geneal_model->getData2('single', $tblName, $where, $Sortdata);

		// Stock update section..
		$stock 						= $productDetails['stock'] - $ORparam['product_qty']  ; // updated stock.

		$updateParams 				=	array( 'stock' => (int)$stock );	
		$updatedstatus 				= $this->geneal_model->editData('da_products',$updateParams,'products_id',(int)$ORparam['product_id']);
		
		$updateParams 					=	array( 'order_status' => 'Success','created_at' => $ORparam['created_at']);	
		$updatedstatus = $this->geneal_model->editData('da_ticket_orders',$updateParams,'users_id',(int)$ORparam['user_id']);

		// Deduct the purchesed points and get available arabian points of user.
		$currentBal 					= 	$this->geneal_model->debitPointsByAPI($ORparam['total_price'],$ORparam["user_id"]); 

		// //Get current Ticket order sequence from admin panel.
		$tblName = 'da_tickets_sequence';
		$whereCon2['where']		 			= 	array('product_id' => (int)$ORparam['product_id'] , 'status' => 'A');	
		$shortField 						= 	array('tickets_seq_id'=>'DESC');
		$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

		$tblName = 'da_quickcoupons_totallist';
		$whereCon3['where']		 			= 	array('product_id' => (int)$ORparam['product_id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id']);	
		$shortField3 						= 	array('coupon_id'=>'DESC');
		$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

		$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
		
		if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
			$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
		endif;



		if($ORparam['product_is_donate'] == 'Y'):
			$check_availblity = $this->input->post('product_qty') * 2;
		endif;

		$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  

		//Get current sponsored coupon count from product.
		$wconsponsoredCount['where'] 	=	array('products_id' => (int)$ORparam['product_id'] );
		$productDetails 				=	$this->geneal_model->getData2('single', 'da_products', $wconsponsoredCount);

		if($productDetails['sponsored_coupon']):
			$sponsored_coupon = $productDetails['sponsored_coupon'];
		else:
			$sponsored_coupon = 1;
		endif;
		//END

		if($ORparam['product_is_donate'] == 'N' ):
			$soldOutQty = $ORparam['product_qty']*$sponsored_coupon ;
		elseif($ORparam['product_is_donate'] == 'Y'):
		 	$soldOutQty = $ORparam['product_qty']*$sponsored_coupon*2 ;
		endif;

		if($this->input->get('type') == 'buy_voucher'):
			$soldOutQty = 2*$ORparam['product_qty']*$productDetails['sponsored_coupon'];
		endif;

		// Created 1st ticket sequence record.
		if(empty($SoldoutTicketList['coupon_sold_number'])):
			
			//Storing new ticket sequence in da_quickcoupons_totallist
			$quickcoupons["id"]					=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
			$quickcoupons["product_id"] 		=	(int)$ORparam['product_id'];
		    $quickcoupons["tickets_seq_id"] 	=	(int)$CurrentTicketSequence['tickets_seq_id'];
		    $quickcoupons["coupon_sold_number"] =	'';//$soldOutQty;
		    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
		    $quickcoupons["created_at"] 		=	date('Y-m-d H:i');

		    //Saving quick coupons number  
		    $orderInsertID 						=	$this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);

		else:
			// checking existing ticket sequence record.
			if($SoldoutTicketList['tickets_seq_id'] != $CurrentTicketSequence['tickets_seq_id'] ):
			//Storing new ticket sequence in da_quickcoupons_totallist
			$quickcoupons["id"]					=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
			$quickcoupons["product_id"] 		=	(int)$ORparam['product_id'];
		    $quickcoupons["tickets_seq_id"] =		(int)$CurrentTicketSequence['tickets_seq_id'];
		    $quickcoupons["coupon_sold_number"] =	''; //$soldOutQty;
		    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
		    $quickcoupons["created_at"] 		=	date('Y-m-d H:i');

		    //Saving quick coupons number  
		    $orderInsertID 						=	$this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);
				
			endif;

		endif;


		$available_ticket =  $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
		

		if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
			$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
		endif;

			if ($available_ticket > $coupon_sold_number ):

				// Getting product details
				$wcon2['where']					=	array('products_id' => (int)$ORparam['product_id'] );
				$ProductData        			=	$this->geneal_model->getData2('single', 'da_products', $wcon2);

				if( !empty($ORparam["product_is_donate"]) && !empty($CurrentTicketSequence)):

						for($i=1; $i <= $soldOutQty; $i++){
							if($CurrentTicketSequence['tickets_sequence_start']):
								
								// generating ticket order..
								if(!empty($SoldoutTicketList['coupon_sold_number'])):
									 $TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $SoldoutTicketList['coupon_sold_number'] +$i;
									 $totalsoldqty = $SoldoutTicketList['coupon_sold_number'] +$i;
								else:
									$TicketSequence = $CurrentTicketSequence['tickets_sequence_start']+ $i;
									$totalsoldqty = $i;
								endif;
								$coupon_code = $CurrentTicketSequence['tickets_prefix'].$TicketSequence;

								array_push($couponList,$coupon_code);
								
								//Storing new ticket sequence in da_quickcoupons_totallist start
								// $quickcoupons["product_id"] 		=	(int)$ORparam['product_id'];
							 	$quickcoupons1["coupon_sold_number"] =	(int)$totalsoldqty;
							    $quickcoupons1["creation_ip"] 		=	$this->input->ip_address();
							    $quickcoupons1["updated_at"] 		=	date('Y-m-d H:i');
							    //Saving quick coupons number  
								// echo $totalsoldqty.'<br>';

								$this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
							    //End

							endif;
						}

						if($this->input->get('type') == 'buy_voucher'):
					    	$couponData['isVoucher']			= 	'Y';
					    	$couponData['voucher_code']			= 	generateRandomString(10,"n");
						else:
					    	$couponData['isVoucher']			= 	'N';
						endif;
						$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
						$couponData['users_id']			= 	(int)$ORparam["user_id"];
						$couponData['users_email']		= 	$ORparam["user_email"];

						$couponData['order_first_name']		= 	$ORparam["order_first_name"];
						$couponData['order_last_name']		= 	$ORparam["order_last_name"];
						$couponData['order_users_country_code']	= 	$ORparam["order_users_country_code"]?$ORparam["order_users_country_code"]:"+971";
						$couponData['order_users_mobile']	= 	$ORparam["order_users_mobile"];
						$couponData['order_users_email']	= 	$ORparam["order_users_email"];
						$couponData['ticket_order_id']		= 	$ORparam["ticket_order_id"];
						$couponData['product_id']			= 	(int)$ORparam['product_id'];
						$couponData['product_title']		= 	$ORparam['product_title'];
						$couponData['product_qty']			= 	$ORparam['product_qty'];
						$couponData['total_price']			= 	$ORparam['total_price'];
						$couponData["product_qty"] 			=	$ORparam['product_qty'];
						$couponData["product_color"] 		=	$ORparam['product_color'];
						$couponData["prize_title"] 			=	$ORparam['prize_title'];
						$couponData["device_type"] 			=	$ORparam['device_type'];
					    $couponData["app_version"] 			=	$ORparam['app_version'];
						$couponData['is_donated'] 			=	$ORparam["product_is_donate"];
						$couponData['coupon_status'] 		=	'Live';
						$couponData['coupon_code'] 			= 	$couponList;
						if($ORparam["product_is_donate"] == "Y"):
						$couponData['coupon_type'] 			= 	'Donated';
						endif;
						$couponData['draw_date'] 			= 	array($ProductData['draw_date']);
						$couponData['draw_time'] 			= 	array($ProductData['draw_time']);
						$couponData['created_at']			=	date('Y-m-d H:i');


						$this->geneal_model->addData('da_ticket_coupons',$couponData);

						$results = $couponData;

						$mail = $this->input->post('mail');
						if($couponData['order_users_email'] && !empty($mail) ):
							$this->emailsendgrid_model->sendQuickMailToUser($couponData['ticket_order_id']);
						endif;

						if($ORparam['SMS'] == "Y"):
							$this->sms_model->sendQuickTicketDetails($couponData['ticket_order_id'],$couponData['order_users_mobile'],$couponList,$couponData['product_id'],$couponData['order_users_country_code'],$couponData['is_donated']);
						endif;
				endif;

			else:

				$pid    = $this->input->get('pid');
				$type   = $this->input->get('type');

				$url = base_url('quick-buy-ticket/?pid='.$pid.'&type='.$type);
				$this->session->set_flashdata('error', lang('Ticket_FULL'));
				redirect($url);die();
			endif;
			//Start Create Coupons for donate product
	}


 
}


