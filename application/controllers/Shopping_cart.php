<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping_cart extends CI_Controller {
	public function  __construct() 
	{ 
		parent:: __construct();
		error_reporting(0);
		$this->load->model('geneal_model');
		$this->lang->load('statictext','front');
	} 
	/***********************************************************************
	** Function name 	: index
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for show to cart items
	** Date 			: 20 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function index()
	{  	
		$data  = array();
		$data['page'] 			=	'Shopping Cart';
		
		if($this->session->userdata('DZL_USERID')):
			$CTwhere['where'] 	= [ 'user_id'=>(int)$this->session->userdata('DZL_USERID') ];
			$currentUserId  	= (int)$this->session->userdata('DZL_USERID');
		else:
			$CTwhere['where'] 	= [ 'currsystem_id'=> currentSystemId() ];
			$currentUserId  	= (int)0;
		endif;
		$data['cartItems']  	=	$this->geneal_model->getData2('multiple', 'da_cartItems', $CTwhere, []);

		if(empty($data['cartItems'])):
			$data['cartItems'] 	= 	$this->cart->contents();
			if(empty($data['cartItems'])):
				if($this->session->userdata('DZL_USERID')):
					$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID'));
				else:
					$this->geneal_model->deleteData('da_cartItems', 'currsystem_id', currentSystemId());
				endif;
			else:
				if($this->session->userdata('DZL_USERID')):
					$this->geneal_model->deleteData('da_cartItems', 'user_id', (int)$this->session->userdata('DZL_USERID'));
				else:
					$this->geneal_model->deleteData('da_cartItems', 'currsystem_id', currentSystemId());
				endif;
				foreach ($data['cartItems'] as $items):
					if($this->checkActiveProduct($items['id'])):
						$this->geneal_model->deleteData('da_cartItems', 'rowid', $items['rowid']);
						$userId 	= $items['user_id']==0?$currentUserId:$items['user_id'];
						$Ctabledata = array(
										'user_id'	=>	(int)$userId,//$items['user_id'],
										'id'		=>	(int)$items['id'],
										'currsystem_id'	=> currentSystemId(),
										'name'		=>	$items['name'],
										'qty'		=>	(int)$items['qty'],
										'price' 	=>	$items['price'],
										'other' 	=>	array(
															'image' 		=>	$items['other']['image'],
															'description' 	=>	$items['other']['description'],
															'aed'			=>	$items['other']['aed']
														),
										'is_donated'=>  $items['is_donated'],
										'create_at'=>  $items['create_at'],
										'current_ip'=>  $items['current_ip'],
										'rowid'		=>	$items['rowid'],
										'subtotal'	=>	$items['subtotal'],
										'curprodrowid' => $items['curprodrowid']
									);
						if($items['color']):
							$Ctabledata['color']		=	$items['color'];
						endif;
						if($items['size']):
							$Ctabledata['size']			=	$items['size'];
						endif;

						$CPOwhere['where'] 	= 	[ 'curprodrowid'=> $items['curprodrowid'] ];
						$checkPlaceOrder  	=	$this->geneal_model->getData2('single', 'da_orders_details', $CPOwhere, []);
						if($checkPlaceOrder):
							$deleteCurrItemData = array('rowid'=>	$items['rowid'],'qty'=>	0);
							$this->cart->update($deleteCurrItemData);
						else:
							$updateCurrItemData = array('rowid'=>	$items['rowid'],'user_id'=> $userId);
							$this->cart->update($updateCurrItemData);
							$this->geneal_model->addData('da_cartItems', $Ctabledata);
						endif;
					else:
						$deleteCurrItemData = array('rowid'=>	$items['rowid'],'qty'=>	0);
						$this->cart->update($deleteCurrItemData);
					endif;
				endforeach;
			endif;
		else:  
			//destrop CI cart and set data to cart from cart table
			$this->cart->destroy();
			foreach ($data['cartItems'] as $items):
				$userId 	= $items['user_id']==0?$currentUserId:$items['user_id'];
				$Ctabledata = array(
							    'user_id'	=>	(int)$userId,//$items['user_id'],
								'id'		=>	(int)$items['id'],
								'currsystem_id'=> currentSystemId(),
								'name'		=>	$items['name'],
								'qty'		=>	(int)$items['qty'],
								'price' 	=>	$items['price'],
								'other' 	=>	array(
													'image' 		=>	$items['other']->image,
													'description' 	=>	$items['other']->description,
													'aed'			=>	$items['other']->aed
												),
								'is_donated'=>  $items['is_donated'],
								'create_at'=>  $items['create_at'],
								'current_ip'=>  $items['current_ip'],
								'subtotal'  =>  $items['subtotal'],
								'curprodrowid' => $items['curprodrowid']
							);
				if($items['color']):
					$Ctabledata['color']		=	$items['color'];
				endif;
				if($items['size']):
					$Ctabledata['size']			=	$items['size'];
				endif;
				$this->cart->insert($Ctabledata);
			endforeach;
			if($this->cart->contents()):
				foreach ($this->cart->contents() as $CTitems):	
					if($this->checkActiveProduct($CTitems['id'])):
						// //update rowid to cart table
						$CTTdata = array( 'rowid'=> $CTitems['rowid'], 'user_id'=> $userId);
						$this->geneal_model->editData('da_cartItems', $CTTdata, 'curprodrowid', $CTitems['curprodrowid']);
					else:
						$deleteCurrItemData = array('rowid'=>$CTitems['rowid'],'qty'=>	0);
						$this->cart->update($deleteCurrItemData);
						$this->geneal_model->deleteData('da_cartItems', 'curprodrowid', $CTitems['curprodrowid']);
					endif;
				endforeach;
			endif;
		endif; 

		$tbl 					=	'da_products';
	/*$wcon['where']          =  	array( 'stock'=> array('$ne'=> 0,),
									  'clossingSoon' => 'N',
									  'status' => 'A'	
									);*/
	$wcon['where']          =  	array( 'stock'=> array('$gt'=> 0,),
									  'clossingSoon' => 'N',
									  'status' => 'A',
									  'remarks'=> array('$ne' => 'lotto-products')		
									);
	$shortField 					=	['seq_order' => 1];

	$ProductList		=	$this->geneal_model->getProductWithPrizeDetails('multiple', $tbl, $wcon, $shortField);

	// Campaign filtering by validdate and drawdate wise.
	if($ProductList):

		$ourCampaigns = [];
		foreach($ProductList as $info2):
			$valid2 			= 	$info2['validuptodate'].' '.$info2['validuptotime'].':0';
			$drawDate2 			= 	$info2['draw_date'].' '.$info2['draw_time'].':0';
			$today2 			= 	date('Y-m-d H:i:s');

			if(strtotime($valid2) > strtotime($today2) && strtotime($drawDate2) > strtotime($today2)):
			
				array_push($ourCampaigns,$info2);

			endif;
		endforeach;
	endif;
	
	$data['products'] = $ourCampaigns;

		$useragent=$_SERVER['HTTP_USER_AGENT'];

		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))):
			$this->load->view('mobile-cart',$data);
		else:

			if($this->session->userdata('DZL_USERID')){
				$data['level_details'] = 'hide'; 
				$this->load->view('user_cart', $data);
			}else{
				$this->load->view('cart', $data);
			}
		endif;
		
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: add
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for add product to cart
	** Date 			: 21 APRIL 2022
	** Updated By		: Afsar Ali
	** Updated Date 	: 12-12-2022
	************************************************************************/ 	
	public function add()
	{  	
		$where = ['products_id' => (int)$this->input->post('product_id') ];
		$product_data = $this->geneal_model->getOnlyOneData('da_products', $where);
		
		$product_name 				= 	str_replace('/', '', $product_data['title']);
		$product_name 				= 	str_replace("'", '___', $product_name);

		$cartdata['user_id']		=	$this->session->userdata('DZL_USERID')?(int)$this->session->userdata('DZL_USERID'):(int)0;
		$cartdata['id']				=	(int)$product_data['products_id'];
		$cartdata['currsystem_id']	=	currentSystemId();
		$cartdata['name']			=	 $product_name;
		$cartdata['qty']			=	1;
		$cartdata['price']			=	$product_data['adepoints'];
		if($this->input->post('fcolor')):
			$cartdata['color']		=	$this->input->post('fcolor');
		endif;

		if($this->input->post('fsize')):
			$cartdata['size']		=	$this->input->post('fsize');
		endif;

		$cartdata['other']		=	array(
										'image' 		=>	$product_data['product_image'],
										'description' 	=>	$product_data['description'],
										'aed'			=>	$product_data['adepoints']
									);
		$cartdata['is_donated']	=	'N';
		$cartdata['current_ip']	=	currentIp();
		$cartdata['create_at']	=	(int)$this->timezone->utc_time();//currentDateTime();
		$cartdata['rowid']		=	md5(generateToken());
		$cartdata['subtotal']	=	(1*$product_data['adepoints']);
		$cartdata['curprodrowid']	=	md5(generateToken());

		if($this->checkProductInCart($product_data['products_id']) == 'No'):
			$this->cart->insert($cartdata);
			$cartItems 	= 	$this->cart->contents();

			if($cartItems):
				foreach ($cartItems as $items):
					if($items['id'] == $product_data['products_id']):
						if($this->session->userdata('DZL_USERID')):
							$CTwhere['where'] 	= [ 'user_id'=>(int)$this->session->userdata('DZL_USERID'), 'id' =>(int)$product_data['products_id'] ];
						else:
							$CTwhere['where'] 	= [ 'currsystem_id'=> currentSystemId(), 'id' =>(int)$product_data['products_id'] ];
						endif;
						$cartTableProductData = $this->geneal_model->getOnlyOneData('da_cartItems', $CTwhere);
						if($cartTableProductData):
							//$currentQty    				=	((int)$cartTableProductData['qty']+(int)1);
							//$currentSubToral  			=	($currentQty*$product_data['adepoints']);
							
							//$CTdata['qty']				=	$currentQty;
							//$CTdata['subtotal']			=	$currentSubToral;

							$CTdata['rowid']			=	$items['rowid'];
							if($this->input->post('fcolor')) :
								$CTdata['color']		=	$this->input->post('fcolor');
							endif;
							if($this->input->post('fsize')) :
								$CTdata['size']			=	$this->input->post('fsize');
							endif;		
				
							//$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $cartTableProductData['rowid']);

							$updateWhere = [ 'user_id'=>(int)$this->session->userdata('DZL_USERID'), 'id' =>(int)$product_data['products_id'] ];
							$this->geneal_model->editSingleData('da_cartItems', $CTdata, $updateWhere);
						else:
							$cartdata['rowid']			=	$items['rowid'];
							$this->geneal_model->addData('da_cartItems', $cartdata);
						endif;
					endif;
				endforeach;
			endif;
		else:
			$cartItems 	= 	$this->cart->contents();
		endif;
		$rows = count($this->cart->contents());
		echo $rows;
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: add
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for add product to cart
	** Date 			: 21 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function add_from_wishlist()
	{  	
		$where = ['products_id' => (int)$this->input->post('product_id') ];
		$product_data = $this->geneal_model->getOnlyOneData('da_products', $where);
		$cartdata = array(
		    'user_id'	=>	$this->session->userdata('DZL_USERID')?(int)$this->session->userdata('DZL_USERID'):(int)0,
			'id'		=>	(int)$product_data['products_id'],
			'name'		=>	$product_data['title'],
			'qty'		=>	1,
			'price' 	=>	$product_data['adepoints'],
			'other' 	=>	array(
								'image' 		=>	$product_data['product_image'],
								'description' 	=>	$product_data['description'],
								'aed'			=>	$product_data['adepoints']
							),
			'is_donated'=>  'N',
			'current_ip'=>  currentIp(),
			'rowid'     =>  md5(generateToken()),
			'subtotal'  => (1*$product_data['adepoints']),
			'curprodrowid' => md5(generateToken())
		);

		if($this->session->userdata('DZL_USERID')):
			$CTwhere = [ 'user_id'=>(int)$this->session->userdata('DZL_USERID'), 'id' =>(int)$product_data['products_id'] ];
		else:
			$CTwhere = [ 'current_ip'=>currentIp(), 'id' =>(int)$product_data['products_id'] ];
		endif;
		$cartTableProductData = $this->geneal_model->getOnlyOneData('da_cartItems', $CTwhere);
		if($cartTableProductData):
			$currentQty    		=	($cartTableProductData['qty']+1);
			$currentSubToral  	=	($currentQty*$product_data['adepoints']);
			$CTdata = array('qty'=>$currentQty, 'subtotal'=>$currentSubToral);
			$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $cartTableProductData['rowid']);
		else:
			$this->geneal_model->addData('da_cartItems', $cartdata);
		endif;

		//Remove from wishlist
		$deleteWhere  = array('users_id'=>(int)$this->session->userdata('DZL_USERID'),'product_id'=>(int)$this->input->post('product_id'));
		$isDelete = $this->geneal_model->deleteDataByCondition('da_wishlist', $deleteWhere);

		$this->cart->insert($cartdata);
		$rows = count($this->cart->contents());
		echo $rows;
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: remove_items
	** Developed By 	: MANOJ KUMAR
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
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for all product qty
	** Date 			: 20 APRIL 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 	
	public function addqty($data='')
	{  	
		// echo $data;die();
		if($data != ''){
			$port_data 			=	explode('___', $data);	
		} else{
			$port_data 			=	explode('___', $_POST['data']);
		}
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
			if($items['rowid'] == $rowid):
				//update quantity to cart table
				$CTdata = array( 'qty'=> (int)$qty, 'subtotal'=> $items['subtotal'] );
				$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $rowid);
			endif;
		}
		$cartData[2] = $totalAmt;
		$cartData[3] = 0;//$totalAmt * 5/100;
		$cartData[4] = $cartData[2] - $cartData[3];

		echo number_format($cartData[1],2).'__'.number_format($cartData[2],2).'__'.number_format($cartData[3],2).'__'.number_format($cartData[4],2);
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: donate
	** Developed By 	: MANOJ KUMAR
	** Purpose 			: This function used for add donate tag in cart
	** Date 			: 18 MAY 2022
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 
	public function donate(){
		
		if($_GET):
			$donate =	$_GET['donate'];
			$rowid 	=	$_GET['rowid'];
		else:
			$donate =	$_POST['donate'];
			$rowid 	=	$_POST['rowid'];
		endif;

		$data = array('rowid'=>	$rowid,'is_donated'=> $donate);
		$this->cart->update($data);

		$CTdata = array( 'is_donated'=> $donate );
		$this->geneal_model->editData('da_cartItems', $CTdata, 'rowid', $rowid);

		echo $donate;
	}// END OF FUNCTION

	/***********************************************************************
	** Function name 	: checkActiveProduct
	** Developed By 	: Afsar Ali
	** Purpose 			: This function used for check Active Product
	** Date 			: 18 JUNE 2023
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 
	public function checkActiveProduct($product_id)
	{	
		$active_product 	= 	1;
		$CTwhere['where']	= 	array('products_id'=>(int)$product_id ,'status' => 'A');
		$shortField 		= 	array('id' => -1 );
		$available_product  =	$this->geneal_model->getData2('single', 'da_products', $CTwhere, $shortField);
		if(empty($available_product)):
			$active_product = 0;
		else:
			$draw_date =  strtotime($available_product['draw_date'] . ' ' .$available_product['draw_time']);
			$today_date = strtotime(date('Y-m-d h:i'));
			if($today_date>=$draw_date):
				$active_product = 0;	
			endif;
		endif;
		return $active_product;
	}// END OF FUNCTION

	/***********************************************************************
	** Function name 	: checkProductInCart
	** Developed By 	: Afsar Ali
	** Purpose 			: This function used for check Product In Cart
	** Date 			: 18 JUNE 2023
	** Updated By		:
	** Updated Date 	: 
	************************************************************************/ 
	public function checkProductInCart($product_id)
	{	
		$productAvl =   'No';
		$cartItems 	= 	$this->cart->contents();
		if($cartItems):
			foreach ($cartItems as $items):
				if($items['id'] == $product_id):
					$productAvl =   'Yes';
					break;
				endif;
			endforeach;
		endif;
		return $productAvl;
	}// END OF FUNCTION

}
