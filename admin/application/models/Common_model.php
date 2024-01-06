<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
	}

	function milliseconds() {
	    $mt = explode(' ', microtime());
	    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
	}

	function microseconds() {
	    $mt = explode(' ', microtime());
	    return ((int)$mt[1]) * 1000000 + ((int)round($mt[0] * 1000000));
	}

	// This function will return a random
	// string of specified length
	function random_strings($length_of_string)
	{
	 
	    // String of all alphanumeric character
	    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	 
	    // Shuffle the $str_result and returns substring
	    // of specified length
	    return substr(str_shuffle($str_result),
	                       0, $length_of_string);
	}

	/***********************************************************************
	** Function Name returnIntegerEncryptValue
	** Developed By : Manoj Kumar
	** Input Parameters 
	** 1. inputInteger = The integer value which need to encrypted
	** 2. returnLength = THe number of digit which need to be return from functon.
	** Function Process :- The function will take integr input and multiply it with current unixtimestamp.
	** The new value will be encrypt using md5 which return 32 bit string, The encrypt string convert to ASCII
	** value and then the desire lenght sub string will be return by function.
	** Date : 14 JUNE 2021
	************************************************************************/
	public function returnIntegerEncryptValue($inputInteger, $returnLength = 16)
	{
		$returnEncryptInterValue = '';
		$lenghtCounter = 0;
		$currentTimeStamp = $this->microseconds();//$this->milliseconds();//time();
		$vauleToBeEncrypted = $inputInteger * $currentTimeStamp;
		$encryptedString = md5($vauleToBeEncrypted);
		$encryptedStringCharArray = str_split($encryptedString);
		foreach($encryptedStringCharArray as $charValue):
			$asciiValue = ord($charValue);
			$asciiValueLength = strlen($asciiValue);
			$lenghtCounter = $lenghtCounter + $asciiValueLength;
			if($lenghtCounter < $returnLength):	
				$returnEncryptInterValue = $returnEncryptInterValue.$asciiValue;
				$returnEncryptInterValue.' rln '.strlen($returnEncryptInterValue);
			else:
				break;
			endif;
		endforeach;
		$remaingNumberOfDigits = $returnLength - strlen($returnEncryptInterValue);
		if($remaingNumberOfDigits > 0):
			for($remaingDigitsCounter = 0; $remaingDigitsCounter < $remaingNumberOfDigits; $remaingDigitsCounter++):
				$returnEncryptInterValue = $returnEncryptInterValue .rand ( 0 , 9);
			endfor;
		endif;
		return $returnEncryptInterValue;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name : getNextSequence
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get Next Sequence
	** Date : 14 JUNE 2021
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
	** Function name : getNextIdSequence
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get Next Id Sequence
	** Date : 29 JULY 2021
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

		if($type == 'Freelancer'):
			$constant 		=	array('users_seq_id'=>'FL');
		endif;
		
		if($type == 'lotto_products'):
			$constant 		=	array('users_seq_id'=>'LP');
		endif;
		
		$cueNewId 	 	= 	$newId<10?'0000'.$newId:($newId<100?'000'.$newId:($newId<1000?'00'.$newId:($newId<10000?'0'.$newId:$newId)));
		return $constant[$sequenceType].$cueNewId;
	}	// END OF FUNCTION


	/***********************************************************************
	** Function name : getNextInspectorIdSequence
	** Developed By : Manoj Kumar
	** Purpose  : This function used for get Inspector Next Id Sequence
	** Date : 03 AUGUST 2021
	************************************************************************/
	public function getNextInspectorIdSequence()
	{
		$sequenceType		=	'inspector_sequence_id';
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
		$constant 		=	array('inspector_sequence_id'=>'CMPI');
		$cueNewId 	 	= 	$newId<10?'000'.$newId:($newId<100?'00'.$newId:($newId<1000?'0'.$newId:$newId));
		return $constant[$sequenceType].$cueNewId;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name : addData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for add data
	** Date : 14 JUNE 2021
	************************************************************************/
	public function addData($tableName='',$param=array())
	{
		$last_insert_id 		=	$this->mongo_db->insert($tableName,$param);
		return $last_insert_id;
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : editData
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for edit data
	 * * Date : 14 JUNE 2021
	 * * **********************************************************************/
	function editData($tableName='',$param='',$fieldName='',$fieldValue='')
	{ 
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$this->mongo_db->set($param);
		$this->mongo_db->update($tableName);
		return true;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name : addManyData
	** Developed By : Afsar Ali
	** Purpose  : This function used for multi add data
	** Date : 11 FEB 2023
	************************************************************************/
	public function addManyData($tableName='',$param=array())
	{
		$last_insert_id 		=	$this->mongo_db->batch_insert($tableName,$param);
		return $last_insert_id;
	}	// END OF FUNCTION

	
	/***********************************************************************
	** Function name : editDataByMultipleCondition
	** Developed By : Manoj Kumar
	** Purpose  : This function used for edit data by multiple condition
	** Date : 14 JUNE 2021
	************************************************************************/
	function editDataByMultipleCondition($tableName='',$param=array(),$whereCondition=array())
	{
		$this->mongo_db->where($whereCondition);
		$this->mongo_db->set($param);
		$this->mongo_db->update($tableName);
		return true;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name : editMultipleDataByMultipleCondition
	** Developed By : Manoj Kumar
	** Purpose  : This function used for edit data by multiple condition
	** Date : 14 JUNE 2021
	************************************************************************/
	function editMultipleDataByMultipleCondition($tableName='',$param=array(),$whereCondition=array())
	{
		$this->mongo_db->where($whereCondition);
		$this->mongo_db->set($param);
		$this->mongo_db->update_all($tableName);
		return true;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name : editMultipleDataByMultipleCondition
	** Developed By : Manoj Kumar
	** Purpose  : This function used for edit data by multiple condition
	** Date : 14 JUNE 2021
	************************************************************************/
	function editMultipleDataBySingleCondition($tableName='',$param='',$fieldName='',$fieldValue='')
	{ 
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$this->mongo_db->set($param);
		$this->mongo_db->update_all($tableName);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : deleteData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete data
	** Date : 14 JUNE 2021
	************************************************************************/
	function deleteData($tableName='',$fieldName='',$fieldValue='')
	{
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$this->mongo_db->delete_all($tableName);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : deleteParticularData
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete particular data
	** Date : 14 JUNE 2021
	************************************************************************/
	function deleteParticularData($tableName='',$fieldName='',$fieldValue='')
	{ 
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$this->mongo_db->delete_all($tableName);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : deleteByMultipleCondition
	** Developed By : Manoj Kumar
	** Purpose  : This function used for delete by multiple condition
	** Date : 14 JUNE 2021
	************************************************************************/
	function deleteByMultipleCondition($tableName='',$whereCondition=array())
	{
		$this->mongo_db->where($whereCondition);
		$this->mongo_db->delete_all($tableName);
		return true;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: getDataByParticularField
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by encryptId
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getDataByParticularField($tableName='',$fieldName='',$fieldValue='')
	{  
	    
		$this->mongo_db->select('*');
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$result = $this->mongo_db->find_one($tableName);
		
		if($result):
		  //print_r($result);die;
			return $result;
		else:
			return false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getSingleDataByParticularField
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Single Data By Particular Field
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getSingleDataByParticularField($fields=array(),$tableName='',$fieldName='',$fieldValue='')
	{  
		if(empty($fields)): $fields 	=	'*'; endif; 
		$this->mongo_db->select($fields);
		if($fieldName && $fieldValue):
			$this->mongo_db->where(array($fieldName=>$fieldValue));
		endif;
		$result = $this->mongo_db->find_one($tableName);
		if($result):
			return json_decode(json_encode($result),true);
		else:
			return false;
		endif;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: getDataByQuery
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by query
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getData($action='',$tbl_name='',$wcon='',$shortField='',$num_page='',$cnt='')
	{  
		
		$this->mongo_db->select('*');		
		if(isset($wcon['where']) && $wcon['where'])	$this->mongo_db->where($wcon['where']);	
		if(isset($wcon['where_or']) && $wcon['where_or'])	$this->mongo_db->where_or($wcon['where_or']);	
		if(isset($wcon['where_ne']) && $wcon['where_ne'])	$this->mongo_db->where_ne($wcon['where_ne'][0],$wcon['where_ne'][1]);	
		if(isset($wcon['where_in']) && $wcon['where_in'])	$this->mongo_db->where_in($wcon['where_in'][0],$wcon['where_in'][1]);	
		if(isset($wcon['where_between']) && $wcon['where_between'])	$this->mongo_db->where_between($wcon['where_between'][0],$wcon['where_between'][1],$wcon['where_between'][2]);	
		
		if(isset($wcon['where_gte']) && $wcon['where_gte'])	$this->mongo_db->where_gte($wcon['where_gte'][0][0] ,$wcon['where_gte'][0][1]);	
		if(isset($wcon['where_lte']) && $wcon['where_lte'])	$this->mongo_db->where_lte($wcon['where_lte'][0][0] ,$wcon['where_lte'][0][1]);	
		
		if(isset($wcon['like']) && $wcon['like'])	$this->mongo_db->like($wcon['like'][0],$wcon['like'][1],'i',TRUE,TRUE);
		if($shortField)				$this->mongo_db->order_by($shortField);				
		if($num_page):				$this->mongo_db->limit($num_page);
									$this->mongo_db->offset($cnt);						
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
	** Function name: getFieldInArray
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by condition
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getFieldInArray($field='',$tbl_name='',$wcon='')
	{  
		$returnarray			=	array();
		$this->mongo_db->select(array($field));	
		if(isset($wcon['where']))	$this->mongo_db->where($wcon['where']);	
		if(isset($wcon['where_ne']) && $wcon['where_ne'])	$this->mongo_db->where_ne($wcon['where_ne'][0],$wcon['where_ne'][1]);	
		if(isset($wcon['where_in']) && $wcon['where_in'])	$this->mongo_db->where_in($wcon['where_in'][0],$wcon['where_in'][1]);	
		if(isset($wcon['where_or']) && $wcon['where_or'])	$this->mongo_db->where_or($wcon['where_or']);	
		if(isset($wcon['where_between']) && $wcon['where_between'])	$this->mongo_db->where_between($wcon['where_between'][0],$wcon['where_between'][1],$wcon['where_between'][2]);	
		if(isset($wcon['like']))	$this->mongo_db->like($wcon['like'][0],$wcon['like'][1],'i',TRUE,TRUE);
		$result = $this->mongo_db->get($tbl_name);
		if($result):	
			foreach($result as $info):
				array_push($returnarray,$info[$field]);
			endforeach;
		endif;
		return $returnarray;
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name: getLastOrderByFields
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Last Order By Fields
	** Date : 14 JUNE 2021
	************************************************************************/ 
	public function getLastOrderByFields($field='',$tbl_name='',$fieldName='',$fieldValue='')
	{  
		$this->mongo_db->select(array($field));	
		if(isset($fieldName) && isset($fieldValue)):
			$this->mongo_db->where(array($fieldName=>$fieldValue));
		endif;
		$this->mongo_db->order_by(array($field=>'DESC'));	
		$this->mongo_db->limit(1);
		$result = $this->mongo_db->find_one($tbl_name);  
		if($result):	
			return $result[$field];
		else:
			return 0;
		endif;
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : setAttributeInUse
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for set Attribute In Use
	 * * Date : 14 JUNE 2021
	 * * **********************************************************************/
	function setAttributeInUse($tableName='',$param='',$fieldName='',$fieldValue='')
	{ 
		$paramarray[$param]	=	'Y';
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$this->mongo_db->set($paramarray);
		$this->mongo_db->update($tableName);
		return true;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getPaticularFieldByFields
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Paticular Field By Fields
	** Date : 14 JUNE 2021
	************************************************************************/ 
	public function getPaticularFieldByFields($field='',$tbl_name='',$fieldName='',$fieldValue='')
	{  
		$this->mongo_db->select(array($field));	
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$this->mongo_db->limit(1);
		$result = $this->mongo_db->find_one($tbl_name);  
		if($result):	
			return $result[$field];
		else:
			return 0;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getParticularFieldByMultipleCondition
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Particular Field By Multiple Condition
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getParticularFieldByMultipleCondition($fields=array(),$tableName='',$wcon='')
	{  
		if(empty($fields)): $fields 	=	'*'; endif; 
		$this->mongo_db->select($fields);
		if(isset($wcon['where']))	$this->mongo_db->where($wcon['where']);	
		if(isset($wcon['where_ne']) && $wcon['where_ne'])	$this->mongo_db->where_ne($wcon['where_ne'][0],$wcon['where_ne'][1]);	
		if(isset($wcon['where_in']) && $wcon['where_in'])	$this->mongo_db->where_in($wcon['where_in'][0],$wcon['where_in'][1]);	
		if(isset($wcon['where_or']) && $wcon['where_or'])	$this->mongo_db->where_or($wcon['where_or']);	
		if(isset($wcon['where_between']) && $wcon['where_between'])	$this->mongo_db->where_between($wcon['where_between'][0],$wcon['where_between'][1],$wcon['where_between'][2]);	
		if(isset($wcon['like']))	$this->mongo_db->like($wcon['like'][0],$wcon['like'][1],'i',TRUE,TRUE);
		$result = $this->mongo_db->find_one($tableName);
		if($result):
			return json_decode(json_encode($result),true);
		else:
			return false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getDataByNewQuery
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by query
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getDataByNewQuery($fields=array(),$action='',$tbl_name='',$wcon='',$shortField='',$num_page='',$cnt='')
	{  
		if(empty($fields)): $fields 	=	'*'; endif; 
		$this->mongo_db->select($fields);	
		if(isset($wcon['where']) && $wcon['where'])	$this->mongo_db->where($wcon['where']);	
		if(isset($wcon['where_ne']) && $wcon['where_ne'])	$this->mongo_db->where_ne($wcon['where_ne'][0],$wcon['where_ne'][1]);	
		if(isset($wcon['where_or']) && $wcon['where_or'])	$this->mongo_db->where_or($wcon['where_or']);	
		if(isset($wcon['where_between']) && $wcon['where_between'])	$this->mongo_db->where_between($wcon['where_between'][0],$wcon['where_between'][1],$wcon['where_between'][2]);	
		if(isset($wcon['like']) && $wcon['like']):	
			$this->mongo_db->like($wcon['like'][0],$wcon['like'][1],'i',TRUE,TRUE);
		endif;
		if(isset($wcon['where_in']) && $wcon['where_in']):	
			foreach($wcon['where_in'] as $whereInData):  
				$this->mongo_db->where_in($whereInData[0],$whereInData[1]);
			endforeach;
		endif;
		if(isset($wcon['where_gte']) && $wcon['where_gte']):	
			foreach($wcon['where_gte'] as $whereGteData):  
				$this->mongo_db->where_gte($whereGteData[0],$whereGteData[1]);
			endforeach;
		endif;
		if(isset($wcon['where_lte']) && $wcon['where_lte']):	
			foreach($wcon['where_lte'] as $whereLteData):  
				$this->mongo_db->where_lte($whereLteData[0],$whereLteData[1]);
			endforeach;
		endif;
		if($shortField)				$this->mongo_db->order_by($shortField);				
		if($num_page):				
			$this->mongo_db->limit($num_page);
			$this->mongo_db->offset($cnt);						
		endif;
		if($action == 'count'):	
			return $this->mongo_db->count($tbl_name);
		elseif($action == 'single'):	
			$result = $this->mongo_db->find_one($tbl_name);
			if($result):
				return json_decode(json_encode($result),true);
			else:
				return false;
			endif;
		elseif($action == 'multiple'):	
			$result = $this->mongo_db->get($tbl_name);
			if($result):	
				return json_decode(json_encode($result),true);
			else:		
				return false;
			endif;
		else:
			return false;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getDataByMultipleAndCondition
	** Developed By: Manoj Kumar
	** Purpose: This function used for get data by query
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getDataByMultipleAndCondition($tbl_name='',$query='',$arrayfield=array())
	{  
		$resultData = array();
		$result 		= 		$this->mongo_db->aggregate($tbl_name,$query,array('batchSize'=>4)); 
		foreach($result as $results):
			foreach($results as $key=>$valye):
				if(is_array($results[$key])):
					$results[$key] 	=	$results[$key][0];
				endif;
			endforeach;
			array_push($resultData,$results);
		endforeach;		
		$groupedData 	= json_decode(json_encode($resultData), true);
		return $groupedData;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getDataByGroupBy
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Data By Group By
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getDataByGroupBy($tbl_name='',$wcon1='',$wcon2='',$wcon3='',$wcon4='')
	{  
		$resultData = array();
		$Query 		=		array($wcon1,$wcon2,$wcon3,$wcon4);
		$result 	= 		$this->mongo_db->aggregate($tbl_name,$Query,array('batchSize'=>4));
		foreach($result as $result){
			$returnData = $result['result'];
			array_push($resultData,$returnData);
		}			
		$groupedData 	= json_decode(json_encode($resultData[0]), true);
		return $groupedData;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name: getMultipleDataByParticularField
	** Developed By: Ashish
	** Purpose: This function used for getMultipleDataByParticularField
	** Date : 14 JUNE 2021
	************************************************************************/
	public function getMultipleDataByParticularField($tableName='',$fieldName='',$fieldValue='')
	{  
		$this->mongo_db->select('*');
		if($fieldName && $fieldValue):
			$this->mongo_db->where(array($fieldName=>$fieldValue));
		endif;
		$result = $this->mongo_db->get($tableName);
		if($result):
			return $result;
		else:
			return false;
		endif;
		
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name : fetch_common_data_type
	** Developed By : Ashish UMrao
	** Purpose  : This function used for fetch common data type
	** Date : 14 JUNE 2021
	************************************************************************/
	function fetch_common_data_type($tableName='',$fieldName='',$fieldValue='')
	{
		$this->mongo_db->select('id,astrologer_id');
		$this->mongo_db->where(array($fieldName=>$fieldValue));
		$result = $this->mongo_db->get($tableName);
		if($result):
			return $result;
		else:
			return false;
		endif;
	}
	/***********************************************************************
	** Function name : delete_image_by_image_name
	** Developed By : Ashish UMrao
	** Purpose  : This function used for delete image by image name
	** Date : 14 JUNE 2021
	************************************************************************/
	function delete_image_by_image_name($tableName='',$fieldName='',$fieldValue='')
	{	//echo $tableName.'---'.$fieldName.'---'.$fieldValue; die;
		$this->mongo_db->where(array($fieldName => $fieldValue));
		$this->mongo_db->delete($tableName);
		return true;
	}

	/***********************************************************************
	** Function name: getTitleSlug
	** Developed By: Manoj Kumar
	** Purpose: This function used for get Title Slug
	** Date : 14 JUNE 2021
	************************************************************************/ 
	public function getTitleSlug($title='',$tbl_name='')
	{  
		$this->mongo_db->select('count');	
		$this->mongo_db->where(array('title'=>$title));
		$this->mongo_db->where(array('table_name'=>$tbl_name));
		$this->mongo_db->limit(1);
		$result = $this->mongo_db->find_one('hcap_title_count');
		$data 	= $result['count']?$result['count']:0;
		if($data == 0):	
			$param['title']					=	$title;
			$param['table_name']			=	$tbl_name;
			$param['count']					=	(int)$data+1;
			$alastInsertId					=	$this->addData('hcap_title_count',$param);
			$titleSlug 						=	url_title(strtolower($title));
		else:
			$count							=	(int)$data+1;
			$this->mongo_db->where(array('title'=>$title));
			$this->mongo_db->where(array('table_name'=>$tbl_name));
			$this->mongo_db->set(array('count'=>(int)$count));
			$this->mongo_db->update('hcap_title_count');
			$titleSlug 						=	url_title(strtolower($title.'-'.$count));
		endif;
		return $titleSlug;//$newId;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: checkDuplicate
	** Developed By 	: AFSAR ALI
	** Purpose 			: This function used for check duplicate entry
	** Date 			: 05 APRIL 2022
	************************************************************************/ 
	public function checkDuplicate($tbl_name, $whereCon){
		$this->mongo_db->where($whereCon);
		return $this->mongo_db->count($tbl_name, $whereCon);
	} // END OF FUNCTION
	/***********************************************************************
	** Function name 	: checkBulkDuplicate
	** Developed By 	: AFSAR ALI
	** Purpose 			: This function used for check duplicate entry
	** Date 			: 11 FEB 2023
	************************************************************************/ 
	public function checkBulkDuplicate($tbl_name, $whereCon = array()){
		$this->mongo_db->where_in('coupon_code',$whereCon);
		return $this->mongo_db->count($tbl_name, $whereCon);
	} // END OF FUNCTION
	/***********************************************************************
	** Function name 	: getRechargeStatistics
	** Developed By 	: AFSAR ALI
	** Purpose 			: This function used for get recharge statistics
	** Date 			: 31 OCT 2022
	************************************************************************/ 
	public function getRechargeStatistics($wcon){
		$this->mongo_db->select('*');
		if(isset($wcon['where']) && $wcon['where']):
			$this->mongo_db->where($wcon['where']);
		endif;
		if(isset($wcon['where_gte']) && $wcon['where_gte']):	
			foreach($wcon['where_gte'] as $whereGteData):  
				$this->mongo_db->where_gte($whereGteData[0],$whereGteData[1]);
			endforeach;
		endif;
		if(isset($wcon['where_lte']) && $wcon['where_lte']):	
			foreach($wcon['where_lte'] as $whereLteData):  
				$this->mongo_db->where_lte($whereLteData[0],$whereLteData[1]);
			endforeach;
		endif;
		
		$result = $this->mongo_db->get('da_loadBalance');
		$data['admin'] = 0;
		$data['Retailer_credit'] = 0;
		$data['Retailer_debit'] = 0;
		$data['sales_person_credit'] = 0;
		$data['sales_person_debit'] = 0;
		$data['users_credit'] = 0;
		$data['users_debit'] = 0;

		foreach ($result as $key => $value) {
			if($value['created_by'] == 'ADMIN'){
				if($value['record_type'] == 'Credit'){
					$data['admin'] = $data['admin'] + $value['arabian_points'];
					$checkUser = $this->getParticularFieldByMultipleCondition(['users_type'], 'da_users', ['users_id' => $value['user_id_cred']]);
					if($checkUser['users_type'] == 'Retailer'){
						$data['Retailer_credit'] = $data['Retailer_credit'] + $value['arabian_points'];
					}elseif($checkUser['users_type'] == 'Sales Person'){
						$data['sales_person_credit'] = $data['sales_person_credit'] + $value['arabian_points'];
					}elseif($checkUser['users_type'] == 'Users'){
						$data['users_credit'] = $data['users_credit'] + $value['arabian_points'];
					}
				}
			}elseif($value['created_by'] == 'Retailer'){
				if($value['record_type'] == 'Credit'){
					$data['Retailer_credit'] = $data['Retailer_credit'] + $value['arabian_points'];
				}elseif($value['record_type'] == 'Debit'){
					$data['Retailer_debit'] = $data['Retailer_debit'] + $value['arabian_points'];
				}
			}elseif($value['created_by'] == 'Sales Person'){
				if($value['record_type'] == 'Credit'){
					$data['sales_person_credit'] = $data['sales_person_credit'] + $value['arabian_points'];
				}elseif($value['record_type'] == 'Debit'){
					$data['sales_person_debit'] = $data['sales_person_debit'] + $value['arabian_points'];
				}	
			}elseif($value['created_by'] == 'Users'){
				if($value['record_type'] == 'Credit'){
					$data['users_credit'] = $data['users_credit'] + $value['arabian_points'];
				}elseif($value['record_type'] == 'Debit'){
					$data['users_debit'] = $data['users_debit'] + $value['arabian_points'];
				}
			}
		}
		return $data;
	} // END OF FUNCTION
	/***********************************************************************
	** Function name 	: getRegistrationStatistics
	** Developed By 	: AFSAR ALI
	** Purpose 			: This function used for get recharge statistics
	** Date 			: 04 NOV 2022
	************************************************************************/ 
	public function getRegistrationStatistics($wcon){
		//echo '<pre>';print_r($wcon);
		$this->mongo_db->select('*');
		if(isset($wcon['where']) && $wcon['where']):
			$this->mongo_db->where($wcon['where']);
		endif;
		if(isset($wcon['where_gte']) && $wcon['where_gte']):	
			foreach($wcon['where_gte'] as $whereGteData):  
				$this->mongo_db->where_gte($whereGteData[0],$whereGteData[1]);
			endforeach;
		endif;
		if(isset($wcon['where_lte']) && $wcon['where_lte']):	
			foreach($wcon['where_lte'] as $whereLteData):  
				$this->mongo_db->where_lte($whereLteData[0],$whereLteData[1]);
			endforeach;
		endif;
		
		$data = $this->mongo_db->count('da_users');
		return $data;
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: getOrdersStatistics
	** Developed By 	: AFSAR ALI
	** Purpose 			: This function used for get recharge statistics
	** Date 			: 04 NOV 2022
	************************************************************************/ 
	public function getOrdersStatistics($wcon){
		//echo '<pre>';print_r($wcon);
		$this->mongo_db->select('*');
		if(isset($wcon['where']) && $wcon['where']):
			$this->mongo_db->where($wcon['where']);
		endif;
		if(isset($wcon['where_gte']) && $wcon['where_gte']):	
			foreach($wcon['where_gte'] as $whereGteData):  
				$this->mongo_db->where_gte($whereGteData[0],$whereGteData[1]);
			endforeach;
		endif;
		if(isset($wcon['where_lte']) && $wcon['where_lte']):	
			foreach($wcon['where_lte'] as $whereLteData):  
				$this->mongo_db->where_lte($whereLteData[0],$whereLteData[1]);
			endforeach;
		endif;
		
		$data = $this->mongo_db->count('da_orders');
		return $data;
	} // END OF FUNCTION

	/***********************************************************************
	** Function name 	: getOrdersStatistics
	** Developed By 	: AFSAR ALI
	** Purpose 			: This function used for get recharge statistics
	** Date 			: 04 NOV 2022
	************************************************************************/ 
	public function getQuickTicketStatistics($wcon){
		//echo '<pre>';print_r($wcon);
		$this->mongo_db->select('*');
		if(isset($wcon['where']) && $wcon['where']):
			$this->mongo_db->where($wcon['where']);
		endif;
		if(isset($wcon['where_gte']) && $wcon['where_gte']):	
			foreach($wcon['where_gte'] as $whereGteData):  
				$this->mongo_db->where_gte($whereGteData[0],$whereGteData[1]);
			endforeach;
		endif;
		if(isset($wcon['where_lte']) && $wcon['where_lte']):	
			foreach($wcon['where_lte'] as $whereLteData):  
				$this->mongo_db->where_lte($whereLteData[0],$whereLteData[1]);
			endforeach;
		endif;
		
		$data = $this->mongo_db->count('da_ticket_orders');
		return $data;
	} // END OF FUNCTION

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
								'order_request_qty'=>1,
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
			//echo $per_page;die();
			if($page):
				array_push($currentQuery,array('$skip'=>(int)$per_page));
				array_push($currentQuery,array('$limit'=>(int)$page));
			endif; 
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name 	: updateInventoryStock
	** Developed By 	: Afar Ali
	** Purpose 			: This function used for update Inventory Stock
	** Date 			: 14 NOV 2022
	************************************************************************/
	public function updateInventoryStock($pid='', $qty='')
	{  
		$resultData = array();
		$whereCon = [ 'products_id' => $pid ];
		$this->mongo_db->select('*');
		$this->mongo_db->where($whereCon);
		$resultData = $this->mongo_db->find_one('da_products');
		$updataData = (int)$resultData['inventory_stock'] - (int)$qty;

		$this->common_model->editData('da_products', [ 'inventory_stock' =>  $updataData ], 'products_id', $pid );
		return;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name: getProductRequestList
	** Developed By: Afsar Ali
	** Purpose: This function used for get product request Data
	** Date : 23 NOV 2022
	************************************************************************/
	public function getProductRequestList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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
								'request_id'=>1,
								'collection_point_id'=>1,
								'inventory_id'=>1,
								'product_id'=>1,
								'users_id'=>1,
								'request_qty'=>1,
								'sent_qty'=>1,
								'creation_date'=>1,
								'sent_date'=>1,
								'status'=>1,

								'collection_point_name'=>'$from_collection_point.collection_point_name',
								'users_email'=>'$from_collection_point.users_email',
								'users_mobile'=>'$from_collection_point.users_mobile',

								'product_name'=>'$from_product.title',
								'stock'=>'$from_product.stock',
								'product_seq_id'=>'$from_product.product_seq_id',

								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_emirate_collection_point','localField'=>'collection_point_id','foreignField'=>'collection_point_id','as'=>'from_collection_point')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'products_id','foreignField'=>'product_id','as'=>'from_product')),

												  array('$lookup'=>array('from'=>'da_orders','localField'=>'order_id','foreignField'=>'order_id','as'=>'from_order')),

												  $selectFields,
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field)); //echo '<pre>';print_r($currentQuery);die;

		if($action == 'count'):
			$totalDataCount				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			if($totalDataCount):
				return count($totalDataCount);
			endif;
		elseif($action == 'single'):
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData[0];
		elseif($action == 'multiple'):	
			// if($per_page):
			// 	array_push($currentQuery,array('$skip'=>(int)$page));
			// 	array_push($currentQuery,array('$limit'=>(int)$per_page));
			// endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getReferralList
	** Developed By: Afsar Ali
	** Purpose: This function used for get referral point Data
	** Date : 04 JAN 2023
	************************************************************************/
	public function getReferralList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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
								'referral_id'=>1,
								'referral_user_code'=>1,
								'referral_from_id'=>1,
								'referral_to_id'=>1,
								'referral_percent'=>1,
								'referral_cart_amount'=>1,
								'referral_amount'=>1,
								'referral_product_id'=>1,
								'creation_ip'=>1,
								'created_at'=>1,
								'created_by'=>1,
								'status'=>1,

								'product_name'=>'$from_products.title',
								
								'sender_name' => '$from_sender.users_name',
								'sender_mobile' => '$from_sender.users_mobile',
								'sender_email' => '$from_sender.users_email',

								'receiver_name' => '$from_receiver.users_name',
								'receiver_mobile' => '$from_receiver.users_mobile',
								'receiver_email' => '$from_receiver.users_email',


								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;
		

		if(count($whereCondition) != 0):
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_users','localField'=>'referral_from_id','foreignField'=>'users_id','as'=>'from_sender')),
												  array('$lookup'=>array('from'=>'da_users','localField'=>'referral_to_id','foreignField'=>'users_id','as'=>'from_receiver')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'referral_product_id','foreignField'=>'products_id','as'=>'from_products')),

												  $selectFields, 
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field));//echo '<pre>';print_r($currentQuery);die;
		else:
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_users','localField'=>'referral_from_id','foreignField'=>'users_id','as'=>'from_sender')),
												  array('$lookup'=>array('from'=>'da_users','localField'=>'referral_to_id','foreignField'=>'users_id','as'=>'from_receiver')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'referral_product_id','foreignField'=>'products_id','as'=>'from_products')),

												  $selectFields, 
												  // array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field));//echo '<pre>';print_r($currentQuery);die;
		endif;

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
	** Function name: getReferralList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Sales Data
	** Date : 11 JAN 2023
	************************************************************************/
	public function getSaleslList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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
								'title'=>1,
								'products_id'=>1,
								'stock'=>1,
								'totalStock'=>1,
								'target_stock'=>1,
							  	'category_name'=>1,
							  	'sub_category_name'=>1,
							  	'product_image'=>1,
							  	'clossingSoon'=>1,
							  	'product_seq_id'=>1,
							  	'validuptodate'=>1,
							  	'validuptotime'=>1,
								'actual_product_name'=>'$from_prize.title',
								'actual_product_image'=>'$from_prize.prize_image'
								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		// echo "<pre>";
		// print_r($whereCondition);
		// die();
		

		if(count($whereCondition) != 0):
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_prize','localField'=>'products_id','foreignField'=>'product_id','as'=>'from_prize')),
												  $selectFields, 
												  array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field));//echo '<pre>';print_r($currentQuery);die;
		else:
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_users','localField'=>'referral_from_id','foreignField'=>'users_id','as'=>'from_sender')),
												  array('$lookup'=>array('from'=>'da_users','localField'=>'referral_to_id','foreignField'=>'users_id','as'=>'from_receiver')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'referral_product_id','foreignField'=>'products_id','as'=>'from_products')),

												  $selectFields, 
												  // array('$match'=>array('$and'=>$whereCondition)),
												  array('$sort'=>$short_field));//echo '<pre>';print_r($currentQuery);die;
		endif;

		// echo "<pre>";
		// print_r($currentQuery);
		// die();

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
	** Function name: getsignupBonusList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Signup Bonus List
	** Date : 04 JAN 2023
	************************************************************************/
	public function getsignupBonusList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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
								'load_balance_id'=>1,
								'user_id_cred'=>1,
								'arabian_points'=>1,
								'record_type'=>1,
								'arabian_points_from'=>1,
								'creation_ip'=>1,
								'created_at'=>1,
								'created_by'=>1,
								'status'=>1,
								
								'users_name' => '$from_users.users_name',
								'users_mobile' => '$from_users.users_mobile',
								'users_email' => '$from_users.users_email',


								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_users','localField'=>'user_id_cred','foreignField'=>'users_id','as'=>'from_users')),

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
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION


	/***********************************************************************
	** Function name: getMembershipCashbackList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Membership Cashback List
	** Date : 04 JAN 2023
	************************************************************************/
	public function getMembershipCashbackList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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

		$selectFields 			=array('$project' => array(
								'_id'=>0 ,
								'arabian_points'=>1,
								'created_at' => 1 ,
								'status'=>1,
								'arabian_points_from'=>1,
								'record_type'=>1,
								'users_name'=>'$from_users.users_name',
								'users_type'=>'$from_users.users_type' 
							));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_users','localField'=>'user_id_cred','foreignField'=>'users_id','as'=>'from_users')),

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
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name: getTicketCount
	** Developed By: Dilip Halder
	** Purpose: This function used for get Ticket soldout count List
	** Date : 14 March 2023
	************************************************************************/
	public function getTicketCount($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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
								'product_id'=>1,
								'tickets_prefix' => 1,
								'tickets_sequence_start' => 1,
								'tickets_sequence_end'=> 1,
								'tickets_seq_id'=>1,
								'coupon_sold_number'=>'$quickcoupons_totallist.coupon_sold_number',
								'product_title' => '$products.title' ,
								'status'=>1,
								'created_at'=>'$quickcoupons_totallist.created_at'
							));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		if($page):
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_quickcoupons_totallist','localField'=>'tickets_seq_id','foreignField'=>'tickets_seq_id','as'=>'quickcoupons_totallist')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'product_id','foreignField'=>'products_id','as'=>'products')),
												  $selectFields,
												  	array('$match'=>array('$and'=>$whereCondition)),
												  	array('$limit' => (int)$page),
												    array('$sort'=>$short_field));

		else:

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_quickcoupons_totallist','localField'=>'tickets_seq_id','foreignField'=>'tickets_seq_id','as'=>'quickcoupons_totallist')),
										    array('$lookup'=>array('from'=>'da_products','localField'=>'product_id','foreignField'=>'products_id','as'=>'products')),
										    $selectFields,
											array('$sort'=>$short_field));

		endif;
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
	** Function name: duemanagement
	** Developed By: Dilip Halder
	** Purpose: This function used for get due management Statements
	** Date : 28 April 2023
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
											    // array('$unwind' => '$last_name' ),
											    // array('$unwind' => '$country_code' ),
											    // array('$unwind' => '$last_name' ),
											    // array('$unwind' => '$users_mobile' ),
											    // array('$unwind' => '$users_email' ),
											    // array('$unwind' => '$sender_users_name' ),
											    // array('$unwind' => '$sender_last_name' ),
											    // array('$unwind' => '$sender_country_code' ),
										        // array('$unwind' => '$sender_users_mobile' ),
										        // array('$unwind' => '$sender_users_email' ),
										        array('$unwind' => '$created_at' ),
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
											  		                    'sender_users_name' 		=> array('$first'=> '$sender_users_name'),
											  		                    'sender_last_name' 		=> array('$first'=> '$sender_last_name'),
											  		                    'sender_country_code' 		=> array('$first'=> '$sender_country_code'),
											  		                    'sender_users_mobile' 		=> array('$first'=> '$sender_users_mobile'),
											  		                    'sender_users_email' 		=> array('$first'=> '$sender_users_email'),
											  		                    'availableArabianPoints' => array('$first'=> '$availableArabianPoints'),
											  		                    'recharge_amt' 		=> array('$sum'=> '$recharge_amt'),
											  		                    'cash_collected' 	=> array('$sum'=> '$cash_collected'),
											  		                    'due_amount' 		=> array('$sum'=> '$due_amount') ,
											  		                    'advanced_amount' 	=> array('$sum'=> '$advanced_amount'), 
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
	** Function name: getAllWalletStatements
	** Developed By: Dilip Halder
	** Purpose: This function used for get Wallet Statements
	** Date : 02 Apirl 2023
	************************************************************************/
	public function getAllWalletStatements($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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
								'users_id' => 1,
								'users_name' => 1,
								'last_name' => 1,
								'users_mobile' => 1,
								'users_email' => 1,
								'users_type' => 1,
								'availableArabianPoints' => 1,

								'order_statement' => '$order',
								// 'order_id'=> '$order.order_id',
								// 'payment_from' => '$order.payment_from',
								// 'created_at' => '$order.created_at',
								// 'price' => '$order.price',
								// 'availableArabianPoints' => '$order.availableArabianPoints',
								// 'end_balance' => '$order.end_balance',
								// 'payment_mode' => '$order.payment_mode',
								// 'order_status' =>  '$order.order_status',
								// 'product_is_donate' => '$order.product_is_donate',

								
								// 'product_name' => '$orderdetails.product_name',
								// 'price' => '$orderdetails.price',
								// 'quantity' => '$orderdetails.quantity',

								// 'users_name' => '$users.users_name',
								// 'users_mobile' => '$users.users_mobile',
								// 'users_email' => '$users.users_email',
								// 'arabian_points' => '$loadBalance.arabian_points',
								// // 'record_type' => '$loadBalance.record_type',
								// 'cashback_amount' => '$cashback.cashback',
								// 'cashback_created_at' => '$cashback.created_at',
								// 'referral_amount' => '$referral.referral_amount',
								// 'referral_created_at' => '$referral.created_at',


								// 'product_name' => '$coupons.product_title',
								// 'quantity' => '$coupons.product_qty',
								// 'total_price' => '$coupons.total_price',
								// 'users_name' => '$coupons.order_first_name',
								// 'last_name' => '$coupons.order_last_name',
								// 'users_mobile' => '$coupons.order_users_mobile',
								// 'users_email' => '$coupons.order_users_email',



							
							));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		if($page):
		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_orders_details','localField'=>'order_id','foreignField'=>'order_id','as'=>'order')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'user_id','foreignField'=>'users_id','as'=>'users')),
										    	  array('$lookup'=>array('from'=>'da_users','localField'=>'user_id','foreignField'=>'users_id','as'=>'users')),
									     		  // array('$lookup'=>array('from'=>'da_loadBalance','localField'=>'order_id','foreignField'=>'order_id','as'=>'loadBalance')),
									     		  array('$lookup'=>array('from'=>'da_cashback','localField'=>'order_id','foreignField'=>'order_id','as'=>'cashback')),
									     		  array('$lookup'=>array('from'=>'referral_product','localField'=>'order_id','foreignField'=>'order_id','as'=>'referral')),
												  $selectFields,
												  	array('$match'=>array('$and'=>$whereCondition)),
												  	array('$limit' => (int)$page),
												    array('$sort'=>$short_field));
		else:

		$currentQuery					=	array(
												  array('$lookup'=>array('from'=>'da_orders','localField'=>'users_id','foreignField'=>'user_id','as'=>'order')),
												  array('$lookup'=>array('from'=>'da_orders_details','localField'=>'order_id','foreignField'=>'order_id','as'=>'orderdetails')),
												  array('$lookup'=>array('from'=>'da_products','localField'=>'user_id','foreignField'=>'users_id','as'=>'users')),
										    	  array('$lookup'=>array('from'=>'da_users','localField'=>'user_id','foreignField'=>'users_id','as'=>'users')),


									     		  array('$lookup'=>array('from'=>'da_ticket_coupons','localField'=>'ticket_order_id','foreignField'=>'ticket_order_id','as'=>'coupons')),






									     		  // array('$lookup'=>array('from'=>'da_loadBalance','localField'=>'order_id','foreignField'=>'order_id','as'=>'loadBalance')),
									     		  array('$lookup'=>array('from'=>'da_cashback','localField'=>'order_id','foreignField'=>'order_id','as'=>'cashback')),
									     		  array('$lookup'=>array('from'=>'referral_product','localField'=>'order_id','foreignField'=>'order_id','as'=>'referral')),
										    $selectFields,
											array('$sort'=>$short_field));

		endif;
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

}	
