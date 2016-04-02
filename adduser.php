<?php 
include_once('functions.php');

$middlename = $surname = $name = $password = $birthdate = $login = $action = $passportData = "";

$action = isset($_POST['action']) ? html_fix_string($_POST['action']) : "Add";

if (isset($_POST['middlename']))
	$middlename = html_fix_string($_POST['middlename']);
if (isset($_POST['surname']))
	$surname = html_fix_string($_POST['surname']);
if (isset($_POST['name']))
	$name = html_fix_string($_POST['name']);
if (isset($_POST['password']))
	$password = html_fix_string($_POST['password']);
if (isset($_POST['birthdate']))
	$birthdate = html_fix_string($_POST['birthdate']);
if (isset($_POST['login']))
	$login = html_fix_string($_POST['login']);

	
$fail  = validate_forename($middlename);
$fail .= validate_surname($surname);
$fail .= validate_username($name);
$fail .= validate_password($password);
$fail .= validate_age($birthdate);
$fail .= validate_login($login);

if ($fail == "") {
	//echo "</head><body>Form data successfully validated: $forename,
	//	$surname, $username, $password, $age, $email.</body></html>";

	// This is where you would enter the posted fields into a database

	exit;
}

function validate_forename($field) {
	if ($field == "") return "No Forename was entered<br />";
	return "";
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

function validate_password($field) {
	if ($field == "") return "No Password was entered<br />";
	else if (strlen($field) < 6)
		return "Passwords must be at least 6 characters<br />";
		else if ( !preg_match("/[a-z]/", $field) ||
				!preg_match("/[A-Z]/", $field) ||
				!preg_match("/[0-9]/", $field))
			return "Passwords require 1 each of a-z, A-Z and 0-9<br />";
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

<!-- The HTML section -->
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Consultants</title>	
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	
<script type="text/javascript">
function validate(form)
{
	fail = validateSurname(form.surname.value)
	fail += validateUsername(form.name.value)
	fail += validatePassword(form.password.value)
	//fail += validateAge(form.age.value)
	//fail += validateEmail(form.email.value)
	if (fail == "") return true;
	else { alert(fail); return false; }
}


function validateSurname(field) {
	if (field == "") return "No Surname was entered.\n"
	return "";
}

function validateUsername(field) {
	if (field == "") return "No Username was entered.\n";
	else if (field.length < 5)
		return "Usernames must be at least 5 characters.\n";
	else if (/[^a-zA-Z0-9_-]/.test(field))
		return "Only letters, numbers, - and _ in usernames.\n";
	return "";
}

function validatePassword(field) {
	if (field == "") return "No Password was entered.\n";
	else if (field.length < 6)
		return "Passwords must be at least 6 characters.\n";
	else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||
		    ! /[0-9]/.test(field))
		return "Passwords require one each of a-z, A-Z and 0-9.\n";
	return "";
}

function validateAge(field) {
	if (isNaN(field)) return "No Age was entered.\n";
	else if (field < 18 || field > 110)
		return "Age must be between 18 and 110.\n";
	return "";
}

function validateEmail(field) {
	if (field == "") return "No Email was entered.\n";
		else if (!((field.indexOf(".") > 0) &&
			     (field.indexOf("@") > 0)) ||
			    /[^a-zA-Z0-9.@_-]/.test(field))
		return "The Email address is invalid.\n";
	return "";
}
</script>
</head>
<body>
<div align="center">
			<form method="post" action="adduser.php" onSubmit="return validate(this)">
				<table class="userEdit">
					<tr>
						<th colspan="2" align="center">New user</th>
					</tr>	
					<?php if (!$fail) :?>
					<tr><td colspan="2">Sorry, the following errors were found<br />
										in your form: 
										<p>	<font color="red" size="1"><i><?php echo $fail?></i></font></p>
						</td>
					</tr>	
					<?php endif ?>
					<tr>
						<td class='right labelCol requiredLbl'>Name</td>
						<td><input required autofocus class='userEditInput' type="text" maxlength="16" name="name" value="<?php echo $name?>" /></td>
					</tr>
					<tr>
						<td class='right requiredLbl'>Surname</td>
						<td><input required class='userEditInput' type="text" maxlength="32" name="surname" value="<?php echo $surname?>" /></td>
					</tr>
					<tr>
						<td class='right'>Middlename</td>
						<td><input type="text" class='userEditInput' maxlength="32" name="middlename" value="<?php echo $middlename?>" /></td>
					</tr>
					<tr>
						<td class='right requiredLbl'>Login</td>
						<td><input required class='userEditInput' type="text" maxlength="64"	name="login" value="<?php $login?>" /></td>
					</tr>
					<tr>
						<td class='right requiredLbl'>Password</td>
						<td><input required class='userEditInput' type="password" maxlength="12" name="password" value="<?php echo $password?>" /></td>
					</tr>
					<tr>
						<td class='right'>Birthdate</td>
						<td><input type="date" class='userEditInput' maxlength="10" name="birthdate" pattern='([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})' placeholder="dd.mm.yyyy" value="<?php echo $birthdate?>" /></td>
					</tr>
					<tr>
						<td class='right'>Phone number</td>
						<td><input type="tel" class='userEditInput' pattern="^(\+7)(\(\d{3}\)|\d{3})\d{7}$"  maxlength="12" placeholder="+7(xxx)yyyyyyyy"	name="phone" value="<?php $phone?>" /></td>
					</tr>
					<tr>
						<td class='right'>E-mail</td>
						<td><input type="email" class='userEditInput' pattern="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$"  maxlength="30" name="email" value="<?php $email?>" /></td>
					</tr>
					<tr>
						<td class='right requiredLbl'>Gender</td>
						<td>
							<input  required type="radio" name="sex" value="<?php is_null($sex)?0:$sex;?>" />Male
							<input  required type="radio" name="sex" value="<?php is_null($sex)?0:$sex;?>" />Female
						</td>
					</tr>
					<tr>
						<td class='right'>Passport data</td>
						<td>
							<textarea class='userEditInput' rows="5" name="passportData"><?php echo $passportData.''?></textarea>							
						</td>
					</tr>
					<tr>
						<td class='right'>Category</td>
						<td>
							<select name='category' class='userEditInput'>
								<option disabled selected>Select category</option>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class='right'>Sale point</td>
						<td>
							<select name='salePoint' class='userEditInput'>
								<option disabled selected>Select sale point</option>
								<option value='1'>sp1</option>
								<option value='2'>sp2</option>
								<option value='3'>sp3</option>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td> 
						<br>
							*  Required fields
						</td>
					</tr>
					<tr>
						<td colspan="2" class="center"> 
							<input type="submit" value="Save" class='cellbut' />
						</td>
					</tr>
				</table>
			</form>
</div>
</body>
</html>