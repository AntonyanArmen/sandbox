<?php
require_once 'db.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

function mysql_entities_fix_string($string)
{
	return htmlentities(mysql_fix_string($string));
}

function mysql_fix_string($string)
{
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return mysql_real_escape_string($string);
}

function html_fix_string($string) {
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return htmlentities ($string);
}

/**
 *
 */
function displayLoggedUser() {
	?>
<h2>Welcome,  <?php echo $_SESSION['name']?$_SESSION['name']:'Undefined' ?>!</h2>
<h4><a href="?logout">Logout</a></h4>
<?php 
}

/**
 * Return current loged user ID or false  
 */
function getLogedUserID() {
	$query ="SELECT pid
	FROM lst_Users 
	  WHERE login = '".$_SESSION['login']."'";
	
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	
	if (!mysql_num_rows($result)) 
		return false;
	if (mysql_num_rows($result) == 1) {
		return  mysql_fetch_row($result)[0];
	}
	return false;	
}

/**
 * Get subordinated consultants
 */
function getSubCons() {
	$query ="SELECT
	  cons.pid, cons.isActive, cons.name, cons.surname
	FROM lst_Users AS cons
	  INNER JOIN lst_Users AS sup
	    ON cons.superior = sup.pid
	  WHERE sup.login = '".$_SESSION['login']."'";
	 
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	 
	$consultants = array();
	while ($row = mysql_fetch_assoc($result))
	{
		array_push($consultants, $row);
	}
	
	return $consultants;
}

function getShoppers() {
	$query ="SELECT
  lst_Shopers.name,
  lst_TradeMark.shortName,
  lst_Shopers.pid,
  lst_Shopers.surname,
  lst_Shopers.middlename,
  lst_Shopers.phoneNumber,
  lst_Shopers.vip,
  lst_Shopers.sex
	  		FROM lst_Shopers
  INNER JOIN lst_TradeMark
    ON lst_Shopers.favTradeMarkID = lst_TradeMark.pid ORDER BY lst_Shopers.pid";

	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());

	$Shoppers = array();
	while ($row = mysql_fetch_assoc($result))
		array_push($Shoppers, $row);
	
	return $Shoppers;
}

function getPurchased($visitId) {
	$query = "SELECT
  		lst_products.name AS good,
		sub_vizits_purchasedproducts.number AS qnt
	FROM sub_vizits_purchasedproducts
  		INNER JOIN jrn_vizits
    		ON sub_vizits_purchasedproducts.vizitID = jrn_vizits.pid
  		INNER JOIN lst_products
    		ON sub_vizits_purchasedproducts.productID = lst_products.pid
  	WHERE jrn_vizits.pid =$visitId";

	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	
	$purchased = array();
	while ($row = mysql_fetch_assoc($result))
		array_push($purchased, $row);
	
	return $purchased;
}

/**
 * get all database record fields
 * */
function getDBRecord($table, $id) {
	if (!$table || !$id) 
		return false;
		
	$query ="SELECT * FROM $table WHERE pid = $id";
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());	
	return mysql_fetch_assoc($result);
}

function GoBack($delay = 0) {
	if (@$_SERVER['HTTP_REFERER'] != null)
	{
		header("Refresh: $delay;");
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
}
function getAllData($table)
{
	if (!$table)
		return false;
	
	$query ="SELECT * FROM $table";
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
		
	if (!mysql_num_fields($result))
		return  false;

	$options = array();
	while ($row = mysql_fetch_assoc($result)) {
		array_push($options, $row);
	}
	return $options;	
}

function getVisits($shopper)
{
	if (!$shopper)
		return false;
	
	$query =
	"SELECT
  jrn_Vizits.pid,
  jrn_Vizits.dateTime,
  jrn_Vizits.comment,
  lst_Users.surname,
  lst_Users.name AS userName,
  lst_SalePoints.name AS spName
FROM jrn_Vizits
  INNER JOIN lst_SalePoints
    ON jrn_Vizits.salePointID = lst_SalePoints.pid
  INNER JOIN lst_Users
    ON jrn_Vizits.userID = lst_Users.pid
    AND lst_Users.favSalePointID = lst_SalePoints.pid
 WHERE jrn_Vizits.shoperID = $shopper			
ORDER BY jrn_Vizits.dateTime DESC";
	
	$result = mysql_query($query);
	if (!mysql_num_fields($result))
		return  false;

	$visits = array();
	while ($row = mysql_fetch_assoc($result)) {
		array_push($visits, $row);
	}
	return $visits;	
}

function getSalesHistory($shopper)
{
	if (!$shopper)
		return false;

		$query =
		"SELECT
  lst_AxeSubtypes.name AS axeSubtype,
  jrn_Vizits.dateTime,
  lst_Products.name AS good,
  sub_Vizits_PurchasedProducts.number AS qnt
FROM sub_Vizits_PurchasedProducts
  INNER JOIN lst_Products
    ON sub_Vizits_PurchasedProducts.productID = lst_Products.pid
  INNER JOIN lst_ProductsLine
    ON lst_Products.lst_ProductsLineID = lst_ProductsLine.pid
  INNER JOIN lst_AxeSubtypes
    ON lst_ProductsLine.axeSubtypesID = lst_AxeSubtypes.pid
  INNER JOIN jrn_Vizits
    ON sub_Vizits_PurchasedProducts.vizitID = jrn_Vizits.pid
		WHERE jrn_Vizits.shoperID = $shopper
		ORDER BY lst_AxeSubtypes.name, jrn_Vizits.dateTime DESC";

		$result = mysql_query($query);
		if (!mysql_num_fields($result))
			return  false;

			$visits = array();
			while ($row = mysql_fetch_assoc($result)) {
				array_push($visits, $row);
			}
			return $visits;
}
function getVisitDetails($visit)
{
	if (!$visit)
		return false;

	// get PurchasedProducts
	$query =
		"SELECT
 			 lst_Products.name AS good,
 			 sub_Vizits_PurchasedProducts.number AS qnt
		FROM sub_Vizits_PurchasedProducts
  			INNER JOIN lst_Products
  			  ON sub_Vizits_PurchasedProducts.productID = lst_Products.pid
		WHERE sub_Vizits_PurchasedProducts.vizitID = $visit";

	$visitDetails =array();
	
	$result = mysql_query($query);
	if (!mysql_num_fields($result))
		return  false;

	$goodslist = array();
	while ($row = mysql_fetch_assoc($result)) {
		array_push($goodslist, $row);
	}
	
	array_push($visitDetails, array('Type'=>'Purchased','GoodsList'=>$goodslist));
	
	// get DeferredProducts
	$query =
		"SELECT
 			 lst_Products.name AS good,
 			 sub_Visits_DeferredProducts.number AS qnt
		FROM sub_Visits_DeferredProducts
  			INNER JOIN lst_Products
  			  ON sub_Visits_DeferredProducts.productID = lst_Products.pid
		WHERE sub_Visits_DeferredProducts.vizitID = $visit";

	$result = mysql_query($query);
	if (!mysql_num_fields($result))
		return  false;

	$goodslist = array();
	while ($row = mysql_fetch_assoc($result)) {
		array_push($goodslist, $row);
	}
	
	array_push($visitDetails, array('Type'=>'Deferred','GoodsList'=>$goodslist));
	
	// get PerformServices
	$query =
		"SELECT
 			 lst_3minService.name  AS service
		FROM sub_Visits_PerformServices
		  INNER JOIN lst_3minService
		    ON sub_Visits_PerformServices.`3minServiceID` = lst_3minService.pid
		WHERE sub_Visits_PerformServices.vizitID = $visit";

	$result = mysql_query($query);
	if (!mysql_num_fields($result))
		return  false;

	$goodslist = array();
	while ($row = mysql_fetch_assoc($result)) {
		array_push($goodslist, $row);
	}
	
	array_push($visitDetails, array('Type'=>'PerformedServices','GoodsList'=>$goodslist));
	
	// get OrderedProducts
	$query =
		"SELECT
		  lst_Products.name AS good,
		  sub_Vizits_OrderedProducts.number AS qnt,
		  sub_Vizits_OrderedProducts.canceled,
		  sub_Vizits_OrderedProducts.buyed
		FROM sub_Vizits_OrderedProducts
		  INNER JOIN lst_Products
		    ON sub_Vizits_OrderedProducts.productID = lst_Products.pid
		WHERE sub_Vizits_OrderedProducts.vizitID = $visit";

	$result = mysql_query($query);
	if (!mysql_num_fields($result))
		return  false;

	$goodslist = array();
	while ($row = mysql_fetch_assoc($result)) {
		array_push($goodslist, $row);
	}
	
	array_push($visitDetails, array('Type'=>'Ordered','GoodsList'=>$goodslist));
	
	return $visitDetails;
}



function validate_surname($field) {
	if ($field == "") return "No Surname was entered<br />";
	return "";
}

function validate_username($field) {
	if ($field == "") return "No Username was entered<br />";
	else if (strlen($field) < 5)
		return "Usernames must be at least 5 characters<br />";
		else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
			return "Only letters, numbers, - and _ in usernames<br />";
			return "";
}

function validate_age($field) {
	if ($field == "") return "No Age was entered<br />";
	else if ($field < 18 || $field > 110)
		return "Age must be between 18 and 110<br />";
		return "";
}

function validate_login($field) {
	// 	if ($field == "") return "No Email was entered<br />";
	// 	else if (!((strpos($field, ".") > 0) &&
	// 			(strpos($field, "@") > 0)) ||
	// 			preg_match("/[^a-zA-Z0-9.@_-]/", $field))
		// 		return "The Email address is invalid<br />";
		// 		return "";
}
?>