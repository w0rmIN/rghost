<?php

function get_http_response_code($URL) {
 $headers = get_headers($URL);
 return substr($headers[0], 9, 3);
 }
 switch (get_http_response_code($URL)) {
 case 403:

 $XMLText = file_get_contents("http://rghost.net/download/metafile/".$_POST["id"]."/1");
preg_match('/<title>(.*)<\\/title>/s', $XMLText, $res);
preg_match_all("<a href=\x22(.+?)\x22>", $XMLText, $matches); // save all links \x22 = "

// delete redundant parts
$matches = str_replace("a href=", "", $matches); // remove a href=
$matches = str_replace("\"", "", $matches); // remove "

// output all matches
 $download_link = $matches[1][4];
 $name = $res[1];  
 echo $name;
 echo '<br>';
 $_POST['text'] = file_get_contents($download_link);
 echo '<a href="'.$download_link.'">download</a>';
 echo ' | ';
 break;
 case 404: echo " 404 not found :( ";
 break;
 case 200;
 echo " U can download it by your self ;) ";
 }
 
?>
