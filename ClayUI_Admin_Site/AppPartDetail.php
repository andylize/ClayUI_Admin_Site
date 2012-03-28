<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<link href="./stylesheets/admin.css" media="screen" rel="stylesheet" type="text/css" />
<?php 

$username = $_SERVER['CLAYUI_USER'];
$password = $_SERVER['CLAYUI_PASS'];
$database = $_SERVER['CLAYUI_DB'];

if((isset($_GET['AppID']) && intval($_GET['AppID'])) && (isset($_GET['AppPartID']) && intval($_GET['AppPartID'])) && (isset($_GET['ElementID']) && intval($_GET['ElementID']))) // this is a get
{
	$appID = intval($_GET['AppID']);
	$appPartID = intval($_GET['AppPartID']);
	$elementID = intval($_GET['ElementID']);
	$sql = sprintf("CALL uspGetElement(%d, %d, %d);", intval($appID), intval($appPartID), intval($elementID));
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		$appID = $row[0];
		$appPartID = $row[1];
		$elementID = $row[2];
		$elementName = $row[3];
		$elementType = $row[4];
		$elementDescr = $row[5];
		$elementLabel = $row[6];
		$isStored = $row[7];
		$dataType = $row[8];
		$dataLength = $row[9];
		$listOrder = $row[10];
		$isEnabled = $row[11];
		
	}
	$saved = "";
}


?>
</head>
<body class="menu">
<div class="containerHeader" style="border-left: none;">
	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
		<input type="hidden" name="appID" value="<?php echo($appID);?>">
		<input type="hidden" name="appPartID" value="<?php echo($appPartID);?>">
		<input type="hidden" name="elementID" value="<?php echo($elementID);?>">
		Element Details&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value="Add New Element" name="addNewElement">
	</form>
</div>
<div style="padding-top: 15px">
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
<input type="hidden" name="appID" value="<?php echo($appID);?>">
<input type="hidden" name="appPartID" value="<?php echo($appPartID);?>">
<input type="hidden" name="elementID" value="<?php echo($elementID);?>">
	<table>
		<tr>
			<td>Name:</td><td><input style="font-family: monospace; width: 500px; border: thin;" type="text" name="appName" value="<?php echo($elementName);?>"></td>
		</tr>
		<tr>
			<td valign="top">Description:</td><td style="vertical-align: text-top;"><textarea name="description" style="width: 500px; height: 250px; border: thin;" rows="10" cols="80"><?php echo($elementDescr);?></textarea></td>
		</tr>
		<tr>
			<td>Element Type:</td><td></td>
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
<?php 
/**TODO Add Save
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

// TODO Add add new element
if(isset($_POST['addNewElement']))
{
$appID = $_POST['appID'];
mysql_connect(localhost, $username, $password);
mysql_select_db($database) or die("Unable to select database");
$sql = sprintf("CALL uspAddAppPart(%d);", intval($appID));
$result = mysql_query($sql);
while($row = mysql_fetch_array($result, MYSQL_NUM))
{
$appPartID = $row[1];
}
$redirect = sprintf("Location: AppPartDetail.php?AppID=%d&AppPartID=%d", intval($appID), intval($appPartID));
header($redirect);
}
**/
?>
