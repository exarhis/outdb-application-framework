<?php

function registry($uri)
{

global $_c,$service,$file;

$parts = explode('/',$uri);

$part = escape_string($parts[0]);

$registry_temp['db'] = db("SELECT * FROM `registry` WHERE `uri` LIKE '".$part."' AND `status` IS NULL LIMIT 1;");

if(!empty($registry_temp['db'])) 
{
if(isset($registry_temp['db']['uri']))
{
$registry['users'] = db("SELECT * FROM `users` WHERE `uri` LIKE '".escape_string($registry_temp['db']['user'])."' LIMIT 1;");
$service = $registry_temp['db']['service'];
$registry_temp[$service] = db("SELECT * FROM `services` WHERE `service` LIKE '$service' LIMIT 1;");
$table = $registry_temp[$service]['service_table'];
$file = $registry_temp[$service]['file'];
$father = $registry_temp[$service]['service_father'];
if(!empty($table)) 
{
$registry[$table] = db("SELECT * FROM `".$table."` WHERE `uri` LIKE '$part' LIMIT 1;");

}

$tservice = $service;

while(!empty($father))
{

$tservice = $registry_temp[$tservice]['service_father'];
$registry_temp[$tservice] = db("SELECT * FROM `services` WHERE `service` LIKE '$tservice' LIMIT 1;");

$table = $registry_temp[$tservice]['service_table'];
$father = $registry_temp[$tservice]['service_father'];
$turi = escape_string($registry_temp['db'][$tservice]);
if(!empty($table)) 
{
$registry[$table] = db("SELECT * FROM `".$table."` WHERE `uri` LIKE '$turi' LIMIT 1;");

}
}

}
}
else 
{
$registry = false;
$file = db("SELECT `file` FROM `services` WHERE `service` LIKE '404' LIMIT 1;");
$service = '404';

}
return $registry;
}


function uri($length = 2,$tries = 20){
	global $config;
	
$try = 0;

	$unique_key = iRandomString($length);
	while(db_rows_num("SELECT `uri` FROM `registry` WHERE `uri` LIKE '$unique_key'")==1) {
        if($try==($tries-1)) $length++;        
        $unique_key = iRandomString($length); 
        $try++;}
   	    return $unique_key;
	}

function iRandomString($length) {  
$str = "";  $characters = array_merge( range('a','z'), range('0','9'));  
$max = count($characters) - 1;  
for ($i = 0; $i < $length; $i++) {   
$rand = mt_rand(0, $max);   $str .= $characters[$rand];
  }  
return $str; }


function ip()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}