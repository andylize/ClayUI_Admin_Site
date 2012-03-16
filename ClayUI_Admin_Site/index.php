<html><head><title>Welcome to ClayUI</title>
<?php 
	$username = $_SERVER['CLAYUI_USER'];
	$password = $_SERVER['CLAYUI_PASS'];
	$database = $_SERVER['CLAYUI_DB'];
	$sql = "SELECT ApplicationID, ApplicationName FROM Applications ORDER BY ApplicationName ASC;";
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	
?>
</head>

<body>
	<div>Welcome to ClayUI</div>
	<div>------------------------------------</div>
	<form action="./AppParts.php" method="get">
	Application: <select name="AppID">
		<?php 
			while($row = mysql_fetch_array($result, MYSQL_NUM))
			{
				printf("<option value=%s>%s</option>", $row[0], $row[1]);
			}
		?> 
	</select>
	<input type="submit" value="submit">
	</form>
<?php 
	mysql_close();
?>
</body>
</html>