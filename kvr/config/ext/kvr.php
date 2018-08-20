<?php
$config->kvr = new stdclass();
$config->kvr->version = '1.0.0';
$config->kvr->key     = '88888888888888888888888888888888'; //Set a 32 byte string as your key.

if (!defined('TABLE_USERCONTACT'))  define('TABLE_USERCONTACT',   '`zt_usercontact`');
if(!defined('TABLE_DEPT')) define('TABLE_DEPT', '`zt_dept`');
