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
	$sql = sprintf("CALL uspGetElementDataTable(%d, %d);", intval($appID), intval($appPartID));
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
}

// TODO Add new Element
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

function getHtmlTable($result){
	// receive a record set and print
	// it into an html table
	$out = '<table>';
	for($i = 0; $i < mysql_num_fields($result); $i++){
		$aux = mysql_field_name($result, $i);
		$out .= "<th>".$aux."</th>";
	}
	while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$out .= "<tr>";
		foreach ($linea as $valor_col) $out .= '<td>'.$valor_col.'</td>';
		$out .= "</tr>";
	}
	$out .= "</table>";
	return $out;
}
	
?>
</head>
<body class="menu">
<div class="containerHeader" style="border-left: none;">
	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
		<input type="hidden" name="appID" value="<?php echo($appID);?>">
		<input type="hidden" name="appPartID" value="<?php echo($appPartID);?>">
		Elements&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value="Add New Element" name="addNewElement">
	</form>
</div>
<div style="padding-top: 15px">
<?php 
	echo getHtmlTable($result);
?>
</div>
<?php 
	mysql_close();
?>
</body>
</html>

