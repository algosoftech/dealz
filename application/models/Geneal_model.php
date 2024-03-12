<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Geneal_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
	}

/***********************************************************************
** Function name 	: getData
** Developed By 	: AFSAR ALI
** Purpose 			: This function used for check duplicate entry
** Date 			: 05 APRIL 2022
************************************************************************/ 
public function getData($tbl_name, $whereCon, $order){
	$this->mongo_db->select('*');
	$this->mongo_db->where($whereCon);
	$this->mongo_db->order_by($order);	
	$result = $this->mongo_db->get($tbl_name);
	return $result;
} // END OF FUNCTION

/* * *********************************************************************
 * * Function name 	: editData
 * * Developed By 	: AFSAR ALI
 * * Purpose  		: This function used for edit data
 * * Date 			: 19 APRIL 2022
 * * **********************************************************************/
function editData($tableName='',$param='',$fieldName='',$fieldValue='')
{ 
	$this->mongo_db->where(array($fieldName=>$fieldValue));
	$this->mongo_db->set($param);
	$this->mongo_db->update_all($tableName);
	//$this->mongo_db->update_all
	return true;
}	// END OF FUNCTION	

/* * *********************************************************************
 * * Function name 	: editSingleData
 * * Developed By 	: MANOJ KUMAR
 * * Purpose  		: This function used for edit Single data
 * * Date 			: 18 JUNE 2023
 * * **********************************************************************/
function editSingleData($tableName,$param,$whereCon)
{ 
	$this->mongo_db->where($whereCon);
	$this->mongo_db->set($param);
	$this->mongo_db->update($tableName);
	//$this->mongo_db->update_all
	return true;
}	// END OF FUNCTION	

/***********************************************************************
** Function name 	: getOnlyOneData
** Developed By 	: AFSAR ALI
** Purpose 			: This function used for get only one entry
** Date 			: 15 APRIL 2022
************************************************************************/ 
public function getOnlyOneData($tbl_name, $whereCon){
	$this->mongo_db->select('*');
	$this->mongo_db->where($whereCon);
	$result = $this->mongo_db->find_one($tbl_name);
	return $result;
} // END OF FUNCTION	

/***********************************************************************
** Function name 	: addData
** Developed By 	: Afsar Ali
** Purpose  		: This function used for add data
** Date 			: 14 APRIL 2022
************************************************************************/
public function addData($tableName='',$param=array())
{
	$last_insert_id 		=	$this->mongo_db->insert($tableName,$param);
	return $last_insert_id;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: checkDuplicate
** Developed By 	: AFSAR ALI
** Purpose 			: This function used for check duplicate entry
** Date 			: 14 APRIL 2022
************************************************************************/ 
public function checkDuplicate($tbl_name, $whereCon){
	$this->mongo_db->where($whereCon);
	return $this->mongo_db->count($tbl_name, $whereCon);
} // END OF FUNCTION

/***********************************************************************
** Function name 	: getUserSeqID
** Developed By 	: AFSAR ALI
** Purpose 			: This function used for check duplicate entry
** Date 			: 14 APRIL 2022
************************************************************************/ 
public function getUserSeqID(){
	$result = $this->mongo_db->find_one('da_users');
	$Seq_ID = ++$result['users_id'];
	$nextSeqID = "DZA-". substr($Seq_ID, -5);
	return $nextSeqID;
} // END OF FUNCTION

/***********************************************************************
** Function name 	: getUserID
** Developed By 	: AFSAR ALI
** Purpose 			: This function used for check duplicate entry
** Date 			: 14 APRIL 2022
************************************************************************/ 
public function getUserID(){
	$result = $this->mongo_db->find_one('da_users');
	$Seq_ID = ++$result['users_id'];
	return $Seq_ID;
} // END OF FUNCTION

/***********************************************************************
** Function name 	: getNextSequence
** Developed By 	: AFSAR ALI
** Purpose  		: This function used for get Next Sequence
** Date 			: 19 APRIL 2022
************************************************************************/
public function getNextSequence($tableName='')
{
	$this->mongo_db->select(array('seq'));
	$this->mongo_db->where(array('_id'=>$tableName));	
	$result = $this->mongo_db->find_one('hcap_counters');
	if($result):  
		$newId				=	$result['seq']+1; 
		$encryptValue 		=	$newId;//$this->returnIntegerEncryptValue($newId,16);
		$this->mongo_db->where(array('_id'=>$tableName));
		$this->mongo_db->set(array('seq'=>(int)$newId,'encrypted'=>(int)$encryptValue));
		$this->mongo_db->update('hcap_counters');
	else:
		$newId				=	100000000000001;
		$encryptValue 		=	$newId;//$this->returnIntegerEncryptValue($newId,16);
		$this->mongo_db->insert('hcap_counters',array('_id'=>$tableName,'seq'=>(int)$newId,'encrypted'=>(int)$encryptValue));
	endif;
	return $encryptValue;//$newId;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getNextOrderId
** Developed By 	: MANOJ KUMAR
** Purpose  		: This function used for get Next OrderI d
** Date 			: 19 APRIL 2022
************************************************************************/
public function getNextOrderId($tableName='')
{
	A:
	// $ord_id = 'DZINV'.rand(1000000,9999999);
	$ord_id = 'DLZIN'.rand(1000000,9999999);
	$this->mongo_db->select(array('order_id'));
	$this->mongo_db->where(array('order_id'=>$ord_id));	
	$result = $this->mongo_db->find_one('da_orders');
	if($result):
		goto A;
	else:
		return $ord_id;
	endif;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getNextUWINOrderId
** Developed By 	: Dilip Halder
** Purpose  		: This function used for get Next OrderI d
** Date 			: 03 February 2024
************************************************************************/
public function getNextUWINOrderId($tableName='')
{
	A:
	$ord_id = 'UWINN'.rand(1000000,9999999);
	$this->mongo_db->select(array('order_id'));
	$this->mongo_db->where(array('order_id'=>$ord_id));	
	$result = $this->mongo_db->find_one('da_lotto_orders');
	if($result):
		goto A;
	else:
		return $ord_id;
	endif;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getNextOrderId
** Developed By 	: MANOJ KUMAR
** Purpose  		: This function used for get Next OrderI d
** Date 			: 19 APRIL 2022
************************************************************************/
public function getNextQuickBuyOrderId($tableName='')
{
	A:
	$ord_id = 'DZINV'.rand(1000000,9999999);
	$this->mongo_db->select(array('ticket_order_id'));
	$this->mongo_db->where(array('ticket_order_id'=>$ord_id));	
	$result = $this->mongo_db->find_one('da_ticket_orders');
	
	if($result):
		goto A;
	else:
		return $ord_id;
	endif;	
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: generatetoken
** Developed By 	: Dilip	Halder
** Purpose  		: This function used for generate login token
** Date 			: 28 September 2023
************************************************************************/
public function generatetoken()
{
	$randomBytes = random_bytes(32); // Generate 32 bytes of random data
	return $token = bin2hex($randomBytes); // Convert the random bytes to a hexadecimal string
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getNextIdSequence
** Developed By 	: Afsar Ali
** Purpose  		: This function used for get Next Id Sequence
** Date 			: 30 APRIL 2022
************************************************************************/
public function getNextIdSequence($sequenceType='',$type='')
{
	$this->mongo_db->select(array('seq'));
	$this->mongo_db->where(array('_id'=>$sequenceType));	
	$result = $this->mongo_db->find_one('hcap_id_sequence');
	if($result):  
		$newId				=	$result['seq']+1; 
		$this->mongo_db->where(array('_id'=>$sequenceType));
		$this->mongo_db->set(array('seq'=>(int)$newId,'encrypted'=>(int)$newId));
		$this->mongo_db->update('hcap_id_sequence');
	else:
		$newId				=	1;
		$this->mongo_db->insert('hcap_id_sequence',array('_id'=>$sequenceType,'seq'=>(int)$newId,'encrypted'=>(int)$newId));
	endif;  
	
	if($type=='Sales Person'):  
		$constant 		=	array('users_seq_id'=>'SR');
	endif;

	if($type=='Retailer'):  
		$constant 		=	array('users_seq_id'=>'RT');
	endif;

	if($type=='Users'):  
		$constant 		=	array('users_seq_id'=>'CS');
	endif;
	if($type=='Freelancer'):  
		$constant 		=	array('users_seq_id'=>'FL');
	endif;

	
	$cueNewId 	 	= 	$newId<10?'0000'.$newId:($newId<100?'000'.$newId:($newId<1000?'00'.$newId:($newId<10000?'0'.$newId:$newId)));
	return $constant[$sequenceType].$cueNewId;
}	// END OF FUNCTION



/***********************************************************************
** Function name 	: deleteData
** Developed By 	: AFSAR ALI
** Purpose  		: This function used for delete data
** Date 			: 19 APRIL 2022
************************************************************************/
function deleteData($tableName='',$fieldName='',$fieldValue='')
{
	$this->mongo_db->where(array($fieldName=>$fieldValue));
	$this->mongo_db->delete_all($tableName);
	return true;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: deleteDataByCondition
** Developed By 	: AFSAR ALI
** Purpose  		: This function used for delete data
** Date 			: 19 APRIL 2022
************************************************************************/
function deleteDataByCondition($tableName='',$whereCon=array())
{
	$this->mongo_db->where($whereCon);
	$this->mongo_db->delete_all($tableName);
	return true;
}	// END OF FUNCTION


/***********************************************************************
** Function name: getDataByQuery
** Developed By: Manoj Kumar
** Purpose: This function used for get data by query
** Date : 14 JUNE 2021
************************************************************************/
public function getData2($action='',$tbl_name='',$wcon='',$shortField='',$num_page='',$cnt='')
{  
	
	$this->mongo_db->select('*');		
	if(isset($wcon['where']) && $wcon['where'])	$this->mongo_db->where($wcon['where']);	
	if(isset($wcon['where_or']) && $wcon['where_or'])	$this->mongo_db->where_or($wcon['where_or']);	
	if(isset($wcon['where_ne']) && $wcon['where_ne'])	$this->mongo_db->where_ne($wcon['where_ne'][0],$wcon['where_ne'][1]);	
	if(isset($wcon['where_in']) && $wcon['where_in'])	$this->mongo_db->where_in($wcon['where_in'][0],$wcon['where_in'][1]);	
	
	if(isset($wcon['where_gte']) && $wcon['where_gte'])	$this->mongo_db->where_gte($wcon['where_gte'][0],$wcon['where_gte'][1]);	
	if(isset($wcon['where_lte']) && $wcon['where_lte'])	$this->mongo_db->where_lte($wcon['where_lte'][0],$wcon['where_lte'][1]);	

	if(isset($wcon['where_between']) && $wcon['where_between'])	$this->mongo_db->where_between($wcon['where_between'][0],$wcon['where_between'][1],$wcon['where_between'][2]);	
	if(isset($wcon['like']) && $wcon['like'])	$this->mongo_db->like($wcon['like'][0],$wcon['like'][1],'i',TRUE,TRUE);
	if($shortField)				$this->mongo_db->order_by($shortField);				
	if($num_page):				$this->mongo_db->limit($cnt);
								$this->mongo_db->offset($num_page);
	elseif($num_page == 'zero'):$this->mongo_db->limit($cnt);
								$this->mongo_db->offset(0);						
	endif;
	if($action == 'count'):	
		return $this->mongo_db->count($tbl_name);
	elseif($action == 'single'):	
		$result = $this->mongo_db->find_one($tbl_name);
		if($result):
			return $result;
		else:
			return false;
		endif;
	elseif($action == 'multiple'):	
		$result = $this->mongo_db->get($tbl_name);
		if($result):	
			return $result;
		else:		
			return false;
		endif;
	else:
		return false;
	endif;
}	// END OF FUNCTION

/***********************************************************************
** Function name : getsummeryReport
** Developed By  : Dilip Halder
** Purpose       : This function used for get data by query
** Date          : 04 Janaury 2024
************************************************************************/
public function getsummeryReport($action='',$tbl_name='',$wcon='',$shortField='',$num_page='',$cnt=''){
	$filterArray 				   =	array();
	
	if($wcon['where']):
		foreach($wcon['where'] as $where_key=>$where_value):
			array_push($filterArray,array($where_key=>$where_value));
		endforeach;
	endif;

	if($wcon['where_gte']):
        foreach($wcon['where_gte'] as $where_key => $where_value):
            array_push($filterArray,array($where_value[0]=> array('$gte' => $where_value[1])));
        endforeach;
    endif;

    if($wcon['where_lte']):
        foreach($wcon['where_lte'] as $where_key => $where_value):
            array_push($filterArray,array($where_value[0]=> array('$lte' => $where_value[1])));
        endforeach;
    endif;

	$selectFields 					=  	array('$project' => array(
																'_id'=>0,
																'ticket_order_id'=>1,
																'user_id'=>1,
																'user_type'=>1,
																'user_email'=>1,
																'user_phone'=>1,
																'product_title'=>1,
														      	"product_qty"=> array( '$toInt' => '$product_qty'),
																'total_price'=>1,
																'prize_title'=>1,
																'status'=>1,
																'product_id'=>1,
																'product_image'=>'$product.product_image',
																'availableArabianPoints'=>1,
																'end_balance' =>1,
																'payment_mode' =>1,
																'product_is_donate' => 1 ,
																'isVoucher' =>'$coupons.isVoucher',
																'created_at' =>1,
															));

	$whereCondition					=	array();



	if($filterArray):
		foreach($filterArray as $filterInfo):
			array_push($whereCondition,$filterInfo);
		endforeach;
	endif;

	$currentQuery					=	array(
											  array('$lookup'=>array('from'=>'da_products','localField'=>'product_id','foreignField'=>'products_id','as'=>'product')),
											  array('$lookup'=>array('from'=>'da_ticket_coupons','localField'=>'ticket_order_id','foreignField'=>'ticket_order_id','as'=>'coupons')),
											  $selectFields,
											  array('$match'=>array('$and'=>$whereCondition)),
										  	  // array('$unwind' => '$product_image' ),
										  	  array('$unwind' => '$isVoucher' ),
										  	  array('$unwind' => '$product_qty' ),
										  	  array(
										  	  		'$group' => array(
							  	  						'_id' => '$product_title' ,
				  		                    			'product_is_donate'   => array('$push' => '$product_is_donate'),
				  		                    			'price'   => array('$first' => '$total_price'),
							  	  						'product_image' =>array('$first' => '$product_image'),
				  		                    			'sales_count'=>array('$sum' =>  '$product_qty'),
				  		                    			'sales'=>array('$sum' => '$total_price'),
				  		                    			'availableArabianPoints'=>array('$last' => '$availableArabianPoints'),
				  		                    			'end_balance' => array('$first' => '$end_balance'),
				  		                    			'isVoucher'   => array('$push' => '$isVoucher'),
				  		                    			'product_qty' => array('$push' => '$product_qty'),
	  		                 		  				   )),
								  	  				array('$sort'=>$shortField),
													);

	if($action == 'count'):
		$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		if($totalDataCount):
			return count($totalDataCount);
		endif;
	elseif($action == 'single'):
		$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		return $currentData[0];
	elseif($action == 'multiple'):	
		if($per_page):
			array_push($currentQuery,array('$skip'=>(int)$page));
			array_push($currentQuery,array('$limit'=>(int)$per_page));
		endif; 
		$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		return $currentData;
	endif;
}

/***********************************************************************
** Function name 	: checkCartItems
** Developed By 	: AFSAR ALI
** Purpose  		: This function used for delete data
** Date 			: 19 APRIL 2022
************************************************************************/
function checkCartItems($productID='')
{	
	//echo $productID;
	$i=0;
	foreach ($this->cart->contents() as $value) {
		$productcartID[$i] = $value['id'];
		$i++;
	}

	if(in_array($productID, $productcartID)){
		$result = 1;
	}else{
		$result =  0;
	}


	return $result;
}	// END OF FUNCTION

/***********************************************************************
** Function name: getDataByMultipleAndCondition
** Developed By: Manoj Kumar
** Purpose: This function used for get data by query
** Date : 21 JUNE 2021
************************************************************************/
public function getDataByMultipleAndCondition($tbl_name='',$query='',$arrayfield=array())
{  
	$resultData = array();
	$result 		= 		$this->mongo_db->aggregate($tbl_name,$query,array('batchSize'=>4)); 
	foreach($result as $results):
		//$returnData = $results['result'];
		foreach($results as $key=>$valye):
			if(is_array($results[$key])):
				$results[$key] 	=	$results[$key];
			endif;
		endforeach;
		array_push($resultData,$results);
	endforeach;		
	$groupedData 	= json_decode(json_encode($resultData), true);
	return $groupedData;
}	// END OF FUNCTION

/***********************************************************************
** Function name: getcartDataQuery
** Developed By: Ravi Negi
** Purpose: This function used for get cart Data
** Date : 29 JULY 2021
************************************************************************/
public function getcartDataQuery($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
{  
	$filterArray 				=	array();

	
	if($where_condition['where']):
		foreach($where_condition['where'] as $where_key=>$where_value):
			array_push($filterArray,array($where_key=>$where_value));
		endforeach;
	endif;

	$selectFields 					=  	array('$project' => array('_id'=>0,'order_id'=>1,'payment_mode'=>1,'order_status'=>1,'user_id'=>1,'product_id'=>1,'cart_id'=>1,'total_price'=>1,'created_at'=>1,'quantity'=>1,
																  'product_name'=>'$from_product.shop_sub_category_name','product_name'=>'$from_product.title','shop_text'=>'$from_product.shop_text','prod_image'=>'$from_product.shop_cat_image'));

	$whereCondition					=	array();

	if($filterArray):
		foreach($filterArray as $filterInfo):
			array_push($whereCondition,$filterInfo);
		endforeach;
	endif;

	$currentQuery					=	array(array('$lookup'=>array('from'=>'da_products','localField'=>'product_id','foreignField'=>'products_id','as'=>'from_product')),
											  $selectFields,
											  array('$match'=>array('$and'=>$whereCondition)));

	if($action == 'count'):
		$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		if($totalDataCount):
			return count($totalDataCount);
		endif;
	elseif($action == 'single'):
		$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		return $currentData[0];
	elseif($action == 'multiple'):	
		if($per_page):
			array_push($currentQuery,array('$skip'=>(int)$page));
			array_push($currentQuery,array('$limit'=>(int)$per_page));
		endif; 
		$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		return $currentData;
	endif;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getCurrentBalance
** Developed By 	: Afar Ali
** Purpose 			: This function used for get current balance
** Date 			: 05 MAY 2022
************************************************************************/
public function getCurrentBalance($userID='')
{  
	$resultData = array();
	$whereCon = [ 'users_id' => (int)$userID ];
	$this->mongo_db->select('*');
	$this->mongo_db->where($whereCon);
	$resultData = $this->mongo_db->find_one('da_users');
	return $resultData['availableArabianPoints'];
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: debitPoints
** Developed By 	: Afar Ali
** Purpose 			: This function used for get cut balance
** Date 			: 05 MAY 2022
************************************************************************/
public function debitPoints($points='')
{  
	$resultData = array();

	$avlBal = $this->geneal_model->getCurrentBalance((int)$this->session->userdata('DZL_USERID'));

	$arabianpoints = $avlBal - $points;

	if($arabianpoints < 0): $arabianpoints = 0; endif;

	$set = ['availableArabianPoints' => (float)$arabianpoints];
	$this->mongo_db->where(array('users_id' => (int)$this->session->userdata('DZL_USERID')));
	$this->mongo_db->set($set);
	$this->mongo_db->update('da_users');
	$this->session->set_userdata('DZL_AVLPOINTS', $arabianpoints); //Reset available ballance of Arabian points.
	return $arabianpoints;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: creaditPoints
** Developed By 	: Afar Ali
** Purpose 			: This function used for creadit points
** Date 			: 05 MAY 2022
** Updaetd by 		: Dilip Halder
* Date 				: 17 February 2023
************************************************************************/
public function creaditPoints($points='',$user_id="")
{  
	$resultData = array();
	if($user_id == ""):
		$avlBal = $this->geneal_model->getCurrentBalance((int)$this->session->userdata('DZL_USERID'));
	else:
		$avlBal = $this->geneal_model->getCurrentBalance((int)$user_id);
	endif;
	$arabianpoints = (float)$avlBal + (float)$points;

	$set = ['availableArabianPoints' => (float)$arabianpoints];

	if($user_id == ""):
		$this->mongo_db->where(array('users_id' => (int)$this->session->userdata('DZL_USERID')));
	else:
		$this->mongo_db->where(array('users_id' => (int)$user_id ));
	endif;

	$this->mongo_db->set($set);
	$this->mongo_db->update('da_users');
	$this->session->set_userdata('DZL_AVLPOINTS', $arabianpoints); //Reset available ballance of Arabian points.
	return $arabianpoints;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getStock
** Developed By 	: Afar Ali
** Purpose 			: This function used for get current stock
** updated by 		: Dilip Halder
** Updated Date 	: 06 March 2023
************************************************************************/
public function getStock($pid='', $qty)
{  
	//echo $qty; die();
	$resultData = array();
	$whereCon = [ 'products_id' => $pid , "status" => "A" ];
	$this->mongo_db->select('*');
	$this->mongo_db->where($whereCon);
	$resultData = $this->mongo_db->find_one('da_products');
	if($resultData['stock'] >= (int)$qty){
		return true;
	}elseif($resultData['stock'] == 0){
		$this->geneal_model->deleteData('da_cartItems', 'id', (int)$pid );
		return;
	}else{
		return 2;
	}
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getInventoryStock
** Developed By 	: Afar Ali
** Purpose 			: This function used for get current stock
** Date 			: 06 MAY 2022
************************************************************************/
public function getInventoryStock($cpid='',$pid='', $qty)
{  
	//echo $qty; 
	$resultData = array();
	$whereCon = [ 'products_id' => (int)$pid,
				  'collection_point_id' => (int)$cpid ];
	//echo '<pre>'; print_r($whereCon);die();
	$this->mongo_db->select('*');
	$this->mongo_db->where($whereCon);
	$resultData = $this->mongo_db->find_one('da_inventory');
	
	$avlQTY = (int)$resultData['available_qty'];
	//echo $avlQTY;die();
	if($avlQTY > (int)$qty){
		//echo 'here';die();
		return 'success';
	}elseif($avlQTY == (int)$qty){
		return 'success';
	}else{
		return 'error';
	}
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: updateStock
** Developed By 	: Afar Ali
** Purpose 			: This function used for update stock
** Date 			: 06 MAY 2022
************************************************************************/
public function updateInventoryStock($cpid='',$pid='', $qty='')
{  

	$resultData = array();
	$whereCon = ['products_id'=>(int)$pid,'collection_point_id' => (int)$cpid ];
	$this->mongo_db->select('*');
	$this->mongo_db->where($whereCon);
	$resultData2 = $this->mongo_db->find_one('da_inventory');

	$inventory_available_qty = (int)$resultData2['available_qty'] - (int)$qty;
	$invParam['available_qty']	=	$inventory_available_qty;
	$invParam['order_request_qty']	=	$order_request_qty;
	$this->mongo_db->where(array('products_id'=>(int)$pid,'collection_point_id' => (int)$cpid));
	$this->mongo_db->set($invParam);
	$this->mongo_db->update('da_inventory');
	//echo '<pre>';print_r($whereCon);die();
	//$this->updateOnlyOne('da_inventory',$invParam,$whereCon);
	return;
}	// END OF FUNCTION

// /***********************************************************************
// ** Function name 	: updateInventoryStock
// ** Developed By 	: Afar Ali
// ** Purpose 			: This function used for update stock
// ** Date 			: 06 MAY 2022
// ************************************************************************/
// public function updateInventoryStock($cpid='',$pid='', $qty='')
// {  

// 	$resultData = array();
// 	$whereCon = ['products_id'=>(int)$pid,'collection_point_id' => (int)$cpid ];
// 	$this->mongo_db->select('*');
// 	$this->mongo_db->where($whereCon);
// 	$resultData2 = $this->mongo_db->find_one('da_inventory');

// 	$inventory_available_qty = (int)$resultData2['available_qty'] - (int)$qty;
// 	$order_request_qty      = (int)$resultData2['order_request_qty'] - (int)$qty;
// 	$invParam['available_qty']	=	$inventory_available_qty;
// 	$invParam['order_request_qty']	=	$order_request_qty;

// 	$this->mongo_db->where(array('products_id'=>(int)$pid,'collection_point_id' => (int)$cpid));
// 	$this->mongo_db->set($invParam);
// 	$this->mongo_db->update('da_inventory');
// 	//echo '<pre>';print_r($whereCon);die();
// 	//$this->updateOnlyOne('da_inventory',$invParam,$whereCon);
// 	return;
// }	// END OF FUNCTION



/***********************************************************************
** Function name 	: updateStock
** Developed By 	: Afar Ali
** Purpose 			: This function used for update stock
** Date 			: 06 MAY 2022
** Updated By 		: Dilip halder
** Updated Date 	: 06 December 2023
************************************************************************/
public function updateStock($pid='', $qty='',$type='')
{  
	$resultData = array();
	$whereCon = [ 'products_id' => (int)$pid ];
	$this->mongo_db->select('*');
	$this->mongo_db->where($whereCon);

	if($type == 'lotto'):
	 $resultData = $this->mongo_db->find_one('da_lotto_products');
	else:
	 $resultData = $this->mongo_db->find_one('da_products');
	endif;

	$updataData = (int)$resultData['stock'] - (int)$qty;
	
	$param['stock']				=	$updataData;

	$this->mongo_db->where(array('products_id'=>(int)$pid));
	$this->mongo_db->set($param);

	if($type == 'lotto'):
		$this->mongo_db->update('da_lotto_products');
	else:
		$this->mongo_db->update('da_products');
	endif;
	return;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: updateOnlyOne
** Developed By 	: Afar Ali
** Purpose 			: This function used for update only one
** Date 			: 14 NOV 2022
************************************************************************/
public function updateOnlyOne($table='', $param = array(), $where=array())
{ 
	$this->mongo_db->where($whereCon);
	$this->mongo_db->set($param);
	$this->mongo_db->update($table);
	return;
}

/***********************************************************************
** Function name: getOrderDetails
** Developed By: Ravi Negi
** Purpose: This function used for get cart Data
** Date : 29 JULY 2021
************************************************************************/
public function getOrderDetails($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
{  
	//echo $action.'__'. $tbl_name.'__'. $where_condition; die();
	//print_r($where_condition); die();
	$filterArray 				=	array();

	/*if($where_condition['search']):
		array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
	endif;*/
	if($where_condition['where']):
		foreach($where_condition['where'] as $where_key=>$where_value):
			array_push($filterArray,array($where_key=>$where_value));
		endforeach;
	endif;

	$selectFields 					=  	array('$project' => array(
											'_id'=>0,
											'order_id'=>1,
											'product_id'=>1,
											'user_id'=>1,
											'cart_id'=>1,
											'dilivertAddress'=>1,
											'total_price'=>1,
											'quantity'=>1,
											'order_status'=>1,
											'created_at'=>1,
											'payment_mode'=>1,

											'users_name'=>'$from_users.users_name',
											'users_email'=>'$from_users.users_email',
											'users_mobile'=>'$from_users.users_mobile',

											'product_name'=>'$from_product.title',
											'product_image'=>'$from_product.product_image',
											'product_desc'=>'$from_product.description',
											));								  

	$whereCondition					=	array();

	if($filterArray):
		foreach($filterArray as $filterInfo):
			array_push($whereCondition,$filterInfo);
		endforeach;
	endif;
	
	$currentQuery					=	array(array('$lookup'=>array(
											'from'=>'da_users',
											'localField'=>'user_id',
											'foreignField'=>'users_id',
											'as'=>'from_users'
											)),
											array('$lookup'=>array(
												'from'=>'da_products',
												'localField'=>'product_id',
												'foreignField'=>'products_id',
												'as'=>'from_product'
												)),
											$selectFields,
											array('$match'=>array('$and'=>$whereCondition)),
											array('$sort'=>$short_field));
	//echo '<pre>';print_r($currentQuery);die;											  

	
	if($action == 'count'):

		$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		//echo "<pre>"; print_r($totalDataCount); die();
		if($totalDataCount):
			return count($totalDataCount);
		endif;
	elseif($action == 'single'):
		$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		return $currentData[0];
	elseif($action == 'multiple'):	
		if($per_page):
			array_push($currentQuery,array('$skip'=>(int)$page));
			array_push($currentQuery,array('$limit'=>(int)$per_page));
		endif; 
		$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
		return $currentData;
	endif;
}	// END OF FUNCTION

/***********************************************************************
** Function name 	: getMembership
** Developed By 	: Afar Ali
** Purpose 			: This function used for get membership
** Date 			: 11 MAY 2022
************************************************************************/
public function getMembership($points='')
{  
	$resultData = array();
	$this->mongo_db->select('*');
	$resultData = $this->mongo_db->get('da_membership');
	$i=1;
	$type = '';
	foreach ($resultData as $key => $value) {
		if($points >= (int)$value['ade'] && (int)@$resultData[$i]['ade'] > $points ){
			$membershipData = array(
				'type'				=>	$value['membership_type'],
				'benifitDetails'	=>	$value['benifitDetails'],
				'benifit'			=>	$value['benifit'],
				);
			break;
		}else{
			$membershipData = array(
				'type'				=>	$value['membership_type'],
				'benifitDetails'	=>	$value['benifitDetails'],
				'benifit'			=>	$value['benifit'],
				);
		}
		$i++;
	}
	$membershipData['membershipData'] 	=	$resultData;
	return $membershipData;
}	// END OF FUNCTION


public function getDataByParticularField($tableName='',$fieldName='',$fieldValue='')
	{  
		$this->mongo_db->select('*');
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$result = $this->mongo_db->find_one($tableName);
		
		if($result):
			return $result;
		else:
			return false;
		endif;
	}	// END OF FUNCTION
	
	
		public function checkOTP($userOtp='')
	{
		$this->mongo_db->select('*');
		$this->mongo_db->where(array('users_otp'=>(int)$userOtp));
		$result = $this->mongo_db->find_one('da_users');

	//	print_r($result);die;
		if($result):
			return $result;
		else:	
			return false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: getCouponData
	** Developed By 	: Afar Ali
	** Purpose 			: This function used for get coupon data
	** Date 			: 18 MAY 2022
	************************************************************************/
	public function getCouponData($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		//echo $action.'__'. $tbl_name.'__'. $where_condition; die();
		//print_r($where_condition); die();
		$filterArray 				=	array();

		
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 					=  	array('$project' => array(
												'_id'=>0,
												'order_id'=>1,
												'product_id'=>1,
												'users_id'=>1,
												'coupon_code'=>1,
												/*'cart_id'=>1,
												'dilivertAddress'=>1,
												'total_price'=>1,
												'quantity'=>1,
												'order_status'=>1,*/
												'created_at'=>1,
												//'payment_mode'=>1,

												'users_name'=>'$from_users.users_name',
												'users_email'=>'$from_users.users_email',
												'users_mobile'=>'$from_users.users_mobile',

												'product_name'=>'$from_product.title',
												'draw_date'=>'$from_product.draw_date',
												//'product_image'=>'$from_product.product_image',
												//'product_desc'=>'$from_product.description',

												));								  

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;
		
		$currentQuery					=	array(array('$lookup'=>array(
												'from'=>'da_users',
												'localField'=>'users_id',
												'foreignField'=>'users_id',
												'as'=>'from_users'
												)),
												array('$lookup'=>array(
													'from'=>'da_products',
													'localField'=>'product_id',
													'foreignField'=>'products_id',
													'as'=>'from_product'
													)),
												$selectFields,
												array('$match'=>array('$and'=>$whereCondition)),
												array('$sort'=>$short_field));
		//echo '<pre>';print_r($currentQuery);die;											  

		
		if($action == 'count'):

			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			//echo "<pre>"; print_r($totalDataCount); die();
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif; 
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: checkWinner
	** Developed By 	: Afar Ali
	** Purpose 			: This function used for check winner 
	** Date 			: 20 MAY 2022
	************************************************************************/
	public function checkWinner($uid='', $pid='')
	{  
		$isParticipant 	= 	'';
		$isWinner		=	'';
		$msg 			=	'';
		$where =	[	'user_id' 	=> (int)$uid,
						'product_id'=> (int)$pid ];
		$isParticipant	= $this->geneal_model->getOnlyOneData('da_orders_details', $where);

		if(empty($isParticipant)){
			$msg = 'Your are not a participant.';
		}else{
			$isWinner = $this->geneal_model->getOnlyOneData('da_winners', $where);
			if(!empty($isWinner)){
				$msg = 'You were the winner.';
			}else{
				$msg = 'Your were the participant.';	
			}
		}
		return $msg;
	}
	/***********************************************************************
	** Function name 	: getParticularDataByParticularField
	** Developed By 	: Afar Ali
	** Purpose 			: This function used for particular data
	** Date 			: 25 JUNE 2022
	************************************************************************/
	public function getParticularDataByParticularField($select ='', $tableName='',$fieldName='',$fieldValue='')
	{  
		$this->mongo_db->select($select);
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$result = $this->mongo_db->find_one($tableName);
		
		if($result):
			return $result;
		else:
			return false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: debitPointsByAPI
	** Developed By 	: Manoj Kumar
	** Purpose 			: This function used for debi tPoints By API
	** Date 			: 21 JULY 2022
	*  Updated by 		: Dilip Halder
	*  Date 			: 17 February 2023.
	************************************************************************/
	public function debitPointsByAPI($points='',$user_id='')
	{  
		$resultData = array();

		$avlBal = $this->geneal_model->getCurrentBalance((int)$user_id);

		$arabianpoints = $avlBal - $points;

		if($arabianpoints < 0): $arabianpoints = 0; endif;

		$set = ['availableArabianPoints' => (float)$arabianpoints];
		$this->mongo_db->where(array('users_id' => (int)$user_id));
		$this->mongo_db->set($set);
		$this->mongo_db->update('da_users');
		return $arabianpoints;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name: getordersList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Property Data
	** Date : 15 NOV 2022
	************************************************************************/
	public function getordersList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 			=  	array(
							'$project' => array(
								'_id'=>1,
								'sequence_id'=>1,
								'order_id'=>1,
								'user_id'=>1,
								'user_type'=>1,
								'user_email'=>1,
								'user_phone'=>1,
								'product_is_donate'=>1,
								'shipping_method'=>1,
								'emirate_id'=>1,
								'emirate_name'=>1,
								'area_id'=>1,
								'area_name'=>1,
								'collection_point_id'=>1,
								'collection_point_name'=>1,
								'shipping_address'=>1,
								'product_count'=>1,
								'shipping_charge'=>1,
								'inclusice_of_vat'=>1,
								'subtotal'=>1,
								'vat_amount'=>1,
								'total_price'=>1,
								'payment_mode'=>1,
								'payment_from'=>1,
								'order_status'=>1,
								'created_at'=>1,
								'collection_code'=>1,
								'collection_status'=>1,
								'expairy_data'=>1,

								'order_details'=>'$from_orders_details',

								'coupon_details' => '$from_coupons',

								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_orders','localField'=>'user_id','foreignField'=>'user_id','as'=>'from_orders')),
												  array('$lookup'=>array('from'=>'da_orders_details','localField'=>'order_id','foreignField'=>'order_id','as'=>'from_orders_details')),
												  array('$lookup'=>array('from'=>'da_coupons','localField'=>'order_id','foreignField'=>'order_id','as'=>'from_coupons')),	
												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field)
												);//echo '<pre>';print_r($currentQuery);die;

		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION


	/***********************************************************************
	** Function name: getProductWithPrizeDetails
	** Developed By: Afsar Ali
	** Purpose: This function used for get Property Data
	** Date : 16 NOV 2022
	************************************************************************/
	public function getProductWithPrizeDetails($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 			=  	array(
							'$project' => array(
								'_id'=>1,
								'title'=>1,
								'title_slug'=>1,
								'description'=>1,
								'category_id'=>1,
								'category_name'=>1,
								'sub_category_id'=>1,
								'sub_category_name'=>1,
								'product_image'=>1,
								'product_image_alt'=>1,
								'totalStock'=>1,
								'stock'=>1,
								'inventory_stock'=>1,
								'adepoints'=>1,
								'draw_date'=>1,
								'draw_time'=>1,
								'clossingSoon'=>1,
								'is_show_closing'=>1,
								'validuptodate'=>1,
								'validuptotime'=>1,
								'products_id'=>1,
								'product_seq_id'=>1,
								'creation_ip'=>1,
								'creation_date'=>1,
								'created_by'=>1,
								'status'=>1,
								'share_limit'=>1,
								'share_percentage_first'=>1,
								'share_percentage_second'=>1,
								'soldout_status'=>1,
								'target_stock'=>1,
								"update_date"=>1,
								"update_ip"=>1,
								"updated_by"=>1,
								"color_size_details"=>1,
								'commingSoon'=>1,
								'sale_percentage'=>1,
								'sale_percentage_final'=>1,
								'seq_order'=>1,
								'countdown_status'=>1,
								"isSoldout"=>1,
								'prizeDetails'=>'$from_prize',
								'remarks'=>1
								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_products','localField'=>'products_id','foreignField'=>'products_id','as'=>'from_products')),
												  array('$lookup'=>array('from'=>'da_prize','localField'=>'products_id','foreignField'=>'product_id','as'=>'from_prize')),
 
												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field)
												);//echo '<pre>';print_r($currentQuery);die;

		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			/*if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;*/ 
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name: getInventoryList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Property Data
	** Date : 12 NOV 2022
	************************************************************************/
	public function getInventoryList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 			=  	array(
							'$project' => array(
								'_id'=>0,
								'products_id'=>1,
								'qty'=>1,
								'available_qty'=>1,
								'collection_point_id'=>1,
								'inventory_id'=>1,
								'creation_date'=>1,
								'status'=>1,


								'product_name'=>'$from_product.title',
								'stock'=>'$from_product.stock',
								'product_seq_id'=>'$from_product.product_seq_id',

								'collection_point_name'=>'$from_collection_point.collection_point_name',
								'users_email'=>'$from_collection_point.users_email',
								'users_mobile'=>'$from_collection_point.users_mobile',

								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_emirate_collection_point','localField'=>'collection_point_id','foreignField'=>'collection_point_id','as'=>'from_collection_point')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'products_id','foreignField'=>'products_id','as'=>'from_product')),

												  //array('$lookup'=>array('from'=>'da_orders','localField'=>'order_id','foreignField'=>'order_id','as'=>'from_order')),

												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field));//echo '<pre>';print_r($currentQuery);die;

		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			/*if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;*/ 
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name: getProductList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Property Data
	** Date : 10 NOV 2022
	************************************************************************/
	public function getProductList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 			=  	array(
							'$project' => array(
								'_id'=>1,
								'title'=>1,
								'title_slug'=>1,
								'description'=>1,
								'category_id'=>1,
								'category_name'=>1,
								'sub_category_id'=>1,
								'sub_category_name'=>1,
								'product_image'=>1,
								'product_image_alt'=>1,
								'totalStock'=>1,
								'stock'=>1,
								'adepoints'=>1,
								'draw_date'=>1,
								'draw_time'=>1,
								'clossingSoon'=>1,
								'is_show_closing'=>1,
								'validuptodate'=>1,
								'validuptotime'=>1,
								'products_id'=>1,
								'status'=>1,
								'soldout_status'=>1,
								'creation_date'=>1,
								'share_limit'=>1,
								'share_percentage_first'=>1,
								'share_percentage_second'=>1,
								'target_stock'=>1,
								'color_size_details'=>1,
								'remarks' =>1,
								'lotto_type' =>1,
								'prize_image'=>'$from_prize',

								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_products','localField'=>'products_id','foreignField'=>'products_id','as'=>'from_products')),
												  array('$lookup'=>array('from'=>'da_prize','localField'=>'products_id','foreignField'=>'product_id','as'=>'from_prize')),

												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field)
												);//echo '<pre>';print_r($currentQuery);die;

		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			/*if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;*/ 
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name : editDataByMultipleCondition
	** Developed By : Afsar Ali
	** Purpose  : This function used for edit data by multiple condition
	** Date : 21 NOV 2021
	************************************************************************/
	function editDataByMultipleCondition($tableName='',$param=array(),$whereCondition=array())
	{
		$this->mongo_db->where($whereCondition);
		$this->mongo_db->set($param);
		$this->mongo_db->update($tableName);
		return true;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getproductrequestList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Property Data
	** Date : 06 DEC 2022
	************************************************************************/
	public function getproductrequestList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 			=  	array(
							'$project' => array(
								'_id'=>1,
								'sequence_id'=>1,
								'order_id'=>1,
								'user_id'=>1,
								'user_type'=>1,
								'user_email'=>1,
								'user_phone'=>1,
								'product_is_donate'=>1,
								'shipping_method'=>1,
								'emirate_id'=>1,
								'emirate_name'=>1,
								'area_id'=>1,
								'area_name'=>1,
								'collection_point_id'=>1,
								'collection_point_name'=>1,
								'shipping_address'=>1,
								'product_count'=>1,
								'shipping_charge'=>1,
								'inclusice_of_vat'=>1,
								'subtotal'=>1,
								'vat_amount'=>1,
								'total_price'=>1,
								'payment_mode'=>1,
								'payment_from'=>1,
								'order_status'=>1,
								'created_at'=>1,
								'collection_code'=>1,
								'collection_status'=>1,
								'expairy_data'=>1,

								'order_details'=>'$from_orders_details',

								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_orders','localField'=>'user_id','foreignField'=>'user_id','as'=>'from_orders')),
												  array('$lookup'=>array('from'=>'da_orders_details','localField'=>'order_id','foreignField'=>'order_id','as'=>'from_orders_details')),	
												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field)
												);//echo '<pre>';print_r($currentQuery);die;

		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: GetQuickTicketHistory
	** Developed By: Dilip Halder
	** Purpose: This function used for getQuick Ticket history.
	** Date : 06 February 2023
	************************************************************************/
	public function GetQuickTicketHistory($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 			=  	array(
									'$project' => array(
									'_id'=>1,
									'coupon_id'=>1,
									'users_id'=>1,
									'users_email'=>1,
									'order_first_name'=>1,
									'order_last_name'=>1,
									'order_users_mobile'=>1,
									'order_users_email'=>1,
									'ticket_order_id'=>1,
									'product_id'=>1,
									'product_title'=>1,
									'product_qty'=>1,
									'total_price'=>1,
									'product_color'=>1,
									'prize_title'=>1,
									'is_donated'=>1,
									'coupon_status'=>1,
									'coupon_type'=>1,
									'coupon_code'=>1,
									'draw_date'=>'$from_products.draw_date',
									'draw_time'=>'$from_products.draw_time',
									'created_at'=>1,
								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_products','localField'=>'product_id','foreignField'=>'products_id','as'=>'from_products')),
												  // array('$lookup'=>array('from'=>'da_prize','localField'=>'products_id','foreignField'=>'product_id','as'=>'from_prize')),
 
												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field)
												);
												// echo '<pre>';print_r($currentQuery);die;

		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			/*if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;*/ 
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: GetQuickOrderTotalCount
	** Developed By: Dilip Halder
	** Purpose: This function used for Quick Order Total Count.
	** Date : 21 march 2023
	** Updated by : Dilip Halder
	** Udated Date : 26-04-2023
	************************************************************************/
	public function GetQuickOrderTotalCount($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 			=  	array(
									'$project' => array(
									'_id'=>1,
									'user_id'=>1,
									'product_id'=>1,
									'product_qty' =>1,
									'product_title'=>1,
									'product_image'=>'$from_products.product_image',
									'total_price'=>1,
									'availableArabianPoints'=>1,
									'end_balance'=>1,
									'product_is_donate'=>1,
									'created_at'=>1,
									'status'=>1,
								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(
													array('$lookup'=>array('from'=>'da_products','localField'=>'product_id','foreignField'=>'products_id','as'=>'from_products')),
												    array('$lookup'=>array('from'=>'da_users','localField'=>'user_id','foreignField'=>'users_id','as'=>'user')),
 
												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$group' => array(
											  			'_id' => '$product_title',
											  			'price' => array('$last' => '$total_price' ) ,
											  			'product_image' => array('$last' => '$product_image' ),
											  			'sales_count' => array('$sum' => 1 ) , 
											  			'sales' => array('$sum' => '$total_price' ) , 
											  			'product_is_donate' => array('$push' => '$product_is_donate' ) ,
											  			'availableArabianPoints' => array('$first' => '$availableArabianPoints' ),
											  			'end_balance' => array('$last' => '$end_balance' )  

											  		)),
												  array('$sort'=>$short_field)
												);
												

												// echo '<pre>';print_r($currentQuery);die;
		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	

			/*if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;*/ 
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getWalletStatements
	** Developed By: Dilip Halder
	** Purpose: This function used for get Wallet Statements
	** Date : 28 March 2023
	************************************************************************/
	public function getWalletStatements($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  

		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 		= array('$project' => array(
								'_id'=>0 ,
								'order_id'=>1,
								'payment_from' => 1,
								'created_at' => 1,
								'price' => '$order.price',
								'availableArabianPoints' => 1,
								'end_balance' => 1,
								'payment_mode' => 1,
								'order_status' => 1,
								'product_is_donate' => 1,
								'product_name' => '$order.product_name',
								'quantity' => '$order.quantity',
								'users_name' => '$users.users_name',
								'users_mobile' => '$users.users_mobile',
								'users_email' => '$users.users_email',
								'arabian_points' => '$loadBalance.arabian_points',
								// 'record_type' => '$loadBalance.record_type',
								'cashback_amount' => '$cashback.cashback',
								'cashback_created_at' => '$cashback.created_at',
								'referral_amount' => '$referral.referral_amount',
								'referral_created_at' => '$referral.created_at',
							));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;
		

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_orders_details','localField'=>'order_id','foreignField'=>'order_id','as'=>'order')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'user_id','foreignField'=>'users_id','as'=>'users')),
										    	  array('$lookup'=>array('from'=>'da_users','localField'=>'user_id','foreignField'=>'users_id','as'=>'users')),
									     		  // array('$lookup'=>array('from'=>'da_loadBalance','localField'=>'order_id','foreignField'=>'order_id','as'=>'loadBalance')),
									     		  array('$lookup'=>array('from'=>'da_cashback','localField'=>'order_id','foreignField'=>'order_id','as'=>'cashback')),
									     		  array('$lookup'=>array('from'=>'referral_product','localField'=>'order_id','foreignField'=>'order_id','as'=>'referral')),
										    array('$match'=>array('$and'=>$whereCondition)),
										    $selectFields,
											array('$sort'=>$short_field));

	    // echo '<pre>';print_r($currentQuery);die;
												  // echo '<pre>';print_r($currentQuery);die;
		
		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getQuickWalletStatements
	** Developed By: Dilip Halder
	** Purpose: This function used for get Quick Wallet Statements
	** Date : 2 March 2023
	************************************************************************/
	public function getQuickWalletStatements($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  

		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 		= array('$project' => array(
								'_id'=>0 ,
								'ticket_order_id'=>1,
								'payment_from' => 1,
								'order_status' => 1,
								'created_at' => 1,
								'price' => '$order.price',
								'availableArabianPoints' => 1,
								'end_balance' => 1,
								'payment_mode' => 1,
								'is_donated' => 1,
								'product_name' => '$coupons.product_title',
								'quantity' => '$coupons.product_qty',
								'total_price' => '$coupons.total_price',
								'users_name' => '$coupons.order_first_name',
								'last_name' => '$coupons.order_last_name',
								'users_mobile' => '$coupons.order_users_mobile',
								'users_email' => '$coupons.order_users_email',
							));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_ticket_coupons','localField'=>'ticket_order_id','foreignField'=>'ticket_order_id','as'=>'coupons')),
										    array('$match'=>array('$and'=>$whereCondition)),
										    $selectFields,
											array('$sort'=>$short_field));
		// echo '<pre>';print_r($currentQuery);die;
		
		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION


	/***********************************************************************
	** Function name: getCouponWalletStatements
	** Developed By: Dilip Halder
	** Purpose: This function used for get Coupon Wallet Statements
	** Date : 05 April 2023
	************************************************************************/
	public function getCouponWalletStatements($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  


		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 		= array('$project' => array(
								'_id'=>0 ,
								'rc_id'=>1,
								'coupon_code'=>1,
								'coupon_code_amount'=>1,
								'coupon_code_statys'=>1,
								'redeemed_by_whom'=>1,
								'redeemed_date'=>1,
								'created_date'=>1,
								'generate_for'=>'$rechargecoupons.generate_for',
								
							));

		$whereCondition		=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		

		$currentQuery					=	array(
										    array('$lookup'=>array('from'=>'da_users','localField'=>'user_id','foreignField'=>'users_id','as'=>'users')),
											array('$lookup'=>array('from'=>'da_rechargecoupons','localField'=>'rc_id','foreignField'=>'rc_id','as'=>'rechargecoupons')),
										  	array('$match'=>array('$and'=>$whereCondition)),
										    $selectFields,
											array('$sort'=>$short_field)
										); // echo '<pre>';print_r($currentQuery);die;
		
		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION


	/***********************************************************************
	** Function name: duemanagement
	** Developed By: Dilip Halder
	** Purpose: This function used for get due management Statements
	** Date : 18 April 2023
	************************************************************************/
	public function duemanagement($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  


		$filterArray 				=	array();

		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		$selectFields 		= array('$project' => array(
								'_id'=>0 ,
								'due_management_id'=>1,
								'user_id_to'=>1,
								'recharge_amt'=>1,
								'cash_collected'=>1,
								'due_amount'=>1,
								'record_type'=>1,
								'advanced_amount'=>1,
								'users_name'=>'$users.users_name',
								'last_name'=>'$users.last_name',
								'country_code'=>'$users.country_code',
								'users_mobile'=>'$users.users_mobile',
								'users_email'=>'$users.users_email',
								'availableArabianPoints'=>'$users.availableArabianPoints',
								'store_name'=>'$users.store_name',
							));

		$whereCondition		=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		

		$currentQuery					=	array(
											  	array('$match'=>array('$and'=>$whereCondition)),
										        array('$lookup'=>array('from'=>'da_users','localField'=>'user_id_to','foreignField'=>'users_id','as'=>'users')),

											    $selectFields,
											    array('$unwind' => '$users_name' ),
											    array('$unwind' => '$last_name' ),
											    array('$unwind' => '$country_code' ),
											    array('$unwind' => '$last_name' ),
											    array('$unwind' => '$users_mobile' ),
											    array('$unwind' => '$users_email' ),
											    array('$unwind' => '$store_name' ),
										        array('$unwind' => '$availableArabianPoints' ),
											  	array('$group' => array('_id' => '$user_id_to' ,
											  		                    'count'=>array('$sum' => 1) ,
											  		                    'users_name' 		=> array('$first'=> '$users_name'),
											  		                    'last_name' 		=> array('$first'=> '$last_name'),
											  		                    'country_code' 		=> array('$first'=> '$country_code'),
											  		                    'users_mobile' 		=> array('$first'=> '$users_mobile'),
											  		                    'users_email' 		=> array('$first'=> '$users_email'),
											  		                    'store_name' 		=> array('$first'=> '$store_name'),
											  		                    'user_id_to' 		=> array('$first'=> '$user_id_to'),
											  		                    'availableArabianPoints' => array('$first'=> '$availableArabianPoints'),
											  		                    'recharge_amt' 		=> array('$sum'=> '$recharge_amt'),
											  		                    'cash_collected' 	=> array('$sum'=> '$cash_collected'),
											  		                    'due_amount' 		=> array('$sum'=> '$due_amount') ,
											  		                    'advanced_amount' 	=> array('$sum'=> '$advanced_amount'), 
											  		                    'advanced_amount' 	=> array('$sum'=> '$advanced_amount'), 
											  		                    'cash_collected' => array('$sum'=> '$cash_collected'), 
											  		                )),
												array('$sort'=>$short_field)
										); 

										// echo '<pre>';print_r($currentQuery);die;
		
		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			// echo '<pre>';
			// print_r($currentQuery);die();
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION


	/***********************************************************************
	** Function name: duemanagement
	** Developed By: Dilip Halder
	** Purpose: This function used for get due management Statements
	** Date : 20 June 2023
	** Updated By : Dilip Halder
	** Updated At : 30 December 2023
	************************************************************************/
	public function duemanagementweb($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
	{  
		$filterArray 				=	array();
		if($where_condition['search']):
			array_push($filterArray,array($where_condition['search'][0]=>new MongoDB\BSON\Regex ($where_condition['search'][1],'i')));
		endif;
		if($where_condition['where']):
			foreach($where_condition['where'] as $where_key=>$where_value):
				array_push($filterArray,array($where_key=>$where_value));
			endforeach;
		endif;

		if($where_condition['where_gte']):

            foreach($where_condition['where_gte'] as $where_key => $where_value):
                // $data = $this->mongo_db->where_gte($where_value[0],$where_value[1]);
                array_push($filterArray,array($where_value[0]=> array('$gte' => $where_value[1])));
            endforeach;
        endif;

        if($where_condition['where_lte']):

            foreach($where_condition['where_lte'] as $where_key => $where_value):
                // $data = $this->mongo_db->where_gte($where_value[0],$where_value[1]);
                array_push($filterArray,array($where_value[0]=> array('$lte' => $where_value[1])));
            endforeach;
        endif;

		$selectFields 		= array('$project' => array(
								'_id'=>0 ,
								'due_management_id'=>1,
								'user_id_to'=>1,
								'user_id_deb'=>1,
								'recharge_amt'=>1,
								'cash_collected'=>1,
								'due_amount'=>1,
								'record_type'=>1,
								'advanced_amount'=>1,
								'users_name'=>'$users.users_name',
								'last_name'=>'$users.last_name',
								'country_code'=>'$users.country_code',
								'users_mobile'=>'$users.users_mobile',
								'users_email'=>'$users.users_email',
								'availableArabianPoints'=>'$users.availableArabianPoints',
								'store_name'=>'$users.store_name',
								'bind_person_id'=>'$users.bind_person_id',
								'sender_users_name'=>'$sender.users_name',
								'sender_last_name'=>'$sender.last_name',
								'sender_country_code'=>'$sender.country_code',
								'sender_users_mobile'=>'$sender.users_mobile',
								'sender_users_email'=>'$sender.users_email',
								'created_by'=>'$sender.users_type',
								'user_type'=>'$users.users_type',
								'created_at'=>'$created_at',
							));

		$whereCondition		=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(
										        array('$lookup'=>array('from'=>'da_users','localField'=>'user_id_to','foreignField'=>'users_id','as'=>'users')),
										        array('$lookup'=>array('from'=>'da_users','localField'=>'user_id_deb','foreignField'=>'users_id','as'=>'sender')),

											    $selectFields,
											    array('$unwind' => '$users_name' ),
											    array('$unwind' => '$last_name' ),
											    array('$unwind' => '$country_code' ),
											    array('$unwind' => '$last_name' ),
											    array('$unwind' => '$users_mobile' ),
											    array('$unwind' => '$users_email' ),
											    array('$unwind' => '$sender_users_name' ),
											    array('$unwind' => '$sender_last_name' ),
											    array('$unwind' => '$sender_country_code' ),
										        array('$unwind' => '$sender_users_mobile' ),
										        array('$unwind' => '$sender_users_email' ),
										        array('$unwind' => '$store_name' ),
										        array('$unwind' => '$created_at' ),
										        array('$unwind' => '$availableArabianPoints' ),
										        array('$unwind' => '$created_by' ),
										        array('$unwind' => '$user_type' ),
										        array('$unwind' => '$bind_person_id' ),
											  	array('$match'=>array('$and'=>$whereCondition)),
											  	array('$group' => array('_id' => '$user_id_to' ,
											  		                    'count'=>array('$sum' => 1) ,
											  		                    'users_name' 		=> array('$first'=> '$users_name'),
											  		                    'last_name' 		=> array('$first'=> '$last_name'),
											  		                    'country_code' 		=> array('$first'=> '$country_code'),
											  		                    'users_mobile' 		=> array('$first'=> '$users_mobile'),
											  		                    'users_email' 		=> array('$first'=> '$users_email'),
											  		                    'store_name' 		=> array('$first'=> '$store_name'),
											  		                    'user_id_to' 		=> array('$first'=> '$user_id_to'),
											  		                    'user_id_deb' 		=> array('$first'=> '$user_id_deb'),
											  		                    'sender_users_name' 	 => array('$first'=> '$sender_users_name'),
											  		                    'sender_last_name' 		 => array('$first'=> '$sender_last_name'),
											  		                    'sender_country_code' 	 => array('$first'=> '$sender_country_code'),
											  		                    'sender_users_mobile' 	 => array('$first'=> '$sender_users_mobile'),
											  		                    'sender_users_email' 	 => array('$first'=> '$sender_users_email'),
											  		                    'availableArabianPoints' => array('$first'=> '$availableArabianPoints'),
											  		                    'bind_person_id' 	=> array('$first'=> '$bind_person_id'), 
											  		                    'recharge_amt' 		=> array('$sum'=> '$recharge_amt'),
											  		                    'cash_collected' 	=> array('$sum'=> '$cash_collected'),
											  		                    'due_amount' 		=> array('$sum'=> '$due_amount') ,
											  		                    'advanced_amount' 	=> array('$sum'=> '$advanced_amount'), 
											  		                    'cash_collected' 	=> array('$sum'=> '$cash_collected'), 
											  		                    'created_by' 		=> array('$last'=> '$created_by'),
											  		                    'user_type' 		=> array('$last'=> '$user_type'),
											  		                    'created_at' 		=> array('$last'=> '$created_at'),
											  		                )),
												array('$sort'=>$short_field)
										); 
										// echo '<pre>';print_r($currentQuery);die;
		
		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION


	/***********************************************************************
	** Function name: todaysales
	** Developed By: Dilip Halder
	** Purpose: This function used for get due management Statements
	** Date : 09 Nov 2023
	************************************************************************/
	public function todaysales($tbl_name='',$whereCon='')
	{  

		$this->mongo_db->select('*');
		$this->mongo_db->where($whereCon);
		$dueData = $this->mongo_db->get($tbl_name);
		
		$todaysales = 0;
		foreach ($dueData as $key => $item):
			$todaysales = $todaysales + $item['total_price'];
		endforeach;

		 return $todaysales;

 			
	}	// END OF FUNCTION

}