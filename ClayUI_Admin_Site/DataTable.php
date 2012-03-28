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
	$sql = sprintf("CALL uspGetAppPartDataTable(%d, %d);", intval($appID), intval($appPartID));
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
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
	while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) 
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
		foreach ($linea as $valor_col) $out .= '<td>'.$valor_col.'</td>';
		$out .= "</tr>";
	}
	$out .= "</tbody></table>";
	return $out;
}
	
?>
</head>
<body class="menu">
<div class="containerHeader" style="border-left: none;">Data Table</div>
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

