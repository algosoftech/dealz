<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cancellationpolicy extends CI_Controller {
public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model('geneal_model');
	} 
/***********************************************************************
** Function name 	: index
** Developed By 	: AFSAR AlI
** Purpose 			: This function used for index
** Date 			: 14 APRIL 2022
** Updated By		:
** Updated Date 	: 
************************************************************************/ 	
public function index()
{  
	$data 					=	array();
	$data['page']			=	'Cancellation Policy';

	$tbl 					=	'da_cms';
	$where 					=	['page_name' => 'Cancellation Policy', 'status'=> 'A'];
	$data['cancellation_policy']		=	$this->geneal_model->getData($tbl, $where,[]);
	if(!$data['cancellation_policy']){
		redirect('/');
	}
	//print_r($data);
	//die();

	$this->load->view('cancellationpolicy',$data);
}

}
