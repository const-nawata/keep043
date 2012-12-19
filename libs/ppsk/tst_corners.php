<?php
global $VclPath; $VclPath = './';
require_once("vcl.php");

class frame1 extends fraimView{
	public function __construct(){

		parent::__construct(get_class($this));
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Corners testing</title>

<style type="text/css">
body,body * {
	margin: 0;
	padding: 0;
}
</style>

</head>

<body>
<center><b>Corners testing</b></center>
<hr
	style="border: 0; border-top: 1px solid #FF0000; margin: 0; padding: 0; width: 10px;" />
<?php
$fraim1_obj = new frame1();


?>
</body>

</html>
