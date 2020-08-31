<?php
/*
Regisrty is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Regisrty is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Regisrty.  If not, see <https://www.gnu.org/licenses/>.
*/


function db($sql, $debug = false)
{
global $_c;

$db = new mysqli($_c['host'], $_c['user'], $_c['pass'], $_c['db']);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
else {
  mysqli_set_charset($db, 'UTF8');
    if ($debug == true)
        echo '<BR />debug it : ' . $sql . '<BR />';
    if (strpos(strtoupper($sql), 'SELECT') === false)
       { $result = $db->query($sql);}
    else {
if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']<br />debug it : '.$sql);
}
        $line = false;
        if (strpos(strtoupper($sql), 'LIMIT 1;') !== false) $line = true;
        if (!$line)
           while($row = $result->fetch_assoc()){
			   if(count($row)==1) foreach($row AS $temp_val) $data[] = $temp_val;
			   else $data[] = $row;
            } else
                { $row = $result->fetch_assoc();
                   if(!empty($row)) if(count($row)==1) { foreach($row AS $data); }
                    else $data = $row;}
}
   $db->close();

    if (!empty($data)) return $data;
    else return false;
}
}
function db_insert($table, $fields, $memberalues)
{

global $_c;
    if (is_array($fields)) {
        $count = count($fields);
		$fields_query = ''; $memberalues_query = '';
        for ($i = 0; $i < $count; $i++) {
            $field = '`' . $fields[$i] . '`';
            $memberalue = escape_string($memberalues[$i]);
            $fields_query .= $field;
            if (is_numeric($field))
                $memberalues_query .= $memberalue;
            else
                $memberalues_query .= "'" . $memberalue . "'";
            if ($i != $count - 1) {
                $fields_query .= ',';
                $memberalues_query .= ',';
            }
        }
    } else {
        $field_query = $field;
        $memberalue_query = escape_string($memberalues);
    }
    $sql = "INSERT into `$table` ($fields_query) VALUES ($memberalues_query)";
    db($sql);
}
function db_update($table, $fields, $memberalues, $where = '')
{
global $_c;
$part_query = '';
    if (is_array($fields)) {
        $count = count($fields) - 1;
        for ($i = 0; $i <= $count; $i++) {
            $field        = $fields[$i];
            $memberalue        = escape_string($memberalues[$i]);
            $fields_query = $field;
            $part_query .= '`' . $field . '`';
            if (is_numeric($field))
                $part_query .= ' = ' . $memberalue;
            else
                $part_query .= " ='" . $memberalue . "'";
            if ($count != $i)
                $part_query .= ' ,';
        }
    } else {
        $part_query = '`' . $fields . '`';
        if (is_numeric($memberalues))
            $part_query .= ' = ' . $memberalues;
        else
            $part_query .= " ='" . escape_string($memberalues) . "'";
    }
    $sql = "UPDATE `$table` SET $part_query $where";
    db($sql);
}
function db_delete($table, $field, $is)
{
global $_c;
    $sql = "DELETE FROM $table where $field = '" . escape_string($is) . "'";
    db($sql);
}
function db_select($table, $fields = "*", $arg1 = '', $arg2 = '', $arg3 = '')
{
global $_c;
    if (!is_array($fields))
        if ($fields == '*')
            $fields_query = $fields;
        else
            $fields_query = '`' . $fields . '`';
    else {
        $count = count($fields) - 1;
        for ($i = 0; $i <= $count; $i++) {
            $fields_query .= '`' . $fields[$i] . '`';
            if ($count != $i)
                $fields_query .= ',';
        }
    }
    $sql = "SELECT $fields_query FROM `$table` $arg1 $arg2 $arg3";
    return db($sql);
}
function db_select_one($table, $fields = "*", $arg1 = '', $arg2 = '')
{
global $_c;
    if (!is_array($fields))
        if ($fields == '*')
            $fields_query = $fields;
        else
            $fields_query = '`' . $fields . '`';
    else {
        $count = count($fields) - 1;
        for ($i = 0; $i <= $count; $i++) {
            $fields_query .= '`' . $fields[$i] . '`';
            if ($count != $i)
                $fields_query .= ',';
        }
    }
    $sql = "SELECT $fields_query FROM `$table` $arg1 $arg2 LIMIT 1 ";
    return db($sql);
}


function db_rows_num($sql)
{
    global $_c;
   $con = new mysqli($_c['host'], $_c['user'], $_c['pass'], $_c['db']);
  mysqli_set_charset($con, 'UTF8');
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if ($result=mysqli_query($con,$sql))
  {

  return mysqli_num_rows($result);

  }

}

function escape_string($memberalue)
{
global $_c;
$mydb = new mysqli($_c['host'], $_c['user'], $_c['pass'], $_c['db']);

if($mydb->connect_errno > 0){
    die('Unable to connect to database [' . $mydb->connect_error . ']');
}
  mysqli_set_charset($mydb, 'UTF8');
return $mydb->real_escape_string($memberalue);
}

function fields($sql)
{

global $_c;

$db = new mysqli($_c['host'], $_c['user'], $_c['pass'], $_c['db']);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
  mysqli_set_charset($db, 'UTF8');

if ($result=mysqli_query($db,$sql))
  {
  $finfo=mysqli_fetch_fields($result);

foreach ($finfo as $memberal) $fields[] =  $memberal->name;


return $fields;
}
}

function datacheck($data)
{
global $_c;
if(is_array($data)) {
$db = new mysqli($_c['host'], $_c['user'], $_c['pass'], $_c['db']);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
  mysqli_set_charset($db, 'UTF8');
return array_map(array($db, 'real_escape_string'), $data);
}
}

function insert($table,$memberalues)
{
$query = "SELECT * FROM `$table` LIMIT 1;";
$fields = fields($query);
foreach($fields AS $field)
{
if(isset($memberalues[$field])) {$f[] = $field; $member[] = $memberalues[$field];}
}
db_insert($table,$f,$member);
}
function update($table,$memberalues,$where)
{
$query = "SELECT * FROM `$table` LIMIT 1;";
$fields = fields($query);
foreach($fields AS $field)
{
if(isset($memberalues[$field])) {$f[] = $field; $member[] = $memberalues[$field];}
}
db_update($table,$f,$member,$where);

}
?>
