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
		
		if($type=='doctor'):  
		$constant 		=	array('doctor_seq_id'=>'DOC-');
		endif;

		if($type=='hospital'):  
		$constant 		=	array('hospital_seq_id'=>'HID-');
		endif;

		if($type=='product'):  
		$constant 		=	array('product_seq_id'=>'PID-');
		endif;

		if($type=='health'):  
		$constant 		=	array('health_seq_id'=>'HID-');
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
		$result =	$this->mongo_db->where(array($fieldName=>$fieldValue));
		$this->mongo_db->set($param);
		$this->mongo_db->update($tableName);
		
		return true;
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
	** Function name: getInventoryList
	** Developed By: Afsar Ali
	** Purpose: This function used for get Property Data
	** Date : 16 NOV 2022
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
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION
	/***********************************************************************
	** Function name: getOrderList
	** Developed By: Afsar Ali
	** Purpose: This function used for get order Data
	** Date : 16 NOV 2022
	************************************************************************/
	public function getOrderList($action='',$tbl_name='',$where_condition='',$short_field='',$page='',$per_page='')
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
								'sequence_id'=>1,
								'order_id'=>1,
								'user_id'=>1,
								'user_type'=>1,
								'user_email'=>1,
								'user_phone'=>1,
								'product_is_donate'=>1,
								//'shipping_method'=>1,
								//'emirate_id'=>1,
								//'emirate_name'=>1,
								'collection_point_id'=>1,
								'collection_point_name'=>1,
								//'shipping_address'=>1,
								//'shipping_charge'=>1,
								//'inclusice_of_vat'=>1,
								'subtotal'=>1,
								'vat_amount'=>1,
								'total_price'=>1,
								'payment_mode'=>1,
								'payment_from'=>1,
								'order_status'=>1,
								'creation_ip'=>1,
								'created_at'=>1,
								'collection_status'=>1,
								'expairy_data'=>1,

								'product_id'=>'$from_order_details.product_id',
								'product_name'=>'$from_orders_details.product_name',
								'quantity'=>'$from_orders_details.quantity',

								// 'collection_point_name'=>'$from_collection_point.collection_point_name',
								// 'users_email'=>'$from_collection_point.users_email',
								// 'users_mobile'=>'$from_collection_point.users_mobile',

								));

		$whereCondition					=	array();

		if($filterArray):
			foreach($filterArray as $filterInfo):
				array_push($whereCondition,$filterInfo);
			endforeach;
		endif;

		$currentQuery					=	array(array('$lookup'=>array('from'=>'da_orders','localField'=>'collection_point_id','foreignField'=>'collection_point_id','as'=>'from_orders')),
												  array('$lookup'=>array('from'=>'da_orders_details','localField'=>'order_details_id','foreignField'=>'sequence_id','as'=>'from_orders_details')),

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
			if($per_page):
				array_push($currentQuery,array('$skip'=>(int)$page));
				array_push($currentQuery,array('$limit'=>(int)$per_page));
			endif;
			$currentData				=	$this->getDataByMultipleAndCondition($tbl_name,$currentQuery);
			return $currentData;
		endif;
	}	// END OF FUNCTION

	
	/***********************************************************************
	** Function name: getAvailableTickets
	** Developed By: Dilip Halder
	** Purpose: This function used for get Available Tickets
	** Date : 10 August 2023
	************************************************************************/

	public function getAvailableTickets($CA='')
	{		
		 	//Get current Ticket order sequence from admin panel.
			$tblName = 'da_tickets_sequence';
			$whereCon2['where']		 			= 	array('product_id' => (int)$CA['product_id'] , 'status' => 'A');	
			$shortField 						= 	array('tickets_seq_id'=>'ASC');
			$TicketSequence 				= 	$this->common_model->getData('multiple',$tblName,$whereCon2,$shortField,'0','0');

			$tblName = 'da_products';
			$whereCon2['where']		 			= 	array('products_id' => (int)$CA['product_id'] , 'status' => 'A');	
			$productDetails 					= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

			if($productDetails):
				// getting number of tickets genereating wirth this order.
				if($CA['is_donated'] == 'N'):
					$tickets = $CA['quantity']*$productDetails['sponsored_coupon'];
				else:
					$tickets = 2*$CA['quantity']*$productDetails['sponsored_coupon'];
				endif;
			else:

			 	if($CA['payment_from'] == 'App'):
			 		echo outPut(0,lang('SUCCESS_CODE'),lang('CAMPAIGN_CLOSED'). " for " .$CA['name'],$result);die();
			 	else:
			 		$this->session->set_flashdata('error', lang('CAMPAIGN_CLOSED'));
        			redirect('user-cart');
			 	endif;

			endif;

			foreach ($TicketSequence as $key => $CurrentTicketSequence):
				//Getting Tickets generating sequence from each available slots ..
				$ticketID[] 				= $CurrentTicketSequence['tickets_seq_id'];
				$series[] 					= $CurrentTicketSequence['tickets_prefix'];
				$tickets_sequence_start[] 	= $CurrentTicketSequence['tickets_sequence_start'];
				$tickets_sequence_end[] 	= $CurrentTicketSequence['tickets_sequence_end'];
				$tickets_sold_count[] 		= $CurrentTicketSequence['tickets_sold_count'];
				$total_tickets[] 			= $CurrentTicketSequence['tickets_sequence_end'] - $CurrentTicketSequence['tickets_sequence_start'] ;
			endforeach;

			// available slots count.
			for($i=0; $i < $tickets ; $i++):
				if(empty($NextSolt)):
					$soltNo    = 0;
				endif;

				A: //ticket slot changing.

				if($NextSolt && empty($tickets_sequence_start[$soltNo])):

					if(count($coupons) >= $tickets):
						$coupons = array_slice($coupons, 0,$tickets); 
						return $coupons;
					endif;

					if($CA['payment_from'] == 'App'):
			 			echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'). " for " .$CA['name'],$result);die();
				 	else:
				 		$this->session->set_flashdata('error', lang('TICKET_NOT_AVAILABLE'). " for " .$CA['name']);
	        			redirect('user-cart');
				 	endif;

				endif;

				// Coupon sequence start..
				$sequence = $tickets_sequence_start[$soltNo]+$i;
				$singleCoupon = $series[$soltNo].$sequence;

				// Online coupon checking...
				$whereCondition['where'] = array('coupon_code' => $singleCoupon , 'product_id' => (int)$CA['product_id']);
				$CouponCount			 = $this->geneal_model->getData2('count','da_coupons',$whereCondition);

				// Quick coupon checking...
				$whereCondition1['where']        = array( 'coupon_code' => array('$in' => [$singleCoupon]) ,'product_id'=> (int)$CA['product_id']   );
				$QuickSoldoutTickets			 = $this->geneal_model->getData2('count','da_ticket_coupons',$whereCondition1);

				if($CouponCount == 1 || $QuickSoldoutTickets == 1):

					 $tickets_sequence_start[$soltNo]++;
					 // echo 'Soldout Tickets = '.$singleCoupon .'<br>';
					 goto A;
				elseif($tickets_sequence_end[$soltNo] == $sequence):
			 		 
			 		 $coupons[] 			= $singleCoupon;
					 $tickets_sequence_start++;
				  	 $NextSolt ='next slot';
					 $i =0;
					 $soltNo++;
					 goto A;

				endif;
			 		
			 		$coupons[] 			= $singleCoupon;
			endfor;

			$coupons = array_slice($coupons, 0, $tickets);

			return $coupons;
			
	}

	/***********************************************************************
	** Function name: getOrderList
	** Developed By: Dilip Halder
	** Purpose: This function used for get order Data
	** Date : 14 July 2023
	************************************************************************/
	public function addCoupons($couponData='')
	{
		
		$ticket_count = count($couponData['tickets']);
 		
		//Get current Ticket order sequence from admin panel.
		$tblName = 'da_tickets_sequence';
		$whereCon2['where']		 			= 	array('product_id' => (int)$couponData['product_id'] , 'status' => 'A');	
		$shortField 						= 	array('tickets_seq_id'=>'ASC');
		$TicketSequence 				= 	$this->common_model->getData('multiple',$tblName,$whereCon2,$shortField,'0','0');
		
		// echo "<pre>";print_r($couponData);die();

		if($TicketSequence):

			foreach($TicketSequence  as $key => $CurrentTicketSequence ):
				
				$range = range($CurrentTicketSequence['tickets_sequence_start'], $CurrentTicketSequence['tickets_sequence_end']); 
				foreach($range as $number):
				    
				    $ticketNumber = $CurrentTicketSequence['tickets_prefix'] .$number;

				    //Adding soldout number for current ticket sequence.
					if(in_array($ticketNumber, $couponData['tickets'])):

						$couponArray[] 		= $ticketNumber;
						$Soldoutcouponcount =  count($couponArray);
						
						$couponTicketSequence["tickets_sold_count"] 	 =	(int)$CurrentTicketSequence['tickets_sold_count']+ $Soldoutcouponcount;
						$this->geneal_model->editData('da_tickets_sequence',$couponTicketSequence,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);
					endif;
				endforeach;

				// removing soldout number to asssign soldout quantity for next slot. 
				unset($couponArray);
				
				// Addeding status inactive for complete soldout solts.
				$ticket  	= $CurrentTicketSequence['tickets_prefix'].$CurrentTicketSequence['tickets_sequence_end'];
				$ticketID   =  $CurrentTicketSequence['tickets_seq_id'];

				$slotEndingTickect =  in_array( $ticket , $couponData['tickets']);
				if($slotEndingTickect):
					$updateTickectSequence['status']		=	 "I";
					$whereTickectSequence =	array('tickets_seq_id' => (int)$ticketID);
					$this->common_model->editDataByMultipleCondition('da_tickets_sequence',$updateTickectSequence,$whereTickectSequence);
				endif;
				//end

			endforeach;

			$lowerBound = (int)$CurrentTicketSequence['tickets_sequence_start'];
			$upperBound = (int)$CurrentTicketSequence['tickets_sequence_end'];
			$percentage = 90;

			/* Finds the number near to 90% from start to end. */
		  	$rangeStart 		= (int)$CurrentTicketSequence['tickets_sequence_start'];
		  	$rangeEnd 			= (int)$CurrentTicketSequence['tickets_sequence_end'];
		  	$percentage 		= 0.90;
		  	$result 			= ($rangeEnd - $rangeStart) * $percentage;
		  
		    $tickectnumber 		= $CurrentTicketSequence['tickets_prefix'].round($result);
	    	
		    // echo $tickectnumber;die();

		    $SoldoutSMStickect 	=  in_array($tickectnumber, $couponData['tickets']);

		    if($SoldoutSMStickect):



			    for ($i=1; $i<=2 ; $i++):
					// send sms form here.
					if($i == 1):
						$country_code='+971';
						$mobileNumber='+971501292591';
						$this->sendTickectFullSMS($mobileNumber,$country_code ,$couponData['product_name']);
					endif;

					if($i == 2):
						$country_code='+971';
						$mobileNumber='+97154332166';
						$this->sendTickectFullSMS($mobileNumber,$country_code ,$couponData['product_name']);
					endif;

					$country_code='+91';
					$mobileNumber='+918700144841';
					$this->sendTickectFullSMS($mobileNumber,$country_code ,$couponData['product_name']);
					die();
			    endfor;
		    endif;

		    for ($i=0; $i < $ticket_count; $i++):

				//Coupon storing work.
				$coupon_code = $couponData['tickets'][$i];

				$couponParam['coupon_id']		= 	(int)$this->geneal_model->getNextSequence('da_coupons');
				$couponParam['order_id']		= 	$couponData['order_id'];;
				$couponParam['users_id']		= 	(int)$couponData['userID'];
				$couponParam['users_email']		= 	$couponData['emailID'];
				$couponParam['product_id']		= 	$couponData['product_id'];
				$couponParam['product_name']	= 	$couponData['product_name'];
				$couponParam['is_donated'] 		=	$couponData['is_donated'];
				$couponParam['coupon_status'] 	=	'Live';
				$couponParam['coupon_code'] 	= 	$coupon_code;
				$couponParam['coupon_type'] 	= 	$couponData['is_donated'] == 'Y' ? "Donated": 'Simple';
				$couponParam['created_at']		=	date('Y-m-d H:i');

				$this->geneal_model->addData('da_coupons',$couponParam);
			endfor;

		endif;

	}


	/***********************************************************************
	** Function name 	: sendTickectFullSMS
	** Developed By 	: Dilip Halder
	** Purpose  		: This is use for send send tickect sms to admin.'
	** Date 			: 17-07-2023
	************************************************************************/
	function sendTickectFullSMS($mobileNumber='',$country_code='',$product_title='') {  
		
		$enableSMS = $this->common_model->getData('single','da_enablesms');

		// Finding country code and sending sms using sms country.
        if($enableSMS['smscountry'] == "enable"):

        	$SMSCOUNTRY = explode(',', $enableSMS['sms_country_available_country']);

			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY as $key => $item):
				$SMSCOUNTRY[$key] = trim($item);
			endforeach;

            // checing country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY) ):
				if($mobileNumber):
					$message		=	$product_title . " Campaign 90% Soldout. Please add more tickets";
					$senderid		=	"AD-DLZARBA";
					$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
        endif;

        // Finding country code and sending sms using digitizebird.
        if($enableSMS['digitizebird'] == "enable"):

        	$SMSCOUNTRY1 = explode(',', $enableSMS['digitizebird_available_country']);
			
			// Removed extra space from country code ...
			foreach ($SMSCOUNTRY1 as $key => $item1):
				$SMSCOUNTRY1[$key] = trim($item1);
			endforeach;

            // checing country code exist or Not...
			if(in_array($country_code, $SMSCOUNTRY1) ):
				if($mobileNumber):
					$message		=	"Your DealzArabia password has been reset successfully";
					$senderid		=	"DLZRBIA";
					$returnMessage	=	$this->sendMessageDigitizebirdFunction($mobileNumber,$message,$senderid);
					return $returnMessage;
				endif;
			endif; 
        endif;
	} //END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : sendMessageFunction 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Message Function
	 * * Date : 08 APRIL 2021
	 * * **********************************************************************/
	public function sendMessageFunction($phone='',$message='',$sender_id='') {
		try {
			if(!empty($phone) && !empty($message) && !empty($sender_id)):
				//Please Enter Your Details
				$user='pltech'; //your username
				$password=SMSCOUNTRYPASSWORD; //your password
				$mobilenumbers=substr($phone,1); //enter Mobile numbers comma seperated
				$message = $message; //enter Your Message
				$senderid=$sender_id; //Your senderid
				
				$messagetype="N"; //Type Of Your Message
				$DReports="Y"; //Delivery Reports
				$url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
				$message = urlencode($message);
				$ch = curl_init();
				if (!$ch){die("Couldn't initialize a cURL handle");}
				$ret = curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt ($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt ($ch, CURLOPT_POSTFIELDS,
				"User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
				$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				//If you are behind proxy then please uncomment below line and provide your proxy ip with port.
				// $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
				$curlresponse = curl_exec($ch); // execute
				if(curl_errno($ch))
				return "ERROR";
				//echo 'curl error : '. curl_error($ch);
				if (empty($ret)) {
					return "some kind of an error happened";
				// some kind of an error happened
				//die(curl_error($ch));
				curl_close($ch); // close cURL handler
				} else {
				$info = curl_getinfo($ch);
				curl_close($ch); // close cURL handler
				return $curlresponse; //echo "Message Sent Succesfully" ;
				}
			endif;
		} catch (\Throwable $th) {
			return "FAIL";
		}
		
	}

	public function sendMessageDigitizebirdFunction($phone='',$message='',$senderid='')
	{
		try {
			if(!empty($phone) && !empty($message) && !empty($senderid)):
				
				$ApiKey 		= 'ybG+HgfvR2YzK/LOlwwBXU7YRhKu+LK5Vi6Mfg5N5AI=';
				$ClientId 		= 'a2d20910-34bc-4e0d-a3bf-904667459a01';
				$CompanyId 		= '7';
				$message = urlencode($message);
				$url = "https://user.digitizebirdsms.com/api/v2/SendSMS?SenderId=$senderid&Is_Unicode=false&Is_Flash=true&Message=$message&MobileNumbers=$phone&ApiKey=$ApiKey&ClientId=$ClientId&CompanyId=$CompanyId";
				
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array( 'accept: text/plain' ),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				return $response; 
			endif;
		} catch (\Throwable $th) {
			return "FAIL";
		}
	}

	/***********************************************************************
	** Function name : addorder
	** Developed By : Dilip Halder
	** Purpose  : This function used Addd new Order details..
	** Date : 28 July 2023
	************************************************************************/
	public function addorder($POST='')
	{	

			$UserID 			 	=   $POST['users_id'];
			$UserTYPE 			 	=	$POST['user_type'];
			$UserEMAIL 			 	=	$POST['user_email'];
			$UserPHONE 			 	=	$POST['user_phone'];
			$product_is_donate 		=	$POST['product_is_donate'];
			$shipping_method 		=	$POST['shipping_method'];
			$emirate_id 			=	$POST['emirate_id'];
			$emirate_name 			=	$POST['emirate_name'];
			$area_id 				=	$POST['area_id'];
			$area_name 				=	$POST['area_name'];
			$collection_point_id 	=	$POST['collection_point_id'];
			$collection_point_name 	=	$POST['collection_point_name'];
			$shipping_charge 		=	$POST['shipping_charge'];
			$inclusice_of_vat 		=	$POST['inclusice_of_vat'];
			$subtotal 				=	$POST['subtotal'];
			$vat_amount 			=	$POST['vat_amount'];
			$total_price 			=	$POST['inclusice_of_vat'];
			$payment_method 		=	$POST['payment_mode'];
			$device_type 			=	$POST['device_type'];
			$app_version 			=	$POST['app_version'];
			
			//Checking user detail in user collection.
			$user_wcon['where']		=	array('users_id' => (int)$UserID );
			$userData 				=	$this->geneal_model->getData2('single', 'da_users', $user_wcon);

			
			if($payment_method == 'Arabian Points'):
				if($inclusice_of_vat > $userData['availableArabianPoints']):
					 echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result); die();
				endif;
			endif;


			if($userData):
				if($userData['status'] == 'A'):
							$wcon['where']		=	array('user_id' => (int)$UserID );
							$cartItems          =	$this->geneal_model->getData2('multiple', 'da_cartItems', $wcon);
							
							if($cartItems == 0):
			                    echo outPut(0,lang('SUCCESS_CODE'),lang('CART_EMPTY'),$result);die();
			                endif;

							foreach($cartItems as $CA):
								$finalPrice += $CA['qty'] * $CA['price'];
								//check Stock
								$stockCheck = $this->geneal_model->getStock($CA['id'], $CA['qty']);
								if($CA['is_donated'] == 'N'):
									$data['product_is_in_donate'] 		 =  'N';
								endif;

								// Ticket generation work......
								$ticketCheck['product_id']  = $CA['id'];
								$ticketCheck['name']  		= $CA['name'];
								$ticketCheck['quantity'] 	= $CA['qty'];
								$ticketCheck['is_donated'] 	= $CA['is_donated'];
								$ticketCheck['payment_from']= 'App';

								// Checking soldout tickets and available tickets.
								$tickets 	= $this->common_model->getAvailableTickets($ticketCheck);

							endforeach;
								
							$data['finalprice'] = $finalPrice;

							if($data['product_is_in_donate'] == 'N'):
								$data['shipping']   = 0;//SHIPPING_CHARGE;
							else:
								$data['shipping']   = 0;
							endif;
							 

							$productCount   =	$this->geneal_model->getData2('count', 'da_cartItems', $wcon);
					
							if($this->input->post('product_is_donate') == 'N'):
								$collection_points  				=	explode('_____',$this->input->post('collection_points'));
							endif;

						    $tblName1 					=	'da_emirate_collection_point';
							$where1['where'] 			=	array( 'status' => 'A','collection_point_id' => (int)$collection_points[2]);
							$order1 					=	['collection_point_id' => 'ASC'];
							$data1						=	$this->geneal_model->getData2('single',$tblName1,$where1,$order1);

							// Generating order param
							$ORparam["sequence_id"]		    	=	(int)$this->geneal_model->getNextSequence('da_orders');
							$ORparam["order_id"]		        =	$this->geneal_model->getNextOrderId();
							$ORparam["user_id"] 				=	(int)$UserID;
							$ORparam["user_type"] 				=	$UserTYPE;
							$ORparam["user_email"] 				=	$UserEMAIL ? $UserEMAIL : '';
							$ORparam["user_phone"] 				=	$UserPHONE;

							if($product_is_donate):
								$ORparam["product_is_donate"] 		=	$product_is_donate;
							else:
								$ORparam["product_is_donate"] 		= '';
							endif;


							if($shipping_method):
								$ORparam["shipping_method"] 		=	$shipping_method;
							else:
								$ORparam["shipping_method"] 		= '';
							endif;

							if($emirate_id):
								$ORparam["emirate_id"] 		=	$emirate_id;
							else:
								$ORparam["emirate_id"] 		= '';
							endif;

							if($emirate_name):
								$ORparam["emirate_name"] 		=	$emirate_name;
							else:
								$ORparam["emirate_name"] 		= '';
							endif;

							if($area_id):
								$ORparam["area_id"] 		=	$area_id;
							else:
								$ORparam["area_id"] 		= '';
							endif;

							if($area_name):
								$ORparam["area_name"] 		=	$area_name;
							else:
								$ORparam["area_name"] 		= '';
							endif;

							if($collection_point_id):
								$ORparam["collection_point_id"] 		=	$collection_point_id;
							else:
								$ORparam["collection_point_id"] 		= '';
							endif;

							if($collection_point_name):
								$ORparam["collection_point_name"] 		=	$collection_point_name;
							else:
								$ORparam["collection_point_name"] 		= '';
							endif;
							$ORparam["shipping_address"]		=	'';
							$ORparam["shipping_charge"] 		=	(float)$shipping_charge ? (float)$shipping_charge :(float)$this->input->post('shipping_charge');
							$ORparam["inclusice_of_vat"] 		=	(float)$inclusice_of_vat ? (float)$inclusice_of_vat : (float)$this->input->post('inclusice_of_vat') ;
							$ORparam["subtotal"] 				=	(float)$subtotal ? (float)$subtotal : (float)$this->input->post('subtotal') ;
							$ORparam["vat_amount"] 				=	(float)$vat_amount ? (float)$vat_amount : (float)$this->input->post('vat_amount');
							$ORparam["total_price"] 			=	(float)$total_price ? (float)$total_price : (float)$ORparam["inclusice_of_vat"];
							$ORparam["payment_mode"] 			=	$payment_method;
							$ORparam["payment_from"] 			=	'App';
							$ORparam["order_status"] 			=	"Initialize";
							$ORparam["device_type"] 			=	$device_type;
						    $ORparam["app_version"] 			=	$app_version;
						 	$ORparam["availableArabianPoints"] 	=	(float)$userData["availableArabianPoints"];
					        $ORparam["end_balance"] 			=	(float)$userData["availableArabianPoints"] - (float)$ORparam["inclusice_of_vat"] ;
							$ORparam["creation_ip"] 			=	$this->input->ip_address();
							$ORparam["created_at"] 				=	date('Y-m-d H:i');

							// Adding details in Order Collection..
							$orderInsertID 						=	$this->geneal_model->addData('da_orders', $ORparam);

							//These field for dealzareabia.ea site.
							$ORparam["product_count"] 			=	(int)$productCount;
							$ORparam["finaltotal"] 				=	(float)$finalPrice;

							foreach($cartItems as $CA):	
								//Manage Inventory
								if($ORparam["product_is_donate"] == 'N'):
									$where['where']		=	array(
																'products_id'			=>	(int)$CA['id'],
																'collection_point_id' 	=>	(int)$ORparam["collection_point_id"]
															);

									$INVcheck  =	$this->geneal_model->getData2('single','da_inventory',$where);
									if($INVcheck <> ''):
										$orqty = $INVcheck['order_request_qty'] + (int)$CA['qty'];
										$INVUpdate['order_request_qty']		=	$orqty;
										$this->geneal_model->editDataByMultipleCondition('da_inventory',$INVUpdate,$where['where']);
										else:
											$INVparam['products_id']				= 	(int)$CA['id'];
											$INVparam['qty']						=	(int)0;
											$INVparam['available_qty']				=	(int)0;
											$INVparam['order_request_qty']			=	(int)$CA['qty'];
											$INVparam['collection_point_id']		=	(int)$ORparam["collection_point_id"];

											$INVparam['inventory_id']				=	(int)$this->common_model->getNextSequence('da_inventory');
											
											$INVparam['creation_ip']				=	currentIp();
											$INVparam['creation_date']				=	(int)$this->timezone->utc_time();//currentDateTime();
											$INVparam['status']						=	'A';
											$this->geneal_model->addData('da_inventory', $INVparam);
										endif;
									endif;
									
									//END
									$ORDparam["order_details_id"] 	=	(int)$this->geneal_model->getNextSequence('da_orders_details');
									$ORDparam["order_sequence_id"]	=	(int)$ORparam["sequence_id"];
									$ORDparam["order_id"]			=	$ORparam["order_id"];
									$ORDparam["user_id"]			=	(int)$CA['user_id'];
									$ORDparam["product_id"] 		=	(int)$CA['id'];
									$ORDparam["product_name"] 		=	$CA['name'];
									$ORDparam["quantity"] 		    =	(int)$CA['qty'];
									if($CA['color']):
										$ORDparam["color"] 		    =	$CA['color'];
									endif;
									if($CA['size']):
										$ORDparam["size"] 		    =	$CA['size'];
									endif;
									$ORDparam["price"] 		        =	(float)$CA['price'];
									$ORDparam["tax"] 		        =	(float)0;
									$ORDparam["subtotal"] 		    =	(float)$CA['subtotal'];
									$ORDparam["is_donated"] 		=	$CA['is_donated'];
									$ORDparam["other"] 		        =	array(
																				'image' 		=>	$CA['other']->image,
																				'description' 	=>	$CA['other']->description,
																				'aed'			=>	$CA['other']->aed
																			);
									$ORDparam["current_ip"] 		=	$CA['current_ip'];
									$ORDparam["rowid"] 				=	$CA['rowid'];
									$ORDparam["curprodrowid"] 		=	$CA['curprodrowid'];

									$this->geneal_model->addData('da_orders_details', $ORDparam);

							endforeach;
										
									$ORparam['userData'] = $userData;
									$serializedArray = urlencode(serialize($ORparam));
							return $serializedArray;

				else:
					echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);die();
				endif;

			else:
				 echo outPut(0,lang('SUCCESS_CODE'),lang('USER_ID_INCORRECT'),$result); die();
			endif;

		
	} // END OF FUNCTION


	public function addquickorders($POST='',$sellerDetails='')
	{	
			$USRERMOBILE = ltrim($this->input->post('users_mobile'), '0');


			// Adding order details.
		    $ORparam["sequence_id"]                 =   (int)$this->geneal_model->getNextSequence('da_ticket_orders');
	        $ORparam["ticket_order_id"]             =   $this->geneal_model->getNextQuickBuyOrderId();
	        $ORparam["user_id"]                     =   (int)$this->input->get('users_id');
	        $ORparam["user_type"]                   =   $sellerDetails['users_type']; //$this->input->post('user_type');
	        $ORparam["user_email"]                  =   $sellerDetails['users_email'];  //$this->input->post('user_email');
	        $ORparam["user_phone"]                  =   $sellerDetails['users_mobile']; //$this->input->post('user_phone');
	        $ORparam["product_id"]                  =   (int)$this->input->post('product_id');
	        $ORparam["product_title"]               =   $this->input->post('product_title');
	        $ORparam["product_qty"]                 =   $this->input->post('product_qty');
	        $ORparam["product_color"]               =   $this->input->post('product_color');
	        $ORparam["product_size"]                =   $this->input->post('product_size');
	        $ORparam["prize_title"]                 =   $this->input->post('prize_title');
	        $ORparam["vat_amount"]                  =   (float)$this->input->post('vat_amount');
	        $ORparam["subtotal"]                    =   (float)$this->input->post('subtotal');
	        $ORparam["total_price"]                 =   (float)$this->input->post('total_price');
	        $ORparam["availableArabianPoints"]      =   (float)$sellerDetails["availableArabianPoints"];
	        $ORparam["end_balance"]                 =   (float)$sellerDetails["availableArabianPoints"] - (float)$ORparam["total_price"] ;
	        $ORparam["payment_mode"]                =   'Arabian Points';
	        $ORparam["payment_from"]                =   'Quick';
	        $ORparam["product_is_donate"]           =   $this->input->post('product_is_donate'); //$this->input->post('product_is_donate');
	        $ORparam["order_status"]                =   "Success";
	        $ORparam["device_type"]                 =   $this->input->post('device_type');
	        $ORparam["app_version"]                 =   $this->input->post('app_version');
	        $ORparam["order_first_name"]            =   $this->input->post('first_name');
	        $ORparam["order_last_name"]             =   $this->input->post('last_name');
	        $ORparam["order_users_country_code"]    =   $this->input->post('country_code')?$this->input->post('country_code'):"+971";
	        $ORparam["order_users_mobile"]          =   $USRERMOBILE;
	        $ORparam["order_users_email"]           =   $this->input->post('users_email');
	        $ORparam["SMS"]                         =   $this->input->post('SMS');
	        $ORparam["creation_ip"]                 =   $this->input->ip_address();
	        $ORparam["created_at"]                  =   date('Y-m-d H:i');
	        
	        //Saving order details for Ticket
	        $orderInsertID                          =   $this->geneal_model->addData('da_ticket_orders', $ORparam);

	        
	        //

	        $tblName                    =   'da_products';
	        $where['where']             =   array( 'products_id'=> (int)$ORparam['product_id'] ,'status' => 'A');
	        $Sortdata                   =   array('category_id'=> 'DESC');
	        $productDetails             =   $this->geneal_model->getData2('single', $tblName, $where, $Sortdata);

	        // Stock update section..
	        $stock                      = (int)$productDetails['stock'] - (int)$ORparam['product_qty']  ; // updated stock.
	        $updateParams               =   array( 'stock' => (int)$stock );    
	        $updatedstatus              = $this->geneal_model->editData('da_products',$updateParams,'products_id',(int)$ORparam['product_id']);

	        
	        $updateParams               =   array( 'order_status' => 'Success','created_at' => $ORparam['created_at']); 
	        $updatedstatus 				= $this->geneal_model->editData('da_ticket_orders',$updateParams,'users_id',(int)$ORparam['user_id']);

	        // Deduct the purchesed points and get available arabian points of user.
        	$currentBal                     =   $this->geneal_model->debitPointsByAPI($ORparam['total_price'],$ORparam["user_id"]); 


        	 // checking available tickets..
			$ticketCheck['product_id']  	= $ORparam['product_id'];
			$ticketCheck['quantity'] 		= $ORparam['product_qty'];
			$ticketCheck['is_donated'] 		= $ORparam['product_is_donate'];
			$ticketCheck['name'] 		   	= $ORparam['product_title'];
			$ticketCheck['payment_from'] 	= 'Quick';

			// Checking soldout tickets and available tickets.
			$couponList 	= $this->common_model->getAvailableTickets($ticketCheck);

        	// Adding coupon ..
        	$couponData['coupon_id']        =   (int)$this->geneal_model->getNextSequence('da_coupons');
            $couponData['users_id']         =   (int)$ORparam["user_id"];
            $couponData['users_email']      =   $ORparam["user_email"];

            $couponData['order_first_name']     =   $ORparam["order_first_name"];
            $couponData['order_last_name']      =   $ORparam["order_last_name"];
            $couponData['order_users_country_code'] =   $ORparam["order_users_country_code"]?$ORparam["order_users_country_code"]:"+971";
            $couponData['order_users_mobile']   =   $ORparam["order_users_mobile"];
            $couponData['order_users_email']    =   $ORparam["order_users_email"];

            $couponData['ticket_order_id']      =   $ORparam["ticket_order_id"];
            $couponData['product_id']           =   (int)$ORparam['product_id'];
            $couponData['product_title']        =   $ORparam['product_title'];
            $couponData['product_qty']          =   $ORparam['product_qty'];
            $couponData['total_price']          =   $ORparam['total_price'];
            $couponData["product_qty"]          =   $ORparam['product_qty'];
            $couponData["product_color"]        =   $ORparam['product_color'];
            $couponData["prize_title"]          =   $ORparam['prize_title'];
            $couponData["device_type"]          =   $ORparam['device_type'];
            $couponData["app_version"]          =   $ORparam['app_version'];
            $couponData['is_donated']           =   $ORparam["product_is_donate"];
            $couponData['coupon_status']        =   'Live';
            $couponData['coupon_code']          =   $couponList;
            $couponData['draw_date']            =   array($productDetails['draw_date']);
            $couponData['draw_time']            =   array($productDetails['draw_time']);
            if($ORparam["product_is_donate"] == 'Y'):
            	$couponData['coupon_type']          =   'Donated';
            endif;
            $couponData['created_at']           =   date('Y-m-d H:i');

            $this->geneal_model->addData('da_ticket_coupons',$couponData);

            $couponParam['tickets'] = $couponList;
            $couponParam['product_id']	= (int)$ORparam['product_id'];
            $this->updateCouponsAvailability($couponParam);

            $results = $couponData;

            if($couponData['order_users_email']):
                $this->emailsendgrid_model->sendQuickMailToUser($couponData);
            endif;

            if($ORparam['SMS'] == "Y"):
                $this->sms_model->sendQuickTicketDetails($couponData['ticket_order_id'],$couponData['order_users_mobile'],$couponList,$couponData['product_id'],$couponData['order_users_country_code'],$couponData['is_donated']);
            endif;
            
            return $couponData;


	}


	/***********************************************************************
	** Function name : addorder
	** Developed By : Dilip Halder
	** Purpose  : This function used Addd new Order details..
	** Date : 28 July 2023
	************************************************************************/
	public function updateCouponsAvailability($couponData='')
	{
		$ticket_count = count($couponData['tickets']);

		for ($i=0; $i < $ticket_count; $i++):

			$tblName = 'da_quickcoupons_totallist';
			$whereCon2['where']		 			= 	array( 'product_id' => (int)$couponData['product_id'] );	
			$totalsoldCoupons 					= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField);

			//Get current Ticket order sequence from admin panel.
			$tblName = 'da_tickets_sequence';
			$whereCon2['where']		 			= 	array('product_id' => (int)$couponData['product_id'] , 'status' => 'A');	
			$shortField 						= 	array('tickets_seq_id'=>'ASC');
			$CurrentTicketSequence 				= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField,'0','0');

			//Adding soldout number for current ticket sequence.
			$couponTicketSequence["tickets_sold_count"] 	 =	(int)$CurrentTicketSequence['tickets_sold_count']+ 1;
			$this->geneal_model->editData('da_tickets_sequence',$couponTicketSequence,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);

			if(empty($totalsoldCoupons)):

				//Storing total ticket count in da_quickcoupons_totallist
				$quickcoupons1["id"]				=	(int)$this->geneal_model->getNextSequence('da_quickcoupons_totallist');
				$quickcoupons1["product_id"] 		=	(int)$couponData['product_id'];
			    $quickcoupons1["tickets_seq_id"] 	=	(int)$CurrentTicketSequence['tickets_seq_id'];
			    $quickcoupons1["tickets_sold_count"]=	(int)$i+1;
			    $quickcoupons1["creation_ip"] 		=	$this->input->ip_address();
			    $quickcoupons1["created_at"] 		=	date('Y-m-d H:i');
			    //Saving quick coupons number  
			    $this->geneal_model->addData('da_quickcoupons_totallist', $quickcoupons1);
			else:

				$quickcoupons1["tickets_sold_count"]	=	(int)$totalsoldCoupons['tickets_sold_count']+1;
			    $quickcoupons1["creation_ip"] 			=	$this->input->ip_address();
			    $quickcoupons1["updated_at"] 			=	date('Y-m-d H:i');

				$this->geneal_model->editData('da_quickcoupons_totallist',$quickcoupons1,'tickets_seq_id',(int)$CurrentTicketSequence['tickets_seq_id']);

			endif;

			 
			 
		endfor;
	}

	
	/***********************************************************************
	** Function name : CheckAvailableTickets
	** Developed By : Dilip Halder
	** Purpose  : This function used to validate product.
	** Date : 20 October 2023
	************************************************************************/
	public function CheckAvailableTickets($productID, $productQty,$productIsDonated,$plateform,$CouponGenerate='',$USER='')
	{

		// Checking product current status.
		$tblName 				=  'da_products';
		$whereCon2['where']		= 	array('products_id' => (int)$productID );	
		$productData 			= 	$this->common_model->getData('single',$tblName,$whereCon2);
		// echo "<pre>"; print_r( $productData );die();

		// Product Validation start 
		if($plateform === 'web'):
			// Web Error List
			if(empty($productData) ):
			  $this->session->set_flashdata('error', lang('TICKET_NOT_AVAILABLE'). " for " .$productData['title'] );
	    	  redirect('user-cart');
			  die();
			elseif($productData['stock'] == 0):
			   $this->session->set_flashdata('error', lang('OUTOFSTOCK'). " - " .$productData['title'] );
	    	   redirect('user-cart');
			   die();
			elseif($productData['stock'] < (int)$productQty ):
			  $this->session->set_flashdata('error', lang('PRO_QTY'). " for " .$productData['title'] );
	    	  redirect('user-cart');
			  die();
			endif;
		elseif($plateform === 'mobile-web'):
			// Web Error List
			if(empty($productData) ):
			  $this->session->set_flashdata('error', lang('TICKET_NOT_AVAILABLE'). " for " .$productData['title'] );
	    	  redirect('/');
			  die();
			elseif($productData['stock'] == 0):
			   $this->session->set_flashdata('error', lang('OUTOFSTOCK'). " - " .$productData['title'] );
	    	   redirect('/');
			   die();
			elseif($productData['stock'] < (int)$productQty ):
			  $this->session->set_flashdata('error', lang('PRO_QTY'). " for " .$productData['title'] );
	    	  redirect('/');
			  die();
			endif;

		elseif($plateform === 'app'):
			// App Error List
			if(empty($productData) ):
			  echo outPut(0,lang('SUCCESS_CODE'),lang('TICKET_NOT_AVAILABLE'),$result);die();
			elseif($productData['stock'] == 0):
			  echo outPut(0,lang('SUCCESS_CODE'),lang('OUTOFSTOCK'). " - " .$productData['title'],$result);die();
			elseif($productData['stock'] < (int)$productQty ):
			  echo outPut(0,lang('SUCCESS_CODE'),lang('PRO_QTY'). " for " .$productData['title'],$result);die();
			endif;
		endif;
		// END


		if(!empty($productData)):

			// Checking product current status.
			$tblName 				=  'da_tickets_sequence';
			$whereCon2['where']		= 	array('product_id' => (int)$productID , "status" => "A" );	
			// Tickets details
			$ticketDetails 			= 	$this->common_model->getData('multiple',$tblName,$whereCon2);

			if($productData['sponsored_coupon']):
				$sponsored_coupon = $productData['sponsored_coupon'];
			else:
				$sponsored_coupon = 1;
			endif;

			if($productIsDonated == 'N' ):
				$soldOutQty = $productQty*$sponsored_coupon ;
			elseif($productIsDonated == 'Y'):
			 	$soldOutQty = $productQty*$sponsored_coupon*2 ;
			endif;

			if($this->input->post('isVoucher') == 'Y'):
				$soldOutQty = 2*$productQty*$sponsored_coupon;
			endif;

			if(!empty($ticketDetails)):
				//slots available
				$ticketSlotCount		= 	$this->common_model->getData('count',$tblName,$whereCon2);
				foreach ($ticketDetails as $key => $items):
					$TotalTicketCount   += $items['total_ticket_count'];
				endforeach;
			endif;
			
			$slot= 0;

			


			$tickets_prefix 		= $ticketDetails[$slot]['tickets_prefix'];
			// $totalTickts 			= $ticketDetails[$slot]['total_ticket_count'];
			$totalTickts 			= $TotalTicketCount;
			$randomTicketlength 	= $ticketDetails[$slot]['coupon_length'];
			$soldoutTickts 			= $ticketDetails[$slot]['coupon_sold_number']?$ticketDetails[$slot]['coupon_sold_number']:0;
			$soldOutQty             = $soldOutQty;

			$AvailableTicketCount  = $totalTickts-$soldoutTickts;

			if($plateform === 'web'):
				// WEB Error List.
				if($soldOutQty > $AvailableTicketCount):
					$this->session->set_flashdata('error', lang('TICKET_NOT_AVAILABLE'). " for " .$productData['title']);
			    	redirect('user-cart');
					die();
				endif;
			elseif($plateform === 'mobile-web'):
				// WEB Error List.
				if($soldOutQty > $AvailableTicketCount):
					$this->session->set_flashdata('error', lang('TICKET_NOT_AVAILABLE'). " for " .$productData['title']);
			    	redirect('/');
					die();
				endif;
			elseif($plateform === 'app'):


			endif;


			//USER Verification
			if($USER['USERID']):

				$tbl_name  		= 'da_users';
				$whereCon  		= array('users_id'  =>(int)$USER['USERID'] ,'status'=> 'A');
				$SellerDetails  = $this->geneal_model->getOnlyOneData($tbl_name, $whereCon);

				if($plateform === 'web'):
				// WEB Error List.
					if($SellerDetails['status'] == "A"):
						if($SellerDetails['availableArabianPoints'] < $USER['total_price']):
			  				$this->session->set_flashdata('error', lang('LOW_BALANCE')); redirect('user-cart'); die();
			  			elseif($SellerDetails['status'] == "I" || $SellerDetails['status'] == "D" ):
			  				$this->session->set_flashdata('error', lang('ACCOUNT_BLOCKED')); redirect('user-cart'); die();
						endif;
					endif;

				elseif($plateform === 'mobile-web'):
					// WEB Error List.
					if($SellerDetails['status'] == "A"):
						
						if($SellerDetails['availableArabianPoints']< $USER['total_price']):
			  				$this->session->set_flashdata('error', lang('LOW_BALANCE')); redirect('/'); die();
			  			elseif($SellerDetails['status'] == "I" || $SellerDetails['status'] == "D" ):
			  				$this->session->set_flashdata('error', lang('ACCOUNT_BLOCKED')); redirect('/'); die();
						endif;
						
					endif;

				elseif($plateform === 'app'):

					if($SellerDetails['status'] == "A"):
						
						if($SellerDetails['availableArabianPoints']< $USER['total_price']):
			  				echo outPut(0,lang('SUCCESS_CODE'),lang('LOW_BALANCE'),$result);die();
			  			elseif($SellerDetails['status'] == "I" || $SellerDetails['status'] == "D" ):
			  				echo outPut(0,lang('SUCCESS_CODE'),lang('ACCOUNT_BLOCKED'),$result);die();
						endif;

					endif;

				endif;
				

			endif;


			if($CouponGenerate):
				 
					$tblName 						=	"da_coupons";
					$whereExistinCoupons['where']	=	array('product_id' => (int)$productID );
					$NormalCouponData 		=	$this->common_model->getData('multiple',$tblName, $whereExistinCoupons);


					$tblNameQ 						=	"da_ticket_coupons";
					$whereQExistinCoupons['where']	=	array('product_id' => (int)$productID );
					$QuickCouponData 				=	$this->common_model->getData('multiple',$tblNameQ, $whereQExistinCoupons);

					$currentExistingCouponData1 = array_column($QuickCouponData, 'coupon_code');

					$QuickExistingCoupons =array();
					foreach ($currentExistingCouponData1 as $key => $item) {
						for ($i=0; $i < count($item); $i++) { 
							array_push($QuickExistingCoupons ,$item[$i]);
						}
					}
					
					$NormalCouponData = array_column($NormalCouponData, 'coupon_code');

					$currentExistingCouponData = array_merge($NormalCouponData,$QuickExistingCoupons );

				    if($ticketSlotCount):
				    	for($i=0; $i < $ticketSlotCount; $i++):
							$available_tickets_in_slots = $ticketDetails[$i]['total_ticket_count'] -$ticketDetails[$i]['coupon_sold_number'];
							$tickets_prefix1 = $ticketDetails[$i]['tickets_prefix'];
							$SoltticketCount[] =  $available_ticketsSslots +=$available_tickets_in_slots;
						endfor;
				    endif;
			      	

					// Generate and display unique coupon codes
					$uniqueCoupons = array();
					while($soldOutQty > 0) {
					    
					    $coupon = $this->generateCouponCode($tickets_prefix,$randomTicketlength,$currentExistingCouponData);
					    $uniqueCoupons[] = $coupon;
					    $soldOutQty--;
					    
					    //Assinging next tickets prefix..
					    $GeneratedCouponCount = count($uniqueCoupons);
					    foreach ($SoltticketCount as $key => $item) {
				  		  	if($GeneratedCouponCount == $item):
				  		  		 $tickets_prefix = $ticketDetails[$key+1]['tickets_prefix'];
				  		  	endif;
					    }

					}

					return $uniqueCoupons;

			endif;


		endif;
	}

	/***********************************************************************
	** Function name : generateCouponCode
	** Developed By : Dilip Halder
	** Purpose  : This function used to generate coupons only.
	** Date : 20 October 2023
	************************************************************************/
	function generateCouponCode($tickets_prefix,$randomTicketlength,$currentExistingCouponData) {
	    $characters = '0123456789';
	    $couponCode = '';
		   
	    // Generate a unique coupon code until it's not a duplicate
	    do {
	        $couponCode = '';
	        for ($i = 0; $i < $randomTicketlength- strlen($tickets_prefix); $i++) {
	            $couponCode .= $characters[rand(0, strlen($characters) - 1)];
	        }

	        $couponCode = $tickets_prefix.$couponCode;

	    } while ($this->isCouponCodeDuplicate($couponCode ,$currentExistingCouponData ));
	    
	    return $couponCode;
	}

	// Function to check if a coupon code is a duplicate
	function isCouponCodeDuplicate($code,$currentExistingCouponData) {
	    // Replace this with your database or data structure logic to check for duplicates // $existingCodes = array('ABC123', 'XYZ789'); // Simulated existing codes
	    $existingCodes = $currentExistingCouponData; // Simulated existing codes
	    return in_array($code, $existingCodes);
	}

	/***********************************************************************
	** Function name : reduceAvailableTickets
	** Developed By : Dilip Halder
	** Purpose  : This function used to reduce soldout coupons availibility .
	** Date : 20 October 2023
	************************************************************************/
	public function reduceAvailableTickets($productID,$couponList)
	{
		// Checking product current status.
		if($couponList):
			foreach ($couponList as $key => $item) :
					$shortField 			=  array('tickets_seq_id' => 1);
					$tblName 				=  'da_tickets_sequence';
					$whereCon2['where']		= 	array('product_id' => (int)$productID , "status" => "A" );	
					$ticketDetails 			= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField);
					 
					$coupon_sold_number 					= 	(int)$ticketDetails['coupon_sold_number'] +1;
					$ticketUpdate['coupon_sold_number']		=	(int)$coupon_sold_number;

					if($coupon_sold_number  == $ticketDetails['total_ticket_count']):
						$ticketUpdate['status']	=	'I';
					else:
						$ticketUpdate['status']	=	'A';
					endif;
					
					$ticketwhere['where']		=	array('product_id' => (int)$productID ,'tickets_seq_id' => (int)$ticketDetails['tickets_seq_id'] );
					$this->common_model->editDataByMultipleCondition('da_tickets_sequence',$ticketUpdate,$ticketwhere['where']);

					// 90% soldout sms for amdin.
					$soldoutPercenteage =  round($ticketDetails['total_ticket_count']*90/100);

					if($soldoutPercenteage == $coupon_sold_number):
						//-------------------------------------------------------------------------------------------------------//
		                    $tblName 				=  'da_products';
							$whereCon2['where']		= 	array('products_id' => (int)$productID , "status" => "A" );	
							$productDetails 			= 	$this->common_model->getData('single',$tblName,$whereCon2,$shortField);
						//-------------------------------------------------------------------------------------------------------//
					 	// send sms form here.
	                    $country_code='+971';
	                    $mobileNumber='+971501292591';
	                    $this->common_model->sendTickectFullSMS($mobileNumber,$country_code ,$productDetails['title']);

	                    $country_code='+971';
	                    $mobileNumber='+97154332166';
	                    $this->common_model->sendTickectFullSMS($mobileNumber,$country_code ,$productDetails['title']);

					endif;
			endforeach;

		endif;
	}

}	