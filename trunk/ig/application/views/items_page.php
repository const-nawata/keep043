
<div><?php echo anchor('home', 'Main Page');?></div>
	<h1>Items</h1>

	<table cellpadding="0" cellspacing="0">
		<tr><th>Name</th><th>Date</th></tr>
<?php foreach( $items as $item ){ ?>
		<tr><td style="padding: 5px;"><?php echo $item->name; ?></td><td style="padding: 5px;"><?php echo $item->date; ?></td></tr>
<?php } ?>

	</table>