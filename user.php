<?php 
require_once('functions.php');
require_once 'db.php';
if(!isset($_SESSION))
	session_start();

$login = $passHash = $middlename = $surname = $name = $birthday =  $passportData = "";
$phone = $email = $isActive = $sex = $category = $favSalePoint = '';

$fail ='';
$action = null;

// get all trademarks
$query ="SELECT * FROM lst_TradeMark";
$result = mysql_query($query);
if (!$result) die("Database access failed: " . mysql_error());

$tradeMarks = array();
while ($row = mysql_fetch_assoc($result))
{
	array_push($tradeMarks, $row);
}

// get all sale points
$query ="SELECT pid, name FROM lst_SalePoints";
$result = mysql_query($query);
if (!$result) die("Database access failed: " . mysql_error());

$salePoints = array();
while ($row = mysql_fetch_assoc($result))
{
	array_push($salePoints, $row);
}

$id=-1;
if (isset($_GET['id'])) {
	$action = 'Edit';
	$id = html_fix_string($_GET['id']);
	
	$query ="SELECT * FROM lst_Users WHERE pid = ".$id;	
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	
	$cons = mysql_fetch_assoc($result);
	$login = $cons['login'];
	$isActive = $cons['isActive'];

	$name = $cons['name'];
	$surname = $cons['surname'];
	$middlename = $cons['middleName'];
	$birthday = $cons['birthday'];
	$passportData = $cons['passportData'];
	$phone = $cons['phoneNumber'];
	$email = $cons['email'];
	$sex = $cons['sex'];
	$favSalePoint = $cons['favSalePointID'];

	$category = is_null($cons['categoryID']) ? 0 : intval($cons['categoryID']);
	
	$query ="SELECT rutm.tradeMarkID FROM `rel_Users-TradeMarks` rutm WHERE rutm.userID=".$id;	
	$result = mysql_query($query);
	if (!$result) die("Database access failed: " . mysql_error());
	
	$userTM = array();
	while ($row = mysql_fetch_row($result))
	{
		$userTM[] = (int)$row[0];
	}	
}

if (isset($_POST['action']) ) {
	
	$action = html_fix_string($_POST['action']);
	$action = substr($action, 0, strlen($action)-1);
		
	if (isset($_POST['login']))
		$login = html_fix_string($_POST['login']);
	if (isset($_POST['passHash']))
		$passHash = html_fix_string($_POST['passHash']);
	var_dump($passHash);echo "<br/>";
	if (isset($_POST['isActive']))
	{
		$isActive = html_fix_string($_POST['isActive']);
		$isActive = (int)$isActive;
	}
	
	if (isset($_POST['middlename']))
		$middlename = html_fix_string($_POST['middlename']);
	if (isset($_POST['surname']))
		$surname = html_fix_string($_POST['surname']);
	if (isset($_POST['name']))
		$name = html_fix_string($_POST['name']);
	if (isset($_POST['birthdate']))
		$birthday = html_fix_string($_POST['birthdate']);
		
	if (isset($_POST['phone']))
		$phone = html_fix_string($_POST['phone']);
	if (isset($_POST['email']))
		$email = html_fix_string($_POST['email']);
	if (isset($_POST['sex']))
		$sex = html_fix_string($_POST['sex']);
	if (isset($_POST['passportData']))
		$passportData = html_fix_string($_POST['passportData']);
	
	if (isset($_POST['category']))
		$category = html_fix_string($_POST['category']);
	if (strlen($category) == 0)
		$category = 'NULL';
	
	if (isset($_POST['salePoint']))
		$favSalePoint = html_fix_string($_POST['salePoint']);
	if (strlen($favSalePoint) == 0)
		$favSalePoint = 'NULL';
	
	if (isset($_POST['tradeMarks']))
		$userTM = $_POST['tradeMarks'];
				
	//$fail = validate_login($login);
	//$fail .= validate_surname($surname);
	//$fail .= validate_username($name);
	//$fail .= validate_age($birthdate);
	
	if ($fail == "") {
		switch ($action) {
			case 'Insert':
				$query ="INSERT HIGH_PRIORITY INTO lst_Users
						(login, 
						password, 
						isActive, 
						name, 
						surname, 
						middleName, 
						phoneNumber, 
						passportData, 
						sex, 
						email, 
						workBeginnig, 
						birthday, 
						categoryID, 
						favSalePointID, 
						superior, 
						positionID)
				VALUES
						('$login',
						'".md5($passHash)."',
						'$isActive',
						'$name',
						'$surname',
						'$middlename',
						'$phone',
						'$passportData',
						'$sex',
						'$email',
						NOW(),
						STR_TO_DATE('$birthday','%Y-%m-%d'),
						".$category.",
						".$favSalePoint.",
						'".getLogedUserID()."',
						'1' )"; // positionID = 1 - простые консультанты
				var_dump($query);
				$result = mysql_query($query);
				if (!$result) die("Database access failed: " . mysql_error());
				$id = mysql_insert_id();
									
				// save rel_ table info
				try {
					//mysqli_begin_transaction($db_server,MYSQLI_TRANS_START_READ_WRITE);
					//mysqli_rollback($link);
				
					$query ='INSERT HIGH_PRIORITY INTO `rel_Users-TradeMarks`(
								userID, tradeMarkID)
								VALUES (%d, %d)';
				
					foreach ($userTM as $userTradeMark) {
						$result = mysql_query(sprintf($query,$id,$userTradeMark));
						if (!$result) die("Database access failed: " . mysql_error());
					}
					//mysqli_commit($db_server);
				}
				catch (Exception $e)
				{
					die("Error occured due user trademarks update: " .$e);
					//mysqli_rollback($db_server);
				}
				
				break;
			case 'Edit':
					$id = html_fix_string($_POST['id']);
				
					// save main data
					$query ="UPDATE lst_Users lu 
							SET     lu.isActive = '$isActive', 
									lu.login = '$login',
									lu.name = '$name',
									lu.surname = '$surname',
									lu.sex = '$sex',
									
									lu.middleName = '$middlename',
									lu.birthday = STR_TO_DATE('$birthday','%Y-%m-%d'),
									lu.phoneNumber = '$phone',
									lu.email = '$email',
									lu.passportData = '$passportData',
									lu.categoryID = ".$category.",
									lu.favSalePointID = ".$favSalePoint."									
							WHERE lu.pid = '$id'";
					$result = mysql_query($query);
					if (!$result) die("Database access failed: " . mysql_error());
					
					// save rel_ table info
					try {
						//mysqli_begin_transaction($db_server,MYSQLI_TRANS_START_READ_WRITE);
						//mysqli_rollback($link);
						
 						$query ="DELETE LOW_PRIORITY QUICK 	FROM `rel_Users-TradeMarks`	WHERE `rel_Users-TradeMarks`.userID =".intval($id);
						$result = mysql_query($query);
						if (!$result) die("Database access failed: " . mysql_error());
							
						$query ='INSERT HIGH_PRIORITY INTO `rel_Users-TradeMarks`(
								userID, tradeMarkID)
								VALUES (%d, %d)';

						foreach ($userTM as $userTradeMark) {
							$result = mysql_query(sprintf($query,$id,$userTradeMark));
							if (!$result) die("Database access failed: " . mysql_error());								
						} 						
						//mysqli_commit($db_server);						
					}
					catch (Exception $e) 
					{
						die("Error occured due user trademarks update: " .$e);
						//mysqli_rollback($db_server);
					}					
				break;
			default:
				;
			break;
		}
	}
}
 
?>

<!-- The HTML section -->
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Consultants</title>	
	<link rel="stylesheet" type="text/css" href="./css/style.css">
 	<script type="text/javascript" src="./js/md5.js"></script>
 
	
<script type="text/javascript">
function validate(form)
{
	fail = validateSurname(form.surname.value)
	fail += validateUsername(form.name.value)
	fail += validatePassword(form.password.value)
	//fail += validateAge(form.age.value)
	//fail += validateEmail(form.email.value)
	if (fail == "") 
	{
		form.passHash.value = hex_md5(form.password.value);
		form.password.value = '';
		return true;
	}
	else 
	{ 
		alert(fail); 
		return false; 
	}
}


function validateSurname(field) {
	if (field == "") return "No Surname was entered.\n"
	return "";
}

function validateUsername(field) {
	if (field == "") return "No Username was entered.\n";
	else if (field.length < 3)
		return "Usernames must be at least 3 characters.\n";
	else if (/[^a-zA-Zа-яА-Я0-9_-]/.test(field))
		return "Only letters, numbers, - and _ in usernames.\n";
	return "";
}

function validatePassword(field) {
	if (field == "") return "No Password was entered.\n";
	else if (field.length < 3)
		return "Passwords must be at least 3 characters.\n";
	else if  (!/[0-9]/.test(field))
		return "Passwords require only numbers.\n";
//	else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||
//		    ! /[0-9]/.test(field))
//		return "Passwords require one each of a-z, A-Z and 0-9.\n";
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
			<form name='userData' method="post" action="user.php" onSubmit="return validate(this)">
				<input type="hidden" name='action' value=<?php echo is_null($action)?'Insert':$action?>/>
				<input type="hidden" name="passHash">
				<input type="hidden" name="id" value='<?php echo /*is_null($id)? '' :*/ $id ?>' >
				<table class="userEdit">
					<tr>
						<th colspan="2" align="center"><?php echo !is_null($action) && $action == 'Edit'?'User details':'Add user'?></th>
					</tr>	
					<?php if ($fail!='') :?>
					<tr><td colspan="2">Sorry, the following errors were found<br />
										in your form: 
										<p>	<font color="red" size="1"><i><?php echo $fail?></i></font></p>
						</td>
					</tr>	
					<?php endif ?>
					<tr>
						<td class='right requiredLbl'>Login</td>
						<td><input required  class='userEditInput' type="text" maxlength="64"	name="login" value="<?php echo $login?>" /></td>
					</tr>
					<?php if (is_null($action) || $action == 'Insert') :?>
					<tr>
						<td class='right requiredLbl'>Password</td>
						<td><input required class='userEditInput' type="password" maxlength="20" name="password" value="<?php echo $passHash?>" <?php echo !is_null($action) && $action == 'Edit'?'disabled':''?>/></td>
					</tr>
					<?php endif ?>
					<tr>
						<td class='right'><label for="__isActive">Is active</label></td>
						<td>
							<input type="hidden" name="isActive" value='0'/>
							<input type="checkbox" name="isActive" id="__isActive" value="1"  <?php echo !is_null($isActive) && boolval($isActive)?  'checked': ''; ?>/>
						</td>
					</tr>
					<tr>
						<td class='right labelCol requiredLbl'>Name</td>
						<td><input required class='userEditInput' type="text" autocomplete="on" maxlength="16" name="name" value="<?php echo $name?>" /></td>
					</tr>
					<tr>
						<td class='right requiredLbl'>Surname</td>
						<td><input required class='userEditInput' type="text" autocomplete="on" maxlength="32" name="surname" value="<?php echo $surname?>" /></td>
					</tr>
					<tr>
						<td class='right'>Middlename</td>
						<td><input type="text" class='userEditInput' maxlength="32" autocomplete="on" name="middlename" value="<?php echo $middlename?>" /></td>
					</tr>
					<tr>
						<td class='right'>Birthdate</td>
						<td><input type="date" class='userEditInput' maxlength="10" autocomplete="on" name="birthdate" pattern='([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})' placeholder="dd.mm.yyyy" value="<?php echo $birthday?>" /></td>
					</tr>
					<tr>
						<td class='right'>Phone number</td>
						<td><input type="tel" class='userEditInput' autocomplete="on" pattern="^(\+7)(\(\d{3}\)|\d{3})\d{7}$"  maxlength="12" placeholder="+7(xxx)yyyyyyyy"	name="phone" value="<?php echo $phone?>" /></td>
					</tr>
					<tr>
						<td class='right'>E-mail</td>
						<td><input type="email" class='userEditInput' autocomplete="on" pattern="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$"  maxlength="30" name="email" value="<?php echo $email?>" /></td>
					</tr>
					<tr>
						<td class='right requiredLbl'>Gender</td>
						<td>
							<input  required type="radio" name="sex" value="male" <?php echo !is_null($sex) && $sex == 'male'?  'checked': ''; ?>/>Male
							<input  required type="radio" name="sex" value="female" <?php echo !is_null($sex) && $sex == 'female'?  'checked': ''; ?>/>Female
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
								<option value='4'>4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class='right'>Sale point</td>
						<td>
							<select name='salePoint' class='userEditInput'>
								<option disabled selected>Select sale point</option>
								<?php foreach ($salePoints as $sp): ?>
									<option value='<?php echo $sp['pid']?>' <?php echo $sp['pid']== $favSalePoint?'selected':'' ?>><?php echo $sp['name']?></option>
								<?php endforeach;?> 
							</select>
						</td>
					</tr>
					<tr>
						<td class='right requiredLbl'>Trade marks</td>
						<td>
							<select name='tradeMarks[]' required="required" class='userEditInput' multiple="multiple" size="5" >
							<?php foreach ($tradeMarks as $tm): 
								$id = $tm['pid'];
								$selected = in_array($id, $userTM)?'selected':'';
							?>
								<option value='<?php echo $id?>' <?php echo $selected?>><?php echo $tm['longName']?></option>
							<?php endforeach;?> 
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