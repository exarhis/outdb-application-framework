<?php

include '_c.php';
include 'core/db.php';
include 'core/registry.php';

if(empty($_GET['uri'])) $uri='index'; else $uri = $_GET['uri'];
$registry = registry(escape_string($uri));

include $_c['path'].'/app/'.$_c['app'].'/app.php';
?>
