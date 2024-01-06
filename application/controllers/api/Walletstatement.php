<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walletstatement extends CI_Controller {
	
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


	
	/* * *********************************************************************
	 * * Function name : Walletstatement
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for  get Summary Report
	 * * Date : 31 March 2023
	 * * **********************************************************************/
	public function index()
	{
		
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'GET')):

			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

			    $users_id 				= $this->input->get('users_id');
				
				$tblName 				=	'da_users';
				$shortField 			=	array('_id'=> -1 );
				$whereCon      			=  array( 'users_id'  => (int)$users_id);
				
				$userdetails 	=	$this->geneal_model->getData($tblName, $whereCon, $shortField);

				$Result   		=	array();
				if($userdetails):

					$sd = $this->input->get('start_date');
					$ed = $this->input->get('end_date');

					$start_date = date( "Y-m-d 00:01" ,strtotime($sd));
					$end_date = date( "Y-m-d 23:59" ,strtotime($ed));

					if($this->input->get('end_date')  && $this->input->get('start_date') ):
						

						$whereCon['where']  		=   array( 
													'user_id'  => (int)$users_id  , 
													'$and'=>array(
																array('order_status' => array('$ne' => 'Initialize' )),
																array('order_status' => array('$ne' => 'Failed' ))
																),

													'status' => array('$ne' => 'CL' )
												);
					
						$whereCon['where_gte']			= 	array('0' => 'created_at', '1' => trim($start_date) );
						$whereCon['where_lte']			= 	array('0' => 'created_at', '1' => trim($end_date) );
						

						$tblName 					=	'da_orders';
						$shortField 				=	array('_id'=> -1 );
						$OrderWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon, $shortField);

						$tblName 					=	'da_ticket_orders';
						$shortField 				=	array('sequence_id'=> -1 );
					    $QuickWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon, $shortField);

					    $tblName 					=	'da_coupon_code_only';
						$shortField 				=	array('_id'=> -1 );
						$whereCon1['where']      	=   array( 'redeemed_by_whom'  => $userdetails[0]['users_email']) ;
						$whereCon1['where_gte']		= 	array('0' => 'redeemed_date', '1' => trim($start_date) );
						$whereCon1['where_lte']		= 	array('0' => 'redeemed_date', '1' => trim($end_date) );
						$CouponWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon1, $shortField);


						$where2['where']			=	array('$or' => array( 
															array('user_id_cred' => (int)$this->input->get('users_id'), 'record_type' => 'Credit'), 
															array('user_id_deb' => (int)$this->input->get('users_id'),'record_type' => 'Debit'), 
															),
															'arabian_points_from' => "Recharge"
														);

						$where2['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );
						$where2['where_lte']		= 	array('0' => 'created_at', '1' => trim($end_date) );

						$tblName1 			=	'da_loadBalance';
						$order1 			=	array('_id'=> -1 );
						$userRechargeList 	=	$this->geneal_model->getData2('multiple' , $tblName1, $where2, $order1);
						 
						$whereCashback['where'] 				=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Membership Cashback",
															);

						$whereCashback['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );
						$whereCashback['where_lte']		= 	array('0' => 'created_at', '1' => trim($end_date) );


						$tblName 					=	'da_loadBalance';
						$order						=	array('_id'=> -1 );
						$CashbackWalletstatement 	=	$this->geneal_model->getData2('multiple',$tblName, $whereCashback, $order);

						

						$whereReferral['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Referral"
															);

						$whereReferral['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );
						$whereReferral['where_lte']		= 	array('0' => 'created_at', '1' => trim($end_date) );

						$tblNameReferral 					=	'da_loadBalance';
						$orderReferral						=	array('_id'=> -1 );
						$ReferralWalletstatement 	=	$this->geneal_model->getData2('multiple',$tblNameReferral, $whereReferral, $orderReferral);


						$whereSignUpBonus['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Signup Bonus"
															);

						$whereSignUpBonus['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date));
						$whereSignUpBonus['where_lte']		= 	array('0' => 'created_at', '1' => trim($end_date));

						$tblNameSignUpBonus 					=	'da_loadBalance';
						$orderSignUpBonus						=	array('_id'=> -1 );
						$SignUpBonusWalletstatement 	=	$this->geneal_model->getData2('multiple',$tblNameSignUpBonus, $whereSignUpBonus, $orderSignUpBonus);
					

						//Refund start
						$whereRefund['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Refund"
															);	
						$whereRefund['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date));
						$whereRefund['where_lte']		= 	array('0' => 'created_at', '1' => trim($end_date));

						
						$tblNameRefund 					=	'da_loadBalance';
						$orderRefund						=	array('_id'=> -1 );
						$RefundWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblNameRefund, $whereRefund, $orderRefund);

					elseif($this->input->get('start_date')):
						
						$whereCon['where']  		=   array( 
													'user_id'  => (int)$users_id  , 
													'$and'=>array(
																array('order_status' => array('$ne' => 'Initialize' )),
																array('order_status' => array('$ne' => 'Failed' ))
																),

													'status' => array('$ne' => 'CL' )
												);
						$whereCon['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );
						
						// order start
						$tblName 					=	'da_orders';
						$shortField 				=	array('_id'=> -1 );
						$OrderWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon, $shortField);
						// order end

						// order start
						$tblName 					=	'da_ticket_orders';
						$shortField 				=	array('sequence_id'=> -1 );
					    $QuickWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon, $shortField);
						// order end

						// coupon_code start
					    $tblName 					=	'da_coupon_code_only';
						$shortField 				=	array('_id'=> -1 );
						$whereCon1['where']      	=   array( 'redeemed_by_whom'  => $userdetails[0]['users_email']) ;
						$whereCon1['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );
						$CouponWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon1, $shortField);
						//  coupon_code end


						//  da_loadBalance Start
						$where2['where']			=	array('$or' => array( 
															array('user_id_cred' => (int)$this->input->get('users_id'), 'record_type' => 'Credit'), 
															array('user_id_deb' => (int)$this->input->get('users_id'),'record_type' => 'Debit'), 
															),
															'arabian_points_from' => "Recharge"
														);

						$where2['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );

						$tblName1 			=	'da_loadBalance';
						$order1 			=	array('_id'=> -1 );
						$userRechargeList 	=	$this->geneal_model->getData2('multiple' , $tblName1, $where2, $order1);
						//  da_loadBalance End
						

						// Cashback Start

						$whereCashback['where']				=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Membership Cashback"
															);

						$whereCashback['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );


						$tblName 					=	'da_loadBalance';
						$order						=	array('_id'=> -1 );
						$CashbackWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblName, $whereCashback, $order);
						// Cashback end

					// Referral  Start

					$whereReferral['where']		=	array('$or' => array( 
															array('user_id_cred' => (int)$this->input->get('users_id') ), 
															array('user_id_deb' => (int)$this->input->get('users_id') ), 
															),
															'record_type' => 'Credit',
															'arabian_points_from' => "Referral"
														);

					$whereReferral['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );

					$tblNameReferral 					=	'da_loadBalance';
					$orderReferral						=	array('_id'=> -1 );
					$ReferralWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblNameReferral, $whereReferral, $orderReferral);
					// Referral  End


					//SignUpBonus start
					$whereSignUpBonus['where']		=	array('$or' => array( 
															array('user_id_cred' => (int)$this->input->get('users_id') ), 
															array('user_id_deb' => (int)$this->input->get('users_id') ), 
															),
															'record_type' => 'Credit',
															'arabian_points_from' => "Signup Bonus"
														);

					$whereSignUpBonus['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date) );

					$tblNameSignUpBonus 					=	'da_loadBalance';
					$orderSignUpBonus						=	array('_id'=> -1 );
					$SignUpBonusWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblNameSignUpBonus, $whereSignUpBonus, $orderSignUpBonus);
					//  SignUpBonus end.

					//Refund start
					$whereRefund['where']		=	array('$or' => array( 
															array('user_id_cred' => (int)$this->input->get('users_id') ), 
															array('user_id_deb' => (int)$this->input->get('users_id') ), 
															),
															'record_type' => 'Credit',
															'arabian_points_from' => "Refund"
														);	
					$whereRefund['where_gte']		= 	array('0' => 'created_at', '1' => trim($start_date));
					
					
					$tblNameRefund 					=	'da_loadBalance';
					$orderRefund						=	array('_id'=> -1 );
					$RefundWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblNameRefund, $whereRefund, $orderRefund);

					else:

						// Cashback Start

						$whereCashback['where'] 				=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Membership Cashback"
															);

						$tblName 					=	'da_loadBalance';
						$order						=	array('_id'=> -1 );
						$CashbackWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblName, $whereCashback, $order);
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
						$OrderWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon, $shortField);
						// order end

						// order start
						$tblName 					=	'da_ticket_orders';
						$shortField 				=	array('sequence_id'=> -1 );
					    $QuickWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon, $shortField);
						// order end

						// coupon_code start
					    $tblName 					=	'da_coupon_code_only';
						$shortField 				=	array('_id'=> -1 );
						$whereCon1['where']      	=   array( 'redeemed_by_whom'  => $userdetails[0]['users_email']) ;
						$CouponWalletstatement 		=	$this->geneal_model->getData2('multiple' , $tblName, $whereCon1, $shortField);
						//  coupon_code end


						//  da_loadBalance Start
						$where2['where']			=	array('$or' => array( 
															array('user_id_cred' => (int)$this->input->get('users_id'), 'record_type' => 'Credit'), 
															array('user_id_deb' => (int)$this->input->get('users_id'),'record_type' => 'Debit'), 
															),
															'arabian_points_from' => "Recharge"
														);

						$tblName1 			=	'da_loadBalance';
						$order1 			=	array('_id'=> -1 );
						$userRechargeList 	=	$this->geneal_model->getData2('multiple' , $tblName1, $where2, $order1);
						//  da_loadBalance End
						
						// Referral  Start

						$whereReferral['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Referral"
															);

						$tblNameReferral 					=	'da_loadBalance';
						$orderReferral						=	array('_id'=> -1 );
						$ReferralWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblNameReferral, $whereReferral, $orderReferral);
						// Referral  End


						//SignUpBonus start
						$whereSignUpBonus['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Signup Bonus"
															);

						$tblNameSignUpBonus 					=	'da_loadBalance';
						$orderSignUpBonus						=	array('_id'=> -1 );
						$SignUpBonusWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblNameSignUpBonus, $whereSignUpBonus, $orderSignUpBonus);
						//  SignUpBonus end.
						
						//Refund start
						$whereRefund['where']		=	array('$or' => array( 
																array('user_id_cred' => (int)$this->input->get('users_id') ), 
																array('user_id_deb' => (int)$this->input->get('users_id') ), 
																),
																'record_type' => 'Credit',
																'arabian_points_from' => "Refund"
															);	
						
						
						$tblNameRefund 					=	'da_loadBalance';
						$orderRefund						=	array('_id'=> -1 );
						$RefundWalletstatement 	=	$this->geneal_model->getData2('multiple' ,$tblNameRefund, $whereRefund, $orderRefund);

					
					endif;
					
					$Walletstatement   			=	array();

					foreach($CashbackWalletstatement as $key => $item):

						$data4['statement_type']			= 'cashback';
						$data4['order_id'] 					= $item['order_id'];
						$data4['cashback_amount'] 			= $item['arabian_points'];
						$data4['availableArabianPoints']  	= $item['availableArabianPoints'];
						$data4['end_balance']				= $item['end_balance'];
						$data4['created_at']				= $item['created_at'];

						array_push($Walletstatement , $data4);
				    endforeach;


					

					foreach ($OrderWalletstatement as $key => $item):


						$tblName 					=	'da_orders_details';
						$shortField 				=	array('_id'=> -1 );
						$whereCon['where']      	=   array( 'user_id'  => (int)$users_id , 'order_id' => $item['order_id'] );
						$orderdetails 				=	$this->geneal_model->getData2('multiple',$tblName, $whereCon, $shortField);
						
						if(empty($orderdetails)):
							$orderdetails = array();
						endif;

						$data1['statement_type']= 'purchase';
						$data1['order_id'] 		= $item['order_id'];
						$data1['user_type'] 		= $item['user_type'];
						$data1['availableArabianPoints'] = $item['availableArabianPoints'];
						$data1['end_balance']	= $item['end_balance'];
						$data1['payment_mode']	= $item['payment_mode'];
						$data1['users_name']		= $userdetails[0]['users_name'];
						$data1['last_name']		= $userdetails[0]['last_name'];
						$data1['user_email']		= $item['user_email'];
						$data1['user_phone']		= $item['user_phone'];
						$data1['product_details']	= $orderdetails;
						$data1['created_at']	= $item['created_at'];

						array_push($Walletstatement , $data1);
					
					endforeach;

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
							
				    		$userData 		= 	$this->geneal_model->getOnlyOneData('da_users', $where );


				    		$userRechargeData['statement_type'] =	'recharge';
				    		$userRechargeData['users_name'] 	=	$userData['users_name'];
				    		$userRechargeData['users_email'] 	=	$userData['users_email'];
				    		$userRechargeData['users_mobile'] 	=	$userData['users_mobile'];
				    		
				    		array_push($Walletstatement,$userRechargeData);
						endforeach;
					else:
						$RechargeWalletstatement 	=	array();
					endif;

						
				endif;

				
				foreach($ReferralWalletstatement as $key => $item):

						$data5['statement_type']			= 'referral';
						$data5['order_id'] 					= $item['order_id'];
						$data5['cashback_amount'] 			= $item['arabian_points'];
						$data5['availableArabianPoints']  	= $item['availableArabianPoints'];
						$data5['end_balance']				= $item['end_balance'];
						$data5['created_at']				= $item['created_at'];

						array_push($Walletstatement , $data5);
				endforeach;

				foreach($SignUpBonusWalletstatement as $key => $item):

						$data6['statement_type']			= 'signup_bonus';
						$data6['order_id'] 					= $item['order_id'];
						$data6['cashback_amount'] 			= $item['arabian_points'];
						$data6['availableArabianPoints']  	= $item['availableArabianPoints'];
						$data6['end_balance']				= $item['end_balance'];
						$data6['created_at']				= $item['created_at'];

						array_push($Walletstatement , $data6);
				endforeach;


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

				$result = $Walletstatement;
				

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

}