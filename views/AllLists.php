<!DOCTYPE html>
<html>
<head>
	<META charset="utf8">
	<title>Выбор справочника</title>
	<link rel="stylesheet" href="bootstrap.css">
</head>
<body>
	<script language="JavaScript"> 
		function Show(table,titleName) { 
		document.sendForm.table.value = table;
		document.sendForm.titleName.value = titleName; 
		document.sendForm.submit(); 
		} 
	</script> 
	<form name="sendForm" action="showList.php" method="post"> 
		<input type="hidden" name="table"> 
		<input type="hidden" name="titleName"> 
	</form> 
	<div align="center" valign="center">
		<h3> Выберите справочник</h3>
		<?php foreach ($lists as $value): ?>
			<a href="javascript:Show('<?=$value['Name']?>','<?=$value['Comment']?>');"><?=$value['Comment']?></a> </br>
		<?php endforeach ?>
	<div>
</body>
</html>