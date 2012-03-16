<?php
if(isset($_GET['AppID']) && intval($_GET['AppID']))
{
			
	$appID = intval($_GET['AppID']);
	$username = $_SERVER['CLAYUI_USER'];
	$password = $_SERVER['CLAYUI_PASS'];
	$database = $_SERVER['CLAYUI_DB'];
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$sql = sprintf("SELECT ApplicationName FROM Applications WHERE ApplicationID = %d;", intval($appID));
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		$apptitle = $row[0];
	}
	$sql=sprintf("CALL uspGetAppParts(%d);", intval($appID));
	$result = mysql_query($sql);
	
}
else
{
	$apptitle = "none selected";
}	
?>
<html><head><title>ClayUI -- Administration {<?php echo $apptitle;?></title>
</head>

<body>
	<div>Administration for Application: <?php echo $apptitle;?></div>
	<div>------------------------------------</div>
	<div>Current App Parts</div>
	<div>------------------------------------</div>
	<table>
		<?php 
			while($row = mysql_fetch_array($result, MYSQL_NUM))
			{
				printf("<tr><td>%s</td></tr>", $row[2]);
			}
		?>
	</table>
	<form action="./services/GetAppParts.php" method="post">
	App Parts: <select name="appID">
		<?php 
			mysql_data_seek($result, 0);
			while($row2 = mysql_fetch_array($result, MYSQL_NUM))
			{
				printf("<option value=%s>%s</option>", $row2[0], $row2[2]);
			}
			mysql_free_result($result);
		?> 
	</select>
	<input type="submit" value="submit">
	</form>
<?php 
	mysql_close();
?>
</body>
</html>