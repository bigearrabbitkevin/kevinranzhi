<?php
//this is lang for chinese. 临时放到这里
if(!isset($lang->app)) $lang->app = new stdclass();
$lang->app->name ='活动计划'; 

if(!isset($lang->menu)) $lang->menu = new stdclass();
if(!isset($lang->menu->krap)) $lang->menu->krap = new stdclass();
//common的链接，必须有4段，3个|分开，可以为空
$lang->menu->krap->kractplan  = '计划|kractplan|index|';
$lang->menu->krap->krgoods  = '物品|krgoods|index|';

if(!isset($lang->krgoods)) $lang->krgoods = new stdclass();
if(!isset($lang->krgoods->menu)) $lang->krgoods->menu = new stdclass();
$lang->krgoods->menu->index       = '列表|krgoods|index|';

if(!isset($lang->kractplan)) $lang->kractplan = new stdclass();
if(!isset($lang->kractplan->menu)) $lang->kractplan->menu = new stdclass();
$lang->kractplan->menu->index       = '列表|kractplan|index|';
$lang->kractplan->menu->goods		= '物品清单|kractplan|goods|';

//following is memuorder
$lang->krap->menuOrder[5] = 'kractplan';
$lang->krap->menuOrder[10] = 'krgoods';

$lang->kractplan->menuOrder[5]  = 'index';
$lang->kractplan->menuOrder[20] = 'goods';

$lang->krgoods->menuOrder[5]  = 'index';
$lang->krgoods->menuOrder[20] = 'goods';