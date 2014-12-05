<?php

function toByte($val) {
	
	$aUnits = array('B'=>0, 'KB'=>1, 'MB'=>2, 'GB'=>3, 'TB'=>4, 'PB'=>5, 'EB'=>6, 'ZB'=>7, 'YB'=>8);
	
	$sUnit = strtoupper(trim(substr($val, -2)));
	
	if(intval($sUnit) !== 0)
		$sUnit = 'B';
	
	if(!in_array($sUnit, array_keys($aUnits)))
		return false;
		
	$iUnits = trim(substr($val, 0, strlen($val) - 2));
	
	if(!intval($iUnits) == $iUnits)
		return false;
		
	return $iUnits * pow(1024, $aUnits[$sUnit]);
}

function byteToSize($val) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($val, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
	
	$bytes /= pow(1024, $pow);

    return round($bytes, 2) . ' ' . $units[$pow]; 
}

?>