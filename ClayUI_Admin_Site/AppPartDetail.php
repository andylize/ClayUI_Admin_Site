<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<link href="./stylesheets/admin.css" media="screen" rel="stylesheet" type="text/css" />
<?php 

$username = $_SERVER['CLAYUI_USER'];
$password = $_SERVER['CLAYUI_PASS'];
$database = $_SERVER['CLAYUI_DB'];

if((isset($_GET['AppID']) && intval($_GET['AppID'])) && (isset($_GET['AppPartID']) && intval($_GET['AppPartID']))) // this is a get
{
	$appID = intval($_GET['AppID']);
	$appPartID = intval($_GET['AppPartID']);
	$sql = sprintf("CALL uspGetAppPartDetails(%d, %d);", intval($appID), intval($appPartID));
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		$appID = $row[0];
		$appPartID = $row[1];
		$appName = $row[2];
		$description = $row[3];
	}
	$saved = "";
}

if(isset($_POST['submit'])) // this is a post
{
	$appID = $_POST['appID'];
	$appPartID = $_POST['appPartID'];
	$appName = $_POST['appName'];
	$description = $_POST['description'];
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$sql = sprintf("CALL uspUpdateAppPart(%d, %d, '%s', '%s');", intval($appID), intval($appPartID), mysql_escape_string($appName), mysql_escape_string($description));
	mysql_query($sql);
	$saved = "saved";
}
	
?>
</head>
<body class="menu">
<div class="containerHeader">AppPart Details
</div>
<div style="padding-top: 15px">
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
<input type="hidden" name="appID" value="<?php echo($appID);?>">
<input type="hidden" name="appPartID" value="<?php echo($appPartID);?>">
	<table>
		<tr>
			<td>Name:</td><td><input style="font-family: monospace; width: 500px; border: thin;" type="text" name="appName" value="<?php echo($appName);?>"></td>
		</tr>
		<tr>
			<td valign="top">Description:</td><td style="vertical-align: text-top;"><textarea name="description" style="width: 500px; height: 250px; border: thin;" rows="10" cols="80"><?php echo($description);?></textarea></td>
		</tr>
		<tr>
			<td><span style="color: red; font-style: italic;"><?php echo($saved);?></span></td><td align="right"><input type="submit" value="Save" name="submit"></td>
		</tr>
	</table>
</form>
</div>
<?php 
	mysql_close();
?>
</body>
</html>
