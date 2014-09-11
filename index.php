<?php
include_once('include/utf8.php');
echo '<form action="index.php" method="post">File id: <input type="text" name="id" /><input type="submit"/></form>';
if ($_POST["id"] > 0)
{
$URL ='http://rghost.ru/'.$_POST["id"];
echo " <br> <b>result:</b> ";
include_once('include/header.php'); 
include_once('get.php'); 

}
?>
