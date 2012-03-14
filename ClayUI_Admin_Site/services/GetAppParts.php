<?php

if(isset($_GET['AppID']) && intval($_GET['AppID'])) 
{
	
	$appID = intval($_GET['AppID']);
	$username = $_SERVER['CLAYUI_USER'];
	$password= $_SERVER['CLAYUI_PASS'];
	$database=$_SERVER['CLAYUI_DB'];
	$sql=sprintf("CALL uspGetAppParts(%d);", intval($appID));
	$app_parts = array();
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	
	while($obj = mysql_fetch_object($result))
	{
		$app_parts[] = $obj;
	}
	
	$app_parts_encoded = json_encode($app_parts);
	
	echo $app_parts_encoded;
	
	mysql_close();
}
?>