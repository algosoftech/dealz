<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DueManagement extends CI_Controller {
	
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

			    $users_id 					= $this->input->get('users_id');

				$tblName 					=	'da_dueManagement';
				$shortField 				= 	array('due_management_id'=> -1 );
				// $whereCon['where']			=	array('created_user_id' => (int)$users_id,'record_type' => 'Debit',"recharge_type" => "Advanced Cash");
				$whereCon['where']			=	array('user_id_deb' => (int)$users_id);

				$DueManagement 				=	$this->geneal_model->duemanagementweb('multiple',$tblName,$whereCon,$shortField);

				foreach($DueManagement as $key => $items) {
					 
					 if($items['last_name'][0]):
					 	$DueManagement[$key]['last_name'] = $items['last_name'][0];
					 else:
					 	$DueManagement[$key]['last_name'] = '';
					 endif;

					 if($items['country_code'][0]):
					 	$DueManagement[$key]['country_code'] = $items['country_code'][0];
					 else:
					 	$DueManagement[$key]['country_code'] = '';
					 endif;


					 if($items['users_mobile'][0]):
					 	$DueManagement[$key]['users_mobile'] = $items['users_mobile'][0];
					 else:
					 	$DueManagement[$key]['users_mobile'] = '';
					 endif;
					 
					 if($items['users_email'][0]):
					 	$DueManagement[$key]['users_email'] = $items['users_email'][0];
					 else:
					 	$DueManagement[$key]['users_email'] = '';

					 endif;

					 if($items['store_name'][0]):
					 	$DueManagement[$key]['store_name'] = $items['store_name'][0];
					 else:
					 	$DueManagement[$key]['store_name'] = '';

					 endif;

				}

				if($DueManagement):
					$results = $DueManagement;
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
	 * * Function name : Walletstatement
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for  get Summary Report
	 * * Date : 31 March 2023
	 * * **********************************************************************/
	public function ViewDueManagement()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();

		if(requestAuthenticate(APIKEY,'GET')):

			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			else:

			    $users_id 					= $this->input->get('users_id');
			    $user_id_to 				= $this->input->get('user_id_to');

				$tblName 					=	'da_dueManagement';
				$shortField 				= 	array('due_management_id'=> -1 );
				$whereCon['where']			=	array('created_user_id' => (int)$users_id,'user_id_to' => (int)$user_id_to);

				$DueManagement 				=	$this->geneal_model->getData2('multiple',$tblName,$whereCon,$shortField);
				
				if($DueManagement):
					$results = $DueManagement;
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
	 * * Function name : duecollectcash
	 * * Developed By : Dilip Halder
	 * * Purpose  : This function used for  get due cash
	 * * Date :  20 March 2023
	 * * **********************************************************************/
	public function CollectDueCash()
	{
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP',logOutPut($_POST));
		$result 							= 	array();
		
		if(requestAuthenticate(APIKEY,'POST')):

			if( $this->input->get('users_id') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_EMPTY'),$result);
			elseif( $this->input->post('dueid') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('DUE_ID_EMPTY'),$result);
			elseif( $this->input->post('collect_cash') == ''): 
				echo outPut(0,lang('SUCCESS_CODE'),lang('COLLECT_DUE_CASH'),$result);
			else:

			   
				$due_management_id =  $this->input->post('dueid');


				// Getting current Due Details.
				$tblName   			=  "da_dueManagement";
				$whereCon1['where'] =   array('due_management_id'  => (int)$due_management_id );
				$shortField 		= 	array('due_management_id'=> -1 );
				$dueData 			= 	$this->geneal_model->getData2('single',$tblName,$whereCon1,$shortField);
			   
			   	$param['recharge_amt']				= 	(float)$dueData['recharge_amt'] + (float)$this->input->post('recharge_amt');
			   	$param['due_amount']					= 	(float)$dueData['due_amount'] - (float)$this->input->post('collect_cash') ;
			   
				   if($this->input->post('recharge_amt')):
				    $param['cash_collected']			= 	(float)$dueData['cash_collected'] + (float)$this->input->post('recharge_amt') ;
				   	$param['advanced_amount']			= 	(float)$dueData['advanced_amount'] - (float)$this->input->post('recharge_amt') ;
				   else:
				    $param['cash_collected']			= 	(float)$dueData['cash_collected'] + (float)$this->input->post('collect_cash') ;
				   	$param['advanced_amount']			= 	(float)$dueData['advanced_amount'];

				   endif;

					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->input->get('users_id');

					$data = $this->geneal_model->editData('da_dueManagement',$param,'due_management_id',(int)$due_management_id);

					$results = $data;
					echo outPut(1,lang('SUCCESS_CODE'),lang('SUCCESS_ACTION'),$results);	

			endif;
		else:
			echo outPut(0,lang('FORBIDDEN_CODE'),lang('FORBIDDEN_MSG'),$result);
		endif;
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
				if($DZL_USERID):
					$UsersType	 =	$this->common_model->getPaticularFieldByFields('users_type','da_users','users_id',(int)$DZL_USERID);
				endif;
				$fromDate    =  $this->input->get('fromDate');
				$toDate      =  $this->input->get('toDate');

				if($fromDate):
					$data['fromDate'] 		= date('Y-m-d 00:01' ,strtotime($fromDate));
					$whereCon['where_gte'] 	= array(array("created_at",$data['fromDate']));
				// else:
				// 	$data['fromDate'] 		= date('Y-m-d 00:01');
				// 	$whereCon['where_gte'] 	= array(array("created_at",$data['fromDate']));
				endif;

				if($toDate):
					$data['toDate'] 		= date('Y-m-d 23:59' ,strtotime($toDate));
					$whereCon['where_lte'] 	= array(array("created_at",$data['toDate']));
				// else:
				// 	$data['toDate'] 		= date('Y-m-d 23:59');
				// 	$whereCon['where_lte'] 	= array(array("created_at",$data['toDate']));
				endif;
				
				$whereCon['where']	 	    = array('user_id_deb' => (int)$DZL_USERID,'bind_person_id' => (string)$DZL_USERID );

				// Search by product/retailer details....
				$salesperson = $this->input->get('salesperson');
				if(empty($salesperson)):

					$tbl 			 =  'da_dueManagement';
				 	$shortField 	 =  array('created_at' => -1 );
					$DueManagement 	 =  $this->geneal_model->duemanagementweb('multiple',$tbl,$whereCon ,$shortField);
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
					$shortField 		 = 	array('created_at'=> -1 );
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

					// Todays total sales details.
					$whereCon['where_gte'] 		= 	array(array("created_at",date('Y-m-d 00:01')));
					$whereCon['where_lte'] 		= 	array(array("created_at",date('Y-m-d 23:59')));
					$tblName 			 		= 	'da_dueManagement';
					$shortField  				= 	array('due_management_id'=> -1 );
					$todayDueManagement 		=	$this->geneal_model->duemanagementweb('multiple',$tblName,$whereCon,$shortField);
					$todayTotalRecharge = 0;
					if($todayDueManagement):
						foreach ($todayDueManagement as $key => $item):
						 $todayTotalRecharge  = $todayTotalRecharge  + $item['recharge_amt'];
						 $totalCash_collected = $totalCash_collected + $item['cash_collected'];
						endforeach;
					endif;
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