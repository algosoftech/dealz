<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("application/core/My_Head.php");
class Walletstatement extends My_Head {
	
	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model(array('geneal_model','common_model','notification_model'));
		$this->lang->load('statictext','front');
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
		
		$data 					=	array();
		$data['page']			=	'Wallet Statements';
		$data['filters'] = $this->input->post('filters');

	    $users_id 				=   $this->session->userdata('DZL_USERID');
		$tblName 				=	'da_users';
		$shortField 			=	array('_id'=> -1 );
		$whereCon['where']      =   array( 'users_id'  => (int)$users_id);
		$userdetails 			=	$this->common_model->getData('single',$tblName, $whereCon, $shortField);

		$Result   		=	array();
		
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

		$data['Walletstatement'] = $Walletstatement;
		$data['users_type']   	 = $userdetails['users_type'];

		// echo "<pre>";print_r($Walletstatement);die();

		$useragent = $_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-walletstatement',$data);
		else:
			$this->load->view('walletstatement',$data);
		endif;
	}
	
}