<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<link href="./stylesheets/admin.css" media="screen" rel="stylesheet" type="text/css" />
	<style type="text/css">
		table
		{
			border-collapse: collapse;
		}
		table, th, td
		{
			border: 1px solid black;
		}
		thead
		{
			background-color: black;
		 	color: white;
			font: 10pt verdana, sans-serif;
			font-weight: bold;
			text-align: center;
		}
		tbody
		{
			font: 10pt verdana, sans-serif;
		}
		tr.alt
		{
			background-color: #B5B5B5;
		}
	</style>
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

if(isset($_POST['addNewElement']))
{
	$appID = $_POST['appID'];
	$appPartID = $_POST['appPartID'];
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$sql = sprintf("CALL uspAddNewElement(%d, %d);", intval($appID), intval($appPartID));
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		$elementID = $row[2];
	}
	$redirect = sprintf("Location: ElementDetail.php?AppID=%d&AppPartID=%d&ElementID=%d", intval($appID), intval($appPartID), intval($elementID));
	header($redirect);
}

function getHtmlTable($result){
	// receive a record set and print
	// it into an html table
	$isodd = true;
	
	$out = "<table><thead>";
	for($i = 0; $i < mysql_num_fields($result); $i++)
	{
		$aux = mysql_field_name($result, $i);
		$out .= "<th>".$aux."</th>";
	}
	$out .= "</thead><tbody>";
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
	{
		if ($isodd == true)
		{
			$out .= "<tr>";
			$isodd = false;
		}
		else 
		{
			$out .= '<tr class="alt">';
			$isodd = true;
		}
		foreach ($row as $column) $out .= '<td>'.$column.'</td>';
		$out .= "</tr>";
	}
	$out .= "</tbody></table>";
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
<div style="padding-top: 15px; padding-left: 5px;">
<?php 
	echo getHtmlTable($result);
?>
</div>
<?php 
	mysql_close();
?>
</body>
</html>

