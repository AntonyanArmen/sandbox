<!DOCTYPE html>
<html>
<head>
	<META charset="utf8">
	<title><?=$title?></title>
	<!--link rel="stylesheet" href="style.css"-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<div align='center'>
	<h2><?=$title?></h2>
	<table class='table'>
		<tr>
			<?php for ($j = 0 ; $j < $colNum ; ++$j): 
					$row = mysql_fetch_row($columns);
					if ($row[0]=='pid') continue;
					echo '<th>'.((empty($row[1]))? $row[0] : $row[1]).'</th>';
			 ?>
			<?php endfor ?>
		</tr>
	
		<?php for ($j = 0 ; $j < $rows ; ++$j): ?> 
			<?php $row = mysql_fetch_row($result); ?>
			<tr>
				<?php for ($k = 1 ; $k < $colNum ; ++$k): //начнем с 1 потому что pid не нужен 
				?>
					<td><?=$row[$k]?></td>
				<?php endfor ?>
			</tr>
		<?php endfor ?>
	</table>
<div>
</body>
</html>