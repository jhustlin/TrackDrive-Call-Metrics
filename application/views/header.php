<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<?php if ($this->gclass->base != '') { ?><base href="<?php echo $this->gclass->base; ?>" />
	<?php } ?>
<meta charset="UTF-8" />
	<title><?php echo $title; ?></title>
	<?php if ($this->gclass->favicon != '') echo $this->gclass->favicon."\n"; ?>
	<meta name="author" content="SIA Global Synergy Group" />
	<script type="text/javascript">
		var http_host = '<?php echo $_SERVER['HTTP_HOST']; ?>';
	</script><?php
	if ($keywords != '') echo "\n\t".'<meta name="keywords" content="'.$keywords.'" />';
	if ($description != '') echo "\n\t".'<meta name="description" content="'.$description.'" />';
	if (isset($css) && count($css) > 0) foreach ($css as $c) echo "\n\t".'<link href="'.$c.'" rel="stylesheet" type="text/css" />';
	if (isset($css) && count($css) > 0) foreach ($js as $j) echo "\n\t".'<script type="text/javascript" src="'.$j.'"></script>';
	?>
	<!--[if IE]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>