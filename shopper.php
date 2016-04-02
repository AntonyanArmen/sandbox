<?php
	include_once('functions.php');
	
	if (!isset($_GET["id"]) || !$_GET["id"])
	{
		GoBack();
		exit;
	}
	
	$shopperID = html_fix_string($_GET["id"]);
	$shopper = getDBRecord('lst_Shopers', $shopperID);
	if (!$shopper)
	{
		echo "Can't get shopper details";
		GoBack(2);
		exit;
	}
	
	$visits = getVisits($shopperID);
	$salesHistory = getSalesHistory($shopperID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Shopper details</title>
	 <link rel="stylesheet" type="text/css" href="./css/normalize.css">
	<link rel="stylesheet" type="text/css" href="./css/reset.css">
	<link rel="stylesheet" type="text/css" href="./css/style.css"/>
	<link rel="stylesheet" type="text/css" href="./css/shopper.css"/> 
	<link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css"/>
	<script>
		function XmlHttp()
		{
			var xmlhttp;
			try
			{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e)
			{
			 	try 
			 	{
				 	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				 } 
			 	catch (E) {xmlhttp = false;}
			}
			if (!xmlhttp && typeof XMLHttpRequest!='undefined')
				 xmlhttp = new XMLHttpRequest();
			return xmlhttp;
		}
		
		function showDetails(event) {
			var param = 'id='+event.currentTarget.id;

			var req = XmlHttp();
			req.open("POST", "get_ajax.php", true); 
			req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			req.onreadystatechange = function()
			{
				if (this.readyState == 4)
				{
					if (this.status == 200)
					{
						if (this.responseText != null)
							document.getElementById('details').innerHTML = this.responseText;
							//console.log(this.responseText);
						else 
							alert("Ajax error: No data.");
					}
					else 
						alert( "Ajax error: " + this.statusText)
				}
			}
			req.send(param);
		}

	</script>
</head>
<body>
	<h2>Shopper detail information</h2>
	<fieldset>
		<legend>Main</legend>
		<p>
			<?php if ($shopper["sex"]) :?>
				<span class="fa fa-male"></span>
			<?php else:?>
				<span class="fa fa-female"></span>
			<?php endif ?>								
			<span><?php  printf("%s %s %s",trim($shopper["surname"]),trim($shopper["name"]),trim($shopper["middlename"]) )?></span>
			<?php if ($shopper["vip"]) :?>
				<span class="fa fa-diamond VIP">VIP</span>
			<?php endif ?>
			<br/>
			<span>
				<i class="fa fa-birthday-cake"></i> 
				<input type="date" disabled="disabled" value="<?php echo $shopper["birthday"] ?>"/>
			</span>

		</p>
		<p>
			<span><i class="fa fa-phone"></i> <?php echo $shopper["phoneNumber"]?></span>
			<?php if ($shopper["onlySMS"]) :?>
				<span class="fa-stack">
					<i class="fa fa-mobile fa-stack-2x"></i>
					<i class="fa fa-ban fa-stack-2x text-danger"></i>
				</span>
				<span>Only SMS</span>
			<?php endif ?>		
			<?php if ($shopper["email"]) :?>
				<span><i class="fa fa-envelope-o"></i>&nbsp<?php echo $shopper["email"]?></span>
			<?php endif ?>		
		</p>		
	</fieldset>	
	<fieldset>
		<legend>Reg info</legend>
		<p>Registration date: <?php echo substr($shopper["registration"], 11) ?> <input type="date" disabled="disabled" value="<?php echo substr($shopper["registration"], 0, 10) ?>"/><p>
		<p>Skin type:
			<select disabled="disabled">
				<?php foreach (getAllData('lst_SkinType') as $st): ?>
					<option value='<?php echo $st['pid']?>' <?php echo $st['pid']== $shopper["skinTypeID"]?'selected':'' ?>><?php echo $st['skinType']?></option>
				<?php endforeach;?> 			
			</select> 
		</p>
		<p>Trade mark:
			<select disabled="disabled">
				<?php foreach (getAllData('lst_TradeMark') as $st): ?>
					<option value='<?php echo $st['pid']?>' <?php echo $st['pid']== $shopper["favTradeMarkID"]?'selected':'' ?>><?php echo $st['longName']?></option>
				<?php endforeach;?> 			
			</select> 
		</p>
		<p>Favorite sale point:
			<select disabled="disabled">
				<?php foreach (getAllData('lst_SalePoints') as $st): ?>
					<option value='<?php echo $st['pid']?>' <?php echo $st['pid']== $shopper["favSalePointID"]?'selected':'' ?>><?php echo $st['name']?></option>
				<?php endforeach;?> 			
			</select> 
		</p>
		<p>
			<p>
				<?php if ($shopper["newProdNotifyAccept"]) :?>
					<i class="fa fa-check text-success"></i>
				<?php else:?>
					<i class="fa fa-times text-danger"></i>
				<?php endif ?>
				<span>Consent to receive information about new products</span>	
			</p>
			<p>
				<?php if ($shopper["inviteToClientDayAccept"]) :?>
					<i class="fa fa-check text-success"></i>
				<?php else:?>
					<i class="fa fa-times text-danger"></i>
				<?php endif ?>
				<span>Consent to an invitation to the client days</span>	
			</p>
			<p>
				<?php if ($shopper["makeupLessonsAccept"]) :?>
					<i class="fa fa-check text-success"></i>
				<?php else:?>
					<i class="fa fa-times text-danger"></i>
				<?php endif ?>
				<span>Consent to makeup lessons</span>	
			</p>	
			<p>
				<?php if ($shopper["personalDataProcessingAccept"]) :?>
					<i class="fa fa-check text-success"></i>
				<?php else:?>
					<i class="fa fa-times text-danger"></i>
				<?php endif ?>
				<span>Consent to the processing of personal data</span>	
			</p>
		</p>
	</fieldset>
	<fieldset>
		<legend>Comments</legend>
		<textarea rows="" cols=""><?php echo $shopper["commentShoper"]?></textarea>
		<textarea rows="" cols=""><?php echo $shopper["commentUser"]?></textarea>
	</fieldset>
	<fieldset>
		<legend>Visits</legend>
		<table>
			<thead>
				<td>Date & time</td>
				<td>Comment</td>
				<td>Cons</td>
				<td>Sale point</td>
			</thead>
			<?php foreach ($visits as $visit): ?>
				<tr class="active" id="<?php echo $visit['pid'] ?>" onclick=showDetails(event)>
					<td><?php echo $visit['dateTime']?></td>
					<td><?php echo $visit['comment'].$visit['pid']?></td>
					<td><?php echo $visit['surname'].' '.$visit['userName']?></td>
					<td><?php echo $visit['spName']?></td>
				</tr>
			<?php endforeach;?> 
		</table>
	</fieldset>
	<fieldset>
		<legend>Visit details</legend>
		<div id="details">
		</div>
	</fieldset>
	<fieldset>
		<legend>Sales history</legend>
		<table>
			<thead>
				<td>Axe subtype</td>
				<td>Date & time</td>
				<td>Product</td>
				<td>Qnt</td>
			</thead>
			<?php foreach ($salesHistory as $sale): ?>
				<tr>
					<td><?php echo $sale['axeSubtype']?></td>
					<td><?php echo $sale['dateTime']?></td>
					<td><?php echo $sale['good']?></td>
					<td><?php echo $sale['qnt']?></td>
				</tr>
			<?php endforeach;?> 
		</table>
	</fieldset>
</body>
</html>