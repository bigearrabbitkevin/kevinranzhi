<?php
//this is lang for chinese. 临时放到这里
if(!isset($lang->app)) $lang->app = new stdclass();
$lang->app->name ='手册';

if(!isset($lang->menu)) $lang->menu = new stdclass();
if(!isset($lang->menu->krbook)) $lang->menu->krbook = new stdclass();
//common的链接，必须有4段，3个|分开，可以为空
$lang->menu->krbook->book  =  '帮助手册|book|read|';
//following is memuorder
$lang->krbook->menuOrder[5] = 'book';

if(!isset($lang->book)) $lang->book = new stdclass();
if(!isset($lang->book->menu)) $lang->book->menu = new stdclass();
$lang->book->menu->index       = '手册|book|index|';
$lang->book->menu->read       =  array('link' => '查看|book|read|', 'alias' => 'read,browse');
$lang->book->menu->admin       =  array('link' => '后台|book|admin|', 'alias' => 'admin,index,create,view,edit,batchEdit');
$lang->book->menu->setting       = '设置|book|setting|';

$lang->book->menuOrder[5]  = 'index';
$lang->book->menuOrder[10]  = 'read';
$lang->book->menuOrder[15]  = 'admin';
$lang->book->menuOrder[20]  = 'setting';
