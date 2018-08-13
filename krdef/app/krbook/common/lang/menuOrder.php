<?php
//this is lang for chinese. 临时放到这里
if(!isset($lang->app)) $lang->app = new stdclass();
$lang->app->name ='手册';

if(!isset($lang->menu)) $lang->menu = new stdclass();
if(!isset($lang->menu->krbook)) $lang->menu->krbook = new stdclass();
//common的链接，必须有4段，3个|分开，可以为空
$lang->menu->krbook->kevinbook  =  '手册|kevinbook|read|';

if(!isset($lang->kevinbook)) $lang->kevinbook = new stdclass();
if(!isset($lang->kevinbook->menu)) $lang->kevinbook->menu = new stdclass();
$lang->kevinbook->menu->admin       =  array('link' => '手册列表|kevinbook|admin|', 'alias' => 'admin,index,create,view,edit,batchEdit');
$lang->kevinbook->menu->read       =  array('link' => '查看手册|kevinbook|read|', 'alias' => 'read');
//following is memuorder
$lang->krbook->menuOrder[5] = 'kevinbook';

$lang->kevinbook->menuOrder[5]  = 'admin';
