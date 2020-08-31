<?php
/*
registry is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    registry is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with registry.  If not, see <https://www.gnu.org/licenses/>.
*/
include '_c.php';
include 'core/db.php';
include 'core/registry.php';

if(empty($_GET['uri'])) $uri='index'; else $uri = $_GET['uri'];
$registry = registry(escape_string($uri));

include $_c['path'].'/app/'.$_c['app'].'/app.php';
?>
