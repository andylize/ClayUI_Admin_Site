<?php

if(isset($_GET['AppID']) && intval($_GET['AppID'])) 
{
	
	$appID = intval($_GET['AppID']);
	$username = $_SERVER['CLAYUI_USER'];
	$password= $_SERVER['CLAYUI_PASS'];
	$database=$_SERVER['CLAYUI_DB'];
	$sql=sprintf("CALL uspGetAllElementOptions(%d);", intval($appID));
	$element_options = array();
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	
	while($obj = mysql_fetch_object($result))
	{
		$element_options[] = $obj;
	}
	
	$element_options_encoded = json_encode($element_options);
	
	echo $element_options_encoded;
	
	mysql_close();
}
?>