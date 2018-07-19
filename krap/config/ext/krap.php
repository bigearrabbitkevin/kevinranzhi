<?php
$config->krap = new stdclass();
$config->krap->version = '1.0.0';
$config->krap->key     = '88888888888888888888888888888888'; //Set a 32 byte string as your key.
$prefix = 'kr_';

define('TABLE_KR_GOODS',        '`' . $prefix . 'goods`');
define('TABLE_KR_ACTPLAN',        '`' . $prefix . 'act_plan`');

$config->objectTables['krgoods']        = TABLE_KR_GOODS;
$config->objectTables['kractplan']        = TABLE_KR_ACTPLAN;

$config->rights->member['krgoods']['index']     = 'index';
$config->rights->member['krgoods']['view']      = 'view';
$config->rights->member['krgoods']['create']    = 'create';
$config->rights->member['krgoods']['edit']      = 'edit';
$config->rights->member['krgoods']['delete']    = 'delete';

$config->rights->member['kractplan']['index']     = 'index';
$config->rights->member['kractplan']['view']      = 'view';
$config->rights->member['kractplan']['create']    = 'create';
$config->rights->member['kractplan']['edit']      = 'edit';
$config->rights->member['kractplan']['delete']    = 'delete';