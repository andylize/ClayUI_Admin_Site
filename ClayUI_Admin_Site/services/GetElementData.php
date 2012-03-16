<?php

if((isset($_GET['AppID']) && intval($_GET['AppID'])) && (isset($_GET['AppPartID']) && intval($_GET['AppPartID']))) 
{
	
	$appID = intval($_GET['AppID']);
	$appPartID = intval($_GET['AppPartID']);
	$username = $_SERVER['CLAYUI_USER'];
	$password= $_SERVER['CLAYUI_PASS'];
	$database=$_SERVER['CLAYUI_DB'];
	$sql=sprintf("CALL uspGetElementData(%d, %d);", intval($appID), intval($appPartID));
	$element_data = array();
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	
	while($obj = mysql_fetch_object($result))
	{
		$element_data[] = $obj;
	}
	
	$element_data_encoded = json_encode($element_data);
	
	echo $element_data_encoded;
	
	mysql_close();
}
?>