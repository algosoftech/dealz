<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends CI_Controller {
	
	var $postdata;
	var $user_agent;
	var $request_url; 
	var $method_name;
	
	public function  __construct() 	
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('sms_model','notification_model','emailtemplate_model','emailsendgrid_model'));
		$this->lang->load('statictext', 'api');
		$this->load->helper('apidata');
		$this->load->model(array('geneal_model','common_model'));

		$this->user_agent 		= 	$_SERVER['HTTP_USER_AGENT'];
		$this->request_url 		= 	$_SERVER['REDIRECT_URL'];
		$this->method_name 		= 	$_SERVER['REDIRECT_QUERY_STRING'];

		$this->load->library('generatelogs',array('type'=>'users'));
	} 
 
	public function QuickTicket()
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
			// elseif($this->input->post('users_password') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('PASSWORD_EMPTY'),$result);
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
	 * * Function name : UpdateNotification
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for update Notification
	 * * Date : 2 March 2023
	 * * **********************************************************************/
	public function GetQuickUser()
	{

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PHONE_EMPTY'),$result);
			else:

				//Check user exits or not.
				$wcon['where']			=	array('users_mobile' => (int)$this->input->post('users_mobile') ,'status' => "A" );
				$Userdata 		=	$this->geneal_model->getData2('single', 'da_quick_users', $wcon);

				if(!empty($Userdata)):
					$results = $Userdata;
					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$results);	
				endif;

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : UpdateNotification
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for update Notification
	 * * Date : 3 March 2023
	 * * updated By : Dilip Halder
	 * * Updarted Date : 24 Nov 2023
	 * * **********************************************************************/
	public function GetQuickTicketHistory()
	{

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'GET')):
			
			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

				//Check user exits or not.
				$tblName 					=	'da_ticket_orders';
				$whereCondition['where']    =   array('user_id' => (int)$this->input->get('users_id') ,'order_status' => 'Success' );
				$shortField 				=	array('sequence_id' => -1 );
				$Orderlist 					= 	$this->common_model->getData('multiple',$tblName,$whereCondition,$shortField);

				if($Orderlist):
					$ticket_order_id = array();
					foreach ($Orderlist as $key => $items):
						 $ticket_order_id[$key] = $items['ticket_order_id'];
					endforeach;

					$tableName 		   		=   'da_ticket_coupons';
					$wcon['where']  		=   array( 'users_id' => (int)$this->input->get('users_id'));
					$wcon['where_in']		= 	array(array(0 => 'ticket_order_id' , '1' => $ticket_order_id));	
					$shortField 			=	array('coupon_id' => -1 );
					$userOrderList 			= $this->common_model->getDataByNewQuery('','multiple',$tableName,$wcon,$shortField);

					foreach ($userOrderList as $key => $item) :
						unset($userOrderList[$key]['draw_date']);
						unset($userOrderList[$key]['draw_time']);
						$userOrderList[$key]['status'] =  $Orderlist[$key]['status']?$Orderlist[$key]['status']:'';
						// $userOrderList[$key]['draw_date'] =  array($item['draw_date']);
						// $userOrderList[$key]['draw_time'] =  array($item['draw_time']);
					endforeach;

				endif;

				if(!empty($userOrderList)):
					$results = $userOrderList;
					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$results);	
				endif;

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
	 



	/* * *********************************************************************
	 * * Function name : download Quick invoice.
	 * * Developed By : Dilip
	 * * Purpose  : This function used for download invoice
	 * * Date :: 16 March 2023
	 * * **********************************************************************/
	public function download_quick_invoice($oid ='')
	{	
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

		
		$this->DownlodeOrderPDF($oid.'.pdf');
		return;
	}

	public function DownlodeOrderPDF($file='')
	{  
		// echo $this->config->item("root_path")."/assets/orderpdf/".$file;
		// die();
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
	 * * Function name : SummaryReportSearch
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for  get Summary Report
	 * * Date : 20 March 2023
	 * * Updated by : Dilip Halder
	 * * Udated Date : 26-04-2023
	 * * **********************************************************************/
	public function SummaryReportSearch()
	{


		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'GET')):
			
			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

			 	$product_title =  $from_date = $this->input->get('product_title');
		  		$start_date = date('Y-m-d 00:00' ,strtotime($this->input->get('start_date')) );
		      	$end_date = date('Y-m-d 23:59' ,strtotime($this->input->get('end_date')));
				$wcon['where']          = array();

					
				if(!empty($product_title)):
					$wcon['where']['product_title']   =   $product_title;
				endif;

				if($this->input->get('start_date')):
					$wcon['where_gte'] 	= array(array("created_at",$start_date));
					$whereCon['where_gte'] 	= array(array("created_at",$start_date));
				endif;

				if($this->input->get('end_date')):
					$wcon['where_lte'] 	= array(array("created_at",$end_date));
				endif;
				$wcon['where']['user_id']         =   (int)$this->input->get('users_id');
				$wcon['where']['status']          =   array('$ne'=> 'CL');

				$wconUserCon['where']  =  array('users_id' => (int)$this->input->get('users_id'));
				$userResult			   =  $this->geneal_model->getData2('single', 'da_users', $wconUserCon);
				
				$tbl 				=	'da_ticket_orders';
			 	$shortField 		=	array('product_title' => -1 );
				$orderList 			= $this->geneal_model->getsummeryReport('multiple',$tbl,$wcon ,$shortField);
				$result['totalArabianPoints'] 		= $userResult['totalArabianPoints'];
				$result['availableArabianPoints'] 	= $userResult['availableArabianPoints'];
				$result['products'] 				= $orderList;

				if($this->input->get('product_title') || $this->input->get('start_date')  || $this->input->get('end_date') ):
					$result['totalArabianPoints'] 	  =	$result['products'][0]['availableArabianPoints']?$result['products'][0]['availableArabianPoints']:'N/A';
					$result['availableArabianPoints'] =	$result['products'][0]['end_balance']?$result['products'][0]['end_balance']:'N/A';
				endif;

				if(!empty($result)):
					$results = $result;
					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$results);	
				endif;

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	// /* * *********************************************************************
	//  * * Function name : getAvailableTickets
	//  * * Developed By : Dilip Halder
	//  * * Purpose  : This function used for update Notification
	//  * * Date : 04 August 2023
	//  * * **********************************************************************/
	// public function getAvailableTickets($oid=""){
	
	//   	$apiHeaderData 		=	getApiHeaderData();
	// 	$this->generatelogs->putLog('APP',logOutPut($_POST));
	// 	$result 							= 	array();

	// 	if(requestAuthenticate(APIKEY,'GET')):
			
	// 		if( $this->input->get('product_id') == ''): 
	// 			echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
	// 		else: 

	// 			$product_id =  $this->input->get('product_id');

	// 			$tblName 					=	'da_products';
	// 			$where['where'] 			= 	array( 'products_id'=> (int)$product_id ,'status' => 'A');
	// 			$Sortdata					=	array('category_id'=> 'DESC');
	// 			$productDetails		=	$this->common_model->getData('single', $tblName, $where, $Sortdata);	 

	// 			// sold out status.
	// 			$stock = $productDetails['stock'];

	// 			if($stock > 0):

	// 				$tblName1 					=	'da_tickets_sequence';
	// 				$where1['where'] 			= 	array( 'product_id'=> (int)$product_id ,'status' => 'A');
	// 				$Sortdata					=	array('tickets_seq_id'=> -1);
	// 				$available_ticket			=	$this->common_model->getData('single', $tblName1, $where1, $Sortdata);
 
	// 				//  Soldout Tickets numbers in Online Purchases.
	// 				$tblName1 					=	'da_coupons';
	// 				$where1['where'] 			= 	array( 'product_id'=> (int)$product_id );
	// 				$Sortdata					=	array('coupon_id'=> 1);
	// 				$SoldoutTickets				=	$this->common_model->getData('multiple', $tblName1, $where1, $Sortdata);

	// 				//  Soldout Tickets numbers in Online Purchases.
	// 				$tblName1 					=	'da_ticket_coupons';
	// 				$where1['where'] 			= 	array( 'product_id'=> (int)$product_id );
	// 				$Sortdata					=	array('coupon_id'=> 1);
	// 				$QuickSoldoutTickets				=	$this->common_model->getData('multiple', $tblName1, $where1, $Sortdata);

	// 				// Soldout tickets lists.

	// 				// Fetching online campaign sold out tickets lits.
	// 				$soldoutcouponlist = array();
	// 				foreach ($SoldoutTickets as $key => $items) :
	// 					array_push($soldoutcouponlist, str_replace($available_ticket['tickets_prefix'] , '', $items['coupon_code']));
	// 				endforeach;

	// 				// Fetching Quick campaign sold out tickets lits.
	// 				foreach ($QuickSoldoutTickets as $key => $items) :
	// 					foreach ($items['coupon_code'] as  $couponitem ) :
	// 						array_push($soldoutcouponlist, str_replace($available_ticket['tickets_prefix'] , '', $couponitem));
	// 					endforeach;
	// 				endforeach;

	// 				//  All Campigns coupon list for same products.
	// 				// $couponlist 

	// 				// Getting ticket sequence...
	// 				if($available_ticket):

	// 					$tickets_prefix =  $available_ticket['tickets_prefix'];
	// 					$tickets_sequence_start =  $available_ticket['tickets_sequence_start'];
	// 					$tickets_sequence_end =  $available_ticket['tickets_sequence_end'];

	// 					for ($i=$tickets_sequence_start; $i <= $tickets_sequence_end; $i++) { 
	// 						$couponNumber[]  = $i;
	// 					}
	// 				else:
	// 					echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_OUT_OF_STOCK'),$result);
	// 				endif;
					
	// 				$ticketList = array();
	// 				foreach($couponNumber as $key => $couponList):
	// 					 if(in_array($couponList,$soldoutcouponlist)):
	// 					 	// $ticketList[$key]['ticket']  = (string)$couponList;
	// 					 	// $ticketList[$key]['status']  = 'soldout';
	// 					 else:
	// 					 	$ticketList[$key]['ticket']  = (string)$couponList;
	// 					 	$ticketList[$key]['status']  = 'available';
	// 					 endif;
	// 				endforeach;
 

	// 				// Sample long array with data
	// 				// $longArray = range(1, 100); // Replace this with your actual long array
	// 				$longArray = $ticketList;

	// 				// Items per page
	// 				$itemsPerPage = $this->input->get('itemsPerPage');
	// 				$pageno = $this->input->get('page');

	// 				// Current page number (received from URL query parameter, e.g., ?page=2)
	// 				$page = isset($pageno) ? (int)$pageno : 1;

	// 				// Calculate total number of pages
	// 				$totalPages = ceil(count($longArray) / $itemsPerPage);

	// 				// Calculate the starting index of the current page
	// 				$startIndex = ($page - 1) * $itemsPerPage;

	// 				// Extract the data for the current page
	// 				$data = array_slice($longArray, $startIndex, $itemsPerPage);

	// 				$totalpage= array();
	// 				// Pagination links
	// 				for ($i = 1; $i <= $totalPages; $i++) {
	// 				    if ($i == $page) {
	// 				         $current_page = $i;
	// 				         $totalpage[] = $i;
	// 				    } else {
	// 				         $totalpage[] = $i;
	// 				    }
	// 				}

	// 				$totalpage = count($totalpage);

	// 				$results['series'] 				= $tickets_prefix;
	// 				$results['start_range'] 		= $tickets_sequence_start;
	// 				$results['end_range'] 			= $tickets_sequence_end;
	// 				$results['CouponList'] 			= array_values($data);
	// 				$results['current_page'] 		= $current_page;
	// 				$results['total_page'] 			= $totalpage;

	// 				if(!empty($results)):
	// 					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
	// 				else:
	// 					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$results);	
	// 				endif;
	// 			else:
	// 				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_OUT_OF_STOCK'),$result);
	// 			endif; 

	// 		endif;
	// 	else:
	// 		echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
	// 	endif;	
	// }

/* * *********************************************************************
 * * Function name : getAvailableTickets
 * * Developed By : Dilip Halder
 * * Purpose  : This function used for update Notification
 * * Date : 04 August 2023
 * * **********************************************************************/
public function getAvailableTickets($oid=""){
	
	  	$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'GET')):
			
			if( $this->input->get('product_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			else: 

			// Added this to restrict function.
			echo outPut(0,lang('SUCCESS_CODE'),lang('CHOOSE_COUPON_CODES'),$result);die();


			$CA['product_id'] = $this->input->get('product_id');

			//Get current Ticket order sequence from admin panel.
			$tblName = 'da_tickets_sequence';
			$whereCon2['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'status' => 'A');	
			$shortField 						= 	array('tickets_seq_id'=>'ASC');
			$TicketSequence 				= 	$this->common_model->getData('multiple',$tblName,$whereCon2,$shortField,'0','0');

			$tblName = 'da_products';
			$whereCon2['where']		 			= 	array('products_id' => (int)$CA['product_id'] , 'status' => 'A');	
			$productDetails 					= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');	
			// echo "<pre>";print_r($productDetails['title']);die();
			
			// sold out status.
			$stock = $productDetails['stock'];

			if($stock == 0):
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_OUT_OF_STOCK'),$result);die();
			endif;

			if(empty($productDetails)):
		 		echo outPut(0,lang('SUCCESS_CODE'),lang('CAMPAIGN_CLOSED'). " for " .$productDetails['title'],$result);die();
			endif;

			if($TicketSequence):
				//Getting Tickets generating sequence from each available slots ..
				foreach($TicketSequence as $key => $CurrentTicketSequence):
					$ticketID_Loop[] 				= $CurrentTicketSequence['tickets_seq_id'];
					$series_Loop[] 					= $CurrentTicketSequence['tickets_prefix'];
					$tickets_sequence_start_Loop[] 	= $CurrentTicketSequence['tickets_sequence_start'];
					$tickets_sequence_end_Loop[] 	= $CurrentTicketSequence['tickets_sequence_end'];
					$tickets_sold_count_Loop[] 		= $CurrentTicketSequence['tickets_sold_count'];
					$total_tickets_Loop[] 			= $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'] ;
				endforeach;
			else:
		 		echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " for " .$productDetails['title'],$result);die();
			endif;

			$coupons 		= array();
			$CouponList 	= array();
			$soldoutCoupons = array();

			// Online coupon checking...
			$whereCondition['where'] = array( 'product_id' => (int)$CA['product_id']);
			$CouponCount			 = $this->geneal_model->getData2('multiple','da_coupons',$whereCondition);

			// Quick coupon checking...
			$whereCondition1['where']        = array('product_id'=> (int)$CA['product_id']   );
			$QuickSoldoutTickets			 = $this->geneal_model->getData2('multiple','da_ticket_coupons',$whereCondition1);

			if($CouponCount):
				foreach ($CouponCount as $key => $item):
					$soldoutCoupons[] =  $item['coupon_code'];
				endforeach;
			endif;

			if($CouponCount):
				foreach($QuickSoldoutTickets as $key => $item):
					foreach($item['coupon_code'] as $coupons):
						$soldoutCoupons[] =  $coupons;
					endforeach;
				endforeach;
			endif;
			// echo "<pre>";print_r($soldoutCoupons);die();


			$coupons 		= array();
			$CouponList 	= array();

			foreach($series_Loop as $loopcount => $series):
			    $start_Ticket = $tickets_sequence_start_Loop[$loopcount];
			    $end_Ticket = $tickets_sequence_end_Loop[$loopcount];

			    $sequence = range($start_Ticket, $end_Ticket);

			    $sequence = array_map(function ($number) use ($series) {
			        return $series . $number;
			    }, $sequence);

			    // Use array_filter to remove the specified coupon codes
			    $CouponArray = array_filter($sequence, function($coupon) use ($soldoutCoupons) {
			        return !in_array($coupon, $soldoutCoupons);
			    });

			    // Append the generated coupons to the list
			    $CouponList = array_merge($CouponList, $CouponArray);

			    // If we have at least 10 coupons, break the loop
			    if (count($CouponList) >= $tickets):
			        break;
			    endif;
			endforeach;


			$ticketList = array();


			foreach($CouponList as $key => $singleCouon):
				 if(in_array($couponList,$soldoutCoupons)):

				 	$singleCouon = str_replace($series, '', $singleCouon);

				 	$ticketList[$key]['ticket']  = (string)$singleCouon;
				 	$ticketList[$key]['status']  = 'soldout';
				 else:

				 	$singleCouon = str_replace($series, '', $singleCouon);

				 	$ticketList[$key]['ticket']  = (string)$singleCouon;
				 	$ticketList[$key]['status']  = 'available';
				 endif;
			endforeach;
			
			// echo "<pre>";print_r($CouponList);die();
			// Sample long array with data
			
			// $longArray = range(1, 100); // Replace this with your actual long array
			$longArray = $ticketList;

			// Items per page
			$itemsPerPage = $this->input->get('itemsPerPage');
			$pageno = $this->input->get('page');

			// Current page number (received from URL query parameter, e.g., ?page=2)
			$page = isset($pageno) ? (int)$pageno : 1;

			// Calculate total number of pages
			$totalPages = ceil(count($longArray) / $itemsPerPage);

			// Calculate the starting index of the current page
			$startIndex = ($page - 1) * $itemsPerPage;

			// Extract the data for the current page
			$data = array_slice($longArray, $startIndex, $itemsPerPage);

			$totalpage= array();
			// Pagination links
			for ($i = 1; $i <= $totalPages; $i++) {
			    if ($i == $page) {
			         $current_page = $i;
			         $totalpage[] = $i;
			    } else {
			         $totalpage[] = $i;
			    }
			}

			$totalpage = count($totalpage);

			$results['series'] 				= $series;
			$results['start_range'] 		= $start_Ticket;
			$results['end_range'] 			= $end_Ticket;
			$results['CouponList'] 			= array_values($data);
			$results['current_page'] 		= $current_page;
			$results['total_page'] 			= $totalpage;

			if(!empty($results)):
				echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
			else:
				echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$results);	
			endif;


		endif;
	else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
	endif;	
}

	/* * *********************************************************************
	 * * Function name : getSelectedField
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for get available coupons
	 * * Date : 29 August 2023
	 * * **********************************************************************/
	public function getSelectedField($oid=""){
	
	  	$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'POST')):

			// Added this to restrict function.
			echo outPut(0,lang('SUCCESS_CODE'),lang('CHOOSE_COUPON_CODES'),$result);die();
			
			if( $this->input->get('product_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			else:

				// echo "<pre>";print_r($_POST);die();

				$product_id 	=  $this->input->get('product_id');
				$search_field 	=  $this->input->post('search_field');
				$search_value 	=  $this->input->post('search_value');

				//Get current Ticket order sequence from admin panel.
				$tblName = 'da_tickets_sequence';
				$whereCon2['where']		 			= 	array('product_id' => (int)$product_id , 'status' => 'A');	
				$shortField 						= 	array('tickets_seq_id'=>'ASC');
				$TicketSequence 				= 	$this->common_model->getData('multiple',$tblName,$whereCon2,$shortField,'0','0');

				$tblName = 'da_products';
				$whereCon2['where']		 			= 	array('products_id' => (int)$product_id , 'status' => 'A');	
				$productDetails 					= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');	
				
				// echo "<pre>";print_r($productDetails['title']);die();
				// sold out status.
				$stock = $productDetails['stock'];

				if($stock == 0):
					echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_OUT_OF_STOCK'),$result); die();
				endif;

				if(empty($productDetails)):
			 		echo outPut(0,lang('SUCCESS_CODE'),lang('CAMPAIGN_CLOSED'). " for " .$productDetails['title'],$result);die();
				endif;

				//Getting Tickets generating sequence from each available slots ..
				foreach($TicketSequence as $key => $CurrentTicketSequence):
					$ticketID_Loop[] 				= $CurrentTicketSequence['tickets_seq_id'];
					$series_Loop[] 					= $CurrentTicketSequence['tickets_prefix'];
					$tickets_sequence_start_Loop[] 	= $CurrentTicketSequence['tickets_sequence_start'];
					$tickets_sequence_end_Loop[] 	= $CurrentTicketSequence['tickets_sequence_end'];
					$tickets_sold_count_Loop[] 		= $CurrentTicketSequence['tickets_sold_count'];
					$total_tickets_Loop[] 			= $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'] ;
				endforeach;

				$coupons 		= array();
				$CouponList 	= array();
				$soldoutCoupons = array();

				// Online coupon checking...
				$whereCondition['where'] = array( 'product_id' => (int)$product_id);
				$CouponCount			 = $this->geneal_model->getData2('multiple','da_coupons',$whereCondition);

				// Quick coupon checking...
				$whereCondition1['where']        = array('product_id'=> (int)$product_id   );
				$QuickSoldoutTickets			 = $this->geneal_model->getData2('multiple','da_ticket_coupons',$whereCondition1);

				if($CouponCount):
					foreach ($CouponCount as $key => $item):
						$soldoutCoupons[] =  $item['coupon_code'];
					endforeach;
				endif;

				if($CouponCount):
					foreach($QuickSoldoutTickets as $key => $item):
						foreach($item['coupon_code'] as $coupons):
							$soldoutCoupons[] =  $coupons;
						endforeach;
					endforeach;
				endif;
				// echo "<pre>";print_r($soldoutCoupons);die();

				$coupons 		= array();
				$CouponList 	= array();

				foreach($series_Loop as $loopcount => $series):
				    $start_Ticket = $tickets_sequence_start_Loop[$loopcount];
				    $end_Ticket = $tickets_sequence_end_Loop[$loopcount];

				    $sequence = range($start_Ticket, $end_Ticket);

				    $sequence = array_map(function ($number) use ($series) {
				        return $series . $number;
				    }, $sequence);

				    // Use array_filter to remove the specified coupon codes
				    $CouponArray = array_filter($sequence, function($coupon) use ($soldoutCoupons) {
				        return !in_array($coupon, $soldoutCoupons);
				    });

				    // Append the generated coupons to the list
				    $CouponList = array_merge($CouponList, $CouponArray);

				    // If we have at least 10 coupons, break the loop
				    if (count($CouponList) >= $tickets):
				        break;
				    endif;
				endforeach;
				
				$ticketList = array();

				$CouponList = array_map(function ($number) use ($series) {
			        return str_replace($series, '', $number);
			    }, $CouponList);

				// echo "<pre>";print_r($CouponList);die();


				if($search_field == 'birthday'):

					$CouponList1 = $CouponList;

					if(!empty($search_value)):
						$search_value =  str_replace(' ' ,'' , $search_value);
						$search_value  = explode(',', $search_value);
						$search_value = array_filter($search_value);
						
						$SearchCount = count($search_value);

						$searchList = array();
						foreach ($search_value as $key => $searchitem):
							$searchList[$key]['coupon_code'] = $searchitem;
							$searchList[$key]['coupon_len'] = strlen($searchitem);
						endforeach;

			            $searchArray = array();
						foreach ($searchList as $key => $item):
								for ($i=0; $i <$item['coupon_len']; $i++):
									$first = 0;
									if($i>0):
										$first = $next-1;
									endif;
									$next = $i+2;

									$twodigits = substr($item['coupon_code'],$first, $next);

								 	if(strlen($twodigits)==2):
									 	$searchArray[] = $twodigits;
								 	elseif(empty($twodigits)|| strlen($twodigits)==1 ):
									 	$searchArray[] = $item['coupon_code'];
								 	endif;
								endfor;
						endforeach;

						$searchArray = array_filter(array_unique($searchArray));	
						 

						foreach($searchArray as $loopcount => $series):
							$CouponList = $CouponList1;
							$CouponList = array_filter($CouponList, function($coupon='') use ($series) {
								if(strpos($coupon,$series) !== false){
									return  $coupon;
								}
							});

							$AllCouponList[] = $CouponList;
						endforeach;

						$flattenedArray = [];

						foreach ($AllCouponList as $subArray):
						    $flattenedArray += $subArray;
						endforeach;

						$flattenedArray = array_unique(array_values($flattenedArray));
						sort($flattenedArray);
						
						$ticketList = array();
						foreach ($flattenedArray as $SearchValuekey => $singleCouon):
								$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
								$ticketList[$SearchValuekey]['status']  = 'available';
						endforeach;
					endif;
				endif;


				if($search_field == 'lucky_number'):
					if(!empty($search_value)):
						$search_value  = explode(',', $search_value);
						if(!empty($search_value)):
								foreach($search_value as $SearchValuekey => $singleCouon):
									 if(in_array($singleCouon,$soldoutCoupons)):
									 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
									 	$ticketList[$SearchValuekey]['status']  = 'soldout';
									 elseif(!in_array($singleCouon,$soldoutCoupons)  && !in_array($singleCouon,$CouponList) ):
									 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
									 	$ticketList[$SearchValuekey]['status']  = 'not available';

					 					echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " :- " .$singleCouon ,$result);die();
									 else:
									 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
									 	$ticketList[$SearchValuekey]['status']  = 'available';
									 endif;
								endforeach;
						endif;
					endif;
				endif;

				if($search_field == 'end_with'):

					$range 		   = $CouponList;
					$search_value  = explode(',', $search_value);

					function endsWithDigits($number, $digits) {
					    $lastDigit = substr($number, -1);
					    return in_array($lastDigit, $digits);
					}

					function findNumbersWithEndingDigits($range, $endingDigits) {
					    $NumberValue = array();
					    foreach ($range as $key => $number) {
					        if (endsWithDigits($number, $endingDigits)) {
					            $NumberValue[] = $number;
					        }
					    }
					    return $NumberValue;
					}

					$couponList = findNumbersWithEndingDigits($range, $search_value);

					foreach($couponList as $SearchValuekey => $singleCouon):
						 if(in_array($singleCouon,$soldoutCoupons)):
						 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
						 	$ticketList[$SearchValuekey]['status']  = 'soldout';
						 elseif(!in_array($singleCouon,$soldoutCoupons)  && !in_array($singleCouon,$CouponList) ):
						 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
						 	$ticketList[$SearchValuekey]['status']  = 'not available';

						 	echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " :- " .$singleCouon ,$result);die();
						 else:
						 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
						 	$ticketList[$SearchValuekey]['status']  = 'available';
						 endif;
					endforeach;

				endif;


				if($search_field == 'start_with'):

					 	$search_value = explode(',', $search_value);
						$ticketList = array();
						if(!empty($search_value)):
						
						foreach ($CouponList as $key => $number):
						    $numberStr = (string)$number;
						    $TicketSequenceKey = $key;

						    $numberStr = (string) $number;

						    foreach ($search_value as $startingDigit):
						        if (strpos($numberStr, $startingDigit) === 0):
						            $couponList[] = $number;
						            break;
						        endif;
						    endforeach;
						endforeach;

						foreach($couponList as $SearchValuekey => $singleCouon):
							 if(in_array($singleCouon,$soldoutCoupons)):
							 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
							 	$ticketList[$SearchValuekey]['status']  = 'soldout';
							 elseif(!in_array($singleCouon,$soldoutCoupons)  && !in_array($singleCouon,$CouponList) ):
							 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
							 	$ticketList[$SearchValuekey]['status']  = 'not available';

						 		echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " :- " .$singleCouon ,$result);die();

							 else:
							 	$ticketList[$SearchValuekey]['ticket']  = (string)$singleCouon;
							 	$ticketList[$SearchValuekey]['status']  = 'available';
							 endif;
						endforeach;
				endif;

			endif;	
					

				// echo "<pre>";print_r($ticketList);die();

				// Custom comparison function for array_unique
				function compareArrays($a, $b) {
				    return $a['ticket'] - $b['ticket'];
				}

				// Sort the array by 'ticket' before removing duplicates
				usort($ticketList, 'compareArrays');

				// Remove duplicate arrays based on 'ticket'
				$uniqueTicketArray = array_unique($ticketList, SORT_REGULAR);

				// Reset array keys if needed
				$ticketList = array_values($uniqueTicketArray);


				// $longArray = range(1, 100); // Replace this with your actual long array
				$longArray = $ticketList;

				// Items per page
				$itemsPerPage = $this->input->post('itemsPerPage');
				$pageno = $this->input->post('page');

				// Current page number (received from URL query parameter, e.g., ?page=2)
				$page = isset($pageno) ? (int)$pageno : 1;

				// Calculate total number of pages
				$totalPages = ceil(count($longArray) / $itemsPerPage);

				// Calculate the starting index of the current page
				$startIndex = ($page - 1) * $itemsPerPage;

				// Extract the data for the current page
				$data = array_slice($longArray, $startIndex, $itemsPerPage);

				$totalpage= array();
				// Pagination links
				for ($i = 1; $i <= $totalPages; $i++) {
				    if ($i == $page) {
				         $current_page = $i;
				         $totalpage[] = $i;
				    } else {
				         $totalpage[] = $i;
				    }
				}

				$totalpage = count($totalpage);

				if(empty($current_page)):
					$current_page = 0;
				endif;


				$results['series'] 				= $series;
				$results['start_range'] 		= $start_Ticket;
				$results['end_range'] 			= $end_Ticket;
				$results['CouponList'] 			= array_values($data);
				$results['current_page'] 		= $current_page;
				$results['total_page'] 			= $totalpage;

				if(!empty($results)):
					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('DATA_NOT_FOUND'),$results);	
				endif;



			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;	
	}

	/* * *********************************************************************
	 * * Function name : resendPOSsms
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to resend sms for a order.
	 * * Date : 3 August 2023
	 * * **********************************************************************/
	public function resendPOSsms()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):

			if($this->input->post('ticket_order_id') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_ID_EMPTY'),$result);
			else:
				$USERID = (int)$this->input->get('users_id');

				 // Fetching users details start
				$where 			=	array('users_id' => (int)$USERID);
				$tblName 		=	'da_users';
				$SellerDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($SellerDetails)):
					
					if($SellerDetails['status'] == 'A'):

						$orderID = $this->input->post('ticket_order_id');
						$this->getorderDetails($orderID);
						$results = array();
						echo outPut(1,lang('SUCCESS_CODE'),lang('SMS_SENT'),$results);

					else:
                        echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
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
	 * * Function name : getorderDetails
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to get and send order to customer.
	 * * Date : 3 August 2023
	 * * **********************************************************************/
	function getorderDetails($OrderID='')
	{	
		$tbaleName 			= "da_ticket_coupons";
		$whereCon['where']  = array('ticket_order_id' => $OrderID );
		$couponData         =  $this->common_model->getData('single',$tbaleName , $whereCon);
		// echo "<pre>";print_r($couponData);die();

		if($couponData['order_users_mobile']):
			$this->sms_model->sendQuickTicketDetails($couponData['ticket_order_id'],$couponData['order_users_mobile'],$couponData['coupon_code'],$couponData['product_id'],$couponData['order_users_country_code'],$couponData['is_donated']);
		endif;

		if($couponData['order_users_email']):
			$this->emailsendgrid_model->sendQuickMailToUser($couponData['ticket_order_id']);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : posQuickBuy
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to get and send order to customer.
	 * * Date : 05 August 2023
	 * * **********************************************************************/
	public function posQuickBuy()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 			= 	array();

		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->post('first_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('NAME_EMPTY'),$result);
			elseif($this->input->post('last_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('LASTNAME_EMPTY'),$result);
			elseif($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PHONE_EMPTY'),$result);
			elseif($this->input->post('product_id') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			else:
				// Validate Product
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
				
				if($validUpto < $currDate):
					echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_EXPIRE'),$result);die();
				endif;
				//END

				$this->QuickTicket();
				die();

				$where 			=	['users_id' => (int)$this->input->get('users_id')];
				$tblName 		=	'da_users';
				$sellerDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
				
				//Check user exits or not.
				$wcon['where']	=	[ 'users_mobile' => (int)$this->input->post('users_mobile') ,'status' => "A" ];
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
	    			
	    			$orderInsertID 			= $this->geneal_model->addData('da_quick_users', $user);
    			endif;
    			
				if(!empty($sellerDetails)):

					if($sellerDetails['availableArabianPoints'] >= (float)$this->input->post('total_price')):

						if(!empty($sellerDetails)):
								if($sellerDetails['status'] == 'A'):


									//Checking final coupon availability.
								    $CouponList  = $this->couponCheckList($this->input->post());

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

								   if($this->input->post('isVoucher') == 'Y'):
								    	$couponData['isVoucher']			= 	'Y';
								    	$couponData['voucher_code']			= 	generateRandomString(10,"n");
									else:
								    	$couponData['isVoucher']			= 	'N';
									endif;
								    
								    $couponData['coupon_id']			= 	(int)$this->geneal_model->getNextSequence('da_coupons');
									$couponData['users_id']				= 	(int)$ORparam["user_id"];
									$couponData['users_email']			= 	$ORparam["user_email"];

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
									$couponData["product_color"] 		=	$ORparam['product_color'];
									$couponData["prize_title"] 			=	$ORparam['prize_title'];
									$couponData["device_type"] 			=	$ORparam['device_type'];
								    $couponData["app_version"] 			=	$ORparam['app_version'];

								    if($this->input->post('product_is_donate') == "Y" ):
										$couponData['is_donated'] 		=	'Y';
								    else:
										$couponData['is_donated'] 		=	'N';
								    endif;
									$couponData['coupon_status'] 		=	'Live';
									$couponData['coupon_code'] 			= 	$CouponList;

									$couponData['draw_date'] 			= 	array($productData['draw_date']);
									$couponData['draw_time'] 			= 	array($productData['draw_time']);
									$couponData['created_at']			=	date('Y-m-d H:i');

									$this->geneal_model->addData('da_ticket_coupons',$couponData);
									$result = $couponData;


									// Stock update section..
									$stock 			= $productData['stock'] - $ORparam['product_qty']  ; // updated stock.
									$updateParams 	= array( 'stock' => (int)$stock );	
									$updatedstatus 	= $this->geneal_model->editData('da_products',$updateParams,'products_id',(int)$ORparam['product_id']);

									// Deduct the purchesed points and get available arabian points of user.
									$currentBal 	= 	$this->geneal_model->debitPointsByAPI($ORparam['total_price'],$ORparam["user_id"]); 


									if($couponData['order_users_email']):
										$this->emailsendgrid_model->sendQuickMailToUser($couponData['ticket_order_id']);
									endif;


									if($ORparam['SMS'] == "Y"):
										$this->sms_model->sendQuickTicketDetails($couponData['ticket_order_id'],$couponData['order_users_mobile'],$CouponList,$couponData['product_id'],$couponData['order_users_country_code'],$couponData['is_donated']);
									endif;

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
	 * * Function name : couponCheckList
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to get and send order to customer.
	 * * Date : 05 August 2023
	 * * **********************************************************************/
	public function couponCheckList($POSTFIELDS='')
	{

		$productID 			= $POSTFIELDS['product_id'];
		$productTitle 		= $POSTFIELDS['product_title'];
		$productQuantity 	= $POSTFIELDS['product_qty'];
		$productIsDonated 	= $POSTFIELDS['product_is_donate'];
		$productIsVoucher	= $POSTFIELDS['isVoucher'];

		//Get current Ticket order sequence from admin panel.
		$tblName = 'da_tickets_sequence';
		$whereCon2['where']		 			= 	array('product_id' => (int)$productID , 'status' => 'A');	
		$shortField 						= 	array('tickets_seq_id'=>'ASC');
		$TicketSequence 				= 	$this->common_model->getData('multiple',$tblName,$whereCon2,$shortField,'0','0');

		$tblName = 'da_products';
		$whereCon2['where']		 			= 	array('products_id' => (int)$productID , 'status' => 'A');	
		$productDetails 					= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');	
			

		if($productDetails):
			// getting number of tickets genereating wirth this order.
			if($productIsDonated == 'N'):
				$tickets = $productQuantity*$productDetails['sponsored_coupon'];
			else:
				$tickets = 2*$productQuantity*$productDetails['sponsored_coupon'];
			endif;

			if($productIsVoucher == 'Y'):
				$tickets = 2*$productQuantity*$productDetails['sponsored_coupon'];
				// $tickets = 2*$productQuantity;
			endif;
		else:
		 	echo outPut(0,lang('SUCCESS_CODE'),lang('CAMPAIGN_CLOSED'). " for " .$productTitle,$result);die();
		endif;
		
		$coupons 		= array();
		$CouponList 	= array();
		$soldoutCoupons = array();

		// Online coupon checking...
		$whereCondition['where'] = array( 'product_id' => (int)$productID);
		$CouponCount			 = $this->geneal_model->getData2('multiple','da_coupons',$whereCondition);

		// Quick coupon checking...
		$whereCondition1['where']        = array('product_id'=> (int)$productID   );
		$QuickSoldoutTickets			 = $this->geneal_model->getData2('multiple','da_ticket_coupons',$whereCondition1);

		if($CouponCount):
			foreach ($CouponCount as $key => $item):
				$soldoutCoupons[] =  $item['coupon_code'];
			endforeach;
		endif;

		if($CouponCount):
			foreach($QuickSoldoutTickets as $key => $item):
				foreach($item['coupon_code'] as $coupons):
					$soldoutCoupons[] =  $coupons;
				endforeach;
			endforeach;
		endif;


		// Checking coupons codes and generating tickets count if required..
		if($POSTFIELDS['coupon_code']):
			$selectedCoupons  =  explode(',', $POSTFIELDS['coupon_code']);
			$CouponList1 = array_diff($selectedCoupons, $soldoutCoupons);

			if($tickets > count($CouponList1)):
				$disabledTicketGenerate = "N";
			elseif($tickets <= count($CouponList1)):
				$disabledTicketGenerate = "Y";
			endif;
		else:
				$disabledTicketGenerate = "N";
		endif;


		//if user defined coupon is not enough to complete this order than go to checking ticktes in this condition.
		if($disabledTicketGenerate == "N" ):

			//Getting Tickets generating sequence from each available slots ..
			foreach($TicketSequence as $key => $CurrentTicketSequence):
				$ticketID_Loop[] 				= $CurrentTicketSequence['tickets_seq_id'];
				$series_Loop[] 					= $CurrentTicketSequence['tickets_prefix'];
				$tickets_sequence_start_Loop[] 	= $CurrentTicketSequence['tickets_sequence_start'];
				$tickets_sequence_end_Loop[] 	= $CurrentTicketSequence['tickets_sequence_end'];
				$tickets_sold_count_Loop[] 		= $CurrentTicketSequence['tickets_sold_count'];
				$total_tickets_Loop[] 			= $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'] ;
			endforeach;

			foreach($series_Loop as $loopcount => $series):
			    $start_Ticket = $tickets_sequence_start_Loop[$loopcount];
			    $end_Ticket = $tickets_sequence_end_Loop[$loopcount];

			    $sequence = range($start_Ticket, $end_Ticket);

			    $sequence = array_map(function ($number) use ($series) {
			        return $series . $number;
			    }, $sequence);

			    // Use array_filter to remove the specified coupon codes
			    $CouponArray = array_filter($sequence, function($coupon) use ($soldoutCoupons) {
			        return !in_array($coupon, $soldoutCoupons);
			    });

			    // Append the generated coupons to the list
			    $CouponList_CheckList = $CouponList = array_merge($CouponList, $CouponArray);


			    if($CouponList1):
			    	$CouponList = array_merge($CouponList1,$CouponList);
			    	$CouponList_CheckList = $CouponList = array_unique($CouponList);
			    endif;
			     
			    $CouponList = array_slice($CouponList, 0, $tickets);

			    //generated coupon check in da_quick_check_coupon.
		    	$table 				= 'da_quick_check_coupon';
		    	$whereCon2['where'] = array('coupon_code' => $CouponList );
		    	$Checkcoupon 		=  $this->common_model->getData('single',$table,$where);
		    	
		    	if(empty($Checkcoupon)):
				    $CouponCode["coupon_code"] 	=	$CouponList;
				    $orderInsertID 				=   $this->common_model->addData($table,$CouponCode);
		    	else:

		    		// Multiple users assing coupon removing at a same time .
		    		$coupon_code = array_diff($CouponList_CheckList, $Checkcoupon['coupon_code']);
			    	$CouponList = array_slice($coupon_code, 0, $tickets);

		    		$coupon_code = $Checkcoupon['coupon_code'];
		    		$where['coupon_code'] = $coupon_code;
		    		$this->common_model->deleteByMultipleCondition($table,$where);
					// echo outPut(0,lang('SUCCESS_CODE'),lang('TRY_AGAIN'),$result);die();
		    	endif;

			    // Updating coupon count for this ticket sequence.
				$Soldoutcouponcount =  $couponArray;
				$couponTicketSequence["tickets_sold_count"] 	 =	(int)$tickets_sold_count_Loop[$loopcount]+ count($CouponList);

				$this->geneal_model->editData('da_tickets_sequence',$couponTicketSequence,'tickets_seq_id',(int)$ticketID_Loop[$loopcount]);

			    // If we have at least 10 coupons, break the loop
			    if (count($CouponList) >= $tickets):
			        break;
			    endif;

			endforeach;

		else:
			$CouponList = array_slice($CouponList1, 0, $tickets);
		endif;

		if(count($CouponList) < $tickets):
 			echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " for " .$productTitle,$result);die();
		endif;

		return $CouponList;
	}

	/* * *********************************************************************
	 * * Function name : posCancelledOrders
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to get cancelled order list.
	 * * Date : 06 August 2023
	 * * **********************************************************************/
	public function posCancelledOrders()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else: 

				 // Getting all cancelled order details
				$where['where'] 				=	array( 'user_id' => (int)$this->input->get('users_id') , "status" => "CL" );
				$tblName 			=	'da_ticket_orders';
				$CancelledOrders 	=	$this->common_model->getData('multiple',$tblName, $where);
				// echo "<pre>";print_r($CancelledOrders);die();

				// $longArray = range(1, 100); // Replace this with your actual long array
				$longArray = $CancelledOrders;

				// Items per page
				$itemsPerPage = $this->input->get('itemsPerPage');
				$pageno = $this->input->get('page');

				// Current page number (received from URL query parameter, e.g., ?page=2)
				$page = isset($pageno) ? (int)$pageno : 1;

				// Calculate total number of pages
				$totalPages = ceil(count($longArray) / $itemsPerPage);

				// Calculate the starting index of the current page
				$startIndex = ($page - 1) * $itemsPerPage;

				// Extract the data for the current page
				$data = array_slice($longArray, $startIndex, $itemsPerPage);

				$totalpage= array();
				// Pagination links
				for ($i = 1; $i <= $totalPages; $i++) {
				    if ($i == $page) {
				         $current_page = $i;
				         $totalpage[] = $i;
				    } else {
				         $totalpage[] = $i;
				    }
				}

				if(empty($CancelledOrders)):
					$CancelledOrders = array();
				endif;

				$totalpage = count($totalpage);
				$results['CancelledOrders'] 	= $CancelledOrders;
				$results['current_page'] 		= $current_page;
				$results['total_page'] 			= $totalpage;

				echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$results);
		endif;
	}


	/* * *********************************************************************
	 * * Function name : verifyUser
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to verify user mobile number.
	 * * Date : 05 October 2023
	 * * **********************************************************************/
	public function verifyUser()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):

			
			if($this->input->post('country_code') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COUNTRY_CODE_EMPTY'),$result);
			elseif($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('MOBILE_EMPTY'),$result);
			else:
				
				$country_code   = $this->input->post('country_code');
				$mobile_no 		= $this->input->post('users_mobile');
				$otp            = (int)rand(1000,9999);

				 // Getting all cancelled order details
				$where['where'] 	=	array( 'country_code' => $country_code ,'users_mobile' => (int)$mobile_no);
				$tblName 			=	'da_quick_users';
				$quickUser 			=	$this->common_model->getData('single',$tblName, $where);

				if($quickUser):
					$whereCon 		= array('sequence_id' => (int)$quickUser['sequence_id'] , 'country_code' => $this->input->post('country_code') ,'users_mobile' => (int)$this->input->post('users_mobile'));
					$updateParams 	= array( 'otp' => $otp ,'is_verify' => 'N');	
					$updatedstatus 	= $this->geneal_model->editSingleData('da_quick_users',$updateParams,$whereCon);
				else:

					$user["sequence_id"]	= (int)$this->geneal_model->getNextSequence('da_quick_users');
					$user['first_name'] 	= $this->input->post('first_name');
					$user['last_name'] 		= $this->input->post('last_name');
					$user['country_code'] 	= $this->input->post('country_code');
					$user['users_mobile'] 	= (int)$this->input->post('users_mobile');
					$user['users_email'] 	= $this->input->post('users_email');
					$user['otp'] 			= $otp;
					$user["status"] 		= "A";
				    $user["is_verify"] 		= 	'N';
				    $user["creation_ip"] 	= $this->input->ip_address();
				    $user["created_at"] 	= date('Y-m-d H:i');
					//create new user.
	    			$orderInsertID 			= $this->geneal_model->addData('da_quick_users', $user);
				endif;

				$MOBILENUMBER = $country_code.$mobile_no;

				$this->sms_model->sendForgotPasswordOtpSmsToUser($MOBILENUMBER,$otp,$country_code);
				$results = array();
				echo outPut(1,lang('SUCCESS_CODE'),lang('OTP_SENT') .$MOBILENUMBER,$results);

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$results);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : verifyUserOTP
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to verify user OTP.
	 * * Date : 05 October 2023
	 * * **********************************************************************/
	public function verifyUserOTP()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):

			
			if($this->input->post('country_code') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COUNTRY_CODE_EMPTY'),$result);
			elseif($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('MOBILE_EMPTY'),$result);
			elseif($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('otp'),$result);
			else:
				
				$country_code   = $this->input->post('country_code');
				$mobile_no 		= $this->input->post('users_mobile');
				$otp            = $this->input->post('otp');

				 // Getting all cancelled order details
				$where['where'] 	=	array('country_code' => $country_code ,'users_mobile' => (int)$mobile_no ,'otp' => (int)$otp );
				$tblName 			=	'da_quick_users';
				$quickUser 			=	$this->common_model->getData('single',$tblName, $where);

				 // Checking existing userif exist send this in Result array..
				$userwhere['where'] 	=	array( 'country_code' => $country_code ,'users_mobile' => (int)$mobile_no );
				$tblName 			=	'da_users';
				$UserData 			=	$this->common_model->getData('single',$tblName, $userwhere);

				// echo "<pre>";print_r($quickUser); die();

				if($quickUser):
					$whereCon 		= array('sequence_id' => (int)$quickUser['sequence_id'] , 'country_code' => $this->input->post('country_code') ,'users_mobile' => (int)$this->input->post('users_mobile'));
					$updateParams 	= array( 'otp' => '','is_verify' => 'Y');	
					$updatedstatus 	= $this->geneal_model->editSingleData('da_quick_users',$updateParams,$whereCon);
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('WRONG_OTP'),$results);die();
				endif;

				$MOBILENUMBER = $country_code.$mobile_no;
				// $this->sms_model->sendForgotPasswordOtpSmsToUser($MOBILENUMBER,$otp,$country_code);
				if($UserData):
					$results['UserData'] = $UserData;
				else:
					$results['UserData'] = json_encode();
				endif;
				echo outPut(1,lang('SUCCESS_CODE'),lang('OTP_VERIFIED'),$results);

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$results);
		endif;
	}

	
	/* * *********************************************************************
	 * * Function name : resendUserOTP
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to resend user OTP.
	 * * Date : 05 October 2023
	 * * **********************************************************************/
	public function resendUserOTP()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):

			
			if($this->input->post('country_code') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COUNTRY_CODE_EMPTY'),$result);
			elseif($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('MOBILE_EMPTY'),$result);
			else:
				
				$country_code   = $this->input->post('country_code');
				$mobile_no 		= $this->input->post('users_mobile');
				$otp            = (int)rand(1000,9999);

				 // Getting all cancelled order details
				$where['where'] 	=	array( 'country_code' => $country_code ,'users_mobile' => (int)$mobile_no);
				$tblName 			=	'da_quick_users';
				$quickUser 			=	$this->common_model->getData('single',$tblName, $where);

				if($quickUser):
					$whereCon 		= array('sequence_id' => (int)$quickUser['sequence_id'] , 'country_code' => $this->input->post('country_code') ,'users_mobile' => (int)$this->input->post('users_mobile'));
					$updateParams 	= array( 'otp' => $otp);	
					$updatedstatus 	= $this->geneal_model->editSingleData('da_quick_users',$updateParams,$whereCon);
				else:

					$user["sequence_id"]	= (int)$this->geneal_model->getNextSequence('da_quick_users');
					$user['first_name'] 	= $this->input->post('first_name');
					$user['last_name'] 		= $this->input->post('last_name');
					$user['country_code'] 	= $this->input->post('country_code');
					$user['users_mobile'] 	= (int)$this->input->post('users_mobile');
					$user['users_email'] 	= $this->input->post('users_email');
					$user['otp'] 			= $otp;
					$user["status"] 		= "A";
				    $user["creation_ip"] 	= $this->input->ip_address();
				    $user["created_at"] 	= date('Y-m-d H:i');
	    			
					//create new user.
	    			$orderInsertID 			= $this->geneal_model->addData('da_quick_users', $user);

				endif;

				$MOBILENUMBER = $country_code.$mobile_no;

				$this->sms_model->sendForgotPasswordOtpSmsToUser($MOBILENUMBER,$otp,$country_code);
				$results = array();
				echo outPut(1,lang('SUCCESS_CODE'),lang('OTP_SENT') .$MOBILENUMBER,$results);

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$results);
		endif;
	}


	/* * *********************************************************************
	//  * * Function name : generateOrderId
	//  * * Developed By  : Dilip Halder
	//  * * Purpose  	   : This function used to Capture payment.
	//  * * Date 	       : 10 October 2023
	//  * * **********************************************************************/
	// public function generateOrderId()
	// {
	// 	$apiHeaderData 		=	getApiHeaderData();
	// 	$this->generatelogs->putLog('APP',logOutPut($_POST));
	// 	$result 							= 	array();	
	// 	if(requestAuthenticate(APIKEY,'POST')):
			
	// 		if($this->input->post('first_name') == ''): 
	// 			echo outPut(0,lang('SUCCESS_CODE'),lang('NAME_EMPTY'),$result);
	// 		elseif($this->input->post('last_name') == ''): 
	// 			echo outPut(0,lang('SUCCESS_CODE'),lang('LASTNAME_EMPTY'),$result);
	// 		// elseif($this->input->post('users_email') == ''): 
	// 		// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMAIL_EMPTY'),$result);
	// 		elseif($this->input->post('users_mobile') == ''): 
	// 			echo outPut(0,lang('SUCCESS_CODE'),lang('PHONE_EMPTY'),$result);
	// 		elseif($this->input->post('product_id') == ''):
	// 			echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
	// 		else:
	// 			// Validate Produc
	// 			$where 			=	['products_id' => (int)$this->input->post('product_id')];
	// 			$tblName 		=	'da_products';
	// 			$productData 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

	// 			if(empty($productData)):
	// 				echo outPut(0,lang('SUCCESS_CODE'),lang('Product_not_found'),$result);die();
	// 			elseif($productData['stock'] < $this->input->post('product_qty') ):
	// 				echo outPut(0,lang('SUCCESS_CODE'),lang('PRO_QTY'),$result);die();
	// 			endif;

	// 			// Validate product availablity
	// 			$validUpto 		=	strtotime(date('Y-m-d H:i:s',strtotime($productData['validuptodate'].' '.$productData['validuptotime'])));
	// 			$currDate 		=	strtotime(date('Y-m-d H:i:s'));
				

	// 			if($validUpto < $currDate){
	// 				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_EXPIRE'),$result);die();
	// 			}

	// 			$country_code = $this->input->post('country_code');
	// 			$users_mobile = $this->input->post('users_mobile');

	// 			//END
	// 			$where 			=	array( 'country_code' => $country_code, 'users_mobile' => (int)$this->input->post('users_mobile'));
	// 			$tblName 		=	'da_users';
	// 			$UserDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

    // 			//Get current Ticket order sequence from admin panel.
	// 			$tblName = 'da_tickets_sequence';
	// 			$whereCon2['where']		 			= 	array('product_id' => (int)$_POST['product_id'] , 'status' => 'A');	
	// 			$shortField 						= 	array('tickets_seq_id'=>'DESC');
	// 			$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

	// 			$tblName = 'da_quickcoupons_totallist';
	// 			$whereCon3['where']		 			= 	array('product_id' => (int)$_POST['product_id'] ,'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] );	
	// 			$shortField3 						= 	array('coupon_id'=>'DESC');
	// 			$SoldoutTicketList					= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

	// 			$available_ticket =	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
				
	// 			if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
	// 				$coupon_sold_number =	$SoldoutTicketList['coupon_sold_number'];
	// 			endif;

	// 			if($this->input->post('product_is_donate') == 'Y'):
	// 				$check_availblity = $this->input->post('product_qty') * 2;
	// 			else:
	// 				$check_availblity = $this->input->post('product_qty');
	// 			endif;
				
	// 			$left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  



	// 			if($left_ticket < 0):
	// 				// echo outPut(0,lang('SUCCESS_CODE'),"Exceeding the ticket limit",$result);
	// 				echo outPut(0,lang('SUCCESS_CODE'),"TICKET_FULL",$result);
	// 				die();
	// 			endif;

	// 			if((int)$this->input->post('product_qty') >1):
	// 				if((float)$this->input->post('vat_amount') + (float)$this->input->post('subtotal') != (float)$this->input->post('total_price')):
	// 					// echo outPut(0,lang('SUCCESS_CODE'),"Exceeding the ticket limit",$result);
	// 					echo outPut(0,lang('SUCCESS_CODE'),"PRICE_ERROR",$result);
	// 					die();
	// 				endif;
	// 			endif;

	// 					$country_code =  $this->input->post('country_code');
	// 					$users_mobile =  $this->input->post('users_mobile');

	// 					$where 			=	['country_code' => $this->input->post('country_code') ,  'users_mobile' => (int)$this->input->post('users_mobile') ];
	// 					$tblName 		=	'da_users';
	// 					$sellerDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

	// 					// Storing data
	// 					$ORparam["sequence_id"]		    		=	(int)$this->geneal_model->getNextSequence('da_ticket_orders');
	// 			        if($this->input->post('payment_mode') == "Stripe"):
	// 						$total_price  		=	(int)(trim($this->input->post('total_price'))*100);
	// 						$paymentIntent 	 	= 	createStripePaymentIntent($total_price,$this->input->post('user_email'),$ORparam["sequence_id"]);
	// 						$ORparam["client_secret"] 			=	$paymentIntent['client_secret'];
	// 					endif;
	// 			        $ORparam["ticket_order_id"]		        =	$this->geneal_model->getNextQuickBuyOrderId();
	// 			        $ORparam["user_id"] 					=	(int)$sellerDetails['users_id'];
	// 			        $ORparam["user_type"] 					=	$sellerDetails['users_type']; //$this->input->post('user_type');
	// 		        	$ORparam["user_email"] 					=   $sellerDetails['users_email'];	//$this->input->post('user_email');
	// 				 	$ORparam["user_phone"] 					=	$sellerDetails['users_mobile'];	//$this->input->post('user_phone');
	// 			     	$ORparam["product_id"] 					=	(int)$this->input->post('product_id');
	// 			     	$ORparam["product_title"] 				=	$this->input->post('product_title');
	// 			     	$ORparam["product_qty"] 				=	$this->input->post('product_qty');
	// 			     	$ORparam["product_color"] 				=	$this->input->post('product_color');
	// 			     	$ORparam["product_size"] 				=	$this->input->post('product_size');
	// 			     	$ORparam["prize_title"] 				=	$this->input->post('prize_title');
	// 			        $ORparam["vat_amount"] 					=	(float)$this->input->post('vat_amount');
	// 			        $ORparam["subtotal"] 					=	(float)$this->input->post('subtotal');
	// 			        $ORparam["total_price"] 				=	(float)$this->input->post('total_price');
	// 			        $ORparam["availableArabianPoints"] 		=	'';
	// 					$ORparam["end_balance"] 				=	'';
	// 				    $ORparam["payment_mode"] 				=	 $this->input->post('payment_mode');
	// 				    $ORparam["payment_from"] 				=	'Quick';
	// 			        $ORparam["product_is_donate"] 			=	$this->input->post('product_is_donate'); //$this->input->post('product_is_donate');
	// 				    $ORparam["order_status"] 				=	"Initialize";
	// 				    $ORparam["device_type"] 				=	$this->input->post('device_type');
	// 	   				$ORparam["app_version"] 				=	(float)$this->input->post('app_version');
	// 			     	$ORparam["order_first_name"] 			=	$this->input->post('first_name');
	// 			     	$ORparam["order_last_name"] 			=	$this->input->post('last_name');
	// 			     	$ORparam["order_users_country_code"] 	=	$this->input->post('country_code')?$this->input->post('country_code'):"+971";
	// 			     	$ORparam["order_users_mobile"] 			=	$this->input->post('users_mobile');
	// 			     	$ORparam["order_users_email"] 			=	$this->input->post('users_email');
	// 			     	$ORparam["SMS"] 						=	$this->input->post('SMS');
	// 				    $ORparam["creation_ip"] 				=	$this->input->ip_address();
	// 				    $ORparam["created_at"] 					=	date('Y-m-d H:i');

	// 				    //Saving order details for Ticket
	// 				    $orderInsertID 							=	$this->geneal_model->addData('da_ticket_orders', $ORparam);

	// 					$result = $ORparam;
	// 					echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_ID_GENERATED'),$result);

	// 		endif;
	// 	else:
	// 		echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
	// 	endif;
	// }


	/* * *********************************************************************
	 * * Function name : generateOrderId
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to Capture payment.
	 * * Date 	       : 10 October 2023
	 * * **********************************************************************/
	public function generateOrderId()
    {   
        $apiHeaderData      =   getApiHeaderData();
        $this->generatelogs->putLog('APP',logOutPut($_POST));
        $result                             =   array();    
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


				$product_id  		= (int)$this->input->post('product_id');
				$product_title 		=	$this->input->post('product_title');
		     	$product_qty 		=	$this->input->post('product_qty');
		     	$product_color		=	$this->input->post('product_color');
		     	$product_size 		=	$this->input->post('product_size');
		     	$prize_title 		=	$this->input->post('prize_title');
		     	$vat_amount 		=	(float)$this->input->post('vat_amount');
		        $subtotal 			=	(float)$this->input->post('subtotal');
		        $total_price 		=	(float)$this->input->post('total_price');
		        $country_code 		=  $this->input->post('country_code');
				$users_mobile 		=  $this->input->post('users_mobile');

				$where 			=	array( 'country_code' => $country_code, 'users_mobile' => (int)$users_mobile);
				$tblName 		=	'da_users';
				$UserDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);


				//Otp verified checked
				// $whereCon['where'] 	 =  array('country_code' => $country_code ,'users_mobile' => (int)$users_mobile, "is_verify" => "Y");
				$whereCon['where'] 	 =  array('users_mobile' => (int)$users_mobile, "is_verify" => "Y");
				$ISvarified          =  $this->geneal_model->getData2('count','da_quick_users', $whereCon);

				if($ISvarified  === 1 ):
					$USRUpdate['first_name']	= $this->input->post('first_name');
					$USRUpdate['last_name']		= $this->input->post('last_name');
					
					// $UpdatewhereCon['where'] 	=  array('country_code' => $country_code ,'users_mobile' => (int)$users_mobile);
					$UpdatewhereCon['where'] 	=  array('users_mobile' => (int)$users_mobile);
					$this->geneal_model->editDataByMultipleCondition('da_quick_users',$USRUpdate,$UpdatewhereCon['where']);
				endif;
 
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


                // Available prodcut and soldout product details..
                $tblName = 'da_tickets_sequence';
                $whereCon2['where']                 =   array('product_id' => (int)$product_id , 'status' => 'A');    
                $shortField                         =   array('tickets_seq_id'=>'DESC');
                $CurrentTicketSequence              =   $this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

                $tblName = 'da_quickcoupons_totallist';
                $whereCon3['where']                 =   array('product_id' => (int)$product_id , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] ); 
                $shortField3                        =   array('tickets_seq_id'=>'DESC');
                $SoldoutTicketList                  =   $this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

                $available_ticket = $CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
                
                if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
                    $coupon_sold_number =   $SoldoutTicketList['coupon_sold_number'];
                endif;

                if( $this->input->post('product_is_donate') == "Y"):
                    $check_availblity = $product_qty * 2;
                elseif( $this->input->post('product_is_donate') == "N"):
                    $check_availblity = $product_qty;
                endif;
                
                $left_ticket = $available_ticket - ($coupon_sold_number + $check_availblity);  

                if($left_ticket < 0):
                    echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " for " .$product_title,$result);die();
                    die();
                endif;
                
	                $inclusice_of_vat                   =   (int)(trim($this->input->post('inclusice_of_vat'))*100);
	                $ORparam["order_id"]                =   $this->geneal_model->getNextOrderId();
	                if($ORparam["order_id"]):  
	                /* Order Place Table */
	                $ORparam["sequence_id"]             =   (int)$this->geneal_model->getNextSequence('da_orders');
	                //$ORparam["order_id"]              =   $this->geneal_model->getNextOrderId();
	                $ORparam["user_id"]                 =   (int)$UserDetails['users_id'];
	                $ORparam["user_type"]               =   $UserDetails['user_type']?$UserDetails['user_type']:'Users';
	                
	                if($this->input->post('users_email')):
	                    $ORparam["user_email"]          =    $this->input->post('users_email');
	                elseif($UserDetails['users_email']):
	                	$ORparam["user_email"]          =    $UserDetails['users_email'];
	                else:
	                    $ORparam["user_email"]          =   "dealzarabiasales1@gmail.com";
	                endif;

	                $ORparam["country_code"]              	=   $this->input->post('country_code');
	                $ORparam["user_phone"]              	=   (int)$this->input->post('users_mobile');

	                $ORparam["product_is_donate"]       =   $this->input->post('product_is_donate');
	                if($ORparam["product_is_donate"] == 'N'):
	                    $ORparam["shipping_method"]     =   $this->input->post('shipping_method');
	                    $ORparam["emirate_id"]              =   $this->input->post('emirate_id');
	                    $ORparam["emirate_name"]            =   $this->input->post('emirate_name');
	                    $ORparam["area_id"]                 =   $this->input->post('area_id');
	                    $ORparam["area_name"]               =   $this->input->post('area_name');
	                    $ORparam["collection_point_id"]     =   $this->input->post('collection_point_id');
	                    $ORparam["collection_point_name"]   =   $this->input->post('collection_point_name');
	                    $ORparam["shipping_address"]    =   '';
	                else:
	                    $ORparam["shipping_method"]     =   '';
	                    $ORparam["emirate_id"]              =   '';
	                    $ORparam["emirate_name"]            =   '';
	                    $ORparam["area_id"]                 =   '';
	                    $ORparam["area_name"]               =   '';
	                    $ORparam["collection_point_id"]     =   '';
	                    $ORparam["collection_point_name"]   =   '';
	                    $ORparam["shipping_address"]    =   '';
	                endif;
	                $ORparam["product_count"]           =   (int)$this->input->post('product_qty');
	                $ORparam["shipping_charge"]         =   (float)$this->input->post('shipping_charge');
	                $ORparam["inclusice_of_vat"]        =   (float)$this->input->post('total_price');
	                $ORparam["subtotal"]                =   (float)$this->input->post('subtotal');
	                $ORparam["vat_amount"]              =   (float)$this->input->post('vat_amount');
	                $ORparam["total_price"]             =   (float)$ORparam["inclusice_of_vat"];
	                $ORparam["payment_mode"]            =   $this->input->post('payment_mode');

	                if($this->input->post('payment_mode') === "Stripe" ):

	                	$inclusice_of_vat 				=	(int)(trim($this->input->post('total_price'))*100);
						$ORparam["order_id"]		    =	$this->geneal_model->getNextOrderId();
						$paymentIntent 					= 	createStripePaymentIntent($inclusice_of_vat,$ORparam["user_email"],$ORparam["order_id"]);
                 		$ORparam["client_secret"] 		=	$paymentIntent['client_secret'];
                 	endif;

	                $ORparam["availableArabianPoints"] 	=	(float)$UserDetails["availableArabianPoints"];
	        		$ORparam["end_balance"] 			=	(float)$UserDetails["availableArabianPoints"];
	                $ORparam["payment_from"]            =   'App';
	                $ORparam["device_type"]             =   $this->input->post('device_type');
	                $ORparam["app_version"]             =   $this->input->post('app_version');
	                $ORparam["remark"]            		=   "Quick mobile Web";
	                $ORparam["order_status"]            =   "Initialize";
	                $ORparam["creation_ip"]             =   $this->input->ip_address();
	                $ORparam["created_at"]              =   date('Y-m-d H:i');

	                $orderInsertID                      =   $this->geneal_model->addData('da_orders', $ORparam);

                    if($orderInsertID):

							//Manage Inventory
							if($this->input->post('product_is_donate') == 'N'):
								$whereinv['where']		=	array(
															'products_id'			=>	(int)$product_id,
															'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
														);
								$INVcheck	=	$this->geneal_model->getData2('single','da_inventory',$whereinv);

								if($INVcheck <> ''):
									$orqty = $INVcheck['order_request_qty'] + (int)$product_qty;
									$INVUpdate['order_request_qty']		=	$orqty;
									$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$whereinv['where']);
								else:
									$INVparam['products_id']				= 	(int)$product_id;
									$INVparam['qty']						=	(int)0;
									$INVparam['available_qty']				=	(int)0;
									$INVparam['order_request_qty']			=	(int)$product_qty;
									$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

									$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
									
									$INVparam['creation_ip']				=	currentIp();
									$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
									$INVparam['status']						=	'A';
									$this->geneal_model->addData('da_inventory', $INVparam);
								endif;
							endif;


							$prodDet['where']		=	array('products_id'			=>	(int)$product_id);
							$productDetails			=	$this->geneal_model->getData2('single','da_products',$prodDet);


							//END
							$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
							$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
							$ORDparam["order_id"]			=	$ORparam["order_id"];
							$ORDparam["user_id"]			=	(int)$UserDetails["users_id"];;
							$ORDparam["product_id"] 		=	(int)$product_id;
							$ORDparam["product_name"] 		=	$productDetails['title'];
							$ORDparam["quantity"] 		    =	(int)$product_qty;
							if($productDetails['color']):
								$ORDparam["color"] 		    =	$productDetails['color'];
							endif;
							if($productDetails['size']):
								$ORDparam["size"] 		    =	$productDetails['size'];
							endif;
							$ORDparam["price"] 		        =	(float)$productDetails['adepoints'];
							$ORDparam["tax"] 		        =	(float)0;
							$ORDparam["subtotal"] 		    =	(float)$product_qty*$productDetails['adepoints'];
							$ORDparam["is_donated"] 		=	$ORparam["product_is_donate"];
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


	                //Get current order of user.
	                $wcon1['where']                 =   [ 'order_id' => $ORparam["order_id"] ];
	                $result['orderData']            =   $this->geneal_model->getData2('single', 'da_orders', $wcon1);
	                
	                //Get current order details of user.
	                $wcon2['where']                 =   [ 'order_id' => $ORparam["order_id"] ];
	                $result['orderDetails']         =   $this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);
	                


	                echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_ID_GENERATED'),$result);
	            else:
	                echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_ID_GENERATION_FAILED'),$result);
	            endif;
               
            endif;
        else:
            echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
        endif;
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
			if($this->input->post('order_id') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_ID_EMPTY'),$result);
			elseif($this->input->post('payment_mode') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('PAYMENTMODE'),$result);
			elseif($this->input->post('order_status') == ''):
				echo outPut(0,lang('SUCCESS_CODE'),lang('STATUS_EMPTY'),$result);
			else:


			$modeOFPaymnet = $this->input->post('payment_mode');

			if($modeOFPaymnet == 'Arabian Points'):
				$tblName   						=   "da_orders";
			    $whereMOdeCheck['where']        =   array('order_id' => $this->input->post('order_id'));
			    $CheckOrder              		=   $this->geneal_model->getData2('single',$tblName, $whereMOdeCheck);

			    if(!empty($CheckOrder)  && $CheckOrder['user_id'] == 0):
			    	echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);die();
			    elseif($CheckOrder['user_id'] != 0):


			    		$USERID			 =  $CheckOrder['user_id'];
			    		$total_price 	 = $CheckOrder['total_price'];
					    
					    $tblName   						=   "da_users";
					    $whereMOdeCheck['where']        =   array('users_id' => (int)$USERID);
					    $USerCHEKDATA              		=   $this->geneal_model->getData2('single',$tblName, $whereMOdeCheck); 

					   if($USerCHEKDATA['availableArabianPoints']<$total_price):
			    				echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);die();
					   endif;
			    endif;
			endif;





				if($this->input->post('order_status') == 'Failed'):
				    $tblName   						= "da_orders";
				    $updateParams                   =   array('order_status' => $this->input->post('order_status'),'transaction_id' => $this->input->post('transaction_id'));
				    $updateorderstatus              =   $this->geneal_model->editData($tblName, $updateParams, 'order_id', $this->input->post('order_id'));
				    echo outPut(0,lang('SUCCESS_CODE'),lang('ORDER_FAILED'),$result); die();
				endif;

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

    			//Get current Ticket order sequence from admin panel.
				$tblName = 'da_tickets_sequence';
				$whereCon2['where']		= 	array('product_id' => (int)$_POST['product_id'] , 'status' => 'A');	
				$shortField 			= 	array('tickets_seq_id'=>'DESC');
				$CurrentTicketSequence 	= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

				$tblName = 'da_quickcoupons_totallist';
				$whereCon3['where']		= 	array('product_id' => (int)$_POST['product_id'] ,'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id'] );	
				$shortField3 			= 	array('coupon_id'=>'DESC');
				$SoldoutTicketList		= 	$this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

				$available_ticket 		=	$CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
				
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

                $tblName        =   'da_orders';
                $where2         =   array('order_id' => $this->input->post('order_id'));
                $ORparam   		=   $this->geneal_model->getOnlyOneData($tblName, $where2);
				 	
                $tblName        =   'da_users';
			  	$where          =   array('users_id' => (int)$ORparam['user_id'] );
                $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);

                $orderData = array();

                $data['user_id']                =   (int)$ORparam['user_id'];
                $data['user_email']             =   $ORparam['user_email'];
                $data['inclusice_of_vat']       =   $ORparam['inclusice_of_vat'];
                $data['stripe_token']           =   $ORparam['stripe_token'];
                $data['user_type']              =   $ORparam['user_type'];

                $data['order_id']               =   $ORparam['order_id'];
                $data['stripeToken']            =   $ORparam['stripeToken'];
                $data['customerId']             =   $ORparam['customerId'];
                $data['captureAmount']          =   $ORparam['captureAmount'];
                $data['stripeChargeId']         =   $ORparam['stripeChargeId'];

				 
                //Get current order of user.
                $wcon['where']                  =   [ 'order_id' => $ORparam["order_id"] ];
                $data['orderData']              =   $this->geneal_model->getData2('single', 'da_orders', $wcon);
             
                array_push($orderData,$data['orderData'] );
                //Get current order details of user.
                $wcon2['where']                 =   [ 'order_id' => $ORparam["order_id"] ];
                $OrderorderDetails              =   $this->geneal_model->getData2('multiple', 'da_orders_details', $wcon2);

				//update order status
                if($data['orderData']["product_is_donate"] == 'Y'){                                                                             
                    $updateParams                   =   array('transaction_id'=> $this->input->post('transaction_id') , 'order_status' => 'Success', 'collection_status' => 'Donated');  
                }else{
                    $expairyData                    =   date('Y-m-d H:i', strtotime($data['orderData']['created_at']. ' 14 Days'));
                    $collectionCode                 =   base64_encode(rand(1000,9999)); 
                    $updateParams                   =   array('transaction_id'=> $this->input->post('transaction_id') , 'order_status' => 'Success', 'collection_code' => $collectionCode, 'collection_status' => 'Pending to collect', 'expairy_data' => $expairyData);  
                }
                // $this->geneal_model->addData('da_test',$updateParams);
                //$updateStatus                     =   [ 'order_status' => 'Success' ];
                $updateorderstatus              =   $this->geneal_model->editData('da_orders', $updateParams, 'order_id', $ORparam["order_id"]);

                	$currentOrderDetails            =   array();                                                                            //New code 21-09-2022
                    $couponDetails                  =   array();  
                    $CashbackDetails                =   array(); 
                                                                                              //New code 21-09-2022
                    //Generate coupon 
                    //foreach($data['orderDetails'] as $CA):  
                    $cashbackcount = 0;
					$ref1count =0;                                                                              //Old code 21-09-2022
                    foreach($OrderorderDetails as $CA):                                                                                     //New code 21-09-2022

                        $CPwcon['where']                    =   [ 'product_id' => $CA["product_id"] ];                                      //New code 21-09-2022
                        $CPData                             =   $this->geneal_model->getData2('single', 'da_prize', $CPwcon);               //New code 21-09-2022
                        $CA['actual_product_name']          =   $CPData['title'];                                                           //New code 21-09-2022
                        array_push($currentOrderDetails,$CA);                                                                               //New code 21-09-2022
                        
                        $this->geneal_model->updateStock($CA['product_id'],$CA['quantity']);
                        // if($data['orderData']["product_is_donate"] == 'Y'):
                        //  $this->geneal_model->updateInventoryStock($data['orderData']['collection_point_id'],$CA['product_id'],$CA['quantity']);
                        // endif;

                        $productIdPrice[$CA['product_id']]      =   ($CA['quantity'] * $CA['price']);

                        // //Get current Ticket order sequence from admin panel.
                        $tblName = 'da_tickets_sequence';
                        $whereCon2['where']                 =   array('product_id' => (int)$CA['product_id'] , 'status' => 'A');    
                        $shortField                         =   array('tickets_seq_id'=>'DESC');
                        $CurrentTicketSequence              =   $this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

                        $tblName = 'da_quickcoupons_totallist';
                        $whereCon3['where']                 =   array('product_id' => (int)$CA['product_id'] , 'tickets_seq_id' => (int)$CurrentTicketSequence['tickets_seq_id']  );    
                        $shortField3                        =   array('coupon_id'=>'DESC');
                        $SoldoutTicketList                  =   $this->common_model->getData('single',$tblName,$whereCon3,$shortField3,'0','0');

                        $available_ticket = $CurrentTicketSequence['tickets_sequence_end'] -$CurrentTicketSequence['tickets_sequence_start'];
                        
                        if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
                            $coupon_sold_number =   $SoldoutTicketList['coupon_sold_number'];
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
                            $quickcoupons["id"]                 =   (int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
                            $quickcoupons["product_id"]         =   (int)$CA['product_id'];
                            $quickcoupons["tickets_seq_id"]     =   (int)$CurrentTicketSequence['tickets_seq_id'];
                            $quickcoupons["coupon_sold_number"] =   '';//$soldOutQty;
                            $quickcoupons["creation_ip"]        =   $this->input->ip_address();
                            $quickcoupons["created_at"]         =   date('Y-m-d H:i');

                            //Saving quick coupons number  
                            $orderInsertID                      =   $this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);

                        else:
                            // checking existing ticket sequence record.
                            if($SoldoutTicketList['tickets_seq_id'] != $CurrentTicketSequence['tickets_seq_id'] ):
                            //Storing new ticket sequence in da_quickcoupons_totallist
                                $quickcoupons["id"]                 =   (int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
                                $quickcoupons["product_id"]         =   (int)$CA['product_id'];
                                $quickcoupons["tickets_seq_id"] =       (int)$CurrentTicketSequence['tickets_seq_id'];
                            $quickcoupons["coupon_sold_number"] =   ''; //$soldOutQty;
                            $quickcoupons["creation_ip"]        =   $this->input->ip_address();
                            $quickcoupons["created_at"]         =   date('Y-m-d H:i');

                            //Saving quick coupons number  
                            $orderInsertID                      =   $this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons);

                        endif;

                    	endif;

	                    //Admin announced ticket available number.
	                     $available_ticket =  $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'];
	                    
	                    if($CurrentTicketSequence['tickets_seq_id'] == $SoldoutTicketList['tickets_seq_id'] ):
	                        $coupon_sold_number =   $SoldoutTicketList['coupon_sold_number'];
	                    endif;
	                    
	                    //defining varibale to store coupon list
	                    $couponList = array();

	                    if ($available_ticket > $coupon_sold_number ):

                        // Getting product details
                        $wcon2['where']                 =   array('products_id' => (int)$CA['product_id'] );
                        $ProductData                    =   $this->geneal_model->getData2('single', 'da_products', $wcon2);

                        if($CA['is_donated'] == 'N'):

                                //Start Create Coupons for simple product
                            for($i=1; $i <= $CA['quantity']*$ProductData['sponsored_coupon']; $i++){

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
                                        // $quickcoupons["product_id"]      =   (int)$ORparam['product_id'];
                                    $quickcoupons1["coupon_sold_number"] =  (int)$totalsoldqty;
                                    $quickcoupons1["creation_ip"]       =   $this->input->ip_address();
                                    $quickcoupons1["updated_at"]        =   date('Y-m-d H:i');

                                            //Saving quick coupons number  
                                            // echo $totalsoldqty.'<br>';
                                    $this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
                                            //End

                                endif;

                                $couponData['coupon_id']        =   (int)$this->geneal_model->getNextSequence('da_coupons');
                                $couponData['users_id']         =   (int)$ORparam["user_id"];
                                $couponData['users_email']      =   $ORparam["user_email"];
                                $couponData['order_id']         =   $ORparam["order_id"];
                                $couponData['product_id']       =   $CA['product_id'];
                                $couponData['product_title']    =   $CA['product_name'];
                                $couponData['is_donated']       =   'N';
                                $couponData['coupon_status']    =   'Live';
                                $couponData['coupon_code']      =   $coupon_code;
                                $couponData["device_type"]      =   $ORparam["device_type"];
                                $couponData["app_version"]      =   $ORparam["app_version"];
                                $couponData['draw_date']        =   $ProductData['draw_date'];
                                $couponData['draw_time']        =   $ProductData['draw_time'];
                                $couponData['coupon_type']      =   'Simple';
                                $couponData['created_at']       =   date('Y-m-d H:i');

                                array_push($couponDetails,$couponData);
                                        //creating coupon for secific product.
                                $this->geneal_model->addData('da_coupons',$couponData);
                            }//End Create Coupons

                        endif;

                         //Get current sponsored coupon count from product. Added on 25-06-2023
                         $wconsponsoredCount['where']   =   array('products_id' => (int)$CA['product_id'] );
                         $productDetails                =   $this->geneal_model->getData2('single', 'da_products', $wconsponsoredCount);

                         if($productDetails['sponsored_coupon']):
                             $sponsored_coupon = $productDetails['sponsored_coupon']*2;
                         else:
                             $sponsored_coupon = 2;
                         endif;
                         //END
                            
                            if($CA['is_donated'] == 'Y'):

                                //Start Create Coupons for donate product
                                for($i=1; $i <= $CA['quantity']*$sponsored_coupon; $i++){

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
                                    // $quickcoupons["product_id"]      =   (int)$ORparam['product_id'];
                                $quickcoupons1["coupon_sold_number"] =  (int)$totalsoldqty;
                                $quickcoupons1["creation_ip"]       =   $this->input->ip_address();
                                $quickcoupons1["updated_at"]        =   date('Y-m-d H:i');

                                //Saving quick coupons number  
                                // echo $totalsoldqty.'<br>';
                                $this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
                                //End

                                $couponData['coupon_id']        =   (int)$this->geneal_model->getNextSequence('da_coupons');
                                $couponData['users_id']         =   (int)$ORparam["user_id"];
                                $couponData['users_email']      =   $ORparam["user_email"];
                                $couponData['order_id']         =   $ORparam["order_id"];
                                $couponData['product_id']       =   $CA['product_id'];
                                $couponData['product_title']    =   $CA['product_name'];
                                $couponData['is_donated']       =   'Y';
                                $couponData['coupon_status']    =   'Live';
                                $couponData['coupon_code']      =   $coupon_code;
                                $couponData["device_type"]      =   $ORparam["device_type"];
                                $couponData["app_version"]      =   $ORparam["app_version"];
                                $couponData['draw_date']        =   $ProductData['draw_date'];
                                $couponData['draw_time']        =   $ProductData['draw_time'];
                                $couponData['coupon_type']      =   'Donated';
                                $couponData['created_at']       =   date('Y-m-d H:i');

                                array_push($couponDetails,$couponData);

                                $this->geneal_model->addData('da_coupons',$couponData);
                                }//End Create Coupons

                            endif;

                        endif;

                            

                        //End Create Coupons

                        $data['finalPrice']             =   $ORparam["inclusice_of_vat"];
                        $data['stripe_token']           =   $ORparam["stripe_token"];

                        $wcon['where']                  =   array('order_id' => $ORparam["order_id"]);
                        $data['couponDetails']          =   $this->geneal_model->getData2('multiple', 'da_coupons', $wcon);

                        $where          =   [ 'users_id' => (int)$ORparam["user_id"] ];
                        $tblName        =   'da_users';
                        $userDetails    =   $this->geneal_model->getOnlyOneData($tblName, $where);
                        
                        $membershipData = $this->geneal_model->getMembership((int)$userDetails['totalArabianPoints']);
                        if($cashbackcount == 0):
	                        if($membershipData):
	                            $cashback           =   $data['finalPrice'] * $membershipData['benifit'] /100;
	                            $data['cashback']   =   $cashback;
	                            if($cashback):
	                                $insertCashback = array(
	                                    'cashback_id'   =>  (int)$this->geneal_model->getNextSequence('da_cashback'),
	                                    'user_id'       =>  (int)$ORparam["user_id"],
	                                    'order_id'      =>  $ORparam["order_id"],
	                                    'cashback'      =>  (float)$cashback,
	                                    'created_at'    =>  date('Y-m-d H:i'),
	                                );
	                                $this->geneal_model->addData('da_cashback',$insertCashback);

	                                $where2                     =   ['users_id' => (int)$ORparam["user_id"] ];
	                                $UserData                   =   $this->geneal_model->getOnlyOneData('da_users', $where2);

	                                /* Load Balance Table -- after buy product*/
	                                $Cashbparam["load_balance_id"]      =   (int)$this->geneal_model->getNextSequence('da_loadBalance');
	                                $Cashbparam["user_id_cred"]         =   (int)$ORparam["user_id"];
	                                $Cashbparam["user_id_deb"]          =   (int)$ORparam["user_id"];
	                                $Cashbparam["order_id"]             =   $ORparam["order_id"];
	                                $Cashbparam["availableArabianPoints"]   =   (float)$userDetails["availableArabianPoints"];
	                                $Cashbparam["end_balance"]              =   (float)$userDetails["availableArabianPoints"] + (float)$cashback ;
	                                $Cashbparam["arabian_points"]       =   (float)$cashback;
	                                $Cashbparam["record_type"]          =   'Credit';
	                                $Cashbparam["arabian_points_from"]  =   'Membership Cashback';
	                                $Cashbparam["creation_ip"]          =   $this->input->ip_address();
	                                $Cashbparam["created_at"]           =   date('Y-m-d H:i');
	                                $Cashbparam["created_by"]           =   $ORparam["user_type"];
	                                $Cashbparam["status"]               =   "A";

	                                $this->geneal_model->addData('da_loadBalance', $Cashbparam);

	                                // Credit the purchesed points and get available arabian points of user.
	                                $this->geneal_model->creaditPoints($cashback ,(int)$ORparam["user_id"]);


	                                // Deduct the purchesed points and get available arabian points of user.
									if( $this->input->post('payment_mode')	==	'Arabian Points'):
										$currentBal  = 	$this->geneal_model->debitPointsByAPI($ORparam['total_price'],$ORparam["user_id"]);
									endif;
	                                /* End */
	                            endif;
	                        endif;
                        endif;

                        //Add Referral Point
                        $SPwhereCon['where']        =   array('BUY_USER_ID' => (int)$ORparam["user_id"], 'SHARED_PRODUCT_ID' => (int)$CA['product_id']);
                        $shared_details             =   $this->geneal_model->getData2('single','da_deep_link',$SPwhereCon);
                        
                        if($shared_details['SHARED_USER_ID'] && $shared_details['SHARED_USER_REFERRAL_CODE'] && $shared_details['SHARED_PRODUCT_ID']):
                            if(isset($productIdPrice[$shared_details['SHARED_PRODUCT_ID']])):

                                $prowhere['where']  =   array('products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
                                $prodData           =   $this->geneal_model->getData2('single','da_products',$prowhere);
                                
                                $sharewhere['where']=   array('users_id'=>(int)$shared_details['SHARED_USER_ID'],'products_id'=>(int)$shared_details['SHARED_PRODUCT_ID']);
                                $shareCount         =   $this->geneal_model->getData2('count','da_product_share',$sharewhere);

                                if($shareCount == NULL):
                                    $shareCount = 0;
                                endif;

                                if(isset($prodData['share_limit']) && $shareCount < $prodData['share_limit']):

                                    $param['share_id']                  =   (int)$this->common_model->getNextSequence('da_product_share');
                                    $param['users_id']                  =   (int)$shared_details['SHARED_USER_ID'];
                                    $param['products_id']               =   (int)$shared_details['SHARED_PRODUCT_ID'];
                                    $param['creation_date']             =   date('Y-m-d H:i');
                                    $param['creation_ip']               =   $this->input->ip_address();
                                    $this->common_model->addData('da_product_share',$param);

                                    $productCartAmount          =   $productIdPrice[$shared_details['SHARED_PRODUCT_ID']];
                                    //First label referal amount Credit
                                    // $ref1tbl                     =   'referral_percentage';
                                    // $ref1where                   =   ['referral_lebel' => (int)1 ];
                                    // $referal1Data                =   $this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
                                    $ref1tbl                    =   'da_products';
                                    $ref1where                  =   ['products_id' => (int)$shared_details['SHARED_PRODUCT_ID'] ];
                                    $referal1Data               =   $this->geneal_model->getOnlyOneData($ref1tbl, $ref1where);
                                    if($referal1Data && $referal1Data['share_percentage_first'] > 0):
                                        $referal1Amount         =   (($productCartAmount*$referal1Data['share_percentage_first'])/100);

                                        /* Referal Product Table -- after buy product*/
                                        $ref1Amtparam["referral_id"]            =   (int)$this->geneal_model->getNextSequence('referral_product');
                                        $ref1Amtparam["referral_user_code"]     =   (int)$shared_details['SHARED_USER_REFERRAL_CODE'];
                                        $ref1Amtparam["referral_from_id"]       =   (int)$shared_details['SHARED_USER_ID'];
                                        $ref1Amtparam["referral_to_id"]         =   (int)$ORparam["user_id"];
                                        $ref1Amtparam["referral_percent"]       =   (float)$referal1Data['share_percentage_first'];
                                        $ref1Amtparam["referral_amount"]        =   (float)$referal1Amount;
                                        $ref1Amtparam["referral_cart_amount"]   =   (float)$productCartAmount;
                                        $ref1Amtparam["referral_product_id"]    =   (int)$shared_details['SHARED_PRODUCT_ID'];
                                        $ref1Amtparam["creation_ip"]            =   $this->input->ip_address();
                                        $ref1Amtparam["created_at"]             =   date('Y-m-d H:i');
                                        $ref1Amtparam["created_by"]             =   (int)$ORparam["user_id"];
                                        $ref1Amtparam["status"]                 =   "A";
                                        
                                        array_push($ref1AmtDetails,$ref1Amtparam);


                                        $this->geneal_model->addData('referral_product', $ref1Amtparam);
                                        /* End */
                                        if($ref1count == 0):
	                                        /* Load Balance Table -- after buy product*/
	                                        $ref1param["load_balance_id"]       =   (int)$this->geneal_model->getNextSequence('da_loadBalance');
	                                        $ref1param["user_id_cred"]          =   (int)$shared_details['SHARED_USER_ID'];
	                                        $ref1param["user_id_deb"]           =   (int)0;
	                                        $ref1param["product_id"]            =   (int)$ref1Amtparam["referral_product_id"];
	                                        $ref1param["arabian_points"]        =   (float)$referal1Amount;
	                                        $ref1param["record_type"]           =   'Credit';
	                                        $ref1param["arabian_points_from"]   =   'Referral';
	                                        $ref1param["creation_ip"]           =   $this->input->ip_address();
	                                        $ref1param["created_at"]            =   date('Y-m-d H:i');
	                                        $ref1param["created_by"]            =   (int)$ORparam["user_id"];
	                                        $ref1param["status"]                =   "A";
	                                        
	                                        $this->geneal_model->addData('da_loadBalance', $ref1param);

	                                        $ref1count++;

										endif;

                                        $where25['where']               =   [ 'users_id' => (int)$shared_details['SHARED_USER_ID'] ];
                                        $sharedUserdata                 =   $this->geneal_model->getData2('single','da_users', $where25);
                                        $this->geneal_model->addData('da_test', $sharedUserdata);

                                        $userWhecrCon['where']      =   array('users_id' => (int)$sharedUserdata['users_id']);
                                        $totalArabianPoints         =   $sharedUserdata['totalArabianPoints'] + $ref1param["arabian_points"];
                                        $availableArabianPoints     =   $sharedUserdata['availableArabianPoints'] + $ref1param["arabian_points"];
                                        
                                        $updateArabianPoints['totalArabianPoints']      =   (float)$totalArabianPoints;
                                        $updateArabianPoints['availableArabianPoints']  =   (float)$availableArabianPoints;

                                        $this->geneal_model->editDataByMultipleCondition('da_users',$updateArabianPoints,$userWhecrCon['where']);
                                        /* End */
                                    endif;
                                    
                                endif;
                            endif;
                            $this->geneal_model->deleteData('da_deep_link', 'seq_id', (int)$shared_details['seq_id']);
                        endif;
                        //END

                    endforeach;

                    $result['orderData']              = $data['orderData'];
                    $result['finalPrice']             = $orderData[0]['inclusice_of_vat'];
                    $result['couponDetails']          = $couponDetails;
                    $result['cashback']               = $cashback;
                    $result['orderDetails']           = $currentOrderDetails;         

                    $results['successData'] = $result;
                   
                    $this->emailsendgrid_model->sendOrderMailToUser($this->input->post('order_id'));
                    $this->sms_model->sendTicketDetails($this->input->post('order_id'));

                    echo outPut(1,lang('SUCCESS_CODE'),lang('ORDER_SUCCESS'),$results);
						 
				

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

}