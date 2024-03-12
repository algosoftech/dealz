<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	
	var $postdata;
	var $user_agent;
	var $request_url; 
	var $method_name;
	
	public function  __construct() 	
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('sms_model','emailsendgrid_model','notification_model','emailtemplate_model'));
		$this->lang->load('statictext', 'api');
		$this->load->helper('apidata');
		$this->load->model(array('geneal_model','common_model'));

		$this->user_agent 		= 	$_SERVER['HTTP_USER_AGENT'];
		$this->request_url 		= 	$_SERVER['REDIRECT_URL'];
		$this->method_name 		= 	$_SERVER['REDIRECT_QUERY_STRING'];

		$this->load->library('generatelogs',array('type'=>'common'));
	} 

	/* * *********************************************************************
	 * * Function name : paymentCapture
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to Capture payment.
	 * * Date 	       : 05 October 2023
	 * * **********************************************************************/
	public function paymentCapture()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->post('first_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('NAME_EMPTY'),$result);
			elseif($this->input->post('last_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('LASTNAME_EMPTY'),$result);
			// elseif($this->input->post('users_email') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMAIL_EMPTY'),$result);
			elseif($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PHONE_EMPTY'),$result);
			elseif($this->input->post('product_id') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			else:
				// Validate Produc
				$where 			=	['products_id' => (int)$this->input->post('product_id')];
				$tblName 		=	'da_products';
				$productData 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(empty($productData)):
					echo outPut(0,lang('SUCCESS_CODE'),lang('Product_not_found'),$result);die();
				elseif($productData['stock'] < $this->input->post('product_qty') ):
					echo outPut(0,lang('SUCCESS_CODE'),lang('PRO_QTY'),$result);die();
				endif;



				// Validate product availablity
				$validUpto 		=	strtotime(date('Y-m-d H:i:s',strtotime($productData['validuptodate'].' '.$productData['validuptotime'])));
				$currDate 		=	strtotime(date('Y-m-d H:i:s'));
				
				if($validUpto < $currDate){
					echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_EXPIRE'),$result);die();
				}
				//END
				$where 			=	['users_id' => (int)$this->input->get('users_id')];
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
					$whereCon2['where']		 			= 	array('product_id' => (int)$_POST['product_id'] , 'status' => 'A');	
					$shortField 						= 	array('tickets_seq_id'=>'DESC');
					$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

					$tblName = 'da_quickcoupons_totallist';
					$whereCon3['where']		 			= 	array('product_id' => (int)$_POST['product_id'] ,'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] );	
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
						// echo outPut(0,lang('SUCCESS_CODE'),"Exceeding the ticket limit",$result);
						echo outPut(0,lang('SUCCESS_CODE'),"TICKET_FULL",$result);
						die();
					endif;


					if((int)$this->input->post('product_qty') >1):
						if((float)$this->input->post('vat_amount') + (float)$this->input->post('subtotal') != (float)$this->input->post('total_price')):
							// echo outPut(0,lang('SUCCESS_CODE'),"Exceeding the ticket limit",$result);
							echo outPut(0,lang('SUCCESS_CODE'),"PRICE_ERROR",$result);
							die();
						endif;
					endif;


					if($sellerDetails['availableArabianPoints'] >= (float)$this->input->post('total_price')):

						if(!empty($sellerDetails)):
								if($sellerDetails['status'] == 'A'):
									
									$ORparam["sequence_id"]		    		=	(int)$this->geneal_model->getNextSequence('da_ticket_orders');
							        $ORparam["ticket_order_id"]		        =	$this->geneal_model->getNextQuickBuyOrderId();
							        $ORparam["user_id"] 					=	(int)$this->input->get('users_id');
							        $ORparam["user_type"] 					=	$sellerDetails['users_type']; //$this->input->post('user_type');
						        	$ORparam["user_email"] 					=   $sellerDetails['users_email'];	//$this->input->post('user_email');
								 	$ORparam["user_phone"] 					=	$sellerDetails['users_mobile'];	//$this->input->post('user_phone');
							     	$ORparam["product_id"] 					=	(int)$this->input->post('product_id');
							     	$ORparam["product_title"] 				=	$this->input->post('product_title');
							     	$ORparam["product_qty"] 				=	$this->input->post('product_qty');
							     	$ORparam["product_color"] 				=	$this->input->post('product_color');
							     	$ORparam["product_size"] 				=	$this->input->post('product_size');
							     	$ORparam["prize_title"] 				=	$this->input->post('prize_title');
							        $ORparam["vat_amount"] 					=	(float)$this->input->post('vat_amount');
							        $ORparam["subtotal"] 					=	(float)$this->input->post('subtotal');
							        $ORparam["total_price"] 				=	(float)$this->input->post('total_price');
							        $ORparam["availableArabianPoints"] 		=	(float)$sellerDetails["availableArabianPoints"];
									$ORparam["end_balance"] 				=	(float)$sellerDetails["availableArabianPoints"] - (float)$ORparam["total_price"] ;
								    $ORparam["payment_mode"] 				=	'Arabian Points';
								    $ORparam["payment_from"] 				=	'Quick';
							        $ORparam["product_is_donate"] 			=	$this->input->post('product_is_donate'); //$this->input->post('product_is_donate');
								    $ORparam["order_status"] 				=	"Success";
								    $ORparam["device_type"] 				=	$this->input->post('device_type');
					   				$ORparam["app_version"] 				=	(float)$this->input->post('app_version');
							     	$ORparam["order_first_name"] 			=	$this->input->post('first_name');
							     	$ORparam["order_last_name"] 			=	$this->input->post('last_name');
							     	$ORparam["order_users_country_code"] 	=	$this->input->post('country_code')?$this->input->post('country_code'):"+971";
							     	$ORparam["order_users_mobile"] 			=	$this->input->post('users_mobile');
							     	$ORparam["order_users_email"] 			=	$this->input->post('users_email');
							     	$ORparam["SMS"] 						=	$this->input->post('SMS');
								    $ORparam["creation_ip"] 				=	$this->input->ip_address();
								    $ORparam["created_at"] 					=	date('Y-m-d H:i');

								    //Saving order details for Ticket
								    $orderInsertID 							=	$this->geneal_model->addData('da_ticket_orders', $ORparam);
							    	


							    	$result['successData']					=	$this->successPaymentByArabiyanPoints($ORparam);

							    	echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_SUCCESS'),$result);

								endif;
						else:
							echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
						endif;
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);
					endif;
				else:
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;	

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : successPaymentByArabiyanPoints
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for success Payment By Arabiyan Points
	 * * Date : 23 February 2023
	 * * Updated By : Dilip Halder
	 * * Date : 13 Apirl 2023

	 * * **********************************************************************/
	public function successPaymentByArabiyanPoints($ORparam=array())
	{	
		//Get current order of user.
		$wcon['where']					=	[ 'ticket_order_id' => $ORparam["ticket_order_id"]];
		$data['orderData'] 				=	$this->geneal_model->getData2('single', 'da_ticket_orders', $wcon);
		
		$results = array();
		$couponList = array();
		//update order status

		$tblName 					=	'da_products';
		$where['where'] 			= 	array( 'products_id'=> $ORparam['product_id'] ,'status' => 'A');
		$Sortdata					=	array('category_id'=> 'DESC');
		$productDetails				=	$this->geneal_model->getData2('single', $tblName, $where, $Sortdata);

		// Stock update section..
		$stock 						= $productDetails['stock'] - $ORparam['product_qty']  ; // updated stock.

		$updateParams 				=	array( 'stock' => (int)$stock );	
		$updatedstatus 				= $this->geneal_model->editData('da_products',$updateParams,'products_id',(int)$ORparam['product_id']);
		//

		
		$updateParams 					=	array( 'order_status' => 'Success','created_at' => $ORparam['created_at']);	
		$updatedstatus = $this->geneal_model->editData('da_ticket_orders',$updateParams,'users_id',(int)$ORparam['user_id']);

		// Deduct the purchesed points and get available arabian points of user.
		$currentBal 					= 	$this->geneal_model->debitPointsByAPI($ORparam['total_price'],$ORparam["user_id"]); 

		// //Get current Ticket order sequence from admin panel.
		// $tblName = 'da_tickets_sequence';
		// $whereCon2['where']		 			= 	array('product_id' => (int)$ORparam['product_id'] , 'status' => 'A');	
		// $shortField 						= 	array('tickets_seq_id'=>'DESC');
		// $CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

		// $tblName = 'da_quickcoupons_totallist';
		// $whereCon3['where']		 			= 	array('product_id' => (int)$ORparam['product_id'] );	
		// $shortField3 						= 	array('coupon_id'=>'DESC');
		// $SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

		// //Get current Ticket order sequence from admin panel.
		$tblName = 'da_tickets_sequence';
		$whereCon2['where']		 			= 	array('product_id' => (int)$_POST['product_id'] , 'status' => 'A');	
		$shortField 						= 	array('tickets_seq_id'=>'DESC');
		$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

		$tblName = 'da_quickcoupons_totallist';
		$whereCon3['where']		 			= 	array('product_id' => (int)$_POST['product_id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id']);	
		$shortField3 						= 	array('coupon_id'=>'DESC');
		$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

		$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
		
		if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
			$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
		endif;

		if($this->input->post('product_is_donate') == 'Y'):
			$check_availblity = $this->input->post('product_qty') * 2;
		endif;
		
		$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  

		//Get current sponsored coupon count from product.
		$wconsponsoredCount['where'] 	=	array('products_id' => (int)$_POST['product_id'] );
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

		if($this->input->post('isVoucher') == 'Y'):
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

				if($ORparam["product_is_donate"] == 'Y' && !empty($CurrentTicketSequence)):

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

					if($this->input->post('isVoucher') == 'Y'):
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
					$couponData['is_donated'] 			=	'Y';
					$couponData['coupon_status'] 		=	'Live';
					$couponData['coupon_code'] 			= 	$couponList;
					$couponData['draw_date'] 			= 	array($ProductData['draw_date']);
					$couponData['draw_time'] 			= 	array($ProductData['draw_time']);
					$couponData['coupon_type'] 			= 	'Donated';
					$couponData['created_at']			=	date('Y-m-d H:i');



					$this->geneal_model->addData('da_ticket_coupons',$couponData);

					$results = $couponData;
					

					if($couponData['order_users_email']):
						$this->emailsendgrid_model->sendQuickMailToUser($couponData['ticket_order_id']);
					endif;

					if($ORparam['SMS'] == "Y"):
						$this->sms_model->sendQuickTicketDetails($couponData['ticket_order_id'],$couponData['order_users_mobile'],$couponList,$couponData['product_id'],$couponData['order_users_country_code'],$couponData['is_donated']);
					endif;
					echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_SUCCESS'),$results);
					exit();
				else:



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
						    $quickcoupons["coupon_sold_number"] =	$totalsoldqty;
						    $quickcoupons["creation_ip"] 		=	$this->input->ip_address();
						    $quickcoupons["updated_at"] 		=	date('Y-m-d H:i');
						    //Saving quick coupons number  

							$this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
						    //End
						    
						endif;
					}


					if($this->input->post('isVoucher') == 'Y'):
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
					$couponData['order_users_country_code']	= 	$ORparam["order_users_country_code"];
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
					$couponData['is_donated'] 			=	'N';
					$couponData['coupon_status'] 		=	'Live';
					$couponData['coupon_code'] 			= 	$couponList;
					$couponData['draw_date'] 			= 	array($ProductData['draw_date']);
					$couponData['draw_time'] 			= 	array($ProductData['draw_time']);
					$couponData['created_at']			=	date('Y-m-d H:i');

					$this->geneal_model->addData('da_ticket_coupons',$couponData);
					$results = $couponData;

					if($couponData['order_users_email']):
						$this->emailsendgrid_model->sendQuickMailToUser($ORparam["ticket_order_id"]);
					endif;
					if($ORparam['SMS'] == "Y"):
						$this->sms_model->sendQuickTicketDetails($couponData['ticket_order_id'],$couponData['order_users_mobile'],$couponList,$couponData['product_id'],$couponData['order_users_country_code'],$couponData['is_donated']);
					endif;
					echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_SUCCESS'),$results);	
					exit();
				endif;

			else:
				echo outPut(0,lang('SUCCESS_CODE'),lang('Ticket_FULL'),$results);
				exit();	
			endif;
			//Start Create Coupons for donate product
			

	}

	/* * *********************************************************************
	 * * Function name : newDueManagement
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for  get Summary Report
	 * * Date : 26 September 2023
	 * * **********************************************************************/
	public function newDueManagement()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 			= 	array();

		if(requestAuthenticate(APIKEY,'GET')):

			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

				$DZL_USERID  =   $this->input->get('users_id');
				$fromDate    =  $this->input->get('fromDate');
				$toDate      =  $this->input->get('toDate');
				// Sales Person..
				if($DZL_USERID):
					$UserwhereCon['where'] = array('users_id' => (int)$DZL_USERID );
					$USERDATA   = $this->common_model->getData('single','da_users',$UserwhereCon);
				endif;
					$UsersType  = $USERDATA['users_type'];

				// Binded Users..
				$tblName 	 		  = 'da_users';
				$whereCon['where']    = array('bind_person_id'=> (string)$DZL_USERID , 'status' => 'A');
				$shortField  		  = array('users_id',-1);
				$BindedUsers   		  = $this->common_model->getData('multiple',$tblName,$whereCon,$shortField);

				// Search by product/retailer details....
				$salesperson = $this->input->get('salesperson');
				if(empty($salesperson)):

					$USERID = array();
					$DueManagement = array();
					foreach ($BindedUsers as $key => $items):
						$USERID[] = $items['users_id']; 

						$tbl_name   =  'da_dueManagement';
						// $where      = array( 'user_id_deb' => (int)$DZL_USERID, 'user_id_to'  => ['$in' => $USERID] );
						$where      = array( 'user_id_deb' => (int)$DZL_USERID, 'user_id_to'  => $items['users_id'] );
						$matchStage = array('$match' => $where);
						$groupStage = array('$group' => array(
						    '_id' => '$user_id_to',
						    'count' => array('$sum' => 1),

						    'users_name' 				=> array('$last' =>  $items['users_name']),
						    'last_name' 				=> array('$last' =>  $items['last_name']),
						    'country_code' 				=> array('$last' =>  $items['country_code']),
						    'users_mobile' 				=> array('$last' =>  $items['users_mobile']),
						    'users_email' 				=> array('$last' =>  $items['users_email']),
						    'availableArabianPoints' 	=> array('$last' =>  $items['availableArabianPoints']),
						    'sender_users_name' 		=> array('$last' =>  $USERDATA['users_name']),
						    'sender_last_name' 		    => array('$last' =>  $USERDATA['last_name']),
						    'sender_country_code' 		=> array('$last' =>  $USERDATA['country_code']),
						    'sender_users_mobile' 		=> array('$last' =>  $USERDATA['users_mobile']),
						    'sender_users_email' 		=> array('$last' =>  $USERDATA['users_email']),
						    'user_id_deb' 				=> array('$last' =>  '$user_id_deb'),
						    'bind_person_id'    		=> array('$last' =>  '$user_id_deb'),
						    'user_id_to'        		=> array('$last' =>  '$user_id_to'),
						    'recharge_amt'      		=> array('$sum' => '$recharge_amt'),
						    'cash_collected'    		=> array('$sum' => '$cash_collected'),
						    'due_amount'        		=> array('$sum' => '$due_amount'),
						    'advanced_amount'   		=> array('$sum' => '$advanced_amount'),
						    'created_by'       	 		=> array('$last' => '$created_by'),
						    'user_type'        		 	=> array('$last' => '$user_type'),
						    'created_at'        		=> array('$last' => '$created_at'),
						));

						$sortStage 			  = array('$sort' => array('_id' => -1)); // Assuming you want to sort by user_id_to
						$aggregatePipeline 	  = array($matchStage, $groupStage, $sortStage);
						$DueManagementArray   = $this->mongo_db->aggregate($tbl_name, $aggregatePipeline, array('batchSize' => 4)); 
						
						if($DueManagementArray):
							$DueManagement[] =  $DueManagementArray[0];
						endif;
					endforeach;
					// $data['DueManagement']   = $DueManagement?$DueManagement:'';
				else:
					$data['salesperson'] = $salesperson;
					if(is_numeric($salesperson)):
						$sValue = (int)$salesperson;
						$whereCondition['where']	 = 	array( 'users_mobile' => (int)$sValue ,'status' => 'A');
						// $whereCon['where']	 = 	array( 'sender_users_mobile' => (int)$sValue ,'record_type' => 'Debit','user_type' => 'Promoter');
					else:
						$sValue = $salesperson;
						$whereCondition['where']	 = 	array( 'users_email' => $sValue ,'status' => 'A');
						// $whereCon['where']	 = 	array( 'sender_users_email' => $sValue ,'record_type' => 'Debit','user_type' => 'Promoter');
					endif;

					$Salesperson	   =  $this->common_model->getParticularFieldByMultipleCondition(array('users_id'),'da_users',$whereCondition);
					$DZL_USERID        =  $Salesperson['users_id']; 
					$whereCon['where'] = array('user_id_deb' => (int)$DZL_USERID,'bind_person_id' => (string)$DZL_USERID );

					$tblName 			 = 	'da_dueManagement';
					$shortField 		 = 	array('due_management_id'=> -1 );
					$Salesperson_Due  	 =	$this->geneal_model->duemanagementweb('multiple',$tblName,$whereCon,$shortField);
					// $data['Salesperson_Due']    = $Salesperson_Due?$Salesperson_Due:'';
				endif;

				if($UsersType == "Super Salesperson"):
					$tblName 				= 	'da_users';
					$shortField 			= 	'';
					$whereConsales['where']		=	array('users_type' => 'Sales Person','status' => "A" );
					$whereConsales['where_in']	=   array("0" => "users_email" ,"1"=>array("manawalanwaseem@gmail.com","shafimak25@gmail.com","ismailkk0520@gmail.com","jaseer26@gmail.com","jaleel.dmi@gmail.com"));

					$salesPersonList 		    = 	$this->common_model->getData('multiple',$tblName,$whereConsales,$shortField);
					$data['salespersonList']    = $salesPersonList;

				endif;
				if($Salesperson_Due|| $DueManagement ):
					$dueData        = $DueManagement?$DueManagement : $Salesperson_Due;

					$TotalRecharge  =   0;
					$todayTotalSale =   0;
					foreach($dueData as $key => $items):
					 	$TotalRecharge = $TotalRecharge + $items['recharge_amt'];

					 	$UserIdTo = $items['user_id_to'];
						$tblName 					=	'da_ticket_orders';
						$shortField 				= 	array('sequence_id'=> -1 );
						$whereCona  				=	array(
															'user_id' => (int)$UserIdTo  , 'status' => array('$ne'=> 'CL'),
															'created_at' => array(  '$gte' => $data['fromDate']?$data['fromDate']:date('Y-m-d 00:01') , '$lte' => $data['toDate']?$data['toDate']:date('Y-m-d 23:59'))
														);
						$todaysales					= $this->geneal_model->todaysales($tblName,$whereCona,$shortField);

						

						$todayTotalSale += $todaysales;
						if($DueManagement):
							$DueManagement[$key]['todaySales'] = $todaysales;
							$data['DueManagement']   = $DueManagement?$DueManagement:'';
						else:
							$Salesperson_Due[$key]['todaySales'] = $todaysales;
							$data['Salesperson_Due']   = $Salesperson_Due?$Salesperson_Due:'';
						endif;

					endforeach;	



					$data['todayTotalSale']   	  = $todayTotalSale;
					$data['TotalRecharge']   	  = $TotalRecharge;
					$data['todayTotalRecharge']   = $todayTotalRecharge;

				endif;
				if($data):
					$results = $data;
					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$results);	
				endif;

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
		
   
	
}