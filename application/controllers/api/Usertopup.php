<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usertopup extends CI_Controller {
	
	var $postdata;
	var $user_agent;
	var $request_url; 
	var $method_name;
	
	public function  __construct() 	
	{ 
		parent:: __construct();
		error_reporting(E_ALL ^ E_NOTICE);  
		$this->load->model(array('sms_model','notification_model','emailtemplate_model'));
		$this->lang->load('statictext', 'api');
		$this->load->helper('apidata');
		$this->load->model(array('geneal_model','common_model'));

		$this->user_agent 		= 	$_SERVER['HTTP_USER_AGENT'];
		$this->request_url 		= 	$_SERVER['REDIRECT_URL'];
		$this->method_name 		= 	$_SERVER['REDIRECT_QUERY_STRING'];

		$this->load->library('generatelogs',array('type'=>'Usertopup'));
	} 

	/* * *********************************************************************
	 * * Function name : getRechargeHistory
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Recharge History
	 * * Date : 21 JUNE 2022
	 * * **********************************************************************/
	public function getRechargeHistory()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):
						//$where1 			=	[ 'user_id_deb' => (int)$this->input->get('users_id'), "record_type" => "Credit", "arabian_points_from" => "Recharge" ];
						//$where1 			=	[ 'created_user_id' => (int)$this->input->get('users_id'), "arabian_points_from" => "Recharge" ];
						$where1 			=	array('$or' => array( 
														array('user_id_cred' => (int)$this->input->get('users_id'), 'record_type' => 'Credit'), 
														array('user_id_deb' => (int)$this->input->get('users_id'),'record_type' => 'Debit'), 
														),
														'arabian_points_from' => "Recharge"
													);
						$tblName1 			=	'da_loadBalance';
						$order1 			=	array('_id'=> -1 );
						$userRechargeList 	=	$this->geneal_model->getData($tblName1, $where1, $order1);
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

					    		$userRechargeData['users_name'] 	=	$userData['users_name'];
					    		$userRechargeData['users_email'] 	=	$userData['users_email'];
					    		$userRechargeData['users_mobile'] 	=	$userData['users_mobile'];
					    		array_push($alluserrechargeList,$userRechargeData);
							endforeach;
							$result['userRechargeList'] 	=	$alluserrechargeList;
						else:
							$result['userRechargeList'] 	=	array();
						endif;
						echo outPut(1,lang('SUCCESS_CODE'),lang('get_recharge_history_success'),$result);
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
	 * * Function name : getRechargeHistoryByCondition
	 * * Developed By : Afsar Ali
	 * * Purpose  : This function used for get Recharge History By Condition
	 * * Date : 24 DEC 2022
	 * * **********************************************************************/
	public function getRechargeHistoryByCondition()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):
						if($this->input->get('fromdate')):
							$whereCon['where_lte'] 			= 	array(array("created_at",$this->input->get('fromdate')));
						endif;

						if($this->input->get('fromdate')):
							$whereCon['where_gte'] 			= 	array(array("created_at",$this->input->get('todate')));
						endif;

						if($this->input->get('record_type') == "Debit"):
							$whereCon['where'] 			=	[ 'created_user_id' => (int)$this->input->get('users_id'), "arabian_points_from" => "Recharge", "record_type" => "Debit" ];
						elseif($this->input->get('record_type') == "Credit"):

							$whereCon['where'] 			=	[ 'created_user_id' => (int)$this->input->get('users_id'), "arabian_points_from" => "Recharge", "record_type" => "Credit" ];
							
						else:

							//$whereCon['where'] 			=	[ 'created_user_id' => (int)$this->input->get('users_id'), "arabian_points_from" => "Recharge" ];
							$whereCon['where'] 			=	array('$or' => array( 
								array('user_id_cred' => (int)$this->input->get('users_id'), 'record_type' => 'Credit'), 
								array('user_id_deb' => (int)$this->input->get('users_id'),'record_type' => 'Debit'), 
								),
								'arabian_points_from' => "Recharge"
							);
							
						endif;

						$tblName1 			=	'da_loadBalance';
						//$order1 			=	array('_id'=> -1 );
						$shortField 						= 	array('created_at'=> -1);
						$userRechargeList 	=	$this->common_model->getDataByNewQuery('*','multiple',$tblName1,$whereCon,$shortField,$perPage,$page);
						//$userRechargeList 	=	$this->geneal_model->getData($tblName1, $where1, $order1);
						if($userRechargeList):
							$alluserrechargeList   			=	array();
							foreach($userRechargeList as $userRechargeData):
								if($userRechargeData['record_type'] == 'Credit'):
									$where 			= 	['users_id'=>(int)$userRechargeData['created_user_id']];
								else:
									$where 			= 	['users_id'=>(int)$userRechargeData['user_id_to']];
								endif;
								
					    		$userData 		= 	$this->geneal_model->getOnlyOneData('da_users', $where );

					    		$userRechargeData['users_name'] 	=	$userData['users_name'];
					    		$userRechargeData['users_email'] 	=	$userData['users_email'];
					    		$userRechargeData['users_mobile'] 	=	$userData['users_mobile'];
					    		array_push($alluserrechargeList,$userRechargeData);
							endforeach;
							$result['userRechargeList'] 	=	$alluserrechargeList;
						else:
							$result['userRechargeList'] 	=	array();
						endif;
						echo outPut(1,lang('SUCCESS_CODE'),lang('get_recharge_history_success'),$result);
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
	 * * Function name : rechargeToUser
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for recharge To User
	 * * Date : 21 JUNE 2022
	 * * **********************************************************************/
	public function rechargeToUser()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('recharge_user_email') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMAIL_EMPTY'),$result);
			elseif($this->input->post('recharge_amount') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('RECHARGE_AMOUNT_EMPTY'),$result);
			elseif($this->input->post('recharge_amount') < 5): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('RECHARGE_AMOUNT_ERROR'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						/// check valid users
						$where1 			= 	[];
						$recharge_user_email_type = 'MOBILE';
					    //New conditions
					    if(is_numeric($this->input->post('recharge_user_email'))){ 
							if(strlen($this->input->post('recharge_user_email')) >= 9){
								$where1['where'] 	= 	['users_mobile'=> (int)$this->input->post('recharge_user_email'),'status'=>'A'];
							}else{
								$where1['where'] 	= 	['users_mobile'=> (float)$this->input->post('recharge_user_email'),'status'=>'A'];
							}
					    }else{ 
							$where1['where'] 	= 	['users_email'=>strtolower($this->input->post('recharge_user_email')),'status'=>'A'];
							$recharge_user_email_type = "EMAIL";
					    }
						$checkUser = $this->geneal_model->getOnlyOneData('da_users',$where1['where']);
						
					    if(!empty($checkUser)):
					    	if((int)$checkUser['users_id'] != (int)$this->input->get('users_id')):
						    	/* Check sales person and retailser available points */
						        $whereCon2 				 =	['users_id' => (int)$this->input->get('users_id') ];
						        $user_data  =  $this->geneal_model->getOnlyOneData('da_users',$whereCon2);
						        if($user_data):


							        	if($this->input->post('percentage')):
							        		$rechargeAmount 			=	(int)$this->input->post('recharge_amount');
											$percent 					=	$this->input->post('percentage');
											$percentAmt					=	$rechargeAmount*$percent/100;
											$totalRechargeAmount		=	$rechargeAmount + $percentAmt;
							        	else:
							        		$totalRechargeAmount		=	(int)$this->input->post('recharge_amount');
							        	endif;

							        	if($totalRechargeAmount < $user_data['availableArabianPoints']):
						        		
						        		// From user update arabian points
										//$availableArabianPoints 	= 	((int)$user_data['availableArabianPoints'] - (int)$this->input->post('recharge_amount'));
										if($this->input->post('percentage')):
											$rechargeAmount 			=	(int)$this->input->post('recharge_amount');
											$percent 					=	$this->input->post('percentage');
											$percentAmt					=	$rechargeAmount*$percent/100;
											$totalRechargeAmount		=	$rechargeAmount + $percentAmt;
											$availableArabianPoints 	= 	((int)$user_data['availableArabianPoints'] - $totalRechargeAmount);
											$rechargeDetails 			=	array(
																				'percentage'	=>	(float)$percent,
																				'amount'		=>	(float)$percentAmt,
																				);
										else:
											$availableArabianPoints 	= 	((float)$user_data['availableArabianPoints'] - (float)$this->input->post('recharge_amount'));
											$totalRechargeAmount = (float)$this->input->post('recharge_amount');
										endif;
								        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
								        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->input->get('users_id'));

								        /* Load Balance Table -- from user*/
									    $fromuserparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
										$fromuserparam["user_id_cred"] 		=	(int)0;
										$fromuserparam["user_id_deb"]		=	(int)$this->input->get('users_id');
										$fromuserparam["user_id_to"]		=	(int)$checkUser["users_id"];
										$fromuserparam["arabian_points"] 	=	(float)$this->input->post('recharge_amount');
										$fromuserparam["sum_arabian_points"]	=	(float)$totalRechargeAmount;
										$fromuserparam['availableArabianPoints']	=   (float)$user_data['availableArabianPoints'];
										$fromuserparam["end_balance"] 				=	(float)$user_data["availableArabianPoints"] - (float)$totalRechargeAmount ;
									    $fromuserparam["record_type"] 		=	'Debit';
									    $fromuserparam["arabian_points_from"]=	'Recharge';
										if($rechargeDetails):
											$fromuserparam["rechargeDetails"]=	$rechargeDetails;
										endif;
									    $fromuserparam["creation_ip"] 		=	currentIp();
									    $fromuserparam["created_at"] 		=	date('Y-m-d H:i');
									    $fromuserparam["created_by"] 		=	$checkUser['users_type'];
										$fromuserparam["created_user_id"] 	=	(int)$this->input->get('users_id');
									    $fromuserparam["status"] 			=	"A";
									    
									    $this->geneal_model->addData('da_loadBalance', $fromuserparam);
									    /* End */
										// Send creadited notification 
										if($checkUser["device_id"]):
											$data 		=	array(
																'arabianpoint'	=>	(int)$fromuserparam["arabian_points"],
																'name'			=>	$user_data['users_name'],
																'user_id'		=>	$checkUser["users_id"],
																'device_id'		=>	$checkUser["device_id"]
																);
											$rtn = $this->notification_model->rceivedArabianPointNotification($data);
										endif;
										//END
									    // To user update arabian points
										// $updatedTAP 	= 	((int)$checkUser['totalArabianPoints'] + (int)$this->input->post('recharge_amount')); 
									    // $updateAAP 		= 	((int)$checkUser['availableArabianPoints'] + (int)$this->input->post('recharge_amount'));
										if($this->input->post('percentage')):
											$rechargeAmount2 			=	(int)$this->input->post('recharge_amount');
											$percent2 					=	$this->input->post('percentage');
											$percentAmt2				=	$rechargeAmount2*$percent2/100;
											$totalRechargeAmount2		=	$rechargeAmount2 + $percentAmt2;
											$updatedTAP 				= 	((int)$checkUser['totalArabianPoints'] + $totalRechargeAmount2); 
											$updateAAP 					= 	((int)$checkUser['availableArabianPoints'] + $totalRechargeAmount2);
											$rechargeDetails2 			=	array(
																				'percentage'	=>	(float)$percent,
																				'amount'		=>	(float)$percentAmt2,
																				);
										else:
											$updatedTAP 	= 	((float)$checkUser['totalArabianPoints'] + (float)$this->input->post('recharge_amount')); 
											$updateAAP 		= 	((float)$checkUser['availableArabianPoints'] + (float)$this->input->post('recharge_amount'));
											$totalRechargeAmount2 = (int)$this->input->post('recharge_amount');
										endif;
										$update_data 	= 	array('totalArabianPoints' =>	(float)$updatedTAP, 'availableArabianPoints'=>	(float)$updateAAP);
										$this->geneal_model->editData('da_users', $update_data, 'users_id', (int)$checkUser['users_id']);

								        /* Load Balance Table -- to user*/
									    $touserparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
										$touserparam["user_id_cred"] 		=	(int)$checkUser["users_id"];
										$touserparam["user_id_deb"]			=	(int)0;
										$touserparam["arabian_points"] 		=	(float)$this->input->post('recharge_amount');
										$touserparam["sum_arabian_points"]	=	(float)$totalRechargeAmount2;
										$touserparam['availableArabianPoints']	=   (float)$checkUser['availableArabianPoints'];
										$touserparam["end_balance"] 				=	(float)$checkUser["availableArabianPoints"] + (float)$totalRechargeAmount2 ;
									    $touserparam["record_type"] 		=	'Credit';
									    $touserparam["arabian_points_from"] =	'Recharge';
										if($rechargeDetails2):
											$touserparam["rechargeDetails"]=	$rechargeDetails2;
										endif;
									    $touserparam["creation_ip"] 		=	currentIp();
									    $touserparam["created_at"] 			=	date('Y-m-d H:i');
										$touserparam["created_by"] 			=	$checkUser['users_type'];
										$touserparam["created_user_id"] 	=	(int)$this->input->get('users_id');
									    $touserparam["status"] 				=	"A";
									    
									    $this->geneal_model->addData('da_loadBalance', $touserparam);
									    /* End */

									    $whereSelesPerson = array('users_id' => (int)$this->input->get('users_id'));
										$tblName 		=	'da_users';
										$sellerDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $whereSelesPerson);


									    $dueparam["due_management_id"]	=	(int)$this->geneal_model->getNextSequence('da_dueManagement');
		    
								    	$dueparam["mobile"]				=	(int)$checkUser['users_mobile'];
								    	$dueparam["email"]				=	$checkUser['users_email'];
									    $dueparam["recharge_amt"]		=	(float)$this->input->post('recharge_amount');
									    $dueparam["cash_collected"]		=	(float)$this->input->post('cash_collected');
									    
										$CashCollected = (float)$this->input->post('recharge_amount') -(float)$this->input->post('cash_collected');

									    if($CashCollected > 0):
									    	$dueparam["due_amount"]			=	(float)$CashCollected;
									    else:
									    	$dueparam["due_amount"]	=	(float)0;
									    endif;

									     if($CashCollected < 0):
									    	$dueparam["advanced_amount"]	=	abs($CashCollected);
									    else:
									    	$dueparam["advanced_amount"]	=	abs(0);
									    endif;

										$dueparam["user_id_deb"]		=	(int)$this->input->get('users_id');
										$dueparam["user_id_to"]			=	(int)$checkUser["users_id"];
									   	$dueparam["record_type"] 		=	'Debit';
									    
									    if($sellerDetails["users_type"] == "Sales Person"):
							    		  $dueparam["recharge_type"] 	=	'Advanced Cash';
									    else:
							    		  $dueparam["recharge_type"] 	=	'Direct Cash';
									    endif;
									    $dueparam['recharge_from'] 		=    'App';
									    $dueparam["creation_ip"] 		=	$this->input->ip_address();
									    $dueparam["created_by"] 		=	$sellerDetails["users_type"];
										$dueparam["created_user_id"] 	=	(int)$this->input->get('users_id');
									    $dueparam["created_at"] 		=	date('Y-m-d H:i');
									    $dueparam["status"] 			=	"A";
									    
									    $this->geneal_model->addData('da_dueManagement', $dueparam);


										//Send debited notification 
										if($user_data["device_id"]):
											$Sdata 		=	array(
																'arabianpoint'	=>	(int)$touserparam["sum_arabian_points"],
																'name'			=>	$checkUser["users_name"],
																'user_id'		=>	$user_data["users_id"],
																'device_id'		=>	$user_data["device_id"]
																);
											$rtn = $this->notification_model->sentArabianPointNotification($Sdata);
										endif;
										/* End */
									    echo outPut(1,lang('SUCCESS_CODE'),lang('recharge_success'),$result);
						        	else:
						        		echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);
						        	endif;
						        else:
						        	echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);
						        endif;
					        else:
						    	echo outPut(0,lang('SUCCESS_CODE'),lang('EMAIL_INCORRECT'),$result);
						    endif;
					    else:
							if($recharge_user_email_type == 'MOBILE'):
								echo outPut(0,lang('SUCCESS_CODE'),lang('RECHARGE_PHONE_ERROR'),$result);
							else:
								echo outPut(0,lang('SUCCESS_CODE'),lang('RECHARGE_EMAIL_ERROR'),$result);
							endif;
					    endif;
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
	 * * Function name : bindedPersonList
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for get Users
	 * * Date : 19 Apirl 2023
	 * * **********************************************************************/
	public function bindedPersonList()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:
				
				$whereCon33['where']	= 	array('bind_person_id'=> $this->input->get('users_id'));
				$tblName 				=	'da_users';
				$shortField 			= 	array('_id'=> -1 );
				
				$bind_person_list		=	$this->geneal_model->getData2('multiple',$tblName,$whereCon33,$shortField);

				if(!empty($bind_person_list)):
					 
					 $result['bind_person_list'] 	=	$bind_person_list;
					echo outPut(1,lang('SUCCESS_CODE'),lang('get_user_success'),$result);
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('LIST_NOT_FOUND'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}

	/* * *********************************************************************
	 * * Function name : getUsers
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for get Users
	 * * Date : 21 JUNE 2022
	 * * **********************************************************************/
	public function getUsers()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'GET')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):
						$where1 			=	[ 'created_by' => (int)$this->input->get('users_id') ];
						$tblName1 			=	'da_users';
						$order1 			=	array('users_id'=> -1 );
						$userList 			=	$this->geneal_model->getData($tblName1, $where1, $order1);

						$result['userList'] 	=	$userList;
						echo outPut(1,lang('SUCCESS_CODE'),lang('get_user_success'),$result);
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
	 * * Function name : addUsers
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add User
	 * * Date : 21 JUNE 2022
	 * * **********************************************************************/
	public function addUsers()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();	
		if(requestAuthenticate(APIKEY,'POST')):
			
			if($this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif($this->input->post('users_name') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('NAME_EMPTY'),$result);
			elseif($this->input->post('users_email') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('EMAIL_EMPTY'),$result);
			elseif($this->input->post('country_code') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COUNTRY_CODE_EMPTY'),$result);
			elseif($this->input->post('users_mobile') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PHONE_EMPTY'),$result);
			// elseif($this->input->post('arabianPoints') == ''): 
			// 	echo outPut(0,lang('SUCCESS_CODE'),lang('RECHARGE_AMOUNT_EMPTY'),$result);
			elseif($this->input->post('password') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PASSWORD_EMPTY'),$result);
			else:
				$error 			=	'NO';
				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);
				if($this->input->post('arabianPoints') == ''):
					$post_arabian_point = 0;
				else:
					$post_arabian_point = $this->input->post('arabianPoints');
				endif;
				if(!empty($userDetails)):
					if($userDetails['users_type'] == 'Sales Person' && $this->input->post('users_type') == ''):
						$error 		=	'USER_TYPE_EMPTY';
					endif;
	
					if(!empty($userDetails) && $userDetails['users_type'] == 'Freelancer' && $this->input->post('users_type') == ''):
						$error 		=	'USER_TYPE_EMPTY';
					endif;
				else:
					$error = "USER_ID_INCORRECT";
				endif;			

				if($error = 'NO'):
					if($userDetails['status'] == 'A'):

						$where1 = ['users_email' => $this->input->post('users_email') ];
					    $query1 = $this->geneal_model->checkDuplicate('da_users',$where1);
					    if (empty($query1)):
					    	$where2 = ['users_mobile' => (int)$this->input->post('users_mobile') ];
						    $query2 = $this->geneal_model->checkDuplicate('da_users',$where2);
						    if (empty($query2)):

						    	/* Check sales person and retailser available points */
						        $whereCon2 				 =	['users_id' => (int)$this->input->get('users_id') ];
						        $availableArabianPoints  =  $this->geneal_model->getOnlyOneData('da_users',$whereCon2);
						        if($availableArabianPoints):
						        	if($post_arabian_point < $availableArabianPoints['availableArabianPoints']):

						        		if($userDetails['users_type'] == 'Sales Person'){
											if($this->input->post('users_type') == 'Retailer'){
												$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Retailer');
												$param["store_name"]		=	$this->input->post('store');
												$param["users_type"] 		=	'Retailer';
											}elseif($this->input->post('users_type') == 'Freelancer'){
												$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Freelancer');
												$param['referral_code']		=	strtoupper(uniqid(16));
												$param["users_type"] 		=	'Freelancer';
											}else{
												$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Users');
												$param['referral_code']		=	strtoupper(uniqid(16));
												$param["users_type"] 		=	'Users';
											}
										}elseif ($userDetails['users_type'] == 'Retailer') {
											$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Users');
											$param['referral_code']		=	strtoupper(uniqid(16));
											$param["users_type"] 		=	'Users';
										}elseif ($userDetails['users_type'] == 'Freelancer') {
											if($this->input->post('users_type') == 'Retailer'){
												$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Retailer');
												$param["store_name"]		=	$this->input->post('store');
												$param["users_type"] 		=	'Retailer';
											}else{
												$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Users');
												$param['referral_code']		=	strtoupper(uniqid(16));
												$param["users_type"] 		=	'Users';
											}
										}	
										$param["users_id"]				=	(int)$this->geneal_model->getNextSequence('da_users');
										$param["users_name"]			=	$this->input->post('users_name');
									    $param["users_email"] 			=	$this->input->post('users_email');
									    $param["country_code"] 			=	$this->input->post('country_code');
									    $param["users_mobile"]			=	(int)$this->input->post('users_mobile');
									    $param["password"]				=	md5($this->input->post('password'));
									    $param["totalArabianPoints"]	=	(float)$post_arabian_point;
									    $param["availableArabianPoints"]=	(float)$post_arabian_point;
									    $param["creation_ip"] 			=	currentIp();
									    $param["created_at"] 			=	date('Y-m-d H:i');
									    $param["created_by"] 			=	(int)$this->input->get('users_id');
									    $param["status"] 				=	"A";
										$param['bind_person_id']		=	(int)$userDetails['users_id'];
										$param['bind_person_name']		=	$userDetails['users_name'];
										$param['bind_user_type']		=	$userDetails['users_type'];

									    $isInsert 	=	$this->geneal_model->addData('da_users', $param);
									    if ($isInsert):
									    	
									    	// From user update arabian points
									    	$availableArabianPoints 		= 	((int)$availableArabianPoints['availableArabianPoints'] - (int)$post_arabian_point);
									        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
									        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->input->get('users_id'));
											if($post_arabian_point > 0){
												/* Load Balance Table -- from user*/
												$fromuserparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
												$fromuserparam["user_id_cred"] 		=	(int)$this->input->get('users_id');
												$fromuserparam["user_id_deb"]		=	(int)0;
												$fromuserparam["user_id_to"]		=	(int)$param["users_id"];
												$fromuserparam["arabian_points"] 	=	(float)$post_arabian_point;
												$fromuserparam["record_type"] 		=	'Debit';
												$fromuserparam["arabian_points_from"]=	'Recharge';
												$fromuserparam["creation_ip"] 		=	currentIp();
												$fromuserparam["created_at"] 		=	date('Y-m-d H:i');
												$fromuserparam["created_by"] 		=	(int)$this->input->get('users_id');
												$fromuserparam["status"] 			=	"A";
												
												$this->geneal_model->addData('da_loadBalance', $fromuserparam);
												/* End */

												/* Load Balance Table -- to user*/
												$touserparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
												$touserparam["user_id_cred"] 		=	(int)$param["users_id"];
												$touserparam["user_id_deb"]			=	(int)$this->input->get('users_id');
												$touserparam["arabian_points"] 		=	(float)$post_arabian_point;
												$touserparam["record_type"] 		=	'Credit';
												$touserparam["arabian_points_from"] =	'Recharge';
												$touserparam["creation_ip"] 		=	currentIp();
												$touserparam["created_at"] 			=	date('Y-m-d H:i');
												$touserparam["created_by"] 			=	(int)$this->input->get('users_id');
												$touserparam["status"] 				=	"A";
												
												$this->geneal_model->addData('da_loadBalance', $touserparam);
												/* End */
											}
										    echo outPut(1,lang('SUCCESS_CODE'),lang('add_user_success'),$result);
									    else:
							        		echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);
							        	endif;
						        	else:
						        		echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);
						        	endif;
						        else:
						        	echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);
						        endif;
						    else:
						    	echo outPut(0,lang('SUCCESS_CODE'),lang('PHONE_ALREADY_EXIST'),$result);
						    endif;
					    else:	
					    	echo outPut(0,lang('SUCCESS_CODE'),lang('EMAIL_ALREADY_EXIST'),$result);
					    endif;
					else:
						echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);
					endif;
				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang($error),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
}