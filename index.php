protected function measure()
	{

		if(empty(self::measureHL)) return false;
        if(empty($this->siteId)) return false;

		$this->HLload(self::measureHL);
		$rsData = $this->strEntityDataClass::getList(array(
			'filter' => array('*'),
			'select' => array('UF_NAME', 'ID'),
			'order' => array('ID' => 'DESC')
		));

		while($arItem = $rsData->Fetch()) {
			$arIdMeas[$arItem['UF_NAME']] = $arItem['ID'];
		}

		$lastId = 0;
		//Тянем недостающие элементы
		$Measure = $this->send("get", "measure", $lastId);

		$countAdd = 0;
		$countUpdate = 0;
		if(!empty($Measure)){
			//Заносим 
			foreach($Measure as $kData => $value){
				if(array_key_exists($value['MEASURE_TITLE'], $arIdMeas)){
					// $this->strEntityDataClass::delete($arIdProv[$value['ID']]);
					$update = $this->strEntityDataClass::update($arIdMeas[$value['MEASURE_TITLE']], array(
				      	'UF_NAME'				=> $value['MEASURE_TITLE'],
				      	'UF_XML_ID'				=> $value['MEASURE_TITLE']
				   		));
					$countUpdate++;

				} else {
					$this->strEntityDataClass::add(array(
				      	'UF_NAME'				=> $value['MEASURE_TITLE'],
				      	'UF_XML_ID'				=> $value['MEASURE_TITLE']
				   		));
					$countAdd++;

				}
			}
		if(count($arIdMeas)>0) echo count($arIdMeas).' элементов в базе<br>';
		if($countAdd) echo 'Добавлено '.$countAdd.' элементов<br>';
		if($countUpdate) echo 'Обновлено '.$countUpdate.' элементов<br>';
		}
	}
