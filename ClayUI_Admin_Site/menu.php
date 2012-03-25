<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<link href="./stylesheets/admin.css" media="screen" rel="stylesheet" type="text/css" />
<?php 
	$username = $_SERVER['CLAYUI_USER'];
	$password = $_SERVER['CLAYUI_PASS'];
	$database = $_SERVER['CLAYUI_DB'];
	//$sql = "SELECT ApplicationID, ApplicationName FROM Applications ORDER BY ApplicationName ASC;";
	$sql = "CALL uspGetAppMenuStructure();";
	
	mysql_connect(localhost, $username, $password);
	mysql_select_db($database) or die("Unable to select database");
	$result = mysql_query($sql);
	
?>
</head>
<body class="menu">
<div class="containerHeader">Application Tree&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Reload Menu" onClick="window.location.reload()"></div>
	<?php
		$currentPosition = 1;
		$lastPosition = 0;
		while($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			// get current position
			$currentPosition = $row[1];
			
			// check to see if this is an element or data table
			if($row[4] >= 4)
			{
				// if current position is less than the last position then we've moved up a level and need to close the ordered list
				if($currentPosition < $lastPosition)
				{
					printf('</ol>');
				}
				
				if($row[4] == 4)
				{
					printf('<li class="element"><a href="element.php?AppID=%s&AppPartID=%s&ElementID=%s" target="detail">%s</a></li>', $row[0], $row[3], $row[6], $row[5]);
				}
				if($row[4] == 5)
				{
					printf('<li class="dataTable"><a href="dataTable.php?AppID=%s&AppPartID=%s" target="detail">%s</a></li></ol>', $row[0], $row[3], $row[5]);
				}
			}
			else 
			{			
				// if current position is less than the last position then we've moved up a level and need to close the ordered list
				if($currentPosition < $lastPosition)
				{
					if($lastPosition == 3)
					{
						printf('</li><li>');
					}
					else 
					{
						printf('</li></ol>');
					}
				}
				
				// if current posistion is same as last posistion, then we need to close the lists
				if($currentPosition == $lastPosition)
				{
					printf('</li></ol>');
				}
				
				// check if this is a new app
				if($row[4] == 1)
				{
					// if last position is > 1 then we'll need to close the ordered lists
					for($x = $lastPosition; $x > 0 ; $x--)
					{
						if($x > 1)
						{
							printf('</li></ol>');
						}
						else
						{
							printf('</ol>');
						}
					}
					
					if($lastPosition == 1) // this is another Application
					{
						printf('</li></ol>');
					}
					
					printf('<ol class="tree">');
					printf('<li>');
					printf('<label for="%s" class="application"><a href="ApplicationDetail.php?AppID=%s" target="detail">%s</a></label> <input type="checkbox" id="%s" />', $row[5], $row[0], $row[5], $row[5]);
					printf('<ol>');
					printf('<li>');
				}
				// check if this is a AppPart
				if($row[4] == 2)
				{
					printf('<label for="%s" class="appPart"><a href="AppPartDetail.php?AppID=%s&AppPartID=%s" target="detail">%s</a></label> <input type="checkbox" id="%s" />', $row[5], $row[0], $row[3], $row[5], $row[5]);
					printf('<ol>');
					printf('<li>');
				}
				// check if this is an Elements
				if($row[4] == 3)
				{
					printf('<label for="%s" class="elements"><a href="ElementsDetail.php?AppID=%s&AppPartID=%s" target="detail">%s</a></label> <input type="checkbox" id="%s%s" />', $row[5], $row[0], $row[3], $row[5], $row[5], $row[3]);
					printf('<ol>');
				}
			}			
			// move positions
			$lastPosition = $currentPosition;
		}
		// close out ordered lists
		for($x = $lastPosition; $x > 0 ; $x--)
		{
			if($x > 1)
			{
				printf('</li></ol>');
			}
			else
			{
				printf('</ol>');
			}
		}
	?> 
<?php mysql_close();?>
 </body>
 </html>

