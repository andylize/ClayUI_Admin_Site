<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<link href="./stylesheets/admin.css" media="screen" rel="stylesheet" type="text/css" />
<?php 
if(isset($_GET['AppID']) && intval($_GET['AppID']))
{
	$appID = intval($_GET['AppID']);
	$username = $_SERVER['CLAYUI_USER'];
	$password = $_SERVER['CLAYUI_PASS'];
	$database = $_SERVER['CLAYUI_DB'];
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$sql = sprintf("CALL uspGetApplicationDetails(%d);", intval($appID));
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result, MYSQL_NUM))
	{	
		$appID = $row[0];
		$appName = $row[1];
		$description = $row[2];		
	}
}	
?>
</head>
<body class="menu">
<div class="containerHeader">Application Details</div>
<div style="padding-top: 15px">
<form action="./services/GetAppParts.php" method="post">
	<table>
		<tr>
			<td>Name:</td><td><input style="font-family: monospace; width: 500px; border: thin;" type="text" id="appName" value="<?php echo($appName);?>"></td>
		</tr>
		<tr>
			<td valign="top">Description:</td><td style="vertical-align: text-top;"><textarea style="width: 500px; height: 250px; border: thin;" rows="10" cols="80"><?php echo($description);?></textarea></td>
		</tr>
		<tr>
			<td></td><td align="right"><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>
</div>
<?php 
	mysql_close();
?>
</body>
</html>

