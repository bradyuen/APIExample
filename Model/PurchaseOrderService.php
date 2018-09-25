<?php
namespace BearClaw\Warehousing;

class PurchaseOrderService{

	var $_id_array;
	public function calculateTotals(array $ids){
		//Hard code for test data

		$this->_id_array = array(
			"123" => array(
				"product_type_id" => "1001",
				"total" => "10",
			),
			"124" => array(
				"product_type_id" => "1002",
				"total" => "6",
			),
			"133" => array(
				"product_type_id" => "1202",
				"total" => "7",
			),
		);

		/*
		if run database, this array will return by $this->Order->find("All");
		*/
		$returnArray = array();
		foreach ($ids as $id) {
			if(isset($this->_id_array[$id])){
				$returnArray[] = $this->_id_array[$id];
			}
		}

		if(isset($returnArray) && !empty($returnArray)){
			return $returnArray;
		}else{
			return false;
		}


	}
	
}