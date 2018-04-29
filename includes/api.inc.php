<?php
//error_reporting(E_ALL); ini_set("display_errors", 1);
error_reporting(0);
define('IN_CRONLITE', true);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
date_default_timezone_set("PRC");
$date = date("Y-m-j H:i:s");
session_start();

if(is_file(ROOT.'360safe/360webscan.php')){//360网站卫士
    require_once(ROOT.'360safe/360webscan.php');
}

require (ROOT.'config.php');

if(!isset($port))$port='3306';
//连接数据库
require(SYSTEM_ROOT."db.class.php");
$DB=new DB($host,$user,$pwd,$dbname,$port);

require(SYSTEM_ROOT.'function.php');
require(SYSTEM_ROOT.'member.php');
require(SYSTEM_ROOT.'txprotect.php');

?>