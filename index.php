<?php
define('PATH_ROOT', __DIR__);
header('Content-Type: text/html; charset=utf-8');

session_start();

require_once('authenticate.php');
require_once('functions.php');

if(USER_LOGGED) 
{ 
	if(!check_user($UserLogin)) logout();
    
  	$consultants = getSubCons ();
  	//var_dump(getShoppers());
  	
  	include('views/RsmUsers.php');  
}
else 
	include('views/LoginView.php');	

?>