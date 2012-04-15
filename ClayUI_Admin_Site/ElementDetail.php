<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<link href="./stylesheets/admin.css" media="screen" rel="stylesheet" type="text/css" />
	
<script language=javascript type='text/javascript'> 
	function hideDiv() 
	{ 
		if (document.getElementById) 
		{ // DOM3 = IE5, NS6 
			document.getElementById('hideShow').style.visibility = 'hidden'; 
		} 
		else 
		{ 
			if (document.layers) 
			{ // Netscape 4 
				document.hideShow.visibility = 'hidden'; 
			} 
			else 
			{ // IE 4 
				document.all.hideShow.style.visibility = 'hidden'; 
			}
		} 
	}

	function showDiv() 
	{ 
		if (document.getElementById) 
		{ // DOM3 = IE5, NS6 
			document.getElementById('hideShow').style.visibility = 'visible'; 
		} 
		else 
		{ 
			if (document.layers) 
			{ // Netscape 4 
				document.hideShow.visibility = 'visible'; 
			} 
			else 
			{ // IE 4 
				document.all.hideShow.style.visibility = 'visible'; 
			} 
		} 
	} 
</script>
<?php 

$username = $_SERVER['CLAYUI_USER'];
$password = $_SERVER['CLAYUI_PASS'];
$database = $_SERVER['CLAYUI_DB'];

$optionVisibilty = "hidden";

// default option when page is loaded
if((isset($_GET['AppID']) && intval($_GET['AppID'])) && (isset($_GET['AppPartID']) && intval($_GET['AppPartID'])) && (isset($_GET['ElementID']) && intval($_GET['ElementID']))) // this is a get
{
	$appID = intval($_GET['AppID']);
	$appPartID = intval($_GET['AppPartID']);
	$elementID = intval($_GET['ElementID']);
	$db = new mysqli('localhost', $username, $password, $database);
	
	if(mysqli_connect_errno())
	{
		echo mysqli_connect_error();
	}
	$sql = sprintf("CALL uspGetElement(%d, %d, %d)", intval($appID), intval($appPartID), intval($elementID));
	
	$result = $db->query($sql);
	if ($result)
	{
		while($row = $result->fetch_array()) 
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
		$result->close();
		$db->next_result();
	}
	else
	{
		echo($db->error);
	}
	
	$saved = "";	
}

// add new elemnet
if(isset($_POST['addNewElement']))
{
	$appID = $_POST['appID'];
	$appPartID = $_POST['appPartID'];
	
	$db = new mysqli('localhost', $username, $password, $database);
	
	if(mysqli_connect_errno())
	{
		echo mysqli_connect_error();
	}
	$sql = sprintf("CALL uspAddNewElement(%d, %d);", intval($appID), intval($appPartID));
	$result = $db->query($sql);
	if ($result)
	{
		while($row = $result->fetch_array())
		{
			$elementID = $row[2];
		}
		$result->close();
		$db->next_result();
		
		$redirect = sprintf("Location: ElementDetail.php?AppID=%d&AppPartID=%d&ElementID=%d", intval($appID), intval($appPartID), intval($elementID));
		header($redirect);
	}
	else
	{
		echo($db->error);
	}
}

// save element details
if (isset($_POST['saveDetails']))
{
	$appID = $_POST['appID'];
	$appPartID = $_POST['appPartID'];
	$elementID = $_POST['elementID'];
	$elementName = $_POST['elementName'];
	$elementType = $_POST['elementType'];
	$elementDescr = $_POST['elementDescr'];
	$elementLabel = $_POST['elementLabel'];
	$isStored = $_POST['isStored'];
	$dataType = $_POST['dataType'];
	$dataLength = $_POST['dataLength'];
	$listOrder = $_POST['listOrder'];
	$isEnabled = $_POST['isEnabled'];
	
	$db = new mysqli('localhost', $username, $password, $database);
	
	if(mysqli_connect_errno())
	{
		echo mysqli_connect_error();
	}
	$sql = sprintf("CALL uspUpdateElement(%d, %d, %d, '%s', %d, %d, '%s', '%s', %d, %d, %d, %d)", 
				intval($appID), 
				intval($appPartID), 
				intval($elementID), 
				strval($elementName),
				intval($elementType),
				intval($dataType),
			 	strval($elementLabel),
				strval($elementDescr),
				intval($isStored),
				intval($listOrder),
				intval($isEnabled),
				intval($dataLength));
	$result = $db->query($sql);
	if ($result)
	{		
		$result->close();
		$db->next_result();
	}
	else
	{
		echo($db->error);
	}
	$saved = "saved";
}

// save element options
if (isset($_POST['addOptions']))
{
	$appID = $_POST['appID'];
	$appPartID = $_POST['appPartID'];
	$elementID = $_POST['elementID'];
	$newElementOptionValue = $_POST['newElementOptionValue'];
	$newElementOptionDescr = $_POST['newElementOptionDescr'];
	$optionVisibilty = "visible"; // keep form visible
	
	$db = new mysqli('localhost', $username, $password, $database);
	
	if(mysqli_connect_errno())
	{
		echo mysqli_connect_error();
	}
	$sql = sprintf("CALL uspAddElementOption(%d, %d, %d, '%s', '%s')", intval($appID), intval($appPartID), intval($elementID), strval($newElementOptionValue), strval($newElementOptionDescr));
	$result = $db->query($sql);
	if ($result)
	{
		while($row = $result->fetch_array()) 
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
		$result->close();
		$db->next_result();
	}
	else
	{
		echo($db->error);
	}
}

// delete element
if (isset($_POST['deleteElement']))
{
	$appID = $_POST['appID'];
	$appPartID = $_POST['appPartID'];
	$elementID = $_POST['elementID'];
	
	$db = new mysqli('localhost', $username, $password, $database);
	
	if(mysqli_connect_errno())
	{
		echo mysqli_connect_error();
	}
	$sql = sprintf("CALL uspDeleteElement(%d, %d, %d)", intval($appID), intval($appPartID), intval($elementID));
	$result = $db->query($sql);
	if ($result)
	{
		$redirect = sprintf("Location: ElementsDetail.php?AppID=%d&AppPartID=%d", intval($appID), intval($appPartID));
		header($redirect);
	}
	else 
	{
		echo($db->error);
	}
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
		<input type="submit" value="Add New Element" name="addNewElement">&nbsp;
		<input style="color: red;" type="submit" value="Delete Element" name="deleteElement" onClick="<?php printf("return confirm('This will delete the element %s. Are you sure you want to do this?');", $elementName);?>">
	</form>
</div>
<div style="padding-top: 15px">
<form  method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
<input type="hidden" name="appID" value="<?php echo($appID);?>">
<input type="hidden" name="appPartID" value="<?php echo($appPartID);?>">
<input type="hidden" name="elementID" value="<?php echo($elementID);?>">
	<table>
		<tr>
			<td>Name:</td><td colspan="3"><input style="width: 504px; font-family: monospace; border: thin;" type="text" name="elementName" value="<?php echo($elementName);?>"></td>
		</tr>
		<tr>
			<td valign="top">Description:</td><td colspan="3" style="vertical-align: text-top;"><textarea name="elementDescr" style="width: 500px; height: 150px; border: thin;" rows="10" cols="80"><?php echo($elementDescr);?></textarea></td>
		</tr>
		<tr>
			<td>Element Type:</td>
			<td>
				<select name="elementType">
				<?php
					// get the element type options
					$sql = sprintf("CALL uspGetElementTypes()");
					$result = $db->query($sql);
					while ($row = $result->fetch_array())
					{
						if($elementType == $row[0])
						{
							printf('<option value="%d" selected>%s</option>', $row[0], $row[1]);
						}
						else 
						{
							printf('<option value="%d">%s</option>', $row[0], $row[1]);
						}
					}
					$result->close();
					$db->next_result();
				?>					
				</select>
			</td>
			<td><input type="button" name="editOptions" value="Edit Element Options" onClick="showDiv()"></td>
			<td></td>
		</tr>
		<tr>
			<td>Label:</td><td colspan="3"><input style="width: 500px; font-family: monospace; border: thin;" type="text" name="elementLabel" value="<?php echo($elementLabel);?>"></td>
		</tr>
		<tr>
			<td>Enabled:</td>
			<td><input type="checkbox" name="isEnabled" value="1" <?php if($isEnabled ==1){ echo("checked");}?>></td>
			<td>Data Stored:</td>
			<td><input type="checkbox" name="isStored" value="1" <?php if($isStored == 1){ echo("checked");}?>></td>
		</tr>
		<tr>
			<td>Data Type:</td>
			<td>
				<select name="dataType">
				<?php 
					// get data type options
					$sql = sprintf("CALL uspGetDataTypes()");
					$result = $db->query($sql);
					while ($row = $result->fetch_array())
					{
						if($dataType == $row[0])
						{
							printf('<option value="%d" selected>%s</option>', $row[0], $row[1]);
						}
						else 
						{
							printf('<option value="%d">%s</option>', $row[0], $row[1]);
						}
					}
					$result->close();
					$db->next_result();
				?>
				</select>
			</td>
			<td>Length:</td><td><input style="font-family: monospace; border: thin;" type="text" name="dataLength" value="<?php echo($dataLength);?>"></td>
		</tr>
		<tr>
			<td>List Order:</td><td><input style="font-family: monospace; border: thin;" type="text" name="listOrder" value="<?php echo($listOrder);?>"></td><td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="1"><span style="color: red; font-style: italic;"><?php echo($saved);?></span></td><td colspan="2"></td><td align="right"><input type="submit" value="Save" name="saveDetails"></td>
		</tr>
	</table>
</form>
</div>
<div id="hideShow" class="popUpContainerOuter" style="visibility: <?php echo $optionVisibilty; ?>;">
	<div class="popUpContainerInner">
	<div style="padding-left: 10px; padding-top: 10px;"><span style="font: bold;">Add Element Options</span>
	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
	<input type="hidden" name="appID" value="<?php echo($appID);?>">
	<input type="hidden" name="appPartID" value="<?php echo($appPartID);?>">
	<input type="hidden" name="elementID" value="<?php echo($elementID);?>">
		<table>
			<tr>
				<td>Value:</td>
				<td><input style="font-family: monospace; border: thin;" type="text" name="newElementOptionValue" value=""></td>
				<td></td>
			</tr>
			<tr>
				<td>Description:</td>
				<td><input style="font-family: monospace; border: thin;" type="text" name="newElementOptionDescr" value=""></td>
				<td><input type="submit" name="addOptions" value="Add" onClick="hideDiv()"></td>
			</tr>
		</table>
	</form>
	<p></p>
	<hr />
	<span style="font: bold;">Existing Options</span>
	<?php 
		// get data for element options
		$sql = sprintf("CALL uspGetElementOptions(%d, %d, %d)", intval($appID), intval($appPartID), intval($elementID));
		$result = $db->query($sql);
		printf('<table class="popUpTable"><thead class="popUpThead">');
				
		$fields = $result->fetch_fields(); // get field collection
		foreach($fields as $field)
		{
			printf('<th class="popUpThTd">%s</th>', strval($field->name));
		}
		
		$isodd = true; // for alternate lines
		printf('</thead><tbody class="popUpTbody">');
		while($row = $result->fetch_array())
		{
			if ($isodd == true)
			{
				printf('<tr>');
				$isodd = false;
			}
			else
			{
				printf('<tr class="popUpTrAlt">');
				$isodd = true;
			}
			printf('<td class="popUpThTd">%d</td><td class="popUpThTd">%s</td>', strval($row[0]), strval($row[1]));
			printf('</tr>');
		}
		printf('</tbody></table>');
		$result->close();
		$db->next_result();
		
	?>
	</div>
	<input type="button" name="closeOptions" value="Close" onClick="hideDiv()" align="right">
	</div>
</div>
<?php 
	$db->close();
?>
</body>
</html>
<?php 
/**
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
