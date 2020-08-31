<?php

include 'auth.php';

db("UPDATE `registry` SET `views` = `views` + 1 WHERE `uri` LIKE '$uri';");

include $_c['path'].'/app/'.$_c['app'].'/functions.php';
include $_c['path'].'/app/'.$_c['app'].'/gui.php';
if(!isset($_POST)) include $_c['path'].'/app/'.$_c['app'].'/header.php';
include $_c['path'].'/app/'.$_c['app'].'/'.$file;
if(isset($_POST)) include $_c['path'].'/app/'.$_c['app'].'/footer.php';

if($_c['dev']) {
?>
<!--
<?php
echo '$service => '.$service;
echo '
$url => '.$url;
echo '
$file => '.$file;
echo '
$registry => '; print_r($registry);
}?>
-->