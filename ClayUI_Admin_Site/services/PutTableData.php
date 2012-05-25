<?php 
	$json = $_SERVER['HTTP_JSON'];
	$obj = json_decode($json);
	
	$username = $_SERVER['CLAYUI_USER'];
	$password = $_SERVER['CLAYUI_PASS'];
	$database = $_SERVER['CLAYUI_DB'];
	$appID = $obj->{'appID'};
	$appPartID = $obj->{'appPartID'};
	$columnsCSV = $obj->{'columnsCSV'};
	$valuesCSV = $obj->{'valuesCSV'};
	
	$sql = sprintf("CALL uspAddAppPartData(%d, %d, %s, %s);", intval($appID), intval($appPartID), strval($columnsCSV), strval($valuesCSV));
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query($sql);
	mysql_close();
	
	$posts = array(1);
	header('Content-type: application/json');
	echo json_encode(array('post'=>$posts));
	
?>