<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {
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
		$data['page']			=	'About us';
	
		$tbl 					=	'da_cms';
		$where 					=	['page_name' => 'About us'];
		$data['about']			=	$this->geneal_model->getData($tbl, $where,[]);

		$this->load->view('about',$data);
		//This is staging
	}
}
