<!DOCTYPE html>
<html>
<head>
	<META charset="utf8">
	<title><?=$title?></title>
	<!--link rel="stylesheet" href="style.css"-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
	<script language="JavaScript"> 
		function Delete(id) { 
			if (confirm("Точно?"))
			{
				document.deleteForm.table.value = 'lst_ProductsLine';
				document.deleteForm.id.value = id;
				document.deleteForm.submit();
			} 
		} 
	</script> 
	<form name="deleteForm" action='./showList.php' method="post"> 
		<input type="hidden" name="id"> 
		<input type="hidden" name="table"> 
		<input type="hidden" name="action" value='delete'> 
	</form> 

<div align='center'>
	<h2><?=$title?></h2>
	<a href="./">&lt;&lt;Назад к списку</a> 
	
	<form name="insertForm" action='./showList.php' method="post"> 
		<label>Подгруппа
			<select name="axeSubtype">
				<?php foreach ($axeSubtypes as $key => $value): ?>
				<option value=<?=$key?>><?=$value?></option>			
				<?php endforeach ?>
			</select>
		</label><br>
		<label>Наименование<input name="name"> </label><br>
		<input type="hidden" name="action" value="insert">
		<input type="hidden" name="table" value="lst_ProductsLine"> 
		<input type="submit" value="Добавить новый">		
	</form> 
	
	<table class='table'>
		<tr>
			<th>Подгруппа</th>
			<th>Наименование</th>
			<th/>
		</tr>
	
		<?php for ($j = 0 ; $j < $rows ; ++$j): ?> 
			<?php $row = mysql_fetch_row($result); ?>
			<tr>
				<?php for ($k = 0 ; $k < $colNum ; ++$k):?>
					<td><?=$row[$k]?></td>
				<?php endfor ?>
				<td><a href="javascript:Delete('<?=$row[2]?>')">Удалить</a></td> 
			</tr>
		<?php endfor ?>
	</table>
</div>
</body>
</html>