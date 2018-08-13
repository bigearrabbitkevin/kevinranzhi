<?php
//this is lang for chinese. 临时放到这里
if(!isset($lang->app)) $lang->app = new stdclass();
$lang->app->name ='手册';

if(!isset($lang->menu)) $lang->menu = new stdclass();
if(!isset($lang->menu->krbook)) $lang->menu->krbook = new stdclass();
//common的链接，必须有4段，3个|分开，可以为空
$lang->menu->krbook->book  =  '帮助手册|book|read|';

if(!isset($lang->book)) $lang->book = new stdclass();
if(!isset($lang->book->menu)) $lang->book->menu = new stdclass();
$lang->book->menu->read       =  array('link' => '查看|book|read|', 'alias' => 'read');
$lang->book->menu->admin       =  array('link' => '编辑|book|admin|', 'alias' => 'admin,index,create,view,edit,batchEdit');
//following is memuorder
$lang->krbook->menuOrder[5] = 'book';

$lang->book->menuOrder[5]  = 'admin';
