<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Order extends My_Head {
public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model(array('geneal_model','common_model','emailsendgrid_model','sms_model'));
		$this->lang->load('statictext','front');
	} 
	/***********************************************************************
	** Function name 	: index
	** Developed By 	: RAVI NEGI
	** Purpose 			: This function used for placed order
	** Date 			: 04 MAY 2022
	** Updated By		: Dilip Halder
	** Updated Date 	: 25 March 2023
	************************************************************************/ 		
	public function index()
	{  
		$data  = array();
		$data['page']			=	'Checkout';
		$finalPrice = 0;
		$data['sufficient_point_error'] =  '';
		$data['post_sufficient_point_error'] =  '';
		$data['product_is_in_donate'] 		 =  'Y';

		$where               =      ['users_id' => (int)$this->session->userdata('DZL_USERID') ];
		$user_data           =      $this->geneal_model->getOnlyOneData('da_users', $where);
		$where2              =      ['user_id' => (int)$this->session->userdata('DZL_USERID') ];
		$user_address        =      $this->geneal_model->getData('da_diliveryAddress', $where2,[]);
		$data['ToatlPoints'] =      $user_data['availableArabianPoints'];
		$data['dilivertAddress'] = 	$user_address;

		// if($this->session->userdata('DZL_USEREMAIL') != 'afsarali509@gmail.com'){
		// 	$this->session->set_flashdata('error', 'Please try after 20 min.');
		// 	redirect('user-cart');
		// }
		
		$data['enabledPayment'] =	$this->geneal_model->getData2('single', 'da_paymentmode');

		if($this->session->userdata('DZL_USERID')){
			$wcon['where']		=	[ 'user_id' => (int)$this->session->userdata('DZL_USERID') ];
			$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
			//echo '<pre>';print_r($cartItems);die();
			foreach($cartItems as $CA):
				$finalPrice += $CA['qty'] * $CA['price'];
				//check Stock
				$stockCheck = $this->geneal_model->getStock($CA['id'], $CA['qty']);
				
				// Available prodcut and soldout product details..
				$tblName = 'da_tickets_sequence';
				$whereCon2['where']		 			= 	array('product_id' => (int)$CA['id'] , 'status' => 'A');	
				$shortField 						= 	array('tickets_seq_id'=>'DESC');
				$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

				$tblName = 'da_quickcoupons_totallist';
				$whereCon3['where']		 			= 	array('product_id' => (int)$CA['id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] );	
				$shortField3 						= 	array('tickets_seq_id'=>'DESC');
				$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

				$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
				
				if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
					$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
				endif;

				if( $CA['is_donated'] == "Y"):
					$check_availblity = $CA['qty'] * 2;
				endif;
				
				$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  
                
				if($CA['is_donated'] == "Y" && $left_ticket <= 0  ):
					$this->session->set_flashdata('error', lang('TICKET_NOT_AVAILABLE'). " for " .$CA['name'] );
	        		redirect('user-cart');
					die();
				endif;

				if($CA['is_donated'] == "N" && $left_ticket <1):
					$this->session->set_flashdata('error', lang('TICKET_NOT_AVAILABLE'). " for " .$CA['name'] );
	        		redirect('user-cart');
					die();
				endif;

				if($CA['is_donated'] == 'N'):
					$data['product_is_in_donate'] =  'N';
				endif;
			endforeach;

			if($data['product_is_in_donate'] == 'N'):
				$data['shipping']   = 0;//SHIPPING_CHARGE;
			else:
				$data['shipping']   = 0;
			endif;
			//$data['finaltotal'] = $finalPrice + $data['shipping'];
			$data['finaltotal'] = $finalPrice;

			if(($data['finaltotal']+$data['shipping']) > $data['ToatlPoints']){
				// echo 'here';die();
			    $data['sufficient_point_error'] =  "You don't have sufficient Arabian points";
			    $data['post_sufficient_point_error'] =  "You don't have sufficient Arabian points";
			}
		}else{
			redirect('login');
		}

		if($this->input->post('placeOrder') == 'YES'){
			$this->form_validation->set_error_delimiters('', '');
			$data['error']			=	'NO';
			$this->form_validation->set_rules('collection_points', 'Collection Point', 'trim|required');
			if($this->input->post('product_is_donate') == 'N'):
				// foreach($cartItems as $CA):
				// 	$collection_pointData  				=	explode('_____',$this->input->post('collection_points'));
				// 	$inventoryStock = $this->geneal_model->getInventoryStock($collection_pointData[2], $CA['id'], $CA['qty']);
				// 	if($inventoryStock == 'error'){
				// 		$this->session->set_flashdata('error', 'OOPs!! Product out of stock. Please select another collection point.');
				// 			redirect('checkout');
				// 	}
				// endforeach;
			endif;
			if($stockCheck == 0){
				$this->session->set_flashdata('error', lang('OUTOFSTOCK'));
		        redirect('user-cart');
			}elseif ($stockCheck <> 1) {
				$this->session->set_flashdata('error', lang('PRO_QTY'));
		        redirect('user-cart');
			}


			if($this->form_validation->run() && $data['error'] == 'NO'): 
				if($this->input->post('product_is_donate') == 'N' && $this->input->post('shipping_method') == 'ship' && $this->input->post('address') == 'N'):
					$this->session->set_flashdata('error', 'Without address not proceed order');
					redirect('checkout');
				else:  

					if($this->input->post('payment_method') == 'arabianpoint'){

						if($data['finaltotal']+$data['shipping'] > $data['ToatlPoints']){
						    //$this->session->set_flashdata('sufficient_point_error', 'You have not sufficient Arabian points');
						    //redirect('checkout');
						    $data['post_sufficient_point_error'] =  "You don't have sufficient Arabian points";
						}else{
							$productCount          				=	$this->geneal_model->getData2('count', 'da_cartItems', $wcon);
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
						        foreach($cartItems as $CA):	
									//Manage Inventory
									if($this->input->post('product_is_donate') == 'N'):
										$where['where']		=	array(
																	'products_id'			=>	(int)$CA['id'],
																	'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
																);
										$INVcheck	=	$this->geneal_model->getData2('single','da_inventory',$where);
										if($INVcheck <> ''):
											$orqty = $INVcheck['order_request_qty'] + (int)$CA['qty'];
											$INVUpdate['order_request_qty']		=	$orqty;
											$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$where['where']);
										else:
											$INVparam['products_id']				= 	(int)$CA['id'];
											$INVparam['qty']						=	(int)0;
											$INVparam['available_qty']				=	(int)0;
											$INVparam['order_request_qty']			=	(int)$CA['qty'];
											$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

											$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
											
											$INVparam['creation_ip']				=	currentIp();
											$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
											$INVparam['status']						=	'A';
											$this->geneal_model->addData('da_inventory', $INVparam);
										endif;
									endif;
									//END
									$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
									$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
									$ORDparam["order_id"]			=	$ORparam["order_id"];
									$ORDparam["user_id"]			=	(int)$CA['user_id'];
									$ORDparam["product_id"] 		=	(int)$CA['id'];
									$ORDparam["product_name"] 		=	$CA['name'];
									$ORDparam["quantity"] 		    =	(int)$CA['qty'];
									if($CA['color']):
										$ORDparam["color"] 		    =	$CA['color'];
									endif;
									if($CA['size']):
										$ORDparam["size"] 		    =	$CA['size'];
									endif;
									$ORDparam["price"] 		        =	(float)$CA['price'];
									$ORDparam["tax"] 		        =	(float)0;
									$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
									$ORDparam["is_donated"] 		=	$CA['is_donated'];
									$ORDparam["other"] 		        =	array(
																				'image' 		=>	$CA['other']->image,
																				'description' 	=>	$CA['other']->description,
																				'aed'			=>	$CA['other']->aed
																			);
									$ORDparam["current_ip"] 		=	$CA['current_ip'];
									$ORDparam["rowid"] 				=	$CA['rowid'];
									$ORDparam["curprodrowid"] 		=	$CA['curprodrowid'];

									$this->geneal_model->addData('da_orders_details', $ORDparam);
								endforeach;
							endif;
							$this->session->set_userdata(array('payment_method' => $this->input->post('payment_method'),'payment_total_price' => $this->input->post('inclusice_of_vat')));
							$this->session->set_userdata('CURRENT_ORDER_ID',$ORparam["order_id"]);
							redirect('order-success/');
					    	/* End */
						}
					}else{
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
						elseif($this->input->post('payment_method') == 'Ngenius'):
							redirect('ngenius');
						endif;
					}
				endif;
			endif;
		}

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
		if($this->session->userdata('Quick_buy') === "Y"):

			 // Getting user details .
			$country_code		= $this->session->userdata('Quick_country_code');
			$users_mobile		= $this->session->userdata('Quick_users_mobile');
			$products_id		= $this->session->userdata('Quick_products_id');
			$adepoints			= $this->session->userdata('Quick_adepoints');
			$is_donated			= $this->session->userdata('Quick_is_donated');
			$title				= $this->session->userdata('Quick_title');
			$Quick_buy			= $this->session->userdata('Quick_buy');

			$quantity			= $this->session->userdata('quantity');
			$first_name			= $this->session->userdata('first_name');
			$last_name			= $this->session->userdata('last_name');


			$finalPrice = $quantity* $adepoints;

			//check Stock
			$stockCheck = $this->geneal_model->getStock( $products_id, $quantity );
			if($is_donated == 'N'):
				$data['product_is_in_donate'] 		 =  'N';
			endif;
			$data['finalprice'] = $finalPrice;
			if($is_donated == 'N'):
				$data['shipping']   = 0;//SHIPPING_CHARGE;
			else:
				$data['shipping']   = 0;
			endif;
			//$data['finaltotal'] 	= $finalPrice + $data['shipping'];
			$data['finaltotal'] 	= $finalPrice;

			$productCount 			= $quantity;
			
		else:
			if($this->session->userdata('DZL_USERID')):
				$wcon['where']		=	[ 'user_id' => (int)$this->session->userdata('DZL_USERID') ];
				$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
				foreach($cartItems as $CA):
					$finalPrice += $CA['qty'] * $CA['price'];
					//check Stock
					$stockCheck = $this->geneal_model->getStock($CA['id'], $CA['qty']);
					if($CA['is_donated'] == 'N'):
						$data['product_is_in_donate'] 		 =  'N';
					endif;
				endforeach;
				$data['finalprice'] = $finalPrice;
				if($data['product_is_in_donate'] == 'N'):
					$data['shipping']   = 0;//SHIPPING_CHARGE;
				else:
					$data['shipping']   = 0;
				endif;
				//$data['finaltotal'] = $finalPrice + $data['shipping'];
				$data['finaltotal'] = $finalPrice;
			else:
				redirect('login');
			endif;
			$productCount 			 =	$this->geneal_model->getData2('count', 'da_cartItems', $wcon);
		endif;

			if(!empty($this->session->userdata('DZL_USERID'))):
				$where  =      array('users_id' => (int)$this->session->userdata('DZL_USERID'));
			else:
				$where  =      array( 'country_code' => $country_code , 'users_mobile' => (int)$users_mobile );
			endif;

			$user_data  =      $this->geneal_model->getOnlyOneData('da_users', $where);

    		$ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
	        $ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
	        $ORparam["user_id"] 				=	(int)$this->session->userdata('DZL_USERID');
	        $ORparam["user_type"] 				=	$this->session->userdata('DZL_USERSTYPE')?$this->session->userdata('DZL_USERSTYPE'):'Users';
	        $ORparam["user_email"] 				=	$this->session->userdata('DZL_USEREMAIL');
	        $ORparam["country_code"] 			=	$country_code;
	        $ORparam["user_phone"] 				=	$this->session->userdata('DZL_USERMOBILE')?$this->session->userdata('DZL_USERMOBILE'):$users_mobile;

	        $ORparam["product_is_donate"] 		=	$this->session->userdata('payment_product_is_donate');
	        if($ORparam["product_is_donate"] == 'N'):
	        	$ORparam["shipping_method"] 	=	$this->session->userdata('payment_shipping_method');
			    $ORparam["emirate_id"] 				=	$this->session->userdata('payment_emirate_id');
	        	$ORparam["emirate_name"] 			=	$this->session->userdata('payment_emirate_name');
				$ORparam["area_id"] 				=	$this->session->userdata('payment_area_id');
	        	$ORparam["area_name"] 				=	$this->session->userdata('payment_area_name');
	        	$ORparam["collection_point_id"] 	=	$this->session->userdata('payment_collection_point_id');
	        	$ORparam["collection_point_name"] 	=	$this->session->userdata('payment_collection_point_name');

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



	        $ORparam["shipping_charge"] 		=	(float)$this->session->userdata('payment_shipping_charge');
	        $ORparam["inclusice_of_vat"] 		=	(float)$this->session->userdata('payment_inclusice_of_vat');
	        $ORparam["subtotal"] 				=	(float)$this->session->userdata('payment_subtotal');
	        $ORparam["vat_amount"] 				=	(float)$this->session->userdata('payment_vat_amount');
	        $ORparam["total_price"] 			=	(float)$this->session->userdata('payment_total_price');
		    $ORparam["payment_mode"] 			=	'Stripe';
		    $ORparam["payment_from"] 			=	'Web';
		    $ORparam["order_status"] 			=	"Initialize";
		   	$ORparam["availableArabianPoints"] 	=	(float)$user_data["availableArabianPoints"];
	        $ORparam["end_balance"] 			=	(float)$user_data["availableArabianPoints"];
	        $ORparam["remark"] 					=	'Quick mobile Web';
		    $ORparam["creation_ip"] 			=	$this->input->ip_address();
		    $ORparam["created_at"] 				=	date('Y-m-d H:i');
		    
		    $orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);
   			
		    //These field for dealzareabia.ea site.
   			$ORparam["product_count"] 			=	(int)$productCount;
    		$ORparam["finaltotal"] 			=	(float)$data["finaltotal"];

		    // echo "<pre>"; print_r($ORparam); die();

    		$this->session->set_userdata('CURRENT_ORDER_ID',$ORparam["order_id"]);

		$curl = curl_init();

		$link = 'https://dealzarabia.ae/payment/order';
		$test_link = 'http://localhost/dealzarabia-Stripe-Payment-gateway/order';

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $link,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $ORparam,
		  CURLOPT_HTTPHEADER => array(
		    'Cookie: ci_session=3jlt77678gsvfr3kg3ng9i27p679ffs9'
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		$Response = json_decode($response);

		if(!empty($Response->insertID) && $Response->status == 'success' ):

			if($this->session->userdata('Quick_buy') === "Y"):

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
				// End


				$prodDet['where']		=	array('products_id'			=>	(int)$products_id);
				$productDetails			=	$this->geneal_model->getData2('single','da_products',$prodDet);


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

			else:
				foreach($cartItems as $CA):	
					//Manage Inventory
					if($ORparam["product_is_donate"] == 'N'):
						$where['where']		=	array(
													'products_id'			=>	(int)$CA['id'],
													'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
												);
						$INVcheck	=	$this->geneal_model->getData2('single','da_inventory',$where);
						if($INVcheck <> ''):
							$orqty = $INVcheck['order_request_qty'] + (int)$CA['qty'];
							$INVUpdate['order_request_qty']		=	$orqty;
							$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$where['where']);
						else:
							$INVparam['products_id']				= 	(int)$CA['id'];
							$INVparam['qty']						=	(int)0;
							$INVparam['available_qty']				=	(int)0;
							$INVparam['order_request_qty']			=	(int)$CA['qty'];
							$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

							$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
							
							$INVparam['creation_ip']				=	currentIp();
							$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
							$INVparam['status']						=	'A';
							$this->geneal_model->addData('da_inventory', $INVparam);
						endif;
					endif;
						
					//END
					$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
					$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
					$ORDparam["order_id"]			=	$ORparam["order_id"];
					$ORDparam["user_id"]			=	(int)$CA['user_id'];
					$ORDparam["product_id"] 		=	(int)$CA['id'];
					$ORDparam["product_name"] 		=	$CA['name'];
					$ORDparam["quantity"] 		    =	(int)$CA['qty'];
					if($CA['color']):
						$ORDparam["color"] 		    =	$CA['color'];
					endif;
					if($CA['size']):
						$ORDparam["size"] 		    =	$CA['size'];
					endif;
					$ORDparam["price"] 		        =	(float)$CA['price'];
					$ORDparam["tax"] 		        =	(float)0;
					$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
					$ORDparam["is_donated"] 		=	$CA['is_donated'];
					$ORDparam["other"] 		        =	array(
																'image' 		=>	$CA['other']->image,
																'description' 	=>	$CA['other']->description,
																'aed'			=>	$CA['other']->aed
															);
					$ORDparam["current_ip"] 		=	$CA['current_ip'];
					$ORDparam["rowid"] 				=	$CA['rowid'];
					$ORDparam["curprodrowid"] 		=	$CA['curprodrowid'];
							
					$this->geneal_model->addData('da_orders_details', $ORDparam);
				endforeach;
			endif;

			// redirecting dealzarabia site
			 $id = base64_encode('DEALZ___'.$Response->insertID.'___'.rand(1000,9999));
			header("Location: https://dealzarabia.ae/payment/?id=".$id);
		endif;

		exit();
	} // END OF FUNCTION

	/***********************************************************************
	** Function name : telrpayment
	** Developed By : Dilip Halder
	** Purpose  : This function used for make telr payment
	** Date : 26 Apirl 2023
	************************************************************************/
	// public function telrpayment()
	// {
			 
	// 	if($this->session->userdata('Quick_buy') === "Y"):
	// 		$serializedArray = $this->Quickaddorder();
	// 	else:
	// 		$serializedArray = $this->addorder();
	// 	endif;

	// 	// $decodedData = urldecode($serializedArray);
	// 	// $ORparam = unserialize(urldecode($decodedData));
	// 	// echo base64_encode($serializedArray);die();
	// 	$this->session->set_userdata('CURRENT_ORDER_ID',$ORparam["order_id"]);
	// 	header("Location: https://dealzarabia.ae/payment/telr-payment/?data=".base64_encode($serializedArray));
	// } // END OF FUNCTION

	/***********************************************************************
	** Function name : telrpayment
	** Developed By : Dilip Halder
	** Purpose  : This function used for make telr payment
	** Date : 26 Apirl 2023
	************************************************************************/
	public function telrpayment()
	{
			 
		if($this->session->userdata('Quick_buy') === "Y"):
			$serializedArray = $this->Quickaddorder();
		else:
			$serializedArray = $this->addorder();
		endif;

		$decodedData = urldecode($serializedArray);
		$ORparam = unserialize(urldecode($decodedData));
		
		// $return_auth = base_url('/telr-success/'.$ORparam["order_id"]);
        // $return_can =  base_url('/telr-cancel/'.$ORparam["order_id"]);
        // $return_decl = base_url('/telr-fail/'.$ORparam["order_id"]);

        $return_auth = "https://dealzarabia.com/telr-success/".$ORparam["order_id"];
        $return_can =  "https://dealzarabia.com//telr-cancel/".$ORparam["order_id"];
        $return_decl = "https://dealzarabia.com/telr-fail/".$ORparam["order_id"];

		if($ORparam["user_email"] == ''){
          $ORparam["user_email"] = "dealzarabiasales1@gmail.com";
        }

        $storeid = 28035;
        $key = 'q9nND~fLxdd@DHtw';

        $randomaddress = $this->randomAddress();

        // test user id
        if($this->session->userdata('DZL_USERID') == '100000000001983'):
            $postfields= '{
            "method": "create",
            "store": "'.$storeid.'",
            "authkey": "'.$key.'",
                "framed":2,
                "language": "en",
                "ivp_applepay":"0",
                "ivp_framed":"2",
            "order": {
                "cartid": "'.$ORparam["order_id"].'",
                "test": "1",
                "amount": "'.$ORparam["inclusice_of_vat"].'",
                "currency": "AED",
                "description": "Product Description",
                    "trantype": "Sale"
            },
             "customer": {
                "ref": "'.$ORparam["user_id"].'",  
                "email": "'.$ORparam["user_email"].'",
                "name": {
                "forenames": "'.$ORparam['userData']['users_name'].'",
                "surname": "'.$ORparam['userData']['users_name'].'"
                },
                "address": {
                "line1": "SA",
                "city": "Dubai",
                "country": "SA"
                },
                "phone": "5555555555"
            },
            "return": {
                "authorised": "'.$return_auth.'",
                "declined": "'.$return_decl.'",
                "cancelled": "'.$return_can.'"
            }	
            }';

        else:

        	$postfields= '{
            "method": "create",
            "store": "'.$storeid.'",
            "authkey": "'.$key.'",
                 "framed":2,
                "language": "en",
                "ivp_applepay":"0",
                "ivp_framed":"2",
            "order": {
                "cartid": "'.$ORparam["order_id"].'",
                "test": "0",
                "amount": "'.$ORparam["inclusice_of_vat"].'",
                "currency": "AED",
                "description": "Product Description",
                    "trantype": "Sale"
            },
             "customer": {
                "ref": "'.$ORparam["user_id"].'",  
                "email": "'.$ORparam["user_email"].'",
                "name": {
                "forenames": "'.$ORparam['userData']['users_name'].'",
                "surname": "'.$ORparam['userData']['users_name'].'"
                },
                "address": {
                "line1": "SA",
                "city": "Dubai",
                "country": "SA"
                },
                "phone": "5555555555"
            },
            "return": {
                "authorised": "'.$return_auth.'",
                "declined": "'.$return_decl.'",
                "cancelled": "'.$return_can.'"
            }	
            }';



        endif;

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://secure.telr.com/gateway/order.json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$postfields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),

            ));
        $results = curl_exec($curl);
        curl_close($curl);

        $pay_data['response'] 	= $results;
        $pay_data['postfields'] = $postfields;
        $pay_data['order_id'] 	= $ORparam['order_id'];
 	
        $this->load->view('telr_payment',$pay_data);

	} // END OF FUNCTION


	/***********************************************************************
	** Function name    : check_telr_payment_status
	** Developed By     : Afsar Ali
	** Purpose          : This function used for check telr payment status
	** Date             : 18 JUNE 2023
	** Update By        :
	** Update Date      :
	************************************************************************/
	public function check_telr_payment_status()
	{
	    
	    if($this->input->post('order_id')):

	        $order_id    = $this->input->post('order_id');
			$whereGetOrderID['where']		=	array('order_id' =>  $order_id );
			$paymentData 					=	$this->geneal_model->getData2('single', 'da_orders', $whereGetOrderID,$shortField);

	        if($paymentData && $paymentData['order_status'] != 'Initialized'):
	            if($paymentData['order_status'] == 'Success'):
	                echo 'Success'.'_____https://dealzarabia.com/order-success/';     die;
	            elseif($paymentData['order_status'] == 'Cancel'):
	                echo 'Success'.'_____https://dealzarabia.com/';    die;
	            elseif($paymentData['order_status'] == 'Fail'):
	                echo 'Success'.'_____https://dealzarabia.com/';    die;
	            endif;              
	        endif;
	    endif;
	    echo 'error'; die;
	} //END OF FUNCTION


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
	** Updated By		: Dilip Halder
	** Updated Date 	: 10 MARCH 2023
	************************************************************************/ 
	public function success($oid='')
	{  
		$oid = '';
		if($this->session->userdata('CURRENT_ORDER_ID')){
			$oid = $this->session->userdata('CURRENT_ORDER_ID');
		}else{
			$shortField  					= 	array('sequence_id' => -1 );
			$whereGetOrderID['where']		=	[ 'user_id' => (int)$this->session->userdata('DZL_USERID') ];
			$currentOrderData 				=	$this->geneal_model->getData2('single', 'da_orders', $whereGetOrderID,$shortField);
			$oid = $currentOrderData['order_id'];
		}

		if($oid == ''){
			redirect('home');
		}

		$data  = array();
		$data['page']	=	'Success';
		$data['finalPrice'] = 0;
		$data['order_id'] = $oid;
		$productIdPrice   = array();

		if(empty($this->session->userdata('Quick_buy')) && empty($this->cart->contents())):
			redirect('home');
		endif;

		//Get current order of user.
		$wcon['where']					=	[ 'order_id' => $oid ];
		$data['orderData'] 				=	$this->geneal_model->getData2('single', 'da_orders', $wcon);
		
		//Get current order details of user.
		$wcon2['where']					=	[ 'order_id' => $oid ];
		$data['orderDetails']         	=	$this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);

		//update order status
		if($data['orderData']['product_is_donate'] == 'Y'){
			$updateParams 					=	array( 'order_status' => 'Success', 'collection_code' => $collectionCode, 'collection_status' => 'Donated');	
		}else{
			$expairyData 					=	date('Y-m-d H:i', strtotime($data['orderData']['created_at']. ' 14 Days'));
			$collectionCode 				=	base64_encode(rand(1000,9999)); 
			$updateParams 					=	array('order_status' => 'Success', 'collection_code' => $collectionCode, 'collection_status' => 'Pending to collect', 'expairy_data' => $expairyData);	
		}

		$updateorderstatus 				= 	$this->geneal_model->editData('da_orders', $updateParams, 'order_id', $oid);


		$couponList = array();

		//Generate coupon 
		$cashbackcount = 0;
		$ref1count =0;
		foreach($data['orderDetails'] as $CA):
			//$data['finalPrice'] += $CA['quantity'] * $CA['price'];
			
			$this->geneal_model->updateStock($CA['product_id'],$CA['quantity']);

			// if($data['orderData']["product_is_donate"] == 'N'):
			// 	$this->geneal_model->updateInventoryStock($data['orderData']['collection_point_id'],$CA['product_id'],$CA['quantity']);
			// endif;
			$productIdPrice[$CA['product_id']] 		=	($CA['quantity'] * $CA['price']);

			// //Get current Ticket order sequence from admin panel.
			// //Get current Ticket order sequence from admin panel.
			$tblName = 'da_tickets_sequence';
			$whereCon2['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'status' => 'A');	
			$shortField 						= 	array('tickets_seq_id'=>'DESC');
			$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

			$tblName = 'da_quickcoupons_totallist';
			$whereCon3['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id']  );	
			$shortField3 						= 	array('coupon_id'=>'DESC');
			$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

			$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
			
			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
			endif;

			if($this->input->post('product_is_donate') == 'Y'):
				$check_availblity = $this->input->post('product_qty') * 2;
			endif;

			$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity); 

			if($CA['is_donated'] == 'N' ):
				$soldOutQty = $CA['quantity'] ;
			elseif($CA['is_donated'] == "Y"):
			 	$soldOutQty = $CA['quantity']*2 ;
			endif;

			// Created 1st ticket sequence record.
			if(empty($SoldoutTicketList['coupon_sold_number'])):
				
				//Storing new ticket sequence in da_quickcoupons_totallist
				$quickcoupons["id"]					=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
				$quickcoupons["product_id"] 		=	(int)$CA['product_id'];
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
				$quickcoupons["product_id"] 		=	(int)$CA['product_id'];
			    $quickcoupons["tickets_seq_id"] =		(int)$CurrentTicketSequence['tickets_seq_id'];
			    $quickcoupons["coupon_sold_number"] =	''; //$soldOutQty;
			    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
			    $quickcoupons["created_at"] 		=	date('Y-m-d H:i');

			    //Saving quick coupons number  
			    $orderInsertID 						=	$this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);
					
				endif;

			endif;

			//Admin announced ticket available number.
			$available_ticket =  $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
			
			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
			endif;

			//defining varibale to store coupon list
			

			if ($available_ticket > $coupon_sold_number ):

				//Get current sponsored coupon count from product.
				$wconsponsoredCount['where'] 	=	array('products_id' => (int)$CA['product_id'] );
				$productDetails 				=	$this->geneal_model->getData2('single', 'da_products', $wconsponsoredCount);

				if($productDetails['sponsored_coupon']):
					$sponsored_coupon = $productDetails['sponsored_coupon'];
				else:
					$sponsored_coupon = 1;
				endif;
				//END

				if($CA['is_donated'] == 'N'):

					for($i=1; $i <= $CA['quantity']*$sponsored_coupon; $i++){
						
						if(!empty($CurrentTicketSequence)):

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

							$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
							$couponData['users_id']			= 	(int)$this->session->userdata('DZL_USERID');
							$couponData['users_email']		= 	$this->session->userdata('DZL_USEREMAIL');
							$couponData['order_id']			= 	$oid;
							$couponData['product_id']		= 	$CA['product_id'];
							$couponData['product_title']	= 	$CA['product_name'];
							$couponData['is_donated'] 		=	'N';
							$couponData['coupon_status'] 	=	'Live';
							$couponData['coupon_code'] 		= 	$coupon_code;
							$couponData['coupon_type'] 		= 	'Simple';
							$couponData['created_at']		=	date('Y-m-d H:i');
							//creating coupon for secific product.
							$this->geneal_model->addData('da_coupons',$couponData);
						endif;
					}
				endif;

				if($CA['is_donated'] == 'Y'):
					
					for($i=1; $i <= $CA['quantity']*$sponsored_coupon*2; $i++){
						
						if(!empty($CurrentTicketSequence)):

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

							$couponData['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
							$couponData['users_id']			= 	(int)$this->session->userdata('DZL_USERID');
							$couponData['users_email']		= 	$this->session->userdata('DZL_USEREMAIL');
							$couponData['order_id']			= 	$oid;
							$couponData['product_id']		= 	$CA['product_id'];
							$couponData['product_title']	= 	$CA['product_name'];
							$couponData['is_donated'] 		=	'Y';
							$couponData['coupon_status'] 	=	'Live';
							$couponData['coupon_code'] 		= 	$coupon_code;
							$couponData['coupon_type'] 		= 	'Donated';
							$couponData['created_at']		=	date('Y-m-d H:i');
							//creating coupon for secific product.
							$this->geneal_model->addData('da_coupons',$couponData);
						endif;
					}

				endif;

			endif;
		endforeach;

		$data['finalPrice'] 			= 	$this->session->userdata('payment_total_price');
		$data['stripe_token'] 			= 	$this->session->userdata('payment_stripe_token');

		$wcon3['where'] 					=	array('order_id' => $oid);
		$data['couponDetails']			=	$this->geneal_model->getData2('multiple', 'da_coupons', $wcon3);

		// Deduct the purchesed points and get available arabian points of user.
		if($data['orderData']['payment_mode'] == "Arabian Points"):
			$currentBal 					= 	$this->geneal_model->debitPoints($data['finalPrice']); 
		endif;

		if($this->session->userdata('payment_method') == 'arabianpoint'):
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
		endif;
	    
		$membershipData = $this->geneal_model->getMembership((int)$this->session->userdata('DZL_TOTALPOINTS'));
		// Enabled cashback for once at a time.
		if($cashbackcount == 0):
			if($membershipData):
				$cashback 			=	$data['finalPrice'] * $membershipData['benifit'] /100;
				$data['cashback'] 	= 	$cashback;
				if($cashback):
					$insertCashback = array(
						'cashback_id'	=>	(int)$this->geneal_model->getNextSequence('da_cashback'),
						'user_id'		=>	(int)$this->session->userdata('DZL_USERID'),
						'order_id'		=>	$data['order_id'],
						'cashback'		=>	(float)$cashback,
						'created_at'	=>	date('Y-m-d H:i'),
					);
					$this->geneal_model->addData('da_cashback',$insertCashback);


					$where2 					=	['users_id' => (int)$this->session->userdata('DZL_USERID') ];
					$UserData					=	$this->geneal_model->getOnlyOneData('da_users', $where2);


					/* Load Balance Table -- after buy product*/
				    $Cashbparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
				    $Cashbparam['order_id']				=	$data['order_id'];
					$Cashbparam["user_id_cred"] 		=	(int)$this->session->userdata('DZL_USERID');
					$Cashbparam["user_id_deb"]			=	(int)$this->session->userdata('DZL_USERID');
					$Cashbparam["arabian_points"] 		=	(float)$cashback;
					$Cashbparam["availableArabianPoints"] 	=	(float)$UserData["availableArabianPoints"];
			        $Cashbparam["end_balance"] 				=	(float)$UserData["availableArabianPoints"] + (float)$cashback ;
				    $Cashbparam["record_type"] 			=	'Credit';
				    $Cashbparam["arabian_points_from"] 	=	'Membership Cashback';
				    $Cashbparam["creation_ip"] 			=	$this->input->ip_address();
				    $Cashbparam["created_at"] 			=	date('Y-m-d H:i');
				    $Cashbparam["created_by"] 			=	(int)$this->session->userdata('DZL_USERSTYPE');
				    $Cashbparam["status"] 				=	"A";
				    
				    $this->geneal_model->addData('da_loadBalance', $Cashbparam);


				    // Credit the purchesed points and get available arabian points of user.
					$this->geneal_model->creaditPoints($cashback); 
				    /* End */
				    $cashbackcount++;

				endif;
			endif;

		endif;
		//ADD SHARED POINT
		foreach($data['orderDetails'] as $CA):
			$SPwhereCon['where']		=	array('BUY_USER_ID' => (int)$this->session->userdata('DZL_USERID'), 'SHARED_PRODUCT_ID' => (int)$CA['product_id']);
			$shared_details 			=	$this->geneal_model->getData2('single','da_deep_link',$SPwhereCon);
			//echo '<pre>'; print_r($productIdPrice);die();
			if($shared_details <> ''):
				if(isset($productIdPrice[$shared_details['SHARED_PRODUCT_ID']])):
					$prowhere['where']	=	array('products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
					$prodData			=	$this->geneal_model->getData2('single','da_products',$prowhere);

					$sharewhere['where']=	array('users_id'=>(int)$shared_details['SHARED_USER_ID'],'products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
					$shareCount			=	$this->geneal_model->getData2('count','da_product_share',$sharewhere,'','0','0');
					if($shareCount == NULL):
						$shareCount = 0;
					endif;
					if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit']):

						$param['share_id']					=	(int)$this->common_model->getNextSequence('da_product_share');
						$param['users_id']					=	(int)$shared_details['SHARED_USER_ID'];
						$param['products_id']				=	(int)$shared_details['SHARED_PRODUCT_ID'];
						$param['creation_date']				=   date('Y-m-d H:i');
						$param['creation_ip']				=   $this->input->ip_address();
						$this->common_model->addData('da_product_share',$param);

						$productCartAmount  		=	$productIdPrice[$shared_details['SHARED_PRODUCT_ID']];
						//First label referal amount Credit
						// $ref1tbl 					=	'referral_percentage';
						// $ref1where 					=	['referral_lebel' => (int)1 ];
						// $referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
						$ref1tbl 					=	'da_products';
						$ref1where 					=	['products_id' => (int)$shared_details['SHARED_PRODUCT_ID'] ];
						$referal1Data				=	$this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
						if($referal1Data && $referal1Data['share_percentage_first'] > 0):
							$referal1Amount  		=	(($productCartAmount*$referal1Data['share_percentage_first'])/100);

							/* Referal Product Table -- after buy product*/
							$ref1Amtparam["referral_id"]			=	(int)$this->geneal_model->getNextSequence('referral_product');
						 	$ref1Amtparam['order_id']				=	$data['order_id'];
							$ref1Amtparam["referral_user_code"] 	=	(int)$shared_details['SHARED_USER_REFERRAL_CODE'];
							$ref1Amtparam["referral_from_id"] 		=	(int)$shared_details['SHARED_USER_ID'];
							$ref1Amtparam["referral_to_id"]			=	(int)$this->session->userdata('DZL_USERID');
							$ref1Amtparam["referral_percent"] 		=	(float)$referal1Data['share_percentage_first'];
							$ref1Amtparam["referral_amount"] 		=	(float)$referal1Amount;
							$ref1Amtparam["referral_cart_amount"] 	=	(float)$productCartAmount;
							$ref1Amtparam["referral_product_id"] 	=	(int)$shared_details['SHARED_PRODUCT_ID'];
							$ref1Amtparam["creation_ip"] 			=	$this->input->ip_address();
							$ref1Amtparam["created_at"] 			=	date('Y-m-d H:i');
							$ref1Amtparam["created_by"] 			=	(int)$this->session->userdata('DZL_USERID');
							$ref1Amtparam["status"] 				=	"A";
							
							$this->geneal_model->addData('referral_product', $ref1Amtparam);
							/* End */
							if($ref1count == 0):
								
								/* Load Balance Table -- after buy product*/
								$ref1param["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
								$ref1param["user_id_cred"] 			=	(int)$shared_details['SHARED_USER_ID'];
								$ref1param["user_id_deb"]			=	(int)$shared_details['SHARED_USER_ID'];
								$ref1param["product_id"] 			=	(int)$ref1Amtparam["referral_product_id"];
								$ref1param["arabian_points"] 		=	(float)$referal1Amount;
								$ref1param["record_type"] 			=	'Credit';
								$ref1param["arabian_points_from"] 	=	'Referral';
								$ref1param["creation_ip"] 			=	$this->input->ip_address();
								$ref1param["created_at"] 			=	date('Y-m-d H:i');
								$ref1param["created_by"] 			=	(int)$this->session->userdata('DZL_USERID');
								$ref1param["status"] 				=	"A";
								
								$this->geneal_model->addData('da_loadBalance', $ref1param);

								$ref1count++;

							endif;
							
							// $wcon4['where']					=	[ 'users_id' => (int)$shared_details['SHARED_USER_ID'] ];
							// $data['orderData'] 				=	$this->geneal_model->getData2('single', 'da_users', $wcon4);
							
							// Credit the shared points and get available arabian points of user.
							//$this->geneal_model->creaditPoints($ref1param["arabian_points"]); 

							//$refusetbl 					=	'da_users';
							$refUser1where 					=	['users_id' => (int)$shared_details['SHARED_USER_ID'] ];
							$referalUser1Data				=	$this->geneal_model->getOnlyOneData('da_users', $refUser1where);

							//echo '<pre>';print_r($refUser1where); print_r($referalUser1Data);

							$refUser3where['where']		=	array('users_id' => (int)$referalUser1Data['users_id']);
							$totalArabianPoints 		= 	$referalUser1Data['totalArabianPoints'] + $ref1param["arabian_points"];
							$availableArabianPoints 	=	$referalUser1Data['availableArabianPoints'] + $ref1param["arabian_points"];
							
							$updateArabianPoints['totalArabianPoints']		=	(float)$totalArabianPoints;
							$updateArabianPoints['availableArabianPoints']	=	(float)$availableArabianPoints;

							//echo '<pre>';print_r($refUser3where); print_r($updateArabianPoints);die();

							$this->geneal_model->editDataByMultipleCondition('da_users',$updateArabianPoints,$refUser3where['where']);
							
							/* End */
						endif;
					endif;
				endif;
				$this->geneal_model->deleteData('da_deep_link', 'seq_id', (int)$shared_details['seq_id']);
			endif;
		endforeach;



		//END OF FUNCTION

		//Delete cart items.
		$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID')); 
		$this->cart->destroy();

		$this->session->unset_userdata(array('SHARED_USER_ID','SHARED_USER_REFERRAL_CODE', 'SHARED_PRODUCT_ID',
										     'payment_product_is_donate','payment_shipping_method',
											 'payment_shipping_address_id', 'payment_shipping_address',
											 'payment_emirate_id', 'payment_emirate_name', 'payment_area_id', 'payment_area_name', 'payment_collection_point_id', 'payment_collection_point_name',
											 'payment_shipping_charge', 'payment_inclusice_of_vat','payment_subtotal', 
											 'payment_vat_amount','payment_total_price', 'payment_stripe_token',
											 'payment_stripe_charge_id','payment_customer_id',
											 'Quick_country_code','Quick_users_mobile','Quick_category_id','Quick_products_id','Quick_title','Quick_adepoints','Quick_is_donated',			
											 'quantity','first_name','last_name','Quick_buy'						
											));
		$this->emailsendgrid_model->sendOrderMailToUser($data['order_id']);
		$this->sms_model->sendTicketDetails($data['order_id']);
 
		$useragent=$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-success',$data);
		else:
			$this->load->view('success', $data);
		endif;
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
		$whereCon['where']		= 	array('user_id'=>(int)$this->session->userdata('DZL_USERID'),'order_status' => 'Success');

		$totalPage 				=	$this->geneal_model->getordersList('count',$tblName,$whereCon,$shortField,'0','0');
		$config 				= 	['base_url'=>base_url('order-list'),'per_page'=>5,'total_rows'=> $totalPage];

		$this->pagination->initialize($config);
		$data['orderData']  =	$this->geneal_model->getordersList('multiple', $tblName, $whereCon,$shortField,$this->uri->segment(2),5);

		$useragent=$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-order_list',$data);
		else:
			$this->load->view('order_list', $data);
		endif;
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


	/* * *********************************************************************
	 * * Function name 	: download_invoice
	 * * Developed By 	: Dilip
	 * * Purpose  		: This function used for download invoice
	 * * Date 			: 01 FEB 2023
	 * * Updated BY 	: Dilip Halder
	 * * Updated Date 	: 24 January 2024
	 * * **********************************************************************/
	public function download_invoice($oid ='')
	{
		$this->load->library('Mpdf');
		
		$tblName 				=	'da_orders';
		$shortField 			= 	array('_id'=> -1 );
		$whereCon['where']		= 	array('order_id'=>$oid);
	
		$orderData     			=	$this->geneal_model->getData2('single', $tblName, $whereCon,$shortField);
		// $orderData  =	$this->geneal_model->getordersList('single', $tblName, $whereCon,$shortField);
		
		// OrderDetails
		// User details fetching
		$where2 			    =	array('users_id' => (int)$orderData['user_id']);
		$userData				=	$this->geneal_model->getOnlyOneData('da_users', $where2);
		// echo "<pre>"; print_r($orderData); die();

		// coupon code start here.
        $tblName                =   'da_coupons';
        $shortField             =   array('coupon_id'=> 1 );
        $whereCon['where']      =   array('order_id'=> $oid);
        $couponlist             =  $this->geneal_model->getData2('multiple', $tblName, $whereCon,$shortField);
        // coupon code end here.

       // Product details code start here.
        $tblName                =  'da_orders_details';
        $shortField             =  array('_id'=> -1 );
        $whereCon['where']      =  array('order_id'=>$oid);
        $orderDetails         =  $this->geneal_model->getData2('multiple', $tblName, $whereCon,$shortField);


        $productName = array();
        $key = 0;// 
        foreach($couponlist as $couponKey => $coupons):
            // Product details code start here.
            $tblName                =  'da_products';
            $shortField             =  array('_id'=> -1 );
            $whereCon['where']      =  array('products_id'=>(int)$coupons['product_id']);
            $ProductDetails         =  $this->geneal_model->getData2('single', $tblName, $whereCon,$shortField);
            $price                  = $ProductDetails['adepoints']?$ProductDetails['adepoints']:$ProductDetails['points'];

          	$tblName                =  'da_prize';
            $shortField             =  array('_id'=> -1 );
            $whereCon['where']      =  array('product_id'=>(int)$coupons['product_id']);
            $PrizeData         =  $this->geneal_model->getData2('single', $tblName, $whereCon,$shortField);


            // echo "<pre>";
            // print_r();
            // die();

            if(!in_array($ProductDetails['title'],array_column($productData, 'title'))):
                $productData[$key]['title']             = $ProductDetails['title'];
                $productData[$key]['price']             = $price;
                $productData[$key]['draw_date']         = $ProductDetails['draw_date'];
                $productData[$key]['draw_time']         = $ProductDetails['draw_time'];
                $productData[$key]['prize_title']		= $PrizeData['title'];
               foreach($orderDetails as $ORD_details):
                    if($ProductDetails['products_id'] == $ORD_details['product_id']):
                         $productData[$key]['quantity'] = $ORD_details['quantity'];
                    endif;
                endforeach;


                foreach($couponlist as $couponKey => $coupon):
                    if($ProductDetails['products_id'] == $coupon['product_id']):
                         $productData[$key]['coupon'][]         = $coupon['coupon_code'];
                    endif;
                endforeach;
                $key++;
            endif;
        endforeach;

        

        $totalQTY = 0;
        foreach ($orderDetails as $key => $ORD_Details):
        	$totalQTY +=$ORD_Details['quantity'];
        endforeach;
        	


		$data['orderData'] 		= $orderData;
		$data['productData'] 	= $productData;
		$data['totalQTY'] 		= $totalQTY;
		$data['name'] 	   		= $userData['users_name'].' '.$userData['last_name'];
		$data['mobile']    		= $userData['country_code'] .' '.$userData['users_mobile'];

		// echo "<pre>"; print_r($data); die();

		// $this->load->view('web_api/order_pdf_template', $data);
		// $this->load->view('web_api/order_pdf_template_table', $data);
		$this->load->view('web_api/pos_order_template', $data);

		

		// $this->DownlodeOrderPDF($oid.'.pdf');
		return;
	}

	/* * *********************************************************************
	 * * Function name : pickup_point
	 * * Developed By : Dilip halder
	 * * Purpose  : This function used to show pick up points.
	 * * Date : 28 FEB 2023
	 * * **********************************************************************/
	public function pickup_point(){
		
		$data 					=	array();
		$data['page'] 			=	'Pick Up Points';

		$useragent=$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-pick_up_point',$data);
		else:
			$this->load->view('pick_up_point',$data);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : DownlodeOrderPDF
	 * * Developed By : Dilip
	 * * Purpose  : This function used for download file
	 * * Date : 01 FEB 2023
	 * * **********************************************************************/
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

	
	
		/* * *********************************************************************
	 * * Function name : checkoutStep
	 * * Developed By : Dilip
	 * * Purpose  : This function used for get checkout staging data
	 * * Date : 28 FEB 2023
	 * * **********************************************************************/
	public function checkoutStep($paymentData=array()){

		$step_number = $this->input->post('step_number');

		if($step_number == 'step1'):

			$collectionPoints   		=	array();
			$tblName1 					=	'da_emirate';
			$where1['where'] 			=	array( 'status' => 'A');
			$order1 					=	['emirate_name' => 'ASC'];
			$data1						=	$this->geneal_model->getData2('multiple',$tblName1,$where1,$order1);

			foreach ($data1 as $i => $result):

				$emirate_id = $result['emirate_id'];
				$emirate_name = $result['emirate_name'];
				// $emirate_slug = $result['emirate_slug'];

				// createing staging list in array.
				$emeratelist[] = array('emirate_id' => $emirate_id,'emirate_name' => $emirate_name);
			endforeach;

			header('Content-type: application/json');
			echo json_encode($emeratelist); die;
		endif;

		if($step_number == 'step2'):

			 $step_number = $this->input->post('step_number');
			 $emirate_id = $this->input->post('emirate_id');

			$collectionPoints   		=	array();
			$tblName1 					=	'da_emirate_collection_point';
			$where1['where'] 			=	array( 'status' => 'A','emirate_id' => (int)$emirate_id);
			$order1 					=	['area_id' => 'ASC'];
			$data1						=	$this->geneal_model->getData2('multiple',$tblName1,$where1,$order1);

			foreach ($data1 as $i => $result):

				$emirate_id = $result['emirate_id'];
				$area_name = $result['area_name'];
				$area_id = $result['area_id'];
				
				// createing staging list in array.
				$emeratelist1[] = array('area_id'=> $area_id,'emirate_id'=> $emirate_id ,'area_name' => $area_name );
			endforeach;

			// removing duplicate value from array.
			$area_id = array_column($emeratelist1, 'area_id');
			$area_name = array_column($emeratelist1, 'area_name');
			
			$areaIdList  		= array_unique($area_id);
			$areaNameList 		= array_unique($area_name);

			foreach ($areaIdList as $key1 => $IDlist) {

				foreach ($areaNameList as $key2 => $NAMElist) {
					
					//created list combine of area_id and area_name.
					if($key1 == $key2):
						$emeratelist[] = array('area_id'=> $IDlist,'area_name' => $NAMElist );
					endif;
				}
			}

			header('Content-type: application/json');
			echo json_encode($emeratelist); die;
		endif;

		if($step_number == 'step3'):

			 $step_number = $this->input->post('step_number');
			 $area_id = $this->input->post('area_id');

			$collectionPoints   		=	array();
			$tblName1 					=	'da_emirate_collection_point';
			$where1['where'] 			=	array( 'status' => 'A','area_id' => (int)$area_id);
			$order1 					=	['area_id' => 'ASC'];
			$data1						=	$this->geneal_model->getData2('multiple',$tblName1,$where1,$order1);
			
			foreach ($data1 as $i => $result):

				$emirate_id = $result['emirate_id'];
				$emirate_name = $result['emirate_name'];
				$area_name = $result['area_name'];
				$collection_point_id = $result['collection_point_id'];
				$area_id = $result['area_id'];
				$area_name = $result['area_name'];

				// Seperating address and google map.
				// $collection_point_name = $result['collection_point_name'];
				if($result['collection_point_name']):
                    $collection_point =   explode("https", $result['collection_point_name']); 

                    $collection_point_name = $collection_point[0];
                    if($collection_point[1]):
                        $google_link = 'https'.$collection_point[1];
                    endif;
                endif;

				// createing staging list in array.
                if($google_link):
					$emeratelist[] = array('emirate_id'=> $emirate_id ,'emirate_name' => $emirate_name ,'area_name'=>$area_name, 'collection_point_id' => $collection_point_id,'collection_point_name' => $collection_point_name,'area_id'=>$area_id ,'area_name'=>$area_name ,'google_link' =>$google_link );
                else:
					$emeratelist[] = array('emirate_id'=> $emirate_id ,'emirate_name' => $emirate_name ,'area_name'=>$area_name, 'collection_point_id' => $collection_point_id,'collection_point_name' => $collection_point_name,'area_id'=>$area_id ,'area_name'=>$area_name);
                endif;

			endforeach;
			header('Content-type: application/json');
			echo json_encode($emeratelist); die;
		endif;

		if($step_number == 'step4'):

			$step_number = $this->input->post('step_number');
			$collection_point_id = $this->input->post('collection_point_id');

			$collectionPoints   		=	array();
			$tblName1 					=	'da_emirate_collection_point';
			$where1['where'] 			=	array( 'status' => 'A','collection_point_id' => (int)$collection_point_id);
			$order1 					=	['collection_point_id' => 'ASC'];
			$data1						=	$this->geneal_model->getData2('multiple',$tblName1,$where1,$order1);

			
			foreach ($data1 as $i => $result):

				$emirate_id = $result['emirate_id'];
				$emirate_slug = $result['emirate_slug'];
				// $emirate_name = $result['emirate_name'];
				$area_id = $result['area_id'];
				$area_slug = $result['area_slug'];
				// $area_name = $result['area_name'];
				$collection_point_id = $result['collection_point_id'];
				$collection_point_slug = $result['collection_point_slug'];
				$collection_point_name = $result['collection_point_name'];

				// createing host list in array.
				$emeratelist[] = array('emirate_id'=> $emirate_id ,'emirate_slug' => $emirate_slug  ,'collection_point_id' => $collection_point_id,'collection_point_name' => $collection_point_name,'collection_point_slug' => $collection_point_slug,'area_id'=>$area_id,'area_slug'=>$area_slug);
			endforeach;
			header('Content-type: application/json');
			echo json_encode($emeratelist); die;
		endif;
	}

	public function test()
	{	

		 echo $_SERVER['HTTP_USER_AGENT'];
	}


	public function telr_payment_fail(){
		echo 'fail';die();
	}

	public function telr_payment_cancel(){
		echo 'cancel';die();
	}

	public function telr_payment_success(){
		echo 'Success';die();
	}

	/***********************************************************************
	** Function name : telrpayment
	** Developed By : Dilip Halder
	** Purpose  : This function used for make telr payment
	** Date : 22 July 2023
	************************************************************************/
	public function noonpayment()
	{	

		if($this->session->userdata('Quick_buy') === "Y"):
			$serializedArray = $this->Quickaddorder();
		else:
			$serializedArray = $this->addorder();
		endif;



		$decodedData = urldecode($serializedArray);
		$ORparam = unserialize(urldecode($decodedData));

		$order_id = $ORparam['order_id'];


		$tblName 					= 'da_orders';
		$whereCon2['where']		 	= 	array('order_id' => $order_id);	
		$order 		 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

		$tblName 					= 'da_orders_details';
		$whereCon2['where']		 	= 	array('order_id' => $order_id);	
		$orderDetails 		 		= 	$this->common_model->getData('multiple',$tblName,$whereCon2);

		


		$items = array();
		foreach ($orderDetails as $key => $orderitem):
				$item['name'] 		= $orderitem['product_name']; 
				$item['quantity']   = (int)$orderitem['quantity']; 
				$item['unitPrice']  = (float) $orderitem['price']; 
				array_push($items , $item);
		endforeach;
		$items = json_encode($items);

		$curl = curl_init();

		$POSTFIELDS ='{
		    "apiOperation": "INITIATE",
		    "order": {
		        "reference": "'.$order["order_id"].'",
		        "amount": "'.$order["total_price"].'",
		        "currency": "AED",
		        "name": "dealz",
		        "channel": "web",
		        "category": "pay",
		        "items":  '.$items.',
		        "ipAddress": "'.$this->input->ip_address().'"
		    },
		    "configuration": {
		        "returnUrl":  "'.base_url('order-status').'",
		        "locale": "en"
		    }
		}';

		// echo "<pre>";print_r($POSTFIELDS);die();

		$NOONURL = "https://api.noonpayments.com/payment/v1/order";
		// $NOONURL = "https://api-test.noonpayments.com/payment/v1/order";

		curl_setopt_array($curl, array(
		CURLOPT_URL => $NOONURL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>$POSTFIELDS,
							CURLOPT_HTTPHEADER => array(
								'Content-Type: application/json',
								'Authorization: ' .Noon_Authorization_Header
							),
						));

		$response = curl_exec($curl);

		curl_close($curl);

		$response = json_decode($response);
		// echo "<pre>";print_r($result);die();

		if($response->resultCode == 0):
			 
			 $checkoutUrl = $response->result->checkoutData->postUrl; 
			 header("Location:" .$checkoutUrl);
		else:
			echo "Error";
		endif;
	}
	// END OF FUNCTION


	/***********************************************************************
	** Function name : ngenius
	** Developed By : Dilip Halder
	** Purpose      : This function used for make telr payment
	** Date         : 20 December 2023
	************************************************************************/
	public function ngenius()
	{	

		if($this->session->userdata('Quick_buy') === "Y"):
			$serializedArray = $this->Quickaddorder();
		else:
			$serializedArray = $this->addorder();
		endif;

		$decodedData = urldecode($serializedArray);
		$ORparam = unserialize(urldecode($decodedData));

		 


		// Creating auth for new order.
	 	$apikey = "ZmE1OTkzYzktMmQzYy00ZTI3LTg2NTUtZmUzZDVlMjc3ZWFiOmJiZjNiZDY5LTUyN2QtNGU3My05ZjM2LWEwNjFiNWY2MTA1Yg==";		// enter your API key here
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token"); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    "accept: application/vnd.ni-identity.v1+json",
		    "authorization: Basic ".$apikey,
		    "content-type: application/vnd.ni-identity.v1+json"
		   )); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,  "{\"realmName\":\"ni\"}"); 
		$output = json_decode(curl_exec($ch)); 
		$access_token = $output->access_token;
		$this->session->set_userdata('access_token' , $access_token);
		// Order Creating code start here...
		$postData = new StdClass();
		$postData->action = "PURCHASE";
		$postData->amount = new StdClass();
		$postData->amount->currencyCode = "AED";
		$postData->amount->value = $ORparam['total_price'] * 100;
		// $postData->language = 'en';
		
		$postData->merchantAttributes->maskPaymentInfo = false;
		$postData->merchantAttributes->merchantOrderReference = $ORparam['order_id'];
		$postData->merchantAttributes->redirectUrl = "https://dealzarabia.com/ngenius-order-status";
		$postData->merchantAttributes->cancelUrl = "https://dealzarabia.com/ngenius-order-status";
      	// $postData->merchantAttributes->cancelUrl = "https://dealzarabia.com";
      	$postData->merchantAttributes->skipConfirmationPage = true;
      	$postData->merchantAttributes->skip3DS = true;
		

		$outlet = "a7b64f18-0fee-4dcd-b326-debc3c867a73";
		$token  = $access_token;
		$json   = json_encode($postData);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://api-gateway.sandbox.ngenius-payments.com/transactions/outlets/".$outlet."/orders");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer ".$token, 
		"Content-Type: application/vnd.ni-payment.v2+json",
		"Accept: application/vnd.ni-payment.v2+json"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		$output = json_decode(curl_exec($ch));
		$order_reference = $output->reference;
		$order_paypage_url = $output->_links->payment->href;

		curl_close ($ch);
		// Order Creating code end here...
		// $url = $order_paypage_url."&slim=true";
		$url = $order_paypage_url;
		redirect($url);

	}


	/***********************************************************************
	** Function name : addorder
	** Developed By : Dilip Halder
	** Purpose  : This function used Addd new Order details..
	** Date : 22 July 2023
	************************************************************************/
	public function addorder($value='')
	{
		$UserID 			 	=   $this->session->userdata('DZL_USERID');
		$UserTYPE 			 	=	$this->session->userdata('DZL_USERSTYPE');
		$UserEMAIL 			 	=	$this->session->userdata('DZL_USEREMAIL');
		$UserPHONE 			 	=	$this->session->userdata('DZL_USERMOBILE');
		$product_is_donate 		=	$this->session->userdata('payment_product_is_donate');
		$shipping_method 		=	$this->session->userdata('payment_shipping_method');
		$emirate_id 			=	$this->session->userdata('payment_emirate_id');
		$emirate_name 			=	$this->session->userdata('payment_emirate_name');
		$area_id 				=	$this->session->userdata('payment_area_id');
		$area_name 				=	$this->session->userdata('payment_area_name');
		$collection_point_id 	=	$this->session->userdata('payment_collection_point_id');
		$collection_point_name 	=	$this->session->userdata('payment_collection_point_name');
		$shipping_charge 		=	$this->session->userdata('payment_shipping_charge');
		$inclusice_of_vat 		=	$this->session->userdata('payment_inclusice_of_vat');
		$subtotal 				=	$this->session->userdata('payment_subtotal');
		$vat_amount 			=	$this->session->userdata('payment_vat_amount');
		$total_price 			=	$this->session->userdata('payment_total_price');
		if($this->input->post('payment_method')):
			$payment_method 		=	$this->input->post('payment_method');
		else:
			$payment_method 		=	$this->session->userdata('payment_method');
		endif;

		if($UserID):
			$wcon['where']		=	array('user_id' => (int)$UserID );
			$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
			
			$user_wcon['where']		=	[ 'users_id' => (int)$UserID ];
			$userData          	=	$this->geneal_model->getData2('single', 'da_users', $user_wcon);

			foreach($cartItems as $CA):
				$finalPrice += $CA['qty'] * $CA['price'];
				//check Stock
				$stockCheck = $this->geneal_model->getStock($CA['id'], $CA['qty']);
				if($CA['is_donated'] == 'N'):
					$data['product_is_in_donate'] 		 =  'N';
				endif;
			endforeach;
			$data['finalprice'] = $finalPrice;
			if($data['product_is_in_donate'] == 'N'):
				$data['shipping']   = 0;//SHIPPING_CHARGE;
			else:
				$data['shipping']   = 0;
			endif;
			//$data['finaltotal'] = $finalPrice + $data['shipping'];
			$data['finaltotal'] = $finalPrice;

		else:
			redirect('login');
		endif;

		$productCount   =	$this->geneal_model->getData2('count', 'da_cartItems', $wcon);
		
		if($this->input->post('product_is_donate') == 'N'):
			$collection_points  				=	explode('_____',$this->input->post('collection_points'));
		endif;

	    $tblName1 					=	'da_emirate_collection_point';
		$where1['where'] 			=	array( 'status' => 'A','collection_point_id' => (int)$collection_points[2]);
		$order1 					=	['collection_point_id' => 'ASC'];
		$data1						=	$this->geneal_model->getData2('single',$tblName1,$where1,$order1);

		// Generating order param
		$ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
		$ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
		$ORparam["user_id"] 				=	(int)$UserID;
		$ORparam["user_type"] 				=	$UserTYPE;
		$ORparam["user_email"] 				=	$UserEMAIL;
		$ORparam["user_phone"] 				=	$UserPHONE;

		if($product_is_donate):
			$ORparam["product_is_donate"] 		=	$product_is_donate;
		elseif( $this->input->post('product_is_donate')):
			$ORparam["product_is_donate"] 		=	$this->input->post('product_is_donate');
		else:
			$ORparam["product_is_donate"] 		= '';
		endif;


		if($shipping_method):
			$ORparam["shipping_method"] 		=	$shipping_method;
		elseif($this->input->post('shipping_method')):
			$ORparam["shipping_method"] 		=	$this->input->post('shipping_method');
		else:
			$ORparam["shipping_method"] 		= '';
		endif;

		if($emirate_id):
			$ORparam["emirate_id"] 		=	$emirate_id;
		elseif($collection_points[0]):
			$ORparam["emirate_id"] 		=	$collection_points[0];
		else:
			$ORparam["emirate_id"] 		= '';
		endif;

		if($emirate_name):
			$ORparam["emirate_name"] 		=	$emirate_name;
		elseif($data1['emirate_name']):
			$ORparam["emirate_name"] 		=	$data1['emirate_name'];
		else:
			$ORparam["emirate_name"] 		= '';
		endif;

		if($area_id):
			$ORparam["area_id"] 		=	$area_id;
		elseif($collection_points[4]):
			$ORparam["area_id"] 		=	$collection_points[4];
		else:
			$ORparam["area_id"] 		= '';
		endif;

		if($area_name):
			$ORparam["area_name"] 		=	$area_name;
		elseif($data1['area_name']):
			$ORparam["area_name"] 		=	$data1['area_name'];
		else:
			$ORparam["area_name"] 		= '';
		endif;

		if($collection_point_id):
			$ORparam["collection_point_id"] 		=	$collection_point_id;
		elseif($collection_points[2]):
			$ORparam["collection_point_id"] 		=	$collection_points[2];
		else:
			$ORparam["collection_point_id"] 		= '';
		endif;

		if($collection_point_name):
			$ORparam["collection_point_name"] 		=	$collection_point_name;
		elseif($data1['collection_point_name']):
			$ORparam["collection_point_name"] 		=	$data1['collection_point_name'];
		else:
			$ORparam["collection_point_name"] 		= '';
		endif;

		// $ORparam["shipping_method"] 		=	$shipping_method ? $shipping_method : $this->input->post('shipping_method') ? $this->input->post('shipping_method'): '' ;
		// $ORparam["emirate_id"] 				=	$emirate_id ? $emirate_id : $collection_points[0] ? $collection_points[0] : '' ;
		// $ORparam["emirate_name"] 			=	$emirate_name ? $emirate_name : $data1['emirate_name'] ? $data1['emirate_name'] : '';
		// $ORparam["area_id"] 				=	$area_id ? $area_id :  $collection_points[4]? $collection_points[4] : '' ;
		// $ORparam["area_name"] 				=	$area_name ? $area_name : $data1['area_name'] ? $data1['area_name'] : '' ;
		// $ORparam["collection_point_id"] 	=	$collection_point_id ? $collection_point_id : $collection_points[2] ? $collection_points[2] : '';
		// $ORparam["collection_point_name"] 	=	$collection_point_name ? $collection_point_name : $data1['collection_point_name'] ? $data1['collection_point_name'] : '' ;
		$ORparam["shipping_address"]		=	'';
		$ORparam["shipping_charge"] 		=	(float)$shipping_charge ? (float)$shipping_charge :(float)$this->input->post('shipping_charge');
		$ORparam["inclusice_of_vat"] 		=	(float)$inclusice_of_vat ? (float)$inclusice_of_vat : (float)$this->input->post('inclusice_of_vat') ;
		$ORparam["subtotal"] 				=	(float)$subtotal ? (float)$subtotal : (float)$this->input->post('subtotal') ;
		$ORparam["vat_amount"] 				=	(float)$vat_amount ? (float)$vat_amount : (float)$this->input->post('vat_amount');
		$ORparam["total_price"] 			=	(float)$total_price ? (float)$total_price : (float)$ORparam["inclusice_of_vat"];
		$ORparam["payment_mode"] 			=	$payment_method;
		$ORparam["payment_from"] 			=	'Web';
		$ORparam["order_status"] 			=	"Initialize";
	 	$ORparam["availableArabianPoints"] 	=	(float)$userData["availableArabianPoints"];
        $ORparam["end_balance"] 			=	(float)$userData["availableArabianPoints"];
		$ORparam["creation_ip"] 			=	$this->input->ip_address();
		$ORparam["created_at"] 				=	date('Y-m-d H:i');
		// Adding details in Order Collection..
		$orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);

		//These field for dealzareabia.ea site.
		$ORparam["product_count"] 			=	(int)$productCount;
		$ORparam["finaltotal"] 				=	(float)$data["finaltotal"];

		foreach($cartItems as $CA):	
			//Manage Inventory
			if($ORparam["product_is_donate"] == 'N'):
				$where['where']		=	array(
											'products_id'			=>	(int)$CA['id'],
											'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
										);

				$INVcheck  =	$this->geneal_model->getData2('single','da_inventory',$where);
				if($INVcheck <> ''):
					$orqty = $INVcheck['order_request_qty'] + (int)$CA['qty'];
					$INVUpdate['order_request_qty']		=	$orqty;
					$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$where['where']);
				else:
					$INVparam['products_id']				= 	(int)$CA['id'];
					$INVparam['qty']						=	(int)0;
					$INVparam['available_qty']				=	(int)0;
					$INVparam['order_request_qty']			=	(int)$CA['qty'];
					$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

					$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
					
					$INVparam['creation_ip']				=	currentIp();
					$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
					$INVparam['status']						=	'A';
					$this->geneal_model->addData('da_inventory', $INVparam);
				endif;
			endif;
				
			//END
			$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
			$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
			$ORDparam["order_id"]			=	$ORparam["order_id"];
			$ORDparam["user_id"]			=	(int)$CA['user_id'];
			$ORDparam["product_id"] 		=	(int)$CA['id'];
			$ORDparam["product_name"] 		=	$CA['name'];
			$ORDparam["quantity"] 		    =	(int)$CA['qty'];
			if($CA['color']):
				$ORDparam["color"] 		    =	$CA['color'];
			endif;
			if($CA['size']):
				$ORDparam["size"] 		    =	$CA['size'];
			endif;
			$ORDparam["price"] 		        =	(float)$CA['price'];
			$ORDparam["tax"] 		        =	(float)0;
			$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
			$ORDparam["is_donated"] 		=	$CA['is_donated'];
			$ORDparam["other"] 		        =	array(
														'image' 		=>	$CA['other']->image,
														'description' 	=>	$CA['other']->description,
														'aed'			=>	$CA['other']->aed
													);
			$ORDparam["current_ip"] 		=	$CA['current_ip'];
			$ORDparam["rowid"] 				=	$CA['rowid'];
			$ORDparam["curprodrowid"] 		=	$CA['curprodrowid'];

			$this->geneal_model->addData('da_orders_details', $ORDparam);
		endforeach;
		
		$ORparam['userData'] = $userData;
		$serializedArray = urlencode(serialize($ORparam));
		// echo base64_encode($serializedArray);die();
		$this->session->set_userdata('CURRENT_ORDER_ID',$ORparam["order_id"]);

		return $serializedArray;
	} // END OF FUNCTION



	/***********************************************************************
	** Function name : Quickaddorder
	** Developed By : Dilip Halder
	** Purpose  : This function used Addd new Order details..
	** Date : 14 October 2023
	************************************************************************/
	public function Quickaddorder($value='')
	{
		$UserID 			 	=   $this->session->userdata('DZL_USERID');
		$UserTYPE 			 	=	$this->session->userdata('DZL_USERSTYPE');
		$UserEMAIL 			 	=	$this->session->userdata('DZL_USEREMAIL');
		$UserPHONE 			 	=	$this->session->userdata('DZL_USERMOBILE');
		$product_is_donate 		=	$this->session->userdata('payment_product_is_donate');
		$shipping_method 		=	$this->session->userdata('payment_shipping_method');
		$emirate_id 			=	$this->session->userdata('payment_emirate_id');
		$emirate_name 			=	$this->session->userdata('payment_emirate_name');
		$area_id 				=	$this->session->userdata('payment_area_id');
		$area_name 				=	$this->session->userdata('payment_area_name');
		$collection_point_id 	=	$this->session->userdata('payment_collection_point_id');
		$collection_point_name 	=	$this->session->userdata('payment_collection_point_name');
		$shipping_charge 		=	$this->session->userdata('payment_shipping_charge');
		$inclusice_of_vat 		=	$this->session->userdata('payment_inclusice_of_vat');
		$subtotal 				=	$this->session->userdata('payment_subtotal');
		$vat_amount 			=	$this->session->userdata('payment_vat_amount');
		$total_price 			=	$this->session->userdata('payment_total_price');
		$payment_method 		=	$this->session->userdata('payment_method');


		// getting users details for quick buy with login.
		$country_code		= $this->session->userdata('Quick_country_code');
		$users_mobile		= $this->session->userdata('Quick_users_mobile');
		$products_id		= $this->session->userdata('Quick_products_id');
		$adepoints			= $this->session->userdata('Quick_adepoints');
		$is_donated			= $this->session->userdata('Quick_is_donated');
		$title				= $this->session->userdata('Quick_title');
		$quantity			= $this->session->userdata('quantity');
		$first_name			= $this->session->userdata('first_name');
		$last_name			= $this->session->userdata('last_name');



		$user_wcon['where']		=	array( 'country_code' => $country_code , 'users_mobile' => (int)$users_mobile );
		$userData          		=	$this->geneal_model->getData2('single', 'da_users', $user_wcon);


		$finalPrice += $quantity* $adepoints;
		//check Stock
		$stockCheck = $this->geneal_model->getStock($products_id, $quantity);
		if($is_donated == 'N'):
			$data['product_is_in_donate'] 		 =  'N';
		endif;
			

		$data['finalprice'] = $finalPrice;
		if($data['product_is_in_donate'] == 'N'):
			$data['shipping']   = 0;//SHIPPING_CHARGE;
		else:
			$data['shipping']   = 0;
		endif;
		//$data['finaltotal'] = $finalPrice + $data['shipping'];
		$data['finaltotal'] = $finalPrice;
			
		$productCount   =	$quantity;
			
		
		if($this->input->post('product_is_donate') == 'N'):
			$collection_points  				=	explode('_____',$this->input->post('collection_points'));
		endif;

	    $tblName1 					=	'da_emirate_collection_point';
		$where1['where'] 			=	array( 'status' => 'A','collection_point_id' => (int)$collection_points[2]);
		$order1 					=	['collection_point_id' => 'ASC'];
		$data1						=	$this->geneal_model->getData2('single',$tblName1,$where1,$order1);

		// Generating order param
		$ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
		$ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
		$ORparam["user_id"] 				=	(int)$UserID;
		$ORparam["user_type"] 				=	$UserTYPE?$UserTYPE:'Users';
		$ORparam["user_email"] 				=	$UserEMAIL?$UserEMAIL:'';
		$ORparam["country_code"] 			=	$country_code;
		$ORparam["user_phone"] 				=	$users_mobile;


		if($product_is_donate):
			$ORparam["product_is_donate"] 		=	$product_is_donate;
		elseif( $this->input->post('product_is_donate')):
			$ORparam["product_is_donate"] 		=	$this->input->post('product_is_donate');
		else:
			$ORparam["product_is_donate"] 		= '';
		endif;


		if($shipping_method):
			$ORparam["shipping_method"] 		=	$shipping_method;
		elseif($this->input->post('shipping_method')):
			$ORparam["shipping_method"] 		=	$this->input->post('shipping_method');
		else:
			$ORparam["shipping_method"] 		= '';
		endif;

		if($emirate_id):
			$ORparam["emirate_id"] 		=	$emirate_id;
		elseif($collection_points[0]):
			$ORparam["emirate_id"] 		=	$collection_points[0];
		else:
			$ORparam["emirate_id"] 		= '';
		endif;

		if($emirate_name):
			$ORparam["emirate_name"] 		=	$emirate_name;
		elseif($data1['emirate_name']):
			$ORparam["emirate_name"] 		=	$data1['emirate_name'];
		else:
			$ORparam["emirate_name"] 		= '';
		endif;

		if($area_id):
			$ORparam["area_id"] 		=	$area_id;
		elseif($collection_points[4]):
			$ORparam["area_id"] 		=	$collection_points[4];
		else:
			$ORparam["area_id"] 		= '';
		endif;

		if($area_name):
			$ORparam["area_name"] 		=	$area_name;
		elseif($data1['area_name']):
			$ORparam["area_name"] 		=	$data1['area_name'];
		else:
			$ORparam["area_name"] 		= '';
		endif;

		if($collection_point_id):
			$ORparam["collection_point_id"] 		=	$collection_point_id;
		elseif($collection_points[2]):
			$ORparam["collection_point_id"] 		=	$collection_points[2];
		else:
			$ORparam["collection_point_id"] 		= '';
		endif;

		if($collection_point_name):
			$ORparam["collection_point_name"] 		=	$collection_point_name;
		elseif($data1['collection_point_name']):
			$ORparam["collection_point_name"] 		=	$data1['collection_point_name'];
		else:
			$ORparam["collection_point_name"] 		= '';
		endif;

		// $ORparam["shipping_method"] 		=	$shipping_method ? $shipping_method : $this->input->post('shipping_method') ? $this->input->post('shipping_method'): '' ;
		// $ORparam["emirate_id"] 				=	$emirate_id ? $emirate_id : $collection_points[0] ? $collection_points[0] : '' ;
		// $ORparam["emirate_name"] 			=	$emirate_name ? $emirate_name : $data1['emirate_name'] ? $data1['emirate_name'] : '';
		// $ORparam["area_id"] 				=	$area_id ? $area_id :  $collection_points[4]? $collection_points[4] : '' ;
		// $ORparam["area_name"] 				=	$area_name ? $area_name : $data1['area_name'] ? $data1['area_name'] : '' ;
		// $ORparam["collection_point_id"] 	=	$collection_point_id ? $collection_point_id : $collection_points[2] ? $collection_points[2] : '';
		// $ORparam["collection_point_name"] 	=	$collection_point_name ? $collection_point_name : $data1['collection_point_name'] ? $data1['collection_point_name'] : '' ;
		$ORparam["shipping_address"]		=	'';
		$ORparam["shipping_charge"] 		=	(float)$shipping_charge ? (float)$shipping_charge :(float)$this->input->post('shipping_charge');
		$ORparam["inclusice_of_vat"] 		=	(float)$inclusice_of_vat ? (float)$inclusice_of_vat : (float)$this->input->post('inclusice_of_vat') ;
		$ORparam["subtotal"] 				=	(float)$subtotal ? (float)$subtotal : (float)$this->input->post('subtotal') ;
		$ORparam["vat_amount"] 				=	(float)$vat_amount ? (float)$vat_amount : (float)$this->input->post('vat_amount');
		$ORparam["total_price"] 			=	(float)$total_price ? (float)$total_price : (float)$ORparam["inclusice_of_vat"];
		$ORparam["payment_mode"] 			=	$payment_method;
		$ORparam["payment_from"] 			=	'Web';
		$ORparam["order_status"] 			=	"Initialize";
	 	$ORparam["availableArabianPoints"] 	=	(float)$userData["availableArabianPoints"];
        $ORparam["end_balance"] 			=	(float)$userData["availableArabianPoints"];
        $ORparam["remark"] 					=	'Quick mobile Web';
		$ORparam["creation_ip"] 			=	$this->input->ip_address();
		$ORparam["created_at"] 				=	date('Y-m-d H:i');


		// Adding details in Order Collection..
		$orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);

		//These field for dealzareabia.ea site.
		$ORparam["product_count"] 			=	(int)$productCount;
		$ORparam["finaltotal"] 				=	(float)$data["finaltotal"];

		
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
		$ORparam['userData'] = $userData;

		$serializedArray = urlencode(serialize($ORparam));
		// echo base64_encode($serializedArray);die();
		$this->session->set_userdata('CURRENT_ORDER_ID',$ORparam["order_id"]);

		return $serializedArray;
	} // END OF FUNCTION




	
	public function orderStatus()
	{
		 $orderId = $_GET['orderId'];
		 $merchantReference = $_GET['merchantReference'];
		 $paymentType = $_GET['paymentType'];

		// // order status checking..
		//  $curl = curl_init();

		//   curl_setopt_array($curl, array(
		//   CURLOPT_URL => 'https://api-test.noonpayments.com/payment/v1/order/'.$orderId,
		//   CURLOPT_RETURNTRANSFER => true,
		//   CURLOPT_ENCODING => '',
		//   CURLOPT_MAXREDIRS => 10,
		//   CURLOPT_TIMEOUT => 0,
		//   CURLOPT_FOLLOWLOCATION => true,
		//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		//   CURLOPT_CUSTOMREQUEST => 'GET',
		//   CURLOPT_HTTPHEADER => array(
		//     'Content-Type: application/json',
		//     'Authorization: Key_Test ZGVhbHpfYXJhYmlhLkRlYWx6YXJhYmlhOjYzZDI0OGNmYjIxMTRlNjY4MzVhMDFkYTI3MDQ5ZTBj'
		//   ),
		// ));

		// $response = curl_exec($curl);

		// curl_close($curl);
		// $response = json_decode($response);
		// echo "<pre>";print_r($response);die();

		 $curl = curl_init();

		 $POSTFIELDS ='{
		    "apiOperation":"SALE",
		     "order": {
		        "Id": '.$orderId.'
		    }
		}';

		$NOONURL = "https://api.noonpayments.com/payment/v1/order";
		// $NOONURL = "https://api-test.noonpayments.com/payment/v1/order";

		curl_setopt_array($curl, array(
		CURLOPT_URL => $NOONURL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>$POSTFIELDS,
							CURLOPT_HTTPHEADER => array(
								'Content-Type: application/json',
								'Authorization: '.Noon_Authorization_Header
							),
						));

		$response = curl_exec($curl);

		curl_close($curl);

		$response = json_decode($response);
		
		if($response->resultCode == 0):
		 	$status = 'Success';
		else:
	 		$status = 'Failed';
		endif;

		$order_id 		= $this->input->get('merchantReference');
		$referance_id 	= $this->input->get('orderId');
		
		$updateParams 					=	array( 'order_status' => $status , 'referance_id' => $referance_id);
		$updateorderstatus 				= 	$this->geneal_model->editData('da_orders', $updateParams, 'order_id', $order_id);

		if($status == 'Success'):
			redirect('order-success');
		else:
			redirect('/');
		endif;

	}



	/***********************************************************************
	** Function name : getlottoOrder
	** Developed By : Dilip Halder
	** Purpose  : This function used Addd new Order details..
	** Date : 27 October 2023
	************************************************************************/
	public function getlottoOrder($orderID='')
	{
		$data  = array();
		$data['page']			=	'Lotto orders';
		// orders details
		$orderID;
		$tableName 			= "da_lotto_orders";
		$where['where'] 	= array('order_id'  => $orderID );
		$data['orderData'] 	= $this->common_model->getData('single',$tableName , $where);
		// product details
		$tableName2 		= 'da_products';
		$where2['where'] 	= array('products_id'  => $data['orderData']['product_id']);
		$data['product'] 	= $this->common_model->getData('single',$tableName2 , $where2);

		//Merchant details
		// $tableName3 		= 'da_users';
		// $where3['where'] 	= array('users_id'  => $data['orderData']['user_id']);
		// $data['seller'] 	= $this->common_model->getData('single',$tableName3 , $where3);
		// echo "<pre>";print_r($data);die();


		$this->load->view('lottoOrder', $data);
	}

	public function randomAddress()
	{
	       $addressList =array("Marina Street, Dubai Marina, Dubai, UAE, P.O. Box: 12345",
	                        "Palm Street, Palm Jumeirah, Dubai, UAE, P.O. Box: 12346",
	                        "Sheikh Zayed Road, Downtown, Dubai, UAE, P.O. Box: 12347",
	                        "Corniche Street, Abu Dhabi, UAE, P.O. Box: 12348",
	                        "Beach Street, Jumeirah, Dubai, UAE, P.O. Box: 12349",
	                        "Oasis Street, Silicon Oasis, Dubai, UAE, P.O. Box: 12350",
	                        "City Street, Motor City, Dubai, UAE, P.O. Box: 12351",
	                        "Park Street, Zabeel, Dubai, UAE, P.O. Box: 12352",
	                        "Island Street, Yas Island, Abu Dhabi, UAE, P.O. Box: 12353",
	                        "Garden Street, Al Barsha, Dubai, UAE, P.O. Box: 12354",
	                        "Marina View, Al Satwa, Dubai, UAE, P.O. Box: 12355",
	                        "Beach Lane, JBR, Dubai, UAE, P.O. Box: 12356",
	                        "City Walk, Al Safa, Dubai, UAE, P.O. Box: 12357",
	                        "Park Lane, Mushrif, Abu Dhabi, UAE, P.O. Box: 12358",
	                        "Desert Drive, Al Ain, UAE, P.O. Box: 12359",
	                        "Palm Jumeirah Way, Dubai, UAE, P.O. Box: 12360",
	                        "Creek View, Deira, Dubai, UAE, P.O. Box: 12361",
	                        "Island View, Reem Island, Abu Dhabi, UAE, P.O. Box: 12362",
	                        "Oasis Road, Liwa Oasis, Abu Dhabi, UAE, P.O. Box: 12363",
	                        "Tower Street, Bur Dubai, Dubai, UAE, P.O. Box: 12364",
	                        "Garden Street, Al Warqaa, Dubai, UAE, P.O. Box: 12375",
	                        "Desert Drive, Al Madam, Sharjah, UAE, P.O. Box: 12376",
	                        "Marina Way, Al Hamra, Ras Al Khaimah, UAE, P.O. Box: 12377",
	                        "Creek View, Al Garhoud, Dubai, UAE, P.O. Box: 12378",
	                        "Beach Road, Kite Beach, Dubai, UAE, P.O. Box: 12379",
	                        "Oasis Lane, Al Awir, Dubai, UAE, P.O. Box: 12380",
	                        "Tower Street, Al Nahyan, Abu Dhabi, UAE, P.O. Box: 12381",
	                        "Palm Boulevard, Mina Al Arab, Ras Al Khaimah, UAE, P.O. Box: 12382",
	                        "Marina Drive, Yas Marina, Abu Dhabi, UAE, P.O. Box: 12383",
	                        "Island Street, The World Islands, Dubai, UAE, P.O. Box: 12384",
	                        "Palm Street, Al Khan, Sharjah, UAE, P.O. Box: 12385",
	                        "Marina Way, Al Maryah Island, Abu Dhabi, UAE, P.O. Box: 12386",
	                        "Desert Drive, Sweihan, Abu Dhabi, UAE, P.O. Box: 12387",
	                        "Beach Lane, Al Bateen, Abu Dhabi, UAE, P.O. Box: 12388",
	                        "City Walk, Al Raha Beach, Abu Dhabi, UAE, P.O. Box: 12389",
	                        "Creek View, Baniyas, Abu Dhabi, UAE, P.O. Box: 12390",
	                        "Island Street, Al Ghadeer, Abu Dhabi, UAE, P.O. Box: 12391",
	                        "Park Street, Mushrif Park, Dubai, UAE, P.O. Box: 12392",
	                        "Oasis Road, Remah, Abu Dhabi, UAE, P.O. Box: 12393",
	                        "Tower Lane, Al Quoz, Dubai, UAE, P.O. Box: 12394",
	                        "Marina View, Al Qasba, Sharjah, UAE, P.O. Box: 12395",
	                        "Desert Drive, Madinat Zayed, Abu Dhabi, UAE, P.O. Box: 12396",
	                        "Beach Lane, Mamzar, Dubai, UAE, P.O. Box: 12397",
	                        "City Street, Al Mizhar, Dubai, UAE, P.O. Box: 12398",
	                        "Oasis Boulevard, Al Rashidiya, Dubai, UAE, P.O. Box: 12399",
	                        "Palm Jumeirah Way, Al Sufouh, Dubai, UAE, P.O. Box: 12400",
	                        "Creek View, Deira City Centre, Dubai, UAE, P.O. Box: 12401",
	                        "Island Street, Al Mamzar, Dubai, UAE, P.O. Box: 12402",
	                        "Park Lane, Al Muteena, Dubai, UAE, P.O. Box: 12403",
	                        "Tower Street, Al Shindagha, Dubai, UAE, P.O. Box: 12404",
	                        "Marina Promenade, Al Majaz, Sharjah, UAE, P.O. Box: 12405",
	                        "Island Drive, Al Khan Lagoon, Sharjah, UAE, P.O. Box: 12406",
	                        "Creek Lane, Al Qasimia, Sharjah, UAE, P.O. Box: 12407",
	                        "Tower Street, Al Taawun, Sharjah, UAE, P.O. Box: 12408",
	                        "Oasis Boulevard, Al Nahda, Sharjah, UAE, P.O. Box: 12409",
	                        "Palm Jumeirah Way, Al Nahda, Dubai, UAE, P.O. Box: 12410",
	                        "Creek View, Al Qusais, Dubai, UAE, P.O. Box: 12411",
	                        "Island Street, Umm Suqeim, Dubai, UAE, P.O. Box: 12412",
	                        "Park Avenue, Mirdif, Dubai, UAE, P.O. Box: 12413",
	                        "Marina Drive, Jebel Ali Free Zone, Dubai, UAE, P.O. Box: 12414",
	                        "Desert Lane, Al Barsha South, Dubai, UAE, P.O. Box: 12415",
	                        "Marina Street, Al Furjan, Dubai, UAE, P.O. Box: 12416",
	                        "Palm Boulevard, Al Khawaneej, Dubai, UAE, P.O. Box: 12417",
	                        "Oasis Way, Al Jafiliya, Dubai, UAE, P.O. Box: 12418",
	                        "Beach Road, Umm Ramool, Dubai, UAE, P.O. Box: 12419",
	                        "City Walk, Al Safa, Dubai, UAE, P.O. Box: 12420",
	                        "Tower Street, Al Manara, Dubai, UAE, P.O. Box: 12421",
	                        "Park Avenue, Al Wasl, Dubai, UAE, P.O. Box: 12422",
	                        "Island Drive, Al Satwa, Dubai, UAE, P.O. Box: 12423",
	                        "Creek View, Al Hudaiba, Dubai, UAE, P.O. Box: 12424",
	                        "Marina Promenade, Al Bada, Dubai, UAE, P.O. Box: 12425",
	                        "Desert Lane, Al Quoz Industrial Area, Dubai, UAE, P.O. Box: 12426",
	                        "Palm Street, Al Qusais Industrial Area, Dubai, UAE, P.O. Box: 12427",
	                        "Oasis Way, Al Rashidiya, Dubai, UAE, P.O. Box: 12428",
	                        "Beach Road, Al Warqa'a, Dubai, UAE, P.O. Box: 12429",
	                        "City Walk, Muhaisnah, Dubai, UAE, P.O. Box: 12430",
	                        "Tower Street, Nad Al Hamar, Dubai, UAE, P.O. Box: 12431",
	                        "Park Avenue, Al Twar, Dubai, UAE, P.O. Box: 12432",
	                        "Island Drive, Al Mizhar, Dubai, UAE, P.O. Box: 12433",
	                        "Creek View, Al Nahda, Dubai, UAE, P.O. Box: 12434",
	                        "Marina Drive, Al Karama, Dubai, UAE, P.O. Box: 12435",
	                        "Oasis Lane, Al Rigga, Dubai, UAE, P.O. Box: 12436",
	                        "Island Street, Al Muraqqabat, Dubai, UAE, P.O. Box: 12437",
	                        "Beach Road, Al Garhoud, Dubai, UAE, P.O. Box: 12438",
	                        "City Walk, Al Muteena, Dubai, UAE, P.O. Box: 12439",
	                        "Tower Lane, Al Hamriya, Dubai, UAE, P.O. Box: 12440",
	                        "Creek View, Al Dhagaya, Dubai, UAE, P.O. Box: 12441",
	                        "Palm Street, Al Baraha, Dubai, UAE, P.O. Box: 12442",
	                        "Marina Avenue, Al Murar, Dubai, UAE, P.O. Box: 12443",
	                        "Desert Drive, Al Sabkha, Dubai, UAE, P.O. Box: 12444",
	                        "Oasis Road, Al Raffa, Dubai, UAE, P.O. Box: 12445",
	                        "Palm Jumeirah Way, Al Buteen, Dubai, UAE, P.O. Box: 12446",
	                        "Island Boulevard, Naif, Dubai, UAE, P.O. Box: 12447",
	                        "Beach Street, Port Saeed, Dubai, UAE, P.O. Box: 12448",
	                        "City Avenue, Al Mamzar, Dubai, UAE, P.O. Box: 12449",
	                        "Tower Street, Hor Al Anz, Dubai, UAE, P.O. Box: 12450",
	                        "Creek Lane, Al Waheda, Dubai, UAE, P.O. Box: 12451",
	                        "Marina Drive, Al Qadisia, Dubai, UAE, P.O. Box: 12452",
	                        "Desert Way, Al Tawar, Dubai, UAE, P.O. Box: 12453",
	                        "Beach Road, Al Nahda, Dubai, UAE, P.O. Box: 12454",
	                        "City Walk, Al Qusais, Dubai, UAE, P.O. Box: 12455",
	                        "Tower Street, Al Nahda, Sharjah, UAE, P.O. Box: 12456",
	                        "Creek Avenue, Al Khan, Sharjah, UAE, P.O. Box: 12457",
	                        "Marina Street, Al Majaz, Sharjah, UAE, P.O. Box: 12458",
	                        "Oasis Boulevard, Al Nahda, Sharjah, UAE, P.O. Box: 12459",
	                        "Palm Way, Al Qasimia, Sharjah, UAE, P.O. Box: 12460",
	                        "Island Drive, Al Taawun, Sharjah, UAE, P.O. Box: 12461",
	                        "Beach Lane, Al Nekhailat, Sharjah, UAE, P.O. Box: 12462",
	                        "City Avenue, Al Fisht, Sharjah, UAE, P.O. Box: 12463",
	                        "Tower Boulevard, Al Majaz, Sharjah, UAE, P.O. Box: 12464");
	                        
	                        $randomIndex = array_rand($addressList);
	                        $randomAddress = $addressList[$randomIndex];

	                        $address1 = explode(',' , $randomAddress);


	                        $address['line1'] =  $address1[0].','.$address1[1].','.$address1[4];
	                        $address['city']  =  $address1[2];

	                        if($address['country'] == "UAE"):
	                            $address['country']  =  'AE';
	                        else:
	                            $address['country']  =  'AE';
	                        endif;

	                        return $address;
	}


	/* * *********************************************************************
	 * * Function name 	: download_invoice
	 * * Developed By 	: Dilip
	 * * Purpose  		: This function used for download invoice
	 * * Date 			: 01 FEB 2023
	 * * Updated BY 	: Dilip Halder
	 * * Updated Date 	: 03 February 2024
	 * * **********************************************************************/
	public function download_uwin_invoice($oid ='')
	{
		$this->load->library('Mpdf');
		
		$tblName 				=	'da_lotto_orders';
		$shortField 			= 	array('_id'=> -1 );
		$whereCon['where']		= 	array('order_id'=>$oid);
		$orderData     			=	$this->geneal_model->getData2('single', $tblName, $whereCon,$shortField);

		// User details fetching
		$where2 			    =	array('users_id' => (int)$orderData['user_id']);
		$userData				=	$this->geneal_model->getOnlyOneData('da_users', $where2);
		// echo "<pre>"; print_r($userData); die();

		$data['orderData'] 		= $orderData;
		$data['name'] 	   		= $userData['users_name'].' '.$userData['last_name'];
		$data['mobile']    		= $userData['country_code'] .' '.$userData['users_mobile'];

		$this->load->view('web_api/pos_order_template', $data);
		// return;
	}
	
	// END OF FUNCTION
}