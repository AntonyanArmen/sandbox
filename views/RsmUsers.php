<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Consultants</title>
 	<link rel="stylesheet" type="text/css" href="./css/normalize.css">
	<link rel="stylesheet" type="text/css" href="./css/reset.css">
	<link rel="stylesheet" type="text/css" href="./css/style.css"/>
	<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css"/>
	<script type="text/javascript">
	function go(id) {
		location.href = './user.php?id=' + id;
	}
	</script>
</head>
<body>
<?php 	displayLoggedUser(); ?>
<div >
	<h2>Consultants</h2>
	<a href="./user.php">+ Add new</a>
	<table class='usersTbl2'>
		<tr>
			<th>Name</th>
			<th>Surname</th>
			<th>Is active</th>
		</tr>	
		<?php foreach ($consultants as $cons): ?> 
			<tr class='hover' onclick=go(<?php echo $cons['pid'] ?>);> 
				<td><?= $cons["name"] ?></td>				
				<td><?= $cons["surname"] ?></td>				
				<td class='center'><?= $cons["isActive"]?"yes":"no" ?></td>				
			</tr>
		<?php endforeach ?>
	</table>
</div>
	<hr/>
	<h2>Shoppers</h2>
	<div class="shoppers">
		<?php foreach (getShoppers() as $shopper): ?>
			<a href="./shopper.php?id=<?php echo $shopper["pid"]?>">
				<div class="shopper <?php echo $shopper["pid"]<10?"unregshopper":""?>">
					<span class="tradeMarkShort"><?= $shopper["shortName"] ?></span>
					<?php if ($shopper["vip"]) :?>
						<span class=" fa fa-diamond shopperVIP"></span>
					<?php endif ?>
					<div class="shopperName">
						<?php if ($shopper["sex"]) :?>
							<span class="fa fa-male"></span>
						<?php else:?>
							<span class="fa fa-female"></span>
						<?php endif ?>						
			 			<span><?php  printf("%s %s %s",trim($shopper["surname"]),trim($shopper["name"]),trim($shopper["middlename"]) )?></span>
					</div>				
					<div class="shopperTel"><i class="fa fa-phone "></i><?php echo $shopper["phoneNumber"]?></div>
				</div>
			</a>
		<?php endforeach ?>		
	</div>
</body>
</html>