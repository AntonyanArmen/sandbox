<?php // authenticate.php

require_once 'db.php';
require_once('functions.php');

##Определяем константы
define('USERS_TABLE','lst_Users');
define('SID',session_id());

$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());

mysql_query("SET NAMES utf8");

##Действия - если пользователь авторизирован
if(isset($_SESSION['login'])) { //Если была произведена авторизация, то в сессии есть uid
	//Константу удобно проверять в любом месте скрипта
	define('USER_LOGGED',true);

	$UserName = $_SESSION['name'];
	$UserLogin = $_SESSION['login'];
}
else {
	define('USER_LOGGED',false);
}

##Действия при попытке входа
if (isset($_POST['login'])) 
{
	$user = mysql_entities_fix_string($_POST['user']);
	$pass = mysql_entities_fix_string($_POST['passHash']);
	
	if(login($user,$pass)) 
	{
		header('Refresh: 1');
		header('Location : index.php');
	}
	else 
	{
		header('Refresh: 3;');
		die('Invalid username/password combination or user is inactive!');
	}

}

##Действия при попытке выхода
if(isset($_GET['logout'])) {
	logout();
}

##Определяем функции
//Функция выхода.
//Пользователь считается авторизированым, если в сессии присутствует uid
//см. "Действия - если пользователь авторизирован".
function logout() {
	unset($_SESSION['login']); 
	unset($_SESSION['name']); 
	session_destroy();
	die(header('Location: '.$_SERVER['PHP_SELF']));
}

//Функция входа.
//Все выбраные поля записываются в сессию.
//Таким образом, при каждом просмотре страницы не надо выбирать их заново.
//Для обновления информации из БД можно пользоваться этой же функцией - имя и пароль
//хранятся в сессиях
function login($login,$password)    
{	
	$result = mysql_query("SELECT name,password,login, isActive  FROM lst_Users WHERE login='$login'");

	if (!$result) die("Database access failed: " . mysql_error());
	elseif (mysql_num_rows($result))
	{
		$USER = mysql_fetch_array($result,1); //Генерирует удобный массив из результата запроса
		
		if ($USER['isActive']==0)
			return false;
		
		$token = md5("$password");
		
		if ($token == $USER['password'])
		{
			$_SESSION = array_merge($_SESSION,$USER); //Добавляем массив с пользователем к массиву сессии
			mysql_query("UPDATE `lst_Users` SET `session`='".SID."' WHERE `login`='".$USER['login']."';")
				or die(mysql_error());
					
			session_start();
			return true;
		}
		else
			return false;			
	}
	else 
		return false;
}

//Функция проверки залогинности пользователя.
//При входе, ID сессии записывается в БД.
//Если ID текущей сессии и SID из БД не совпадают, производится logout.
//Благородя этому нельзя одновременно работать под одним ником с разных браузеров.
function check_user($login) {
	$result = mysql_query("SELECT `session` FROM `".USERS_TABLE."` WHERE `login`='$login';") or die(mysql_error());
	$sid = mysql_result($result,0);
	return $sid==SID;
}

?>