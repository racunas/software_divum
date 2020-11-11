<?php

class controladorPlantilla{
	/* LLAMAMOS A LA PLANTILLA */
	
	public static function plantilla(){
		include "vistas/plantilla.php";
	}

	public static function unique_multidim_array($array,$key){
		$temp_array = array();
		$i = 0;
		$key_array = array();

		foreach ($array as $value) {
			if(!in_array($value[$key], $key_array)){
				$key_array[$i] = $value[$key];
				$temp_array[$i] = $value;
			}
			$i++;
		}
		return $temp_array;
	}

	public static function orderMultiDimensionalArray ($toOrderArray, $field, $inverse) {  
	    $position = array();  
	    $newRow = array();  
	    foreach ($toOrderArray as $key => $row) {  
	            $position[$key]  = $row[$field];  
	            $newRow[$key] = $row;  
	    }  
	    if ($inverse) {  
	        arsort($position);  
	    }  
	    else {  
	        asort($position);  
	    }  
	    $returnArray = array();  
	    foreach ($position as $key => $pos) {       
	        $returnArray[] = $newRow[$key];  
	    }  
	    return $returnArray;  
	}  

}