<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet;

class Wallet_statements extends CI_Controller {

	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model','order_model'));
		$this->lang->load('statictext', 'admin');
		$this->load->helper('common');
	} 


	/* * *********************************************************************
	 * * Function name : index
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for  get Summary Report
	 * * Date : 31 March 2023
	 * * Updated By : Dilip Halder
	 * * Updated Date : 01 December 2023
	 * * **********************************************************************/
	public function index()
	{
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'wallet';
		$data['activeSubMenu'] 				= 	'wallet_statements';

		$searchField = $this->input->get('searchField');
		$searchValue = $this->input->get('searchValue');

		if($this->input->get('fromDate')):
			$start_date = date( "Y-m-d 00:01" ,strtotime($this->input->get('fromDate')));
		endif;

		if($this->input->get('toDate')):
			$end_date = date( "Y-m-d 23:59" ,strtotime($this->input->get('toDate')));
		endif;

		$data['searchField'] 			=   $searchField;  
		$data['searchValue'] 			=   $searchValue; 
		if($start_date):
		   $data['fromDate'] 				=   date('Y-m-d 00:01', strtotime($start_date));  //2023-03-16 15:13
		endif;
		
		if($start_date):
		   $data['toDate'] 				=   date('Y-m-d 23:59', strtotime($end_date));  //2023-03-16 15:13
		endif;

		if($searchField && $searchValue):

			 if($searchField == 'users_mobile'):
				$whereCon['where'] = array($searchField => (int)$searchValue );
			 else:
				$whereCon['where'] = array($searchField => $searchValue );
			 endif;

			$tblName 				= 'da_users';
			$shortField 			= array('_id'=> -1 );
			$userdetails 			= $this->common_model->getData('single',$tblName, $whereCon, $shortField);

			if($userdetails):

					$DZL_USERMOBILE  = (string)$userdetails['users_mobile'];
					$DZL_USEREMAIL   = (string)$userdetails['users_email'];
	  				$users_type 	 = (string)$userdetails['users_type'];
	  				$users_id 		 = (int)$userdetails['users_id'];

					// OrderWallet Condtion 1 Start ------------
					$OrderWallet_whereCon['where']  		=   array( 
															'user_id'  => (int)$users_id  , 
															'$and'=>array(
																		array('order_status' => array('$ne' => 'Initialize' )),
																		array('order_status' => array('$ne' => 'Failed' ))
																		),

															'status' => array('$ne' => 'CL' )
														);
					if($start_date):
					$OrderWallet_whereCon['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;

					if($end_date):
					$OrderWallet_whereCon['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;

					// OrderWallet Condtion 1 End ------------

					// QuickWallet Condtion 2 Start 
					if($users_type == "Users"):

						$QuickWallet_whereCon['where']  =   array('$or' => array(
											 			array('order_users_mobile' => $DZL_USERMOBILE),
											 			array('order_users_email' => $DZL_USEREMAIL)),
											 			'status' => null );

					else:
						$QuickWallet_whereCon['where']  =   array('$or' => array(
											 			array('user_phone' => (int)$DZL_USERMOBILE),
											 			array('user_email' => $DZL_USEREMAIL)),
											 			'status' => null );
					endif;


					if($start_date):
					$QuickWallet_whereCon['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;

					if($end_date):
					$QuickWallet_whereCon['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// QuickWallet Condtion 2 End 


					// QuickVoucher Condtion 3 Start ------------
				    	$QuickVoucher_whereCon['where'] =   array('$or' => array(
													 	array('order_users_mobile' => $DZL_USERMOBILE),
													 	array('order_users_email' => $DZL_USEREMAIL)),
														'isVoucher' => 'Y'
													);
					    if($start_date):
						$QuickVoucher_whereCon['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
						endif;
						if($end_date):
						$QuickVoucher_whereCon['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));
						endif;
					// QuickVoucher Condtion 3 End ------------

					// Couponcodeonly Condtion 4 Start ------------
					$Couponcodeonly_whereCon['where']      	=   array('$or' => array(
																 	array('redeemed_by_whom' => $DZL_USERMOBILE),
																 	array('redeemed_by_whom' => $DZL_USEREMAIL))
																);

					if($start_date):
						$Couponcodeonly_whereCon['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;
					if($end_date):
					$Couponcodeonly_whereCon['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// Couponcodeonly Condtion 4 End ------------
					

					// userRecharge Condtion 5 Start ------------
					$userRecharge_whereCon['where']			=	array('$or' => array( 
														array('user_id_cred' => (int)$users_id, 'record_type' => 'Credit'), 
														array('user_id_deb' => (int)$users_id,'record_type' => 'Debit'), 
														),
														'arabian_points_from' => "Recharge"
													);
					if($start_date):
					$userRecharge_whereCon['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;
					if($end_date):
					$userRecharge_whereCon['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// userRecharge Condtion 5 End ------------

					 
					
					// CashbackWallet Condtion 6 End ------------
					$CashbackWallet_whereCon['where'] 	=	array('$or' => array( 
																array('user_id_cred' => (int)$users_id ), 
																array('user_id_deb' => (int)$users_id ), 
															),
															'record_type' => 'Credit',
															'arabian_points_from' => "Membership Cashback",
														);

					if($start_date):
					$CashbackWallet_whereCon['where_gte']	= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;
					if($end_date):
					$CashbackWallet_whereCon['where_lte']	= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// CashbackWallet Condtion 6 End ------------

					// ReferralWallet Condtion 6 Start ------------
					$Referral_whereCon['where']		=	array( 
													 		'user_id_cred' => (int)$users_id,
															'record_type' => 'Credit',
															'arabian_points_from' => "Referral"
														);

					if($start_date):
					$Referral_whereCon['where_gte']	= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;
					if($end_date):
					$Referral_whereCon['where_lte']	= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// ReferralWallet Condtion 6 End ------------

					// SignUpBonus Condtion 7 Start ------------
					$SignUpBonus_whereCon['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$users_id ), 
																array('user_id_deb' => (int)$users_id ), 
															),
															'record_type' => 'Credit',
															'arabian_points_from' => "Signup Bonus"
														);

					if($start_date):
					$SignUpBonus_whereCon['where_gte']	= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;
					if($end_date):
					$SignUpBonus_whereCon['where_lte']	= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// SignUpBonus Condtion 7 End ------------


					// Refund Condtion 7 Start ------------
					$RefundWallet_whereCon['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$users_id ), 
																array('user_id_deb' => (int)$users_id ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Refund"
															);	
					if($start_date):
					$RefundWallet_whereCon['where_gte']	= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;
					if($end_date):
					$RefundWallet_whereCon['where_lte']	= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// Refund Condtion 7 End ------------

					// ReverseAmountWallet Condtion 8 Start ------------
					$ReverseAmountWallet_whereCon['where']	=	array('$or' => array( 
																array('user_id_cred' => (int)$users_id ), 
																array('user_id_deb' => (int)$users_id ), 
																),
																'record_type' => 'Debit',
																'arabian_points_from' => "Reverse Amount"
															);	
					
					if($start_date):
					$ReverseAmountWallet_whereCon['where_gte']	= 	array(array('0' => 'created_at', '1' => trim($start_date)));
					endif;
					if($end_date):
					$ReverseAmountWallet_whereCon['where_lte']	= 	array(array('0' => 'created_at', '1' => trim($end_date)));
					endif;
					// ReverseAmountWallet Condtion 8 Start ------------
					
					// OrderWallets start
					$tblName 					=	'da_orders';
					$shortField 				=	array('_id'=> -1 );
					$OrderWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $OrderWallet_whereCon, $shortField);
					// OrderWallets end
	 
					// QuickWallet start
					$tblName 					=	'da_ticket_orders';
					$shortField 				=	array('sequence_id'=> -1 );
				    $QuickWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $QuickWallet_whereCon, $shortField);
					// QuickWallet end
				    
				    
					// QuickVoucher start
	                $tblName                    =   'da_ticket_coupons';
	                $shortField                 =   array('coupon_id'=> -1 );
	                $QuickVoucherstatement      =   $this->common_model->getData('multiple' , $tblName, $QuickVoucher_whereCon, $shortField);
	                // QuickVoucher end

					// Couponcodeonly start
	                $tblName 					=	'da_coupon_code_only';
					$shortField 				=	array('_id'=> -1 );
					$CouponWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $Couponcodeonly_whereCon, $shortField);
					// Couponcodeonly end

					//Common_table and soring for below collections.
					$tblName 			=	'da_loadBalance';
					$shortField 		=	array('_id'=> -1 );
					
					// userRecharge start
					$userRechargeList 	=	$this->common_model->getData('multiple' , $tblName, $userRecharge_whereCon, $shortField);
					// userRecharge end
	 
					// CashbackWallet start 
					$CashbackWalletstatement =	$this->common_model->getData('multiple',$tblName, $CashbackWallet_whereCon, $shortField);
					// CashbackWallet end

					// ReferralWallet start
					$ReferralWalletstatement =	$this->common_model->getData('multiple',$tblName, $Referral_whereCon, $orderReferral);
					// ReferralWallet end

					// SignUpBonusWallet start
					$SignUpBonusWalletstatement =	$this->common_model->getData('multiple',$tblName, $SignUpBonus_whereCon, $shortField);
					// SignUpBonusWallet end
					
					// RefundWallet start 
					$RefundWalletstatement  =	$this->common_model->getData('multiple' ,$tblName, $RefundWallet_whereCon, $shortField);
					// RefundWallet end
					
					// ReverseAmountWallet start
					$ReverseAmountWalletstatement = $this->common_model->getData('multiple' ,$tblName, $ReverseAmountWallet_whereCon, $shortField);
					// ReverseAmountWallet end

			endif;

			$Walletstatement   			=	array();

			if($CashbackWalletstatement):
				foreach($CashbackWalletstatement as $key => $item):

					$data1['statement_type']			= 'cashback';
					$data1['order_id'] 					= $item['order_id'];
					$data1['cashback_amount'] 			= $item['arabian_points'];
					$data1['availableArabianPoints']  	= $item['availableArabianPoints'];
					$data1['end_balance']				= $item['end_balance'];
					$data1['created_at']				= $item['created_at'];
					array_push($Walletstatement , $data1);
			    endforeach;
			endif;

			if($OrderWalletstatement):
				foreach ($OrderWalletstatement as $key => $item):

					$tblName 					=	'da_orders_details';
					$shortField 				=	array('_id'=> -1 );
					$whereConorderdetails['where']      	=   array( 'user_id'  => (int)$users_id , 'order_id' => $item['order_id'] );
					$orderdetails 				=	$this->common_model->getData('multiple',$tblName, $whereConorderdetails, $shortField);

					if(empty($orderdetails)):
						$orderdetails = array();
					endif;

					$data2['statement_type']			= 'purchase';
					$data2['order_id'] 					= $item['order_id'];
					$data2['user_type'] 				= $item['user_type'];
					$data2['availableArabianPoints'] 	= $item['availableArabianPoints'];
					$data2['end_balance']				= $item['end_balance'];
					$data2['payment_mode']				= $item['payment_mode'];
					$data2['payment_from']				= $item['payment_from'];
					$data2['users_name']				= $userdetails['users_name'];
					$data2['last_name']					= $userdetails['last_name'];
					$data2['user_email']				= $item['user_email'];
					$data2['user_phone']				= $item['user_phone'];
					$data2['product_details']			= $orderdetails;
					$data2['created_at']				= $item['created_at'];

					array_push($Walletstatement , $data2);
				
				endforeach;
			endif;

			if($QuickWalletstatement):
				$QuickOrder = array();
				foreach ($QuickWalletstatement as $key => $item):

					$data3['statement_type']= 'quick_buy';
					$data3['order_id'] 		= $item['ticket_order_id'];
					$data3['user_type'] 		= $item['user_type'];
					$data3['availableArabianPoints']  = $item['availableArabianPoints'];
					$data3['end_balance']	= $item['end_balance'];
					$data3['payment_mode']	= $item['payment_mode'];
					$data3['payment_from']	= $item['payment_from'];
					$data3['users_name']		= $item['order_first_name'];
					$data3['last_name']		= $item['order_last_name'];
					$data3['user_email']		= $item['order_users_email'];
					$data3['user_phone']		= $item['order_users_mobile'];
					
					$data3['product_name']	= $item['product_title'];
					$data3['quantity']		= $item['product_qty'];
					$data3['price']			= $item['total_price'];
					$data3['created_at']	= $item['created_at'];


					$QuickOrder['order_id'][] 		= $item['ticket_order_id'];
					// $QuickOrder['status'][] 		= $item['status']?$item['status']:'success';
					array_push($Walletstatement , $data3);
				
				endforeach;
			endif;

			if($QuickVoucherstatement):
				foreach ($QuickVoucherstatement as $key => $item):

					if(in_array($item['ticket_order_id'], $QuickOrder['order_id'])):
						// If coupon is used than added redeemed_date as created_at insted of created_at. of filtering the data 
						if(!empty($item['redeemed_date'])):

							$data4['statement_type']		  = 'quick_vouchers';
							$data4['order_id'] 				  = $item['ticket_order_id'];
							$data4['voucher_code'] 			  = $item['voucher_code'];
							$data4['availableArabianPoints']  = $item['availableArabianPoints'];
							$data4['end_balance']			  = $item['end_balance'];
							$data4['payment_from']			  = $item['payment_from']?$item['payment_from']:'--';
							$data4['payment_mode']			  = 'Arabian Points';
							$data4['price']			  		  = $item['total_price'];
							$data4['coupon_code_statys']	  = $item['coupon_code_statys'];
							$data4['redeemed_by_whom']	      = $item['redeemed_by_whom'];
							$data4['coupon_generated_date']	  = $item['created_at'];
							$data4['created_at']			  = $item['redeemed_date'];
							array_push($Walletstatement , $data4);
						endif;
					endif;
				endforeach;
			endif;

			if($CouponWalletstatement):
				foreach($CouponWalletstatement as $key => $item):

					$data5['statement_type']			= 'coupon';
					$data5['coupon_code'] 				= $item['coupon_code'];
					$data5['coupon_code_amount'] 		= $item['coupon_code_amount'];
					$data5['availableArabianPoints']  	= $item['availableArabianPoints'];
					$data5['end_balance']				= $item['end_balance'];
					$data5['redeemed_by_whom']			= $item['redeemed_by_whom'];
					$data5['created_at']				= $item['redeemed_date'];

					array_push($Walletstatement , $data5);
				endforeach;
			endif;

			if($userRechargeList):
				$alluserrechargeList   			=	array();
				foreach($userRechargeList as $userRechargeData):
		    		if($userRechargeData['record_type'] == 'Credit'):
							if($userRechargeData['created_by'] == 'ADMIN'):
								$where['where'] 			= 	['admin_id'=>(int)$userRechargeData['created_user_id']];
								$adminData 		= 	$this->common_model->getData('single','hcap_admin', $where);
								
								$userData['users_name'] 	=	$adminData['admin_first_name'];
					    		$userData['users_email'] 	=	$adminData['admin_email'];
					    		$userData['users_mobile'] 	=	$adminData['admin_phone'];

							else:
								$where['where'] 			= 	['users_id'=>(int)$userRechargeData['created_user_id']];
				    			$userData 					= 	$this->common_model->getData('single','da_users', $where);
							endif;
					endif;

					if($userRechargeData['record_type'] == 'Debit'):
							$where['where'] 			= 	['users_id'=>(int)$userRechargeData['created_user_id']];
			    			$userData 					= 	$this->common_model->getData('single','da_users', $where);
					endif;

		    		$userRechargeData['statement_type'] =	'recharge';
		    		$userRechargeData['users_name'] 	=	$userData['users_name'];
		    		$userRechargeData['users_email'] 	=	$userData['users_email'];
		    		$userRechargeData['users_mobile'] 	=	$userData['users_mobile'];
		    		
		    		array_push($Walletstatement,$userRechargeData);
				endforeach;
			endif;

			if($ReferralWalletstatement):
				foreach($ReferralWalletstatement as $key => $item):
						
						$fields = array('title');
						$product_whereCon = array('products_id' => (int)$product_id);
						$productdetails = $this->common_model->getSingleDataByParticularField($fields,'da_products',$product_whereCon);
						
						$data6['statement_type']			= 'referral';
						$data6['cashback_amount'] 			= $item['arabian_points'];
						$data6['record_type']  				= $item['record_type'];
						$data6['product_title']  			= $productdetails['title'];
						$data6['created_at']				= $item['created_at'];
						array_push($Walletstatement , $data6);

				endforeach;
			endif;

			if($ReverseAmountWalletstatement):
				foreach($ReverseAmountWalletstatement as $key => $item):

						$data7['statement_type']			= 'reverse_amount';
						$data7['record_type']				= $item['record_type'];
						$data7['arabian_points'] 			= $item['arabian_points'];
						$data7['availableArabianPoints']  	= $item['arabian_points'];
						$data7['end_balance']				= '0';
						$data7['created_at']				= $item['created_at'];
						array_push($Walletstatement , $data7);
				endforeach;
			endif;

			if($SignUpBonusWalletstatement):
				foreach($SignUpBonusWalletstatement as $key => $item):
						$data8['statement_type']			= 'signup_bonus';
						$data8['cashback_amount'] 			= $item['arabian_points'];
						$data8['record_type']  				= $item['record_type'];
						$data8['created_at']				= $item['created_at'];
						array_push($Walletstatement , $data8);
				endforeach;
			endif;

			if($RefundWalletstatement):
				foreach($RefundWalletstatement as $key => $item):

						$data9['statement_type']			= 'refund';
						$data9['order_id'] 					= $item['order_id'];
						$data9['cashback_amount'] 			= $item['arabian_points'];
						$data9['availableArabianPoints']  	= $item['availableArabianPoints'];
						$data9['end_balance']				= $item['end_balance'];
						$data9['created_at']				= $item['created_at'];

						array_push($Walletstatement , $data9);
				endforeach;
			endif;


			function sortByCreatedAt($a, $b) {
			    $timeA = strtotime($a['created_at']);
			    $timeB = strtotime($b['created_at']);
			    return $timeB - $timeA;
			}
			
			if($Walletstatement):
				usort($Walletstatement, 'sortByCreatedAt');
			endif;

		else:
			$Walletstatement  = array();
		endif;

			$data['ALLDATA']      = $Walletstatement;
			$data['users_type']   = $userdetails['users_type'];

		// wallet code end.
		$this->layouts->set_title('Wallet Statements | Dealz Arabia');
		$this->layouts->admin_view('wallet/index',array(),$data);

		// echo "<pre>";
		// print_r($_GET);
		// die();

		 

	    
		
	}


	/***********************************************************************
	** Function name : exportexcel
	** Developed By : Dilip halder
	** Purpose  : This function used for export wallet statements
	** Date : 30-05-2023
	** Updated Date :  
	** Updated By   :  
	************************************************************************/
	function exportexcel()
	{	
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'wallet';
		$data['activeSubMenu'] 				= 	'wallet_statements';

		//wallet code start
		
		if($this->input->post('fromDate')):
			$data['fromDate'] 				=   date('Y-m-d 00:00 ', strtotime($this->input->post('fromDate')));  //2023-03-16 15:13
			$whereCon['where_gte'] 			= 	array(array("created_at",$data['fromDate']));
		endif;

		if($this->input->post('toDate')):
			$data['toDate'] 				=   date('Y-m-d 23:59 ', strtotime($this->input->post('toDate')));  //2023-03-16 15:13
			$whereCon['where_lte'] 			= 	array(array("created_at",$data['toDate']));
		endif;

		if($this->input->post('searchField') && $this->input->post('searchValue')):
				
			$sField							=	$this->input->post('searchField');
			$sValue							=	$this->input->post('searchValue');
			
			if($sField == 'users_mobile'):
			$whereCon['where']			 	= 	array( $sField => (int)$sValue ,'record_type' => 'Debit');
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
			else:
			$whereCon['search']			 	= 	array('0'=>trim($sField),'1'=>trim($sValue));
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
			endif;
		else:
			$whereCon['where']					=	array('record_type' => 'Debit');
			$whereCon['search']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

		if($sField == 'users_email'):
			$Value      		=  $sValue;
		elseif($sField == 'users_mobile'):
			$Value      		=  (int)$sValue;
		endif;

		if($Value):
			$tblName 				=	'da_users';
			$shortField 			=	array('_id'=> -1 );
			$userdetails 			=	$this->common_model->getSingleDataByParticularField('users_id', $tblName, $sField, $Value);
			
			if($userdetails):
				$users_id = $userdetails['users_id'];
				$users_email = $userdetails['users_email'];

				$sd = $this->input->post('fromDate');
				$ed = $this->input->post('toDate');

				$start_date = date( "Y-m-d 00:01" ,strtotime($sd));
				$end_date = date( "Y-m-d 23:59" ,strtotime($ed));


			endif;
			 
		endif;

		// if($this->input->post()):
		// 	echo "<pre>";
		// 	print_r($userdetails);
		// 	die();
		// endif;

		if($this->input->post('toDate')  && $this->input->post('fromDate') ):


			
			$whereCon['where']  		=   array( 
													'user_id'  => (int)$users_id  , 
													'$and'=>array(
																array('order_status' => array('$ne' => 'Initialize' )),
																array('order_status' => array('$ne' => 'Failed' ))
																),

													'status' => array('$ne' => 'CL' )
												);
			$whereCon['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			$whereCon['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));

			$tblName 					=	'da_orders';
			$shortField 				=	array('_id'=> -1 );
			$OrderWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon, $shortField);

			$tblName 					=	'da_ticket_orders';
			$shortField 				=	array('sequence_id'=> -1 );
		    $QuickWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon, $shortField);

		    $tblName 					=	'da_coupon_code_only';
			$shortField 				=	array('_id'=> -1 );
			$whereCon1['where']      	=   array( 'redeemed_by_whom'  => $users_email) ;
			$whereCon1['where_gte']		= 	array(array('0' => 'redeemed_date', '1' => trim($start_date)));
			$whereCon1['where_lte']		= 	array( array('0' => 'redeemed_date', '1' => trim($end_date)));
			$CouponWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon1, $shortField);


			$where2['where']			=	array('$or' => array( 
												array('user_id_cred' => (int)$users_id, 'record_type' => 'Credit'), 
												array('user_id_deb' => (int)$users_id,'record_type' => 'Debit'), 
												),
												'arabian_points_from' => "Recharge"
											);

			$where2['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date) ));
			$where2['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));

			$tblName1 			=	'da_loadBalance';
			$order1 			=	array('_id'=> -1 );
			$userRechargeList 	=	$this->common_model->getData('multiple' , $tblName1, $where2, $order1);
			 
			$whereCashback['where'] 				=	array('$or' => array( 
													array('user_id_cred' => (int)$users_id ), 
													array('user_id_deb' => (int)$users_id ), 
													),
													'record_type' => 'Credit',
													'arabian_points_from' => "Membership Cashback",
												);

			$whereCashback['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			$whereCashback['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));


			$tblName 					=	'da_loadBalance';
			$order						=	array('_id'=> -1 );
			$CashbackWalletstatement 	=	$this->common_model->getData('multiple',$tblName, $whereCashback, $order);

			

			$whereReferral['where']		=	array( 
											 		'user_id_cred' => (int)$users_id,
													'record_type' => 'Credit',
													'arabian_points_from' => "Referral"
												);

			$whereReferral['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			$whereReferral['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));

			$tblNameReferral 					=	'da_loadBalance';
			$orderReferral						=	array('_id'=> -1 );
			$ReferralWalletstatement 	=	$this->common_model->getData('multiple',$tblNameReferral, $whereReferral, $orderReferral);


			$whereSignUpBonus['where']		=	array('$or' => array( 
													array('user_id_cred' => (int)$users_id ), 
													array('user_id_deb' => (int)$users_id ), 
													),
													'record_type' => 'Credit',
													'arabian_points_from' => "Signup Bonus"
												);

			$whereSignUpBonus['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			$whereSignUpBonus['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));

			$tblNameSignUpBonus 					=	'da_loadBalance';
			$orderSignUpBonus						=	array('_id'=> -1 );
			$SignUpBonusWalletstatement 	=	$this->common_model->getData('multiple',$tblNameSignUpBonus, $whereSignUpBonus, $orderSignUpBonus);
		

			//Refund start
			$whereRefund['where']		=	array('$or' => array( 
														array('user_id_cred' => (int)$users_id ), 
														array('user_id_deb' => (int)$users_id ), 
														),
														'record_type' => 'Credit',
														'arabian_points_from' => "Refund"
													);		
			$whereRefund['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			$whereRefund['where_lte']		= 	array(array('0' => 'created_at', '1' => trim($end_date)));

			
			$tblNameRefund 					=	'da_loadBalance';
			$orderRefund						=	array('_id'=> -1 );
			$RefundWalletstatement 	=	$this->common_model->getData('multiple' ,$tblNameRefund, $whereRefund, $orderRefund);

		elseif($this->input->post('fromDate')):

			$whereCon['where']  		=   array( 
													'user_id'  => (int)$users_id  , 
													'$and'=>array(
																array('order_status' => array('$ne' => 'Initialize' )),
																array('order_status' => array('$ne' => 'Failed' ))
																),

													'status' => array('$ne' => 'CL' )
												);
			$whereCon['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			
			// order start
			$tblName 					=	'da_orders';
			$shortField 				=	array('_id'=> -1 );
			$OrderWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon, $shortField);
			// order end
			

			// order start
			$tblName 					=	'da_ticket_orders';
			$shortField 				=	array('sequence_id'=> -1 );
		    $QuickWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon, $shortField);
			// order end

			// coupon_code start
		    $tblName 					=	'da_coupon_code_only';
			$shortField 				=	array('_id'=> -1 );
			$whereCon1['where']      	=   array( 'redeemed_by_whom'  => $users_email) ;
			$whereCon1['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			$CouponWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon1, $shortField);
			//  coupon_code end


			//  da_loadBalance Start
			$where2['where']			=	array('$or' => array( 
												array('user_id_cred' => (int)$users_id, 'record_type' => 'Credit'), 
												array('user_id_deb' => (int)$users_id,'record_type' => 'Debit'), 
												),
												'arabian_points_from' => "Recharge"
											);

			$where2['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));

			$tblName1 			=	'da_loadBalance';
			$order1 			=	array('_id'=> -1 );
			$userRechargeList 	=	$this->common_model->getData('multiple' , $tblName1, $where2, $order1);
			//  da_loadBalance End
			

			// Cashback Start

			$whereCashback['where']				=	array('$or' => array( 
													array('user_id_cred' => (int)$users_id ), 
													array('user_id_deb' => (int)$users_id ), 
													),
													'record_type' => 'Credit',
													'arabian_points_from' => "Membership Cashback"
												);

			$whereCashback['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));


			$tblName 					=	'da_loadBalance';
			$order						=	array('_id'=> -1 );
			$CashbackWalletstatement 	=	$this->common_model->getData('multiple' ,$tblName, $whereCashback, $order);
			// Cashback end

			// Referral  Start

			$whereReferral['where']		=	array( 
													'user_id_cred' => (int)$users_id,
													'record_type' => 'Credit',
													'arabian_points_from' => "Referral"
												);

			$whereReferral['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));

			$tblNameReferral 					=	'da_loadBalance';
			$orderReferral						=	array('_id'=> -1 );
			$ReferralWalletstatement 	=	$this->common_model->getData('multiple' ,$tblNameReferral, $whereReferral, $orderReferral);
			// Referral  End


			//SignUpBonus start
			$whereSignUpBonus['where']		=	array('$or' => array( 
													array('user_id_cred' => (int)$users_id ), 
													array('user_id_deb' => (int)$users_id ), 
													),
													'record_type' => 'Credit',
													'arabian_points_from' => "Signup Bonus"
												);

			$whereSignUpBonus['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));

			$tblNameSignUpBonus 					=	'da_loadBalance';
			$orderSignUpBonus						=	array('_id'=> -1 );
			$SignUpBonusWalletstatement 	=	$this->common_model->getData('multiple' ,$tblNameSignUpBonus, $whereSignUpBonus, $orderSignUpBonus);
			//  SignUpBonus end.

			//Refund start
			$whereRefund['where']		=	array( 
													'user_id_cred' => (int)$users_id,
													'record_type' => 'Credit',
													'arabian_points_from' => "Refund"
												);	
			$whereRefund['where_gte']		= 	array(array('0' => 'created_at', '1' => trim($start_date)));
			
			
			$tblNameRefund 					=	'da_loadBalance';
			$orderRefund						=	array('_id'=> -1 );
			$RefundWalletstatement 	=	$this->common_model->getData('multiple' ,$tblNameRefund, $whereRefund, $orderRefund);

		else:


			// Cashback Start

			$whereCashback['where'] 				=	array('$or' => array( 
													array('user_id_cred' => (int)$users_id ), 
													array('user_id_deb' => (int)$users_id ), 
													),
													'record_type' => 'Credit',
													'arabian_points_from' => "Membership Cashback"
												);

			$tblName 					=	'da_loadBalance';
			$order						=	array('_id'=> -1 );
			$CashbackWalletstatement 	=	$this->common_model->getData('multiple' ,$tblName, $whereCashback, $order);
			// Cashback end


		    $whereCon['where']  		=   array( 
													'user_id'  => (int)$users_id  , 
													'$and'=>array(
																array('order_status' => array('$ne' => 'Initialize' )),
																array('order_status' => array('$ne' => 'Failed' ))
																),

													'status' => array('$ne' => 'CL' )
												);
			
			// order start
			$tblName 					=	'da_orders';
			$shortField 				=	array('_id'=> -1 );
			$OrderWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon, $shortField);
			// order end

			// order start
			$tblName 					=	'da_ticket_orders';
			$shortField 				=	array('sequence_id'=> -1 );
		    $QuickWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon, $shortField);
			// order end

			// coupon_code start
		    $tblName 					=	'da_coupon_code_only';
			$shortField 				=	array('_id'=> -1 );

			if($users_email):
				$whereCon1['where']      	=   array( 'redeemed_by_whom'  => $users_email) ;
				$CouponWalletstatement 		=	$this->common_model->getData('multiple' , $tblName, $whereCon1, $shortField);
			endif;
			//  coupon_code end

			//  da_loadBalance Start
			$where2['where']			=	array('$or' => array( 
												array('user_id_cred' => (int)$users_id, 'record_type' => 'Credit'), 
												array('user_id_deb' => (int)$users_id,'record_type' => 'Debit'), 
												),
												'arabian_points_from' => "Recharge"
											);

			$tblName1 			=	'da_loadBalance';
			$order1 			=	array('_id'=> -1 );
			$userRechargeList 	=	$this->common_model->getData('multiple' , $tblName1, $where2, $order1);
			//  da_loadBalance End
			
			// Referral  Start
			$whereReferral['where']		=	array(
													'user_id_cred' => (int)$users_id,
													'record_type' => 'Credit',
													'arabian_points_from' => "Referral"
												);

			$tblNameReferral 					=	'da_loadBalance';
			$orderReferral						=	array('_id'=> -1 );
			$ReferralWalletstatement 	=	$this->common_model->getData('multiple' ,$tblNameReferral, $whereReferral, $orderReferral);
			// Referral  End
		
			//SignUpBonus start
			$whereSignUpBonus['where']		=	array('$or' => array( 
													array('user_id_cred' => (int)$users_id ), 
													array('user_id_deb' => (int)$users_id ), 
													),
													'record_type' => 'Credit',
													'arabian_points_from' => "Signup Bonus"
												);

			$tblNameSignUpBonus 					=	'da_loadBalance';
			$orderSignUpBonus						=	array('_id'=> -1 );
			$SignUpBonusWalletstatement 	=	$this->common_model->getData('multiple' ,$tblNameSignUpBonus, $whereSignUpBonus, $orderSignUpBonus);
			//  SignUpBonus end.
			
			//Refund start
			$whereRefund['where']		=	array('$or' => array( 
														array('user_id_cred' => (int)$users_id ), 
														array('user_id_deb' => (int)$users_id ), 
														),
														'record_type' => 'Credit',
														'arabian_points_from' => "Refund"
													);		
			
			
			$tblNameRefund 					=	'da_loadBalance';
			$orderRefund						=	array('_id'=> -1 );
			$RefundWalletstatement 	=	$this->common_model->getData('multiple' ,$tblNameRefund, $whereRefund, $orderRefund);

			
			
		endif;


			
		$Walletstatement   			=	array();

		if($CashbackWalletstatement):
			foreach($CashbackWalletstatement as $key => $item):

				$data4['statement_type']			= 'cashback';
				$data4['order_id'] 					= $item['order_id'];
				$data4['cashback_amount'] 			= $item['arabian_points'];
				$data4['availableArabianPoints']  	= $item['availableArabianPoints'];
				$data4['end_balance']				= $item['end_balance'];
				$data4['created_at']				= $item['created_at'];

				array_push($Walletstatement , $data4);
		    endforeach;
		endif;

		if($OrderWalletstatement):
			foreach ($OrderWalletstatement as $key => $item):

				$tblName 					=	'da_orders_details';
				$shortField 				=	array('_id'=> -1 );
				$whereConorderdetails['where']      	=   array( 'user_id'  => (int)$users_id , 'order_id' => $item['order_id'] );
				$orderdetails 				=	$this->common_model->getData('multiple',$tblName, $whereConorderdetails, $shortField);

				if(empty($orderdetails)):
					$orderdetails = array();
				endif;

				$data1['statement_type']= 'purchase';
				$data1['order_id'] 		= $item['order_id'];
				$data1['user_type'] 		= $item['user_type'];
				$data1['availableArabianPoints'] = $item['availableArabianPoints'];
				$data1['end_balance']	= $item['end_balance'];
				$data1['payment_mode']	= $item['payment_mode'];
				$data1['users_name']		= $userdetails['users_name'];
				$data1['last_name']			= $userdetails['last_name'];
				$data1['user_email']		= $item['user_email'];
				$data1['user_phone']		= $item['user_phone'];
				$data1['product_details']	= $orderdetails;
				$data1['created_at']	= $item['created_at'];

				array_push($Walletstatement , $data1);
			
			endforeach;
		endif;

		if($QuickWalletstatement):
			foreach ($QuickWalletstatement as $key => $item):

				$data2['statement_type']= 'quick_buy';
				$data2['order_id'] 		= $item['ticket_order_id'];
				$data2['user_type'] 		= $item['user_type'];
				$data2['availableArabianPoints']  = $item['availableArabianPoints'];
				$data2['end_balance']	= $item['end_balance'];
				$data2['payment_mode']	= $item['payment_from'];
				
				$data2['users_name']		= $item['order_first_name'];
				$data2['last_name']		= $item['order_last_name'];
				$data2['user_email']		= $item['order_users_email'];
				$data2['user_phone']		= $item['order_users_mobile'];
				
				$data2['product_name']	= $item['product_title'];
				$data2['quantity']		= $item['product_qty'];
				$data2['price']			= $item['total_price'];
				$data2['created_at']	= $item['created_at'];

				array_push($Walletstatement , $data2);
			
			endforeach;
		endif;

		if($CouponWalletstatement):
			foreach($CouponWalletstatement as $key => $item):

				$data3['statement_type']			= 'coupon';
				$data3['coupon_code'] 				= $item['coupon_code'];
				$data3['coupon_code_amount'] 		= $item['coupon_code_amount'];
				$data3['availableArabianPoints']  	= $item['availableArabianPoints'];
				$data3['end_balance']				= $item['end_balance'];
				$data3['redeemed_by_whom']			= $item['redeemed_by_whom'];
				$data3['created_at']				= $item['redeemed_date'];

				array_push($Walletstatement , $data3);
			endforeach;
		endif;


		if($userRechargeList):
			$alluserrechargeList   			=	array();
			foreach($userRechargeList as $userRechargeData):
				if($userRechargeData['record_type'] == 'Credit'):
					if($userRechargeData['created_by'] == 'ADMIN'):
						$where 			= 	['users_id'=>(int)$userRechargeData['user_id_cred']];
					else:
						$where 			= 	['users_id'=>(int)$userRechargeData['created_user_id']];
					endif;
				else:
					$where 			= 	['users_id'=>(int)$userRechargeData['user_id_to']];
				endif;
				
	    		$userData 		= 	$this->common_model->getData('single','da_users', $where);

	    		$userRechargeData['statement_type'] =	'recharge';
	    		$userRechargeData['users_name'] 	=	$userData['users_name'];
	    		$userRechargeData['users_email'] 	=	$userData['users_email'];
	    		$userRechargeData['users_mobile'] 	=	$userData['users_mobile'];
	    		
	    		array_push($Walletstatement,$userRechargeData);
			endforeach;
		else:
			$RechargeWalletstatement 	=	array();
		endif;

		if($ReferralWalletstatement):
			foreach($ReferralWalletstatement as $key => $item):

					$data5['statement_type']			= 'referral';
					$data5['order_id'] 					= $item['order_id'];
					$data5['cashback_amount'] 			= $item['arabian_points'];
					$data5['availableArabianPoints']  	= $item['availableArabianPoints'];
					$data5['end_balance']				= $item['end_balance'];
					$data5['created_at']				= $item['created_at'];

					array_push($Walletstatement , $data5);
			endforeach;
		endif;

		if($SignUpBonusWalletstatement):
			foreach($SignUpBonusWalletstatement as $key => $item):

					$data6['statement_type']			= 'signup_bonus';
					$data6['order_id'] 					= $item['order_id'];
					$data6['cashback_amount'] 			= $item['arabian_points'];
					$data6['availableArabianPoints']  	= $item['availableArabianPoints'];
					$data6['end_balance']				= $item['end_balance'];
					$data6['created_at']				= $item['created_at'];

					array_push($Walletstatement , $data6);
			endforeach;
		endif;


		if($RefundWalletstatement):
			foreach($RefundWalletstatement as $key => $item):

					$data7['statement_type']			= 'refund';
					$data7['order_id'] 					= $item['order_id'];
					$data7['cashback_amount'] 			= $item['arabian_points'];
					$data7['availableArabianPoints']  	= $item['availableArabianPoints'];
					$data7['end_balance']				= $item['end_balance'];
					$data7['created_at']				= $item['created_at'];

					array_push($Walletstatement , $data7);
			endforeach;
		endif;


		function sortByCreatedAt($a, $b) {
		    $timeA = strtotime($a['created_at']);
		    $timeB = strtotime($b['created_at']);
		    return $timeB - $timeA;
		}
		
		if($Walletstatement):
			usort($Walletstatement, 'sortByCreatedAt');
		endif;

		 

		// echo "<pre>";print_r($Walletstatement);die();
		
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Sl.No');
		$sheet->setCellValue('B1', 'Record Type');
		$sheet->setCellValue('C1', 'Narration');
		$sheet->setCellValue('D1', "Created");
		$sheet->setCellValue('E1', "Opening Balance");
		$sheet->setCellValue('F1', "Closing Balance");
		$sheet->setCellValue('G1', 'Credit');
		$sheet->setCellValue('H1', 'Debit');
		$sheet->setCellValue('I1', 'Status');
		$slno = 1;
		$start = 2;

		foreach($Walletstatement as $items):

			if($items['statement_type'] == 'coupon'):
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, 'Coupon');
				$sheet->setCellValue('C'.$start, 'Coupon Code : '.$items['coupon_code']);
				$sheet->setCellValue('D'.$start, date('d M Y h:i:s A', strtotime($items['created_at'])));

				$sheet->setCellValue('E'.$start, $items['availableArabianPoints']);
				$sheet->setCellValue('F'.$start, $items['end_balance']);
				$sheet->setCellValue('G'.$start, $items['coupon_code_amount']);
				$sheet->setCellValue('H'.$start, '--');
				$sheet->setCellValue('I'.$start,  'Credit');
			endif;	


			if($items['statement_type'] == 'cashback'):
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, 'Cashback');
				$sheet->setCellValue('C'.$start, 'Order ID : '.$items['order_id']);
				$sheet->setCellValue('D'.$start, date('d M Y h:i:s A', strtotime($items['created_at'])));

				$sheet->setCellValue('E'.$start, $items['availableArabianPoints']);
				$sheet->setCellValue('F'.$start, $items['end_balance']);
				$sheet->setCellValue('G'.$start, $items['cashback_amount']);
				$sheet->setCellValue('H'.$start, '--');
				$sheet->setCellValue('I'.$start,  'Credit');
			endif;	


			if($items['statement_type'] == 'purchase'):
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, 'Purchase');
				 

				$order = 'Order ID - '. $items['order_id'].PHP_EOL . 'Product Name : '.PHP_EOL; 
				foreach ($items['product_details'] as $key => $item_details):
					$productdetail .= $item_details['product_name'].' * '.$item_details['quantity'].PHP_EOL;
				// $productName .=  stripslashes($productdetail['product_name']).PHP_EOL;
				// $productQuantity .=  $productdetail['quantity'].PHP_EOL;
				endforeach;
				$sheet->setCellValue('C'.$start, $order. $productdetail);
				$sheet->getStyle('C'.$start)->getAlignment()->setWrapText(true);

				$sheet->setCellValue('D'.$start, date('d M Y h:i:s A', strtotime($items['created_at'])));

				$sheet->setCellValue('E'.$start, $items['availableArabianPoints']);
				$sheet->setCellValue('F'.$start, $items['end_balance']);
				$sheet->setCellValue('G'.$start, '--');

				foreach ($items['product_details'] as $key => $item_details): 
                  $total_price = $total_price+ $item_details['price'];
                endforeach; 

				$sheet->setCellValue('H'.$start, $total_price);
				$sheet->setCellValue('I'.$start,  'Debit');
			endif;	


			if($items['statement_type'] == 'quick_buy'):
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, 'Quick Buy');

				 
				$naration ="Order ID :" .$items['order_id'].PHP_EOL ;
	            $naration .= "Name : ". $items['users_name'].' '.$items['last_name'].PHP_EOL ;
	            $naration .= "Email :". $items['user_email'] ? $items['user_email'] : '--'.PHP_EOL ;
	            $naration .= "Mobile : ".  $items['user_phone'] .PHP_EOL;
	            $naration .= "Product Name : " . $items['product_name'].' * '.$item_details['quantity'].PHP_EOL;

				 
				$sheet->setCellValue('C'.$start, $naration);
				$sheet->getStyle('C'.$start)->getAlignment()->setWrapText(true);

				$sheet->setCellValue('D'.$start, date('d M Y h:i:s A', strtotime($items['created_at'])));

				$sheet->setCellValue('E'.$start, $items['availableArabianPoints']);
				$sheet->setCellValue('F'.$start, $items['end_balance']);
				$sheet->setCellValue('G'.$start, '--');
				$sheet->setCellValue('H'.$start, $items['price']);
				$sheet->setCellValue('I'.$start,  'Debit');
			endif;	

			if($items['statement_type'] == 'refund'):
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, 'Quick Buy  (Refund)');
				$sheet->setCellValue('C'.$start, 'Order ID : '.$items['order_id']);
				$sheet->setCellValue('D'.$start, date('d M Y h:i:s A', strtotime($items['created_at'])));

				$sheet->setCellValue('E'.$start, $items['availableArabianPoints']);
				$sheet->setCellValue('F'.$start, $items['end_balance']);
				$sheet->setCellValue('G'.$start, $items['cashback_amount']);
				$sheet->setCellValue('H'.$start, '--');
				$sheet->setCellValue('I'.$start,  'Credit');
			endif;

			if($items['statement_type'] == 'recharge'):
				$sheet->setCellValue('A'.$start, $slno);
				$sheet->setCellValue('B'.$start, 'Recharge');
				

				$naration ='Recharge Amount : '.$items['sum_arabian_points'].PHP_EOL;;
				$naration .='Name : ' .$items['users_name'].' '.$items['last_name'].PHP_EOL;
				$naration .='Email: ' . $items['users_email'].PHP_EOL;
				$naration .='Mobile : '.$items['users_mobile'].PHP_EOL;

				$sheet->setCellValue('C'.$start, $naration);
				$sheet->getStyle('C'.$start)->getAlignment()->setWrapText(true);


				$sheet->setCellValue('D'.$start, date('d M Y h:i:s A', strtotime($items['created_at'])));

				$sheet->setCellValue('E'.$start, $items['availableArabianPoints']);
				$sheet->setCellValue('F'.$start, $items['end_balance']);
					

				$credit = ($items['record_type'] == 'Credit') ?$items['sum_arabian_points']  :   '--' ;
				$Debit = ($items['record_type'] == 'Debit') ?$items['sum_arabian_points']  :   '--' ;

				$sheet->setCellValue('G'.$start, $credit);
				$sheet->setCellValue('H'.$start, $Debit);
				$sheet->setCellValue('I'.$start,  $items['record_type'] );
			endif;


			$start = $start+1;
			$slno = $slno+1;
			
		endforeach;


		$styleThinBlackBorderOutline = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => Border::BORDER_THIN,
						'color' => ['argb' => 'FF000000'],
					],
				],
			];
		//Font BOLD
		$sheet->getStyle('A1:I1')->getFont()->setBold(true);		
		$sheet->getStyle('A1:I1')->applyFromArray($styleThinBlackBorderOutline);
		//Alignment
		//fONT SIZE
		//$sheet->getStyle('A1:D10')->getFont()->setSize(12);
		//$sheet->getStyle('A1:D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		//$sheet->getStyle('A2:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		//Custom width for Individual Columns
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(25);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(30);
		$sheet->getColumnDimension('I')->setWidth(25);
		

		$curdate = date('d-m-Y H:i:s');
		$writer = new Xlsx($spreadsheet);
		$filename = 'Wallet statements'.$curdate;
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');


		// wallet code end.


		
	}




}

?>