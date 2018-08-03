<?php
//this is lang for chinese. 临时放到这里
if(!isset($lang->app)) $lang->app = new stdclass();
$lang->app->name ='KV'; 

if(!isset($lang->menu)) $lang->menu = new stdclass();
if(!isset($lang->menu->kvr)) $lang->menu->kvr = new stdclass();
//common的链接，必须有4段，3个|分开，可以为空
$lang->menu->kvr->kevinuser	= '用户|kevinuser|browse|';
$lang->menu->kvr->kevindept	= '部门|kevindept|deptlist|';

//following is memuorder
$lang->kvr->menuOrder[15] = 'kevinuser';
$lang->kvr->menuOrder[20] = 'kevindept';

//------------ sub plugin menus
//kevincom
if(!isset($lang->kevincom)) $lang->kevincom = new stdclass();
if(!isset($lang->kevincom->menu)) $lang->kevincom->menu = new stdclass();
$lang->kevincom->menu->index       = '首页|kevincom|index|';

//kevinuser menu list
//kevinuser
if(!isset($lang->kevinuser)) $lang->kevinuser = new stdclass();
if(!isset($lang->kevinuser->menu)) $lang->kevinuser->menu = new stdclass();
//$lang->kevinuser->menu->index		 = '首页|kevinuser|index|';
$lang->kevinuser->menu->kevinbrowse	 = '用户|kevinuser|browse|';
$lang->kevinuser->menu->kevindomainaccount	 = '域用户管理|kevinuser|domainaccount|';

$lang->kevinuser->menuOrder[0]	 = 'index';
$lang->kevinuser->menuOrder[10]	 = 'kevinbrowse';

//kevinuser
if(!isset($lang->kevindept)) $lang->kevindept = new stdclass();
if(!isset($lang->kevindept->menu)) $lang->kevindept->menu = new stdclass();

$lang->kevindept->menu->index		 = '部门列表|kevindept|deptlist|';
$lang->kevindept->menu->kevinbrowse	 = '部门结构|kevindept|browse|';

$lang->kevindept->menuOrder[0]	 = 'index';
$lang->kevindept->menuOrder[10]	 = 'kevinbrowse';