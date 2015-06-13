<?php

class Application_Model_Research
{
	protected $gpsDataId;

	/**
	*	Load a new file of mco. Each part of the file will be inserted in the properly part.
	*	
	* @param $_FILE $file - mco's file
	* @access public
	* @return integer
	*/
	public function loadFile($file,$projectId)
	{
		if($file['gpsData_file']){
			$handle = @fopen($file['gpsData_file']['tmp_name'], "r");
		    if ($handle){
		    	$x=1;
				while (!feof($handle)){
					$buffer = fgets($handle, 4096);
					$splited = explode (';',$buffer);
					if($x != 1){ 
						if ($splited[1] != '') {       
							$gpsDataId = $this->newGpsData($splited,$projectId);
							if(!$gpsDataId){
								return false;
							}
						}
					}
					$x++;
				}
				fclose($handle);
		    }
		  	return $gpsDataId;
		}
		else if($file['researchData_file']){
			$handle = @fopen($file['researchData_file']['tmp_name'], "r");
		    if ($handle){
		    	$x=1;
				while (!feof($handle)){
					$buffer = fgets($handle, 4096);
					$splited = explode (';',$buffer);
					if($x != 1){
						if ($splited[1] != '') {
						    $gpsDataId = $this->newResearchData($splited,$projectId);
							if(!$gpsDataId){
								return false;
							}
						}  
					}
					$x++;
				}
				fclose($handle);
		    }
		  	return $gpsDataId;
		}

	}


	/**
	*	Register a new Gps Data.
	*	
	* @param array $data - gps' data
	* @access public
	* @return integer
	*/
	public function newGpsData($data,$projectId)
	{
		$authNamespace = new Zend_Session_Namespace('userInformation');
		$gps_data = new Application_Model_DbTable_GpsData();
		$lat = trim(str_replace('"', " ", $data[1]), " ");
		$long = trim(str_replace('"', " ", $data[2]), " ");
		$x = trim(str_replace('"', " ", $data[4]), " ");
		$y = trim(str_replace('"', " ", $data[5]), " ");

		$gps_dataNew = $gps_data->createRow();
		$gps_dataNew->date_time = Application_Model_General::dateTimeToUs($data[3]);
		$gps_dataNew->x = $x; // utm
		$gps_dataNew->y = $y; // utm
		$gps_dataNew->project_id = $projectId;
		$gps_dataNew->user_id = $authNamespace->user_id;
		$gps_dataNew->import_date = new Zend_Db_Expr('NOW()');
		$gps_dataNew->lat = $lat;
		$gps_dataNew->long = $long;
		//print_r($gps_dataNew);exit;
		$gpsDataId = $gps_dataNew->save();
		if($gpsDataId)
		{
			return $gpsDataId;
		}
		return false;
	}


/**
	*	Register a new Research Data.
	*	
	* @param array $data - research's data
	* @access public
	* @return integer
	*/
	public function newResearchData($data,$projectId)
	{
		$authNamespace = new Zend_Session_Namespace('userInformation');
		$research_data = new Application_Model_DbTable_ResearchData();

		$line = trim(str_replace('"', " ", $data[1]), " ");
		$start = trim(str_replace('"', " ", $data[3]), " ");
		$card_cod = trim(str_replace('"', " ", $data[5]), " ");
		$lat = trim(str_replace('"', " ", $data[6]), " ");
		$long = trim(str_replace('"', " ", $data[7]), " ");
		$hour = trim(str_replace('"', " ", $data[8]), " ");
		$x = trim(str_replace('"', " ", $data[9]), " ");
		$y = trim(str_replace('"', " ", $data[10]), " ");

		$research_dataNew = $research_data->createRow();
		$research_dataNew->selected_line = $line;
		if($data[2] == 'Embarque')
			$research_dataNew->e_d = 'E';
		else if($data[2] == 'Desembarque')
			$research_dataNew->e_d = 'D';
		$research_dataNew->selected_start = $start;
		$research_dataNew->date_time = Application_Model_General::dateTimeToUs($data[4]);
		$research_dataNew->card_cod = $card_cod;
		$research_dataNew->lat = $lat;
		$research_dataNew->long = $long;
		$research_dataNew->hour = $hour;
		$research_dataNew->x = $x; // utm
		$research_dataNew->y = $y; // utm
		$research_dataNew->project_id = $projectId;
		$research_dataNew->user_id = $authNamespace->user_id;
		$research_dataNew->import_date = new Zend_Db_Expr('NOW()');
		$researchDataId = $research_dataNew->save();
		//print_r($research_dataNew);
		//echo"<br><br>";
		if($researchDataId)
		{
			return $researchDataId;
		}
		return false;
	}


	/**
	*	List the rearches from a project.
	*	
	* @access public
	* @return array
	*/
	public function listResearches($projectId){
		$data = new Application_Model_DbTable_ResearchData();

		$select = $data->select()->setIntegrityCheck(false);
		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId);
		return $data->fetchAll($select);
	}

    /**
	*	List the rearches from a project by line selected.
	*	
	* @access public
	* @return array
	*/
	public function returnByLine($projectId,$line){
	{
    //$authNamespace = new Zend_Session_Namespace('userInformation');
    $data = new Application_Model_DbTable_ResearchData();
	$select = $data->select()->setIntegrityCheck(false);

		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId)
				->where('r.selected_line = ?',$line);
		return $data->fetchAll($select);
			}
		
	}

	/**
	*	List the rearches from a project by door selected.
	*	
	* @access public
	* @return array
	*/
	public function returnByDoor($projectId,$door){
	{
    //$authNamespace = new Zend_Session_Namespace('userInformation');
    $data = new Application_Model_DbTable_ResearchData();
	$select = $data->select()->setIntegrityCheck(false);

		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId)
				->where('r.e_d = ?',$door);
		return $data->fetchAll($select);
			}
		
	}

	/**
	*	List the rearches from a project by origin selected.
	*	
	* @access public
	* @return array
	*/
	public function returnByOrigin($projectId,$origin){
	{
    //$authNamespace = new Zend_Session_Namespace('userInformation');
    $data = new Application_Model_DbTable_ResearchData();
	$select = $data->select()->setIntegrityCheck(false);

		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId)
				->where('r.selected_start = ?',$origin);
		return $data->fetchAll($select);
			}
		
	}

	/**
	*	List the rearches from a project by card selected.
	*	
	* @access public
	* @return array
	*/
	public function returnByCard($projectId,$card){
	{
    //$authNamespace = new Zend_Session_Namespace('userInformation');
    $data = new Application_Model_DbTable_ResearchData();
	$select = $data->select()->setIntegrityCheck(false);

		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId)
				->where('r.card_cod = ?',$card);
		return $data->fetchAll($select);
			}
		
	}

	/**
	*	List the data from researches by card cods returned from errors reports.
	*	
	* @access public
	* @return array
	*/
	public function listDataByCardCods($projectId){
		$data = new Application_Model_DbTable_ResearchData();

		$card_cods = $this->errorsReport($projectId);

		$select = $data->select()->setIntegrityCheck(false);
		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId)
				->where('r.card_cod IN (?)',$card_cods)
				->where('r.status = 1')
				->order('r.card_cod')
				->order('r.hour');
		return $data->fetchAll($select);
	}
	
	/**
	*	get the rearches from a project grouped by card_cod.
	*	
	* @access public
	* @return array
	*/
	public function getCardCod($projectId){
		$data = new Application_Model_DbTable_ResearchData();

		$select = $data->select()->setIntegrityCheck(false);
		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId)
				->where('r.status = 1')
				->group('r.card_cod');
		return $data->fetchAll($select);
	}

	/**
	*	get the data from the research data by card_cod.
	*	
	* @access public
	* @return array
	*/
	public function getResearchDataByCardCod($projectId, $cardCod){
		$data = new Application_Model_DbTable_ResearchData();

		$select = $data->select()->setIntegrityCheck(false);
		$select	->from(array('r' => 'research_data'))
				->where('r.project_id = ?',$projectId)
				->where('r.card_cod = ?',$cardCod)
				->where('r.status = 1')
				->order('r.card_cod')
				->order('r.hour');
		return $data->fetchAll($select);
	}


	/**
	*	Create and calculate the erros report .
	*	
	* @access public
	* @return array
	*/
	public function errorsReport($projectId){
		$data = new Application_Model_DbTable_ResearchData();
		$get_card_cod = $this->getCardCod($projectId);
		$errors_vector[] = null;
		foreach($get_card_cod as $card_cod){ 
			//echo"Codigo do cartao ".$card_cod->card_cod."<br>";
			$dataResearch = $this->getResearchDataByCardCod($projectId, $card_cod->card_cod);
			$x = 0;
			foreach($dataResearch as $datas){
				if($x % 2 == 0){
					$ed_before = $datas->e_d;
					$hour_before = $datas->hour;
					//echo "par <br>";
				}
				else{
					// IRREGULAR MOVEMENTS
					if($datas->e_d == 'E' && $ed_before == "D"){ // subiu pela porta de DESEMBARQUE e desceu pela de EMBARQUE
						if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = $datas->card_cod;
						}
						//echo"movimento irregular <br>";
					}

					//MIM TIME
					if(Application_Model_General::convertToMinute($datas->hour) == Application_Model_General::convertToMinute($hour_before)){
						if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = $datas->card_cod;
						}
						//echo"tempo menor que 1min <br>";
					}

					//MAX TIME
					if((Application_Model_General::convertToMinute($datas->hour) - Application_Model_General::convertToMinute($hour_before)) > 60){
						if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = $datas->card_cod;
						}
						//echo"tempo maior que 1h <br>";
					}
					//echo "impar <br>";
				}

				$x++;
				//echo $datas->e_d;
				//echo"<br><br><br>";
			}

			// PAIR LACK
			if(($x) % 2 == 0){
				//echo $x." registros de mesmo card_cod - PARES <br>";
			}
			else{
				if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = $datas->card_cod;
				}
				//echo $x." registros de mesmo card_cod - IMPARES <br>";
			}

			//echo"<br><br><br>";
		}
		//echo"<br>";
		unset($errors_vector[0]);
		//print_r($errors_vector);
		//exit;

		return $errors_vector;
	}


	/**
	*	Change the register status .
	*	
	* @access public
	* @return array
	*/
	public function changeStatus($researchDataId,$projectId,$status)
	{
		$researchStatus = new Application_Model_DbTable_ResearchData();
		$researchStatusRow = $researchStatus->fetchRow($researchStatus->select()->where('id = ?',$researchDataId)
																				->where('project_id = ?',$projectId));
		$researchStatusRow->status = $status;
		return $researchStatusRow->save();
	}


	/**
	*	Register data in the error_repost_temp .
	*	
	* @access public
	* @return integer
	*/
	public function registerTemp($card_cod,$projectId,$id,$status)
	{
		$research = new Application_Model_DbTable_ResearchData();
		$error_temp = new Application_Model_DbTable_ErrorReportTemp();
		$researchRow = $research->fetchAll($research->select()->where('card_cod = ?',$card_cod)
															  ->where('project_id = ?',$projectId));
		foreach ($researchRow as $data) {
			$errorTempRow = $error_temp->createRow();
			$errorTempRow->selected_line = $data->selected_line;
			$errorTempRow->e_d = $data->e_d;
			$errorTempRow->date_time = $data->date_time;
			$errorTempRow->card_cod = $data->card_cod;
			$errorTempRow->hour = $data->hour;
			$errorTempRow->project_id = $projectId;

			if($data->id == $id){
				$errorTempRow->id_research_data = $id;
				$errorTempRow->status = $status;
			}
			else{
				$errorTempRow->id_research_data = 0;
				$errorTempRow->status = $data->status;
			}

			$errorTempRow->save();
		}
		return true;
	}

	

	/**
	*	List the data from the new erros generate by change status.
	*	
	* @access public
	* @return array
	*/
	public function listNewErrors($card_cod,$projectId){
		$error = $this->newErrorsReport($projectId, $card_cod);
		//print_r($error);exit;
		if(count($error)){
			return $error;	
		}
		return false;
	}

	/**
	*	Create and calculate the new erros report generate by the change of status.
	*	
	* @access public
	* @return array
	*/
	public function newErrorsReport($projectId, $card_cod){
		$data = new Application_Model_DbTable_ResearchData();
			$errors_vector[] = null;
			$dataResearch = $this->getDataErrorTempByCardCod($projectId, $card_cod);
			$x = 0;
			foreach($dataResearch as $datas){
				if($x % 2 == 0){
					$ed_before = $datas->e_d;
					$hour_before = $datas->hour;
					//echo "par <br>";
				}
				else{
					// IRREGULAR MOVEMENTS
					if($datas->e_d == 'E' && $ed_before == "D"){ // subiu pela porta de DESEMBARQUE e desceu pela de EMBARQUE
						if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = 'mi';
						}
						//echo"movimento irregular <br>";
					}

					//MIM TIME
					if(Application_Model_General::convertToMinute($datas->hour) == Application_Model_General::convertToMinute($hour_before)){
						if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = 'min';
						}
						//echo"tempo menor que 1min <br>";
					}

					//MAX TIME
					if((Application_Model_General::convertToMinute($datas->hour) - Application_Model_General::convertToMinute($hour_before)) > 60){
						if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = 'max';
						}
						//echo"tempo maior que 1h <br>";
					}
					//echo "impar <br>";
				}

				$x++;
				//echo $datas->e_d;
				//echo"<br><br><br>";
			}

			// PAIR LACK
			if(($x) % 2 == 0){
				//echo $x." registros de mesmo card_cod - PARES <br>";
			}
			else{
				if (!array_search($datas->card_cod , $errors_vector)) {
						    $errors_vector[] = $datas->card_cod;
				}
				//echo $x." registros de mesmo card_cod - IMPARES <br>";
			}
		//echo"<br>";
		unset($errors_vector[0]);
		//print_r($errors_vector);
		//exit;

		return $errors_vector;
	}

	/**
	*	get the data from the research data by card_cod.
	*	
	* @access public
	* @return array
	*/
	public function getDataErrorTempByCardCod($projectId, $card_cod){
		$data = new Application_Model_DbTable_ErrorReportTemp();

		$select = $data->select()->setIntegrityCheck(false);
		$select	->from(array('r' => 'error_report_temp'))
				->where('r.project_id = ?',$projectId)
				->where('r.card_cod = ?',$card_cod)
				->where('r.status = 1')
				->order('r.card_cod')
				->order('r.hour');
		return $data->fetchAll($select);
	}


	/**
	*	delete the data from the error_report_temp.
	*	
	* @access public
	* @return array
	*/
	public function deleteErrorTemp(){
		$data = new Application_Model_DbTable_ErrorReportTemp();

		$select = $data->select()->setIntegrityCheck(false);
		$select	->from(array('r' => 'error_report_temp'));
		$data->fetchAll($select);

		// deletar
		return true;
	}
}