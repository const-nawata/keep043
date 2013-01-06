<?php
echo "RRRRRRRRRRRRRRRRRRRRR<br>";

$host	= 'localhost';
$db	= 'keep';
$user	= 'root';
$pass	= '0043';

$con = mysql_connect("localhost","root","0043");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }



