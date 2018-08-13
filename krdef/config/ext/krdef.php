<?php
$config->krdef = new stdclass();
$config->krdef->version = '1.0.0';
$config->krdef->key     = '88888888888888888888888888888888'; //Set a 32 byte string as your key.

define('TABLE_KEVIN_REMOTE', '`' . 'kv_remote`');
define('TABLE_KEVIN_BOOK', '`' . 'kv_book`');

$config->objectTables['kevinremote'] = TABLE_KEVIN_REMOTE;
$config->objectTables['book'] = TABLE_KEVIN_BOOK;

$config->krbook = new stdclass();
$config->krbook->version = '1.0.0';
$config->krbook->key     = '88888888888888888888888888888888'; //Set a 32 byte string as your key.


$config->rights->member['book']['index']     = 'index';
$config->rights->member['book']['view']      = 'view';
$config->rights->member['book']['create']    = 'create';
$config->rights->member['book']['edit']      = 'edit';
$config->rights->member['book']['delete']    = 'delete';
$config->rights->member['book']['parse']    = 'parse';
