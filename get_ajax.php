<?php
	require_once('functions.php');
	header('Content-Type: text/html; charset=utf-8');
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	
	if (!isset($_POST['id']) || $_POST['id']<=0)
	{
		echo "Id is empty";
		exit;
	}
	
		
	$details = getVisitDetails(html_fix_string($_POST['id']));
	//var_dump($details);  echo '<br/>';
	if ($details) 
	{  ?>
		<?php foreach ($details as $visitDetailType):?>
		<?php if ($visitDetailType['GoodsList'] == []) continue;?>
			<fieldset>
				<legend><?php echo $visitDetailType['Type']?></legend>
				<?php switch ($visitDetailType['Type']): 
					case 'Purchased':
					case 'Deferred':?>
						<table>
							<?php foreach ($visitDetailType['GoodsList'] as $list): ?>
							<tr>
								<td><?php echo $list['good']?></td>
								<td><?php echo $list['qnt']?></td>
							</tr>
							<?php endforeach; ?>	
						</table>
					<?php break; 
					case 'Ordered':?>
						<table>
							<?php foreach ($visitDetailType['GoodsList'] as $list): ?>
							<tr>
								<td><?php echo $list['good']?></td>
								<td><?php echo $list['qnt']?></td>
								<td><?php if ($list['canceled']): ?><i class="fa fa-times text-danger"></i><?php endif;?></td>
								<td><?php if ($list['buyed']): ?><i class="fa fa-check text-success"></i><?php endif;?></td>
							</tr>
							<?php endforeach; ?>	
						</table>
					<?php break; 
					case 'PerformedServices':?>
							<?php foreach ($visitDetailType['GoodsList'] as $list): ?>
								<span><?php echo $list['service']?></span>
							<?php endforeach; 
						break;
				endswitch; ?>
			</fieldset>
		<?php endforeach;?>
	<?php }
?>