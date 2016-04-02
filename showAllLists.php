<?php
	header('Content-Type: text/html; charset=utf-8');
	
    $query = 'SELECT TABLE_NAME, TABLE_COMMENT  FROM TABLES WHERE TABLE_NAME LIKE "lst%"';
	require_once 'login.php';
	
	$db_server = mysql_connect($db_hostname, $db_username, $db_password);
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db('information_schema')
		or die("Unable to select database: " . mysql_error());

	mysql_query("SET NAMES utf8");

	$tables = mysql_query($query);
	$tablesNum = mysql_num_rows($tables);
	
	$lists = array();
	for ($j = 0 ; $j < $tablesNum ; ++$j)	{
		$table = mysql_fetch_row($tables);
		$lists[$j] = array('Name'=>$table[0],'Comment'=>$table[1]);
		//echo '<br>'.$table[0].'    '. $table[1];
	}	
	
	include_once("views/AllLists.php");
?>