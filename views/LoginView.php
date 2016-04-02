<!DOCTYPE html>
<html>
<head>
	<META charset="utf8">
	<title>Authorization required</title>
 	<link rel="stylesheet" type="text/css" href="./css/normalize.css">
	<link rel="stylesheet" type="text/css" href="./css/reset.css">
 	<link rel="stylesheet" type="text/css" href="./css/style.css">
  	<script type="text/javascript" src="./js/md5.js"></script>
  <script>
   function makeHash(f) {
	   document.loginForm.passHash.value = hex_md5(document.loginForm.pass.value);
	   document.loginForm.pass.value = '';
	   //alert(hex_md5(document.loginForm.passHash.value));
       f.submit();
   }
  </script>
</head>
<body>
    <div class="loginForm">
		    <form name="loginForm" method="post" action="index.php" onsubmit="makeHash(this);return false;">
				<input type="hidden" name="passHash">
				<input type="hidden" name="login">
                <input required class='loginInput' type="text" name="user" placeholder="Login">
                <input required class='loginInput' type="password" name="pass" placeholder="Password">
                <input type="submit" value="Authorize">
		    </form>        
    </div>	    
</body>
</html>