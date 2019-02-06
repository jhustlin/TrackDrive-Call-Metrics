
<?php
$this->menu->index();
?>


<ul>
	<?php foreach ($list['record_arr'] as $id => $item) { ?>
		<li><a href="<?php echo $item->item_key; ?>"><?php echo $item->title; ?></a></li>
	<?php } ?>
</ul>