<?php

$_l = false;
if(isset($_COOKIE['member_id'])) { 
    $_member = db("SELECT * FROM `members` WHERE `uniq` LIKE '".$_COOKIE['member_id']."' LIMIT 1;");
    if(isset($_member)&&is_array($_member)) $_l = true;
}

