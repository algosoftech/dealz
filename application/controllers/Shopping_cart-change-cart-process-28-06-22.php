<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping_cart extends CI_Controller {
	public function  __construct() 
	{ 
		parent:: __construct();
		$this->load->model('geneal_model');
		$this->lang->load('statictext','front');
	} 
	/***********************************************************************
	** Function name 	: index
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for show to cart items
	** Date 			: 20 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function index()
	{  //echo '<pre>'; echo md5(generateToken()); print_r($this->cart->contents()); die;
		$data  = array();
		$data['page'] 			=	'Shopping Cart';
		if($this->session->userdata('DZL_USERID')){
			$data['cartItems'] 	= 	$this->cart->contents();
		}else{
			$data['cartItems'] 	= 	$this->cart->contents();
			$wcon['where']      =  array('stock'=> array('$ne'=> 0));
			$data['products']  	=	$this->geneal_model->getData2('multiple', 'da_products', $wcon, [], 3);
		}

		if(empty($data['cartItems'])){
			if($this->session->userdata('DZL_USERID')){
				$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID'));
			}
			//redirect('home');
		} else {
			if($this->session->userdata('DZL_USERID')){
				$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID'));
			}
			foreach ($data['cartItems']as $items) {
				$this->geneal_model->deleteData('da_cartItems', 'rowid', $items['rowid']);
				$Ctabledata = array(
								'user_id'	=>	$items['user_id'],
								'id'		=>	$items['id'],
								'name'		=>	$items['name'],
								'qty'		=>	$items['qty'],
								'price' 	=>	$items['price'],
								'other' 	=>	array(
													'image' 		=>	$items['other']['image'],
													'description' 	=>	$items['other']['description'],
													'aed'			=>	$items['other']['aed']
												),
								'is_donated'=>  $items['is_donated'],
								'rowid'		=>	$items['rowid'],
								'subtotal'	=>	$items['subtotal']
							);
				$Ctbl 		=	'da_cartItems';
				$this->geneal_model->addData($Ctbl, $Ctabledata);
			}
		}

		//echo '<pre>'; print_r($data); die;

		if($this->session->userdata('DZL_USERID')){
			$this->load->view('user_cart', $data);
		}else{
			$this->load->view('cart', $data);
		}
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: add
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for add product to cart
	** Date 			: 21 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function add()
	{  	
		$where = ['products_id' => (int)$this->input->post('product_id') ];
		$product_data = $this->geneal_model->getOnlyOneData('da_products', $where);
		$data = array(
		    'user_id'	=>	$this->session->userdata('DZL_USERID')?(int)$this->session->userdata('DZL_USERID'):(int)0,
			'id'		=>	$product_data['products_id'],
			'name'		=>	$product_data['title'],
			'qty'		=>	1,
			'price' 	=>	$product_data['adepoints'],
			'other' 	=>	array(
								'image' 		=>	$product_data['product_image'],
								'description' 	=>	$product_data['description'],
								'aed'			=>	$product_data['adepoints']
							),
			'is_donated'=>  'Y',
			'rowid'     =>  md5(generateToken()),
			'subtotal'  => (1*$product_data['adepoints'])
		);
		$this->cart->insert($data);
		$rows = count($this->cart->contents());
		echo $rows;
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: remove_items
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for remove product to cart
	** Date 			: 20 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function remove_items($id='')
	{  
		if($this->session->userdata('DZL_USERID')){
			$data = array('rowid'=>	$id,'qty'=>	0);
			$this->cart->update($data);
			$this->geneal_model->deleteData('da_cartItems', 'rowid', $id );
			redirect('user-cart');
		}else{
			$data = array('rowid'=>	$id,'qty'=>	0);
			$this->cart->update($data);
			$this->geneal_model->deleteData('da_cartItems', 'rowid', $id );
			redirect('shopping-cart');	
		}
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: addqty
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for all product qty
	** Date 			: 20 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function addqty()
	{  	
		$port_data 			=	explode('___', $_POST['data']);
		$aed 				=	(int)$port_data[0];
		$rowid 				=	$port_data[1];
		$actionType 		=	$port_data[2];
			
		foreach ($this->cart->contents() as $items) {
			if($items['rowid'] == $rowid){
				$getQty = $items['qty'];
				break;
			}
		}
		if($actionType == 'I'){ 
			$qty  = ++$getQty; 
		}else{ 
			$qty = ($getQty == 1) ? 1 : --$getQty;
		}
		$data = array('rowid'=>	$rowid,'qty'=>	$qty);
		$this->cart->update($data);
		$cartData[1] 			= $qty * $aed;	

		$totalAmt = 0;

		foreach ($this->cart->contents() as $items) {
			$totalAmt =  $totalAmt + $items['qty'] * $items['other']['aed'];
			$this->geneal_model->deleteData('da_cartItems', 'rowid', $items['rowid']);
			$Ctabledata = array(
							'user_id'	=>	$items['user_id'],
							'id'		=>	$items['id'],
							'name'		=>	$items['name'],
							'qty'		=>	$items['qty'],
							'price' 	=>	$items['price'],
							'other' 	=>	array(
												'image' 		=>	$items['other']['image'],
												'description' 	=>	$items['other']['description'],
												'aed'			=>	$items['other']['aed']
											),
							'is_donated'=>  $items['is_donated'],
							'rowid'		=>	$items['rowid'],
							'subtotal'	=>	$items['subtotal']
						);
			$Ctbl 		=	'da_cartItems';
			$this->geneal_model->addData($Ctbl, $Ctabledata);
		}
		$cartData[2] = $totalAmt;
		$cartData[3] = $totalAmt * 5/100;
		$cartData[4] = $cartData[2] + $cartData[3];

		echo number_format($cartData[1],2).'__'.number_format($cartData[2],2).'__'.number_format($cartData[3],2).'__'.number_format($cartData[4],2);
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: donate
	** Developed By 	: AFSAR AlI
	** Purpose 			: This function used for add donate tag in cart
	** Date 			: 18 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 
	public function donate(){
		$donate =	$_POST['donate'];
		$rowid 	=	$_POST['rowid'];

		$data = array('rowid'=>	$rowid,'is_donated'=> $donate);
		$this->cart->update($data);

		$CTdata = array( 'is_donated'=> $donate );
		$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $rowid);

		echo $donate;
	}// END OF FUNCTION

}
