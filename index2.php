<?php
include_once('include/utf8.php');
echo '<form action="gen.php" method="post">';
echo 'Start: <input type="text" name="id_st" />';
echo 'Finish: <input type="text" name="id_sp" />';
echo '<input type="submit"/></form>';

$id  = $_POST["id_st"];
$id2 = $_POST["id_sp"];
if (($id2 - $id) < 5)
{
echo 'add more ids';
exit;
}
if (($id2 - $id) > 1000000)
{
echo 'too many ids';
exit;
}

if (($id2 - $id) > 0)

{
while ($id < $id2) {
    $id++;
    echo '<br>';
       $XMLText = file_get_contents("http://rghost.net/download/metafile/".$id."/1");
preg_match('/<title>(.*)<\\/title>/s', $XMLText, $res);
preg_match_all("<a href=\x22(.+?)\x22>", $XMLText, $matches); // save all links \x22 = "

$matches = str_replace("a href=", "", $matches); 
$matches = str_replace("\"", "", $matches);
// output all matches
 $download_link = $matches[1][4];
 $name = $res[1];  
 echo $name;
 echo '<br>';
 $_POST['text'] = file_get_contents($download_link);
 echo '<a href="'.$download_link.'">download</a>';
 
 }

}
?>
