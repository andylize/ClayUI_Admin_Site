<?php

if((isset($_GET['AppID']) && intval($_GET['AppID'])) && (isset($_GET['AppPartID']) && intval($_GET['AppPartID']))) 
{
	
	$appID = intval($_GET['AppID']);
	$appPartID = intval($_GET['AppPartID']);
	$username = $_SERVER['CLAYUI_USER'];
	$password= $_SERVER['CLAYUI_PASS'];
	$database=$_SERVER['CLAYUI_DB'];
	$sql=sprintf("CALL uspGetDataTableSchema(%d, %d);", intval($appID), intval($appPartID));
	$table_schema = array();
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	
	while($obj = mysql_fetch_object($result))
	{
		$table_schema[] = $obj;
	}
	
	$table_schema_encoded = json_encode($table_schema);
	
	echo $table_schema_encoded;
	
	mysql_close();
}
?>