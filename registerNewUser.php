<?php
// Страница регистрации нового пользователя

if(isset($_POST['submit']))
{
	$err = array();
	$login = $name = $phoneNumber = $pwd = NULL;

	function showErrors($arr)
	{
		print "<b>При регистрации произошли следующие ошибки:</b><br>";
		foreach($arr AS $elem)
		{
			print $elem."<br>";
		};
	}

    # проверям логин
    if(isset($_POST['login']))
    {
		$login = $_POST['login'];
		var_dump($login);
		echo '<br>';
		if(strlen($login) < 3 or strlen($login) > 30)
			$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
/* 		if (preg_match("/^[a-zA-Z0-9]+$/",$login))
		{
		}
		else
			$err[] = "Логин должен содержать латинские буквы d и цифры";
 */    } else 
        $err[] = "Укажите логин";

	
    if(isset($_POST['name']))
	{	if(strlen($_POST['name']) < 3 )
		{
			$err[] = "Имя должно быть не меньше 3-х символов";
		}
		else
			$name = $_POST['name'];
	}
	else
        $err[] = "Укажите имя";
	
    if(isset($_POST['password']))
	{	
		$pwd =  $_POST['password'];
		var_dump($pwd);
		echo '<br>';
		if(strlen($pwd) < 6)
			$err[] = "Пароль должен состоять не менее чем из 6 символов";
/* 		if (preg_match("/^[a-zA-Z0-9]+$/",$login))
		{
		}
		else
			$err[] = "Пароль должен содержать латинские буквы и цифры";
 */	}
	else
        $err[] = "Укажите пароль";

    if(isset($_POST['phone']))
		$phoneNumber =  $_POST['phone'];

	# Соединямся с БД
	$response = array();
	require_once  'db_connect.php'; 
	$db = new DB_CONNECT();
	
    # проверяем, не сущестует ли пользователя с таким именем	
    $query = mysql_query("SELECT COUNT(pid) FROM lst_Users WHERE login='".$login."'");
	$result = mysql_fetch_array($query);
    if($result["COUNT(pid)"] > 0)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    # Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {		
        # Убираем лишние пробелы и делаем двойное шифрование
        $passHash = md5(md5(trim($_POST['password'])));

        $query = mysql_query("INSERT INTO lst_Users SET login='".$login."', password='".$passHash."', name='".$name."', phoneNumber='".$phoneNumber."'");
		if(!$query)
		{
			$err[] =  mysql_error();			
			showErrors( $err);
		}
		else        
			print "Готово!";
    }
    else
		showErrors( $err);
}
?>

<form method="POST">
Имя <input name="name" type="text"><br>
Телефон <input name="phone" type="text"><br>
Логин <input name="login" type="text"><br>
Пароль <input name="password" type="password"><br>
<input name="submit" type="submit" value="Зарегистрироваться">
</form>