<?php
//this is lang for chinese. 临时放到这里
if(!isset($lang->app)) $lang->app = new stdclass();
$lang->app->name ='KR';

if(!isset($lang->menu)) $lang->menu = new stdclass();
if(!isset($lang->menu->krdef)) $lang->menu->krdef = new stdclass();

//common的链接，必须有4段，3个|分开，可以为空
$lang->menu->kvr->kevinremote	= '远程唤醒|kevinremote|index|';

//following is memuorder
$lang->kvr->menuOrder[50] = 'kevinremote';

//------------ sub plugin menus
//kevinremote
if(!isset($lang->kevinremote)) $lang->kevinremote = new stdclass();
if(!isset($lang->kevinremote->menu)) $lang->kevinremote->menu = new stdclass();
$lang->kevinremote->menu->index		 = '首页|kevinremote|index|';
