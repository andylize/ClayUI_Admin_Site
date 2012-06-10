<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<link href="./stylesheets/admin.css" media="screen" rel="stylesheet" type="text/css" />
<?php 

$username = $_SERVER['CLAYUI_USER'];
$password = $_SERVER['CLAYUI_PASS'];
$database = $_SERVER['CLAYUI_DB'];

if(isset($_GET['AppID']) && intval($_GET['AppID'])) // this is a get
{
	$appID = intval($_GET['AppID']);
	$sql = sprintf("CALL uspGetApplicationDetails(%d);", intval($appID));
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		$appID = $row[0];
		$appName = $row[1];
		$description = $row[2];
	}
	$saved = "";
}

// save app info
if(isset($_POST['save'])) // this is a save
{
	$appID = $_POST['appID'];
	$appName = $_POST['appName'];
	$description = $_POST['description'];
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$sql = sprintf("CALL uspUpdateApplication(%d, '%s', '%s');", intval($appID), mysql_escape_string($appName), mysql_escape_string($description));
	mysql_query($sql);
	$saved = "saved";
}

// add new app
if(isset($_POST['addNewApp'])) // this is a 
{	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$sql = sprintf("CALL uspAddApp();");
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		$appID = $row[0];
		$appName = $row[1];
		$description = $row[2];
	}	
}

// add new app part
if(isset($_POST['addNewAppPart']))
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

// delete app
if (isset($_POST['deleteApplication']))
{
	$appID = $_POST['appID'];mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$sql = sprintf("CALL uspDeleteApplication(%d);", intval($appID));
	mysql_query($sql);
	$redirect = sprintf("Location: detail.php");
	header($redirect);	
}

?>
</head>
<body class="menu">
<div class="containerHeader" style="border-left: none;">
	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
		<input type="hidden" name="appID" value="<?php echo($appID);?>">
		Application Details&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value="Add New Application" name="addNewApp">&nbsp;
		<input type="submit" value="Add New AppPart" name="addNewAppPart">&nbsp;
		<input style="color: red;" type="submit" value="Delete Application" name="deleteApplication" onClick="<?php printf("return confirm('This will delete the application %s. Are you sure you want to do this?');", $appName);?>">
	</form>
</div>
<div style="padding-top: 15px">
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
<input type="hidden" name="appID" value="<?php echo($appID);?>">
	<table>
		<tr>
			<td>Id:</td></td><td><?php echo($appID)?></td>
		</tr>
		<tr>
			<td>Name:</td><td><input style="font-family: monospace; width: 500px; border: thin;" type="text" name="appName" value="<?php echo($appName);?>"></td>
		</tr>
		<tr>
			<td valign="top">Description:</td><td style="vertical-align: text-top;"><textarea name="description" style="width: 500px; height: 250px; border: thin;" rows="10" cols="80"><?php echo($description);?></textarea></td>
		</tr>
		<tr>
			<td><span style="color: red; font-style: italic;"><?php echo($saved);?></span></td><td align="right"><input type="submit" value="Save" name="save"></td>
		</tr>
	</table>
</form>

</div>
<?php 
	mysql_close();
?>
</body>
</html>

