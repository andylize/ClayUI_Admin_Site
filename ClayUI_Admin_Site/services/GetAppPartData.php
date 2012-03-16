<?php

if(isset($_GET['AppID']) && intval($_GET['AppID'])) 
{
	
	$appID = intval($_GET['AppID']);
	$username = $_SERVER['CLAYUI_USER'];
	$password= $_SERVER['CLAYUI_PASS'];
	$database=$_SERVER['CLAYUI_DB'];
	$sql=sprintf("CALL uspGetAppPartData(%d);", intval($appID));
	$app_part_data = array();
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	
	while($obj = mysql_fetch_object($result))
	{
		$app_part_data[] = $obj;
	}
	
	$app_part_data_encoded = json_encode($app_part_data);
	
	echo $app_part_data_encoded;
	
	mysql_close();
}
?>