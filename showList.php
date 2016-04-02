<?php
	header('Content-Type: text/html; charset=utf-8');
	
	require_once 'login.php';
	if (isset($_POST['table']))
	{
		$db_server = mysql_connect($db_hostname, $db_username, $db_password);
		if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
		mysql_query("SET NAMES utf8");
		//mysqli_set_charset('utf8');
		mysql_select_db($db_database)
		or die("Unable to select database: " . mysql_error());
		
		$table = mysql_real_escape_string($_POST['table']);
		
		switch ($table) {
		case 'lst_ProductsLine':
			if (isset($_POST['action']))
			{
				$action = mysql_real_escape_string($_POST['action']);
				switch ($action) {
					case 'delete':
						$id = mysql_real_escape_string($_POST['id']);
						$query = "DELETE  FROM lst_ProductsLine WHERE pid=".$id;
						if (!mysql_query($query)) die ("Delete failed: " . mysql_error());					
					break;
					case 'insert':
						$axeSubtype = mysql_real_escape_string($_POST['axeSubtype']);
						$name = mysql_real_escape_string($_POST['name']);
						$query = "INSERT INTO lst_ProductsLine (axeSubtypesID, name)   VALUES (".$axeSubtype.", '".$name."')";	
						var_dump($query);
						if (!mysql_query($query)) die ("Insert failed: " . mysql_error());					
					break;
				}				
			}
			$query = "SELECT las.pid, las.name FROM lst_AxeSubtypes las";
			$result = mysql_query($query);
			if (!$result) die ("Database access failed: " . mysql_error());
			$axeSubtypes = array();
			while($row = mysql_fetch_row($result))
			{
				$axeSubtypes[$row[0]]=$row[1];
			}
			
			$query = <<<_EOD
			SELECT
				  lst_AxeSubtypes.name AS Подгруппа,
				  lst_ProductsLine.name AS Наименование,
				  lst_ProductsLine.pid AS pid,
				  lst_AxeSubtypes.pid AS parentPid
				FROM lst_ProductsLine
				INNER JOIN lst_AxeSubtypes
				    ON lst_ProductsLine.axeSubtypesID = lst_AxeSubtypes.pid
				    ORDER BY Подгруппа
_EOD;
			
			$result = mysql_query($query);
			if (!$result) die ("Database access failed: " . mysql_error());
			$rows = mysql_num_rows($result);
			$colNum = 2;
			$title = 'Линейки продуктов';			
			
			include("views/ProductsLines.php");
		break;
		
		default:
			mysql_select_db('information_schema')
				or die("Unable to select database: " . mysql_error());
	
			$query = "SELECT c.COLUMN_NAME, c.COLUMN_COMMENT  FROM information_schema.COLUMNS c WHERE c.TABLE_SCHEMA='lr' AND c.TABLE_NAME='".$table."'";
	
			$columns = mysql_query($query);
			if (!$columns) die ("Database access failed: " . mysql_error());
			$colNum = mysql_num_rows($columns);
		
			mysql_select_db($db_database)
				or die("Unable to select database: " . mysql_error());
			$query = "SELECT * FROM ".$table;
	
			$result = mysql_query($query);
			if (!$result) die ("Database access failed: " . mysql_error());
			$rows = mysql_num_rows($result);
			$title = (isset($_POST['titleName'])?$_POST['titleName']:"Undef");

			include_once("views/List.php");
		break;
		}
	}
?>