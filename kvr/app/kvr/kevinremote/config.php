<?php

$config->kevinremote			 = new stdclass();
$config->kevinremote->create	 = new stdclass();
$config->kevinremote->edit	 = new stdclass();
$config->kevinremote->remoteBatchEditFields	 = 'type1,type2,realname,macname,ip,mactype,macaddress,order';
/* Include the custom config file. my.php*/
$configRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$myConfig   = $configRoot . 'my.php';
if(file_exists($myConfig)) include $myConfig;
