<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quickbuy extends CI_Controller {
public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model(array('geneal_model','common_model','emailsendgrid_model','sms_model'));
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
				// $result 			=  $this->common_model->CheckAvailableTickets($productID,$productQty ,$productIsDonated,$Plateform,$CouponGenerate);

				$country_code = $this->session->userdata('Quick_country_code');
				$users_mobile = $this->session->userdata('Quick_users_mobile');

				//Otp verified checked
				$whereCon['where'] 	 =  array('country_code' => $country_code ,'users_mobile' => (int)$users_mobile, "is_verify" => "Y");
				$ISvarified          =  $this->geneal_model->getData2('count','da_quick_users', $whereCon);

				if($ISvarified  === 1 ):
					$USRUpdate['first_name']	= $this->input->post('first_name');
					$USRUpdate['last_name']		= $this->input->post('last_name');
					
					$UpdatewhereCon['where'] 	=  array('country_code' => $country_code ,'users_mobile' => (int)$users_mobile);
					$this->geneal_model->editDataByMultipleCondition('da_quick_users',$USRUpdate,$UpdatewhereCon['where']);
				endif;

				// Validate Produc
				$where 			=	['products_id' => (int)$productID];
				$tblName 		=	'da_products';
				$productData 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(empty($productData)):
					$this->session->set_flashdata('error', lang('Product_not_found'));
			    	redirect('/');
					die();
				elseif($productData['stock'] < $this->input->post('quantity') ):
					$this->session->set_flashdata('error',lang('PRO_QTY'));
			    	redirect('/');
					die();
				endif;
				
				// Validate product availablity
				$validUpto 		=	strtotime(date('Y-m-d H:i:s',strtotime($productData['validuptodate'].' '.$productData['validuptotime'])));
				$currDate 		=	strtotime(date('Y-m-d H:i:s'));

				if($validUpto < $currDate){
					$this->session->set_flashdata('error',lang('PRODUCT_EXPIRE'));
			    	redirect('/');
					die();
				}

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

	

}


