<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lotto extends CI_Controller {
	
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
	 * * Function name  : checkOut
	 * * Developed By   : Dilip Halder
	 * * Purpose    	: This function used for get Country Code
	 * * Date 			: 27 October 2023
	 * * **********************************************************************/
	public function paymentCapture()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);

			elseif( $this->input->post('prize_title') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);

			// elseif( $this->input->post('first_name') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMPRT_FIRST_NAME'),$result);

			// elseif( $this->input->post('last_name') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_LAST_NAME'),$result);

			// elseif( $this->input->post('product_is_donate') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_IS_DONATE'),$result);
			
			elseif( $this->input->post('product_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);

			elseif( $this->input->post('product_title') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_PRODUCT_TITLE'),$result);

			elseif( $this->input->post('product_qty') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_PRODUCT_QTY'),$result);

			elseif( $this->input->post('draw_date') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_DRAW_DATE'),$result);

			elseif( $this->input->post('lotto_type') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_LOTTO_TYPE'),$result);

			// elseif( $this->input->post('users_email') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_USER_EMAIL'),$result);

			elseif( $this->input->post('subtotal') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_SUBTOTAL'),$result);

			// elseif( $this->input->post('country_code') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_COUNTRYCODE'),$result);

			// elseif( $this->input->post('users_mobile') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_USERMOBILE'),$result);

			// elseif( $this->input->post('SMS') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_SMS'),$result);

			elseif( $this->input->post('device_type') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_DEVICE_TYPE'),$result);

			elseif( $this->input->post('app_version') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_APP_VERSION'),$result);

			elseif( $this->input->post('ticket') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMPTY_TICKET'),$result);

			else:
				
				// Check Product availability
				$productID 			= $this->input->post('product_id');
				$productQty 		= $this->input->post('product_qty');
				$productIsDonated 	= $this->input->post('product_id');
				$Plateform 			= 'app';
				$CouponGenerate 	= '';
				$USER['USERID']		= $this->input->get('users_id');
				$USER['total_price']= $this->input->post('total_price');
				//complete validation..
				$result 			=  $this->common_model->CheckAvailableTickets($productID,$productQty ,$productIsDonated,$Plateform,$CouponGenerate,$USER);

				// Deduct the purchesed points and get available arabian points of user.
				$currentBal 		= 	$this->geneal_model->debitPointsByAPI($USER['total_price'],$USER['USERID']); 

				//Update stock
				$Ptype ='lotto';
				$this->geneal_model->updateStock($productID,$productQty,$Ptype);


				$tbl_name  		= 'da_users';
				$whereCon  		= array('users_id'  =>(int)$USER['USERID'] ,'status'=> 'A');
				$sellerDetails  = $this->geneal_model->getOnlyOneData($tbl_name, $whereCon);

				$ORparam["sequence_id"]		    		=	(int)$this->geneal_model->getNextSequence('da_lotto_orders');
		        $ORparam["order_id"]		        	=	$this->geneal_model->getNextOrderId();
		        $ORparam["user_id"] 					=	(int)$this->input->get('users_id');
		        $ORparam["user_type"] 					=	$sellerDetails['users_type']; 
	        	$ORparam["user_email"] 					=   $sellerDetails['users_email'];	
			 	$ORparam["user_phone"] 					=	$sellerDetails['users_mobile'];	
			 	$ORparam["store_name"] 					=	$sellerDetails['store_name'];
		     	$ORparam["product_id"] 					=	(int)$this->input->post('product_id');
		     	$ORparam["product_title"] 				=	$this->input->post('product_title');
		     	$ORparam["product_qty"] 				=	$this->input->post('product_qty');
		     	$ORparam["prize_title"] 				=	$this->input->post('prize_title');
		        $ORparam["vat_amount"] 					=	(float)$this->input->post('vat_amount');
		        $ORparam["straight_add_on_amount"] 		=	(float)$this->input->post('straight_add_on_amount');
		        $ORparam["rumble_add_on_amount"] 		=	(float)$this->input->post('rumble_add_on_amount');
		        $ORparam["reverse_add_on_amount"] 		=	(float)$this->input->post('reverse_add_on_amount');
		        $ORparam["subtotal"] 					=	(float)$this->input->post('subtotal');
		        $ORparam["total_price"] 				=	(float)$this->input->post('total_price');
		        $ORparam["availableArabianPoints"] 		=	(float)$sellerDetails["availableArabianPoints"];
				$ORparam["end_balance"] 				=	(float)$sellerDetails["availableArabianPoints"] - (float)$ORparam["total_price"] ;
			    $ORparam["payment_mode"] 				=	'Arabian Points';
			    $ORparam["payment_from"] 				=	'Lotto';
		        $ORparam["product_is_donate"] 			=	$this->input->post('product_is_donate'); //$this->input->post('product_is_donate');
			    $ORparam["order_status"] 				=	"Success";
			    $ORparam["device_type"] 				=	$this->input->post('device_type');
   				$ORparam["app_version"] 				=	(float)$this->input->post('app_version');
   				$ORparam["ticket"] 						=	$this->input->post('ticket');
			    $ORparam["status"] 						=	"A";
		     	$ORparam["order_first_name"] 			=	$this->input->post('first_name');
		     	$ORparam["order_last_name"] 			=	$this->input->post('last_name');
		     	$ORparam["order_users_country_code"] 	=	$this->input->post('country_code')?$this->input->post('country_code'):"+971";
		     	$ORparam["order_users_mobile"] 			=	$this->input->post('users_mobile');
		     	$ORparam["order_users_email"] 			=	$this->input->post('users_email');
		     	$ORparam["SMS"] 						=	$this->input->post('SMS');
			    $ORparam["creation_ip"] 				=	$this->input->ip_address();
			    $ORparam["created_at"] 					=	date('Y-m-d H:i');
			    //Saving order details for Ticket
			    $orderInsertID 							=	$this->geneal_model->addData('da_lotto_orders', $ORparam);
			   
			    $result = $ORparam;

				echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_MSG'),$result);

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
	/* * *********************************************************************
	 * * Function name : orderHistory
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used to fetch order history.
	 * * Date : 27 October 2023
	 * * **********************************************************************/
	public function orderHistory()
	{

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'GET')):
			
			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:
				
				$tbl 					=	'da_lotto_orders';
				$wcon['where']          =  array('user_id' => (int)$this->input->get('users_id'));
				$Sortdata 				=	array('sequence_id' => -1);
				$userOrderList	=	$this->common_model->getdata('multiple', $tbl, $wcon,$Sortdata);

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
	 * * Function name : SummaryReportSearch
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to fetch order history.
	 * * Date 		   : 27 October 2023
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


				$tbl 					=	'da_lotto_orders';

				 $product_title =  $from_date = $this->input->get('product_title');
				  $start_date = date('Y-m-d 00:00' ,strtotime($this->input->get('start_date')) );
			      $end_date = date('Y-m-d 23:59' ,strtotime($this->input->get('end_date')));

				 $wcon['where']          = array();


				 

				 if( !empty($product_title)  &&  $this->input->get('start_date')    &&  $this->input->get('end_date') ):
				 //	echo 'product-date-start-end';
				 	$wcon['where']          =  array('user_id' => (int)$this->input->get('users_id') , 
													'product_title' => $product_title ,
				   									'created_at' => array('$gte' => $start_date ,'$lte' => $end_date ),
				   									'status' => array('$ne'=> 'CL')
				   								);
				elseif( $this->input->get('start_date') !='' && $this->input->get('end_date') !='' ):
				 	//echo 'date-start-end';
					
					$wcon['where']          =  array('user_id' => (int)$this->input->get('users_id') ,
				   									 'created_at' => array('$gte' => $start_date ,'$lte' => $end_date ),
				   									 'status' => array('$ne'=> 'CL')
				   								);
				elseif(!empty($product_title) ):
				 	//echo 'product';
					
					$wcon['where']          =  array('user_id' => (int)$this->input->get('users_id') ,
													 'product_title' => $product_title ,
													 'status' => array('$ne'=> 'CL')
				   								);
				elseif( $this->input->get('start_date') !='' ):
				 	//echo 'product';
					
					$wcon['where']          =  array('user_id' => (int)$this->input->get('users_id') ,
													  'created_at' => array('$gte' => $start_date ),
													  'status' => array('$ne'=> 'CL')
				   								);
				else:
				 	//echo 'else';
					
					$wcon['where']          =  array('user_id' => (int)$this->input->get('users_id'),
													 'status' => array('$ne'=> 'CL')
				   								);
				endif;

				 $shortField 				=	['coupon_id' => -1];
				// $productFilter		=	$this->geneal_model->getData2('multiple', $tbl, $wcon,$shortField);
				// $productFilter		=	$this->geneal_model->getData2('multiple', $tbl, $wcon,$shortField);
				
				$wcon1['where']          =  array('users_id' => (int)$this->input->get('users_id') ,'status' => array('$ne'=> 'CL') );


				$userResult		=	$this->geneal_model->getData2('single', 'da_users', $wcon1);

				$result['totalArabianPoints'] 			= $userResult['totalArabianPoints'];
				$result['availableArabianPoints'] 		= $userResult['availableArabianPoints'];
				$result['products']		=	$this->geneal_model->GetQuickOrderTotalCount('multiple', $tbl, $wcon,$shortField);
				
				if($this->input->get('product_title') || $this->input->get('start_date')  || $this->input->get('end_date') ):
					$result['totalArabianPoints'] 	  =	$result['products'][0]['availableArabianPoints'];
					$result['availableArabianPoints'] =	$result['products'][0]['end_balance'];
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

	public function getWinner()
	{

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 			= 	array();

		if(requestAuthenticate(APIKEY,'GET')):
			
			// if($this->input->get('users_id') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			// else
			// 	if( $this->input->post('product_id') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('PRODUCT_ID_EMPTY'),$result);
			// else:

			 	// $productID 					 = $this->input->post('product_id');
				
				// $date 			   			= strtotime(date('Y-m-d'));
				// $whereCon['where'] 			= array('update_date' => array('$gte' => $date ));
			 	$AvailableWinnerCouponList 	=	$this->common_model->getData('multiple','da_lotto_winners',$whereCon);
			 	


			 	if($AvailableWinnerCouponList):
				 	
			 		foreach ($AvailableWinnerCouponList as $key => $list):
					 	$product_id = $list['product_id'];
					 	$tableName = 'da_prize';
					 	$whereCon['where'] = array('product_id' => (int)$product_id);
					 	$PrizeData = $this->common_model->getData('single',$tableName , $whereCon);

					 	$whereConproduct['where'] = array('products_id' => (int)$product_id);
					 	$productData = $this->common_model->getFieldInArray('title','da_products' , $whereConproduct);
					 	$AvailableWinnerCouponList[$key]['title'] = $productData[0];

					 	if($PrizeData):
					 		$AvailableWinnerCouponList[$key]['prizeData'] = $PrizeData;
						else:
						 	$AvailableWinnerCouponList[$key]['prizeData'] = '';
						endif;

			 		endforeach;
				 	
			 		$results = $AvailableWinnerCouponList;
			 	else:
			 		$results = [];
			 	endif;

			 	echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
			// endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
   
	
	/* * *********************************************************************
	 * * Function name : productSettings
	 * * Developed By  : Dilip Halder
	 * * Purpose  	   : This function used to fetch order history.
	 * * Date 		   : 29 January 2024
	 * * **********************************************************************/
	public function productSettings()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_GET));
		$result 			= 	array();

		if(requestAuthenticate(APIKEY,'GET')):
			 	$whereCon['where']  = array('status' => 'A');
			 	$Product_Settings 	= $this->common_model->getData('multiple','da_lotto_settings',$whereCon);
			 	if($Product_Settings):
			 		$results = $Product_Settings;
			 	else:
			 		$results = [];
			 	endif;

			 	echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	
			// endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
}