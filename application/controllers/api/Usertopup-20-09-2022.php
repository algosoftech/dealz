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
						$where1 			=	[ 'user_id_deb' => (int)$this->input->get('users_id'), "record_type" => "Credit", "arabian_points_from" => "Recharge" ];
						$tblName1 			=	'da_loadBalance';
						$order1 			=	array('_id'=> -1 );
						$userRechargeList 	=	$this->geneal_model->getData($tblName1, $where1, $order1);

						$result['userRechargeList'] 	=	$userRechargeList;
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
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
					if($userDetails['status'] == 'A'):

						/// check valid users
						$where1 			= 	[];
						if(is_numeric($this->input->post('recharge_user_email'))){ 
							if($userDetails['users_type'] == 'Sales Person'){
								$where1 	= 	['users_type'=>'Retailer','users_mobile'=>(int)$this->input->post('recharge_user_email'),'status'=>'A'];
							}elseif ($userDetails['users_type'] == 'Retailer') {
								$where1 	= 	['users_type'=>'Users','users_mobile'=>(int)$this->input->post('recharge_user_email'),'status'=>'A'];
							}
					    }else{ 
					    	if($userDetails['users_type'] == 'Sales Person'){
								$where1 	= 	['users_type'=>'Retailer','users_email'=>$this->input->post('recharge_user_email'),'status'=>'A'];
							}elseif ($userDetails['users_type'] == 'Retailer') {
								$where1 	= 	['users_type'=>'Users','users_email'=>$this->input->post('recharge_user_email'),'status'=>'A'];
							}
					    }
					    $checkUser = $this->geneal_model->getOnlyOneData('da_users',$where1);
					    if(!empty($checkUser)):
					    	
					    	/* Check sales person and retailser available points */
					        $whereCon2 				 =	['users_id' => (int)$this->input->get('users_id') ];
					        $availableArabianPoints  =  $this->geneal_model->getOnlyOneData('da_users',$whereCon2);
					        if($availableArabianPoints):
					        	if($this->input->post('recharge_amount') < $availableArabianPoints['availableArabianPoints']):
					        		
					        		// From user update arabian points
									$availableArabianPoints 	= 	((int)$availableArabianPoints['availableArabianPoints'] - (int)$this->input->post('recharge_amount'));
							        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
							        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->input->get('users_id'));

							        /* Load Balance Table -- from user*/
								    $fromuserparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
									$fromuserparam["user_id_cred"] 		=	(int)$this->input->get('users_id');
									$fromuserparam["user_id_deb"]		=	(int)0;
									$fromuserparam["user_id_to"]		=	(int)$checkUser["users_id"];
									$fromuserparam["arabian_points"] 	=	(float)$this->input->post('recharge_amount');
								    $fromuserparam["record_type"] 		=	'Debit';
								    $fromuserparam["arabian_points_from"]=	'Recharge';
								    $fromuserparam["creation_ip"] 		=	currentIp();
								    $fromuserparam["created_at"] 		=	date('Y-m-d H:i');
								    $fromuserparam["created_by"] 		=	(int)$this->input->get('users_id');
								    $fromuserparam["status"] 			=	"A";
								    
								    $this->geneal_model->addData('da_loadBalance', $fromuserparam);
								    /* End */

								    // To user update arabian points
									$updatedTAP 	= 	((int)$checkUser['totalArabianPoints'] + (int)$this->input->post('recharge_amount')); 
								    $updateAAP 		= 	((int)$checkUser['availableArabianPoints'] + (int)$this->input->post('recharge_amount'));
									$update_data 	= 	array('totalArabianPoints' =>	(float)$updatedTAP, 'availableArabianPoints'=>	(float)$updateAAP);
									$this->geneal_model->editData('da_users', $update_data, 'users_id', (int)$checkUser['users_id']);

							        /* Load Balance Table -- to user*/
								    $touserparam["load_balance_id"]		=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
									$touserparam["user_id_cred"] 		=	(int)$checkUser["users_id"];
									$touserparam["user_id_deb"]			=	(int)$this->input->get('users_id');
									$touserparam["arabian_points"] 		=	(float)$this->input->post('recharge_amount');
								    $touserparam["record_type"] 		=	'Credit';
								    $touserparam["arabian_points_from"] =	'Recharge';
								    $touserparam["creation_ip"] 		=	currentIp();
								    $touserparam["created_at"] 			=	date('Y-m-d H:i');
								    $touserparam["created_by"] 			=	(int)$this->input->get('users_id');
								    $touserparam["status"] 				=	"A";
								    
								    $this->geneal_model->addData('da_loadBalance', $touserparam);
								    /* End */
								    echo outPut(0,lang('SUCCESS_CODE'),lang('recharge_success'),$result);
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
			elseif($this->input->post('arabianPoints') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('RECHARGE_AMOUNT_EMPTY'),$result);
			//elseif($this->input->post('store') == ''): 
			//	echo outPut(0,lang('SUCCESS_CODE'),lang('STORE_EMPTY'),$result);
			elseif($this->input->post('password') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('PASSWORD_EMPTY'),$result);
			else:

				$where 			=	[ 'users_id' => (int)$this->input->get('users_id') ];
				$tblName 		=	'da_users';
				$userDetails 	=	$this->geneal_model->getOnlyOneData($tblName, $where);

				if(!empty($userDetails)):
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
						        	if($this->input->post('arabianPoints') < $availableArabianPoints['availableArabianPoints']):

						        		if($userDetails['users_type'] == 'Sales Person'){
											$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Retailer');
											$param["store_name"]		=	$this->input->post('store');
											$param["users_type"] 		=	'Retailer';
										}elseif ($userDetails['users_type'] == 'Retailer') {
											$param["users_seq_id"]		=	$this->geneal_model->getNextIdSequence('users_seq_id', 'Users');
											$param['referral_code']		=	strtoupper(uniqid(16));
											$param["users_type"] 		=	'Users';
										}	

										$param["users_id"]				=	(int)$this->geneal_model->getNextSequence('da_users');
										$param["users_name"]			=	$this->input->post('users_name');
									    $param["users_email"] 			=	$this->input->post('users_email');
									    $param["country_code"] 			=	$this->input->post('country_code');
									    $param["users_mobile"]			=	(int)$this->input->post('users_mobile');
									    $param["password"]				=	md5($this->input->post('password'));
									    $param["totalArabianPoints"]	=	(float)$this->input->post('arabianPoints');
									    $param["availableArabianPoints"]=	(float)$this->input->post('arabianPoints');
									    $param["creation_ip"] 			=	currentIp();
									    $param["created_at"] 			=	date('Y-m-d H:i');
									    $param["created_by"] 			=	(int)$this->input->get('users_id');
									    $param["status"] 				=	"A";

									    $isInsert 	=	$this->geneal_model->addData('da_users', $param);
									    if ($isInsert):
									    	
									    	// From user update arabian points
									    	$availableArabianPoints 		= 	((int)$availableArabianPoints['availableArabianPoints'] - (int)$this->input->post('arabianPoints'));
									        $updatefield        		= 	array( 'availableArabianPoints' => (float)$availableArabianPoints );
									        $this->geneal_model->editData('da_users', $updatefield, 'users_id', (int)$this->input->get('users_id'));

									        /* Load Balance Table -- from user*/
										    $fromuserparam["load_balance_id"]	=	(int)$this->geneal_model->getNextSequence('da_loadBalance');
											$fromuserparam["user_id_cred"] 		=	(int)$this->input->get('users_id');
											$fromuserparam["user_id_deb"]		=	(int)0;
											$fromuserparam["user_id_to"]		=	(int)$param["users_id"];
											$fromuserparam["arabian_points"] 	=	(float)$this->input->post('arabianPoints');
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
											$touserparam["arabian_points"] 		=	(float)$this->input->post('arabianPoints');
										    $touserparam["record_type"] 		=	'Credit';
										    $touserparam["arabian_points_from"] =	'Recharge';
										    $touserparam["creation_ip"] 		=	currentIp();
										    $touserparam["created_at"] 			=	date('Y-m-d H:i');
										    $touserparam["created_by"] 			=	(int)$this->input->get('users_id');
										    $touserparam["status"] 				=	"A";
										    
										    $this->geneal_model->addData('da_loadBalance', $touserparam);
										    /* End */
										    echo outPut(0,lang('SUCCESS_CODE'),lang('add_user_success'),$result);
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
					echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result);
				endif;
			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
	}
}