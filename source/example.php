
<?php 
// require the trackdrive php library
require('trackdrive.php'); 

$number = get_trackdrive_number('48e7931d0bcedc823a6028e6e9ce894a');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Number <?php echo $number ?></title>
</head>
<body>
	<div class="number"><?php echo $number ?></div>
</body>
</html>