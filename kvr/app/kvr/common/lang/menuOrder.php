<?php
//this is lang for chinese. 临时放到这里
if(!isset($lang->app)) $lang->app = new stdclass();
$lang->app->name ='KV'; 

if(!isset($lang->menu)) $lang->menu = new stdclass();
if(!isset($lang->menu->kvr)) $lang->menu->kvr = new stdclass();
//common的链接，必须有4段，3个|分开，可以为空
$lang->menu->kvr->kevincom  = '介绍|kevincom|index|';
//$lang->menu->kvr->kevinremote	= '远程唤醒|kevinremote|index|';
$lang->menu->kvr->kevinuser	= '用户|kevinuser|index|';
$lang->menu->kvr->kevindept	= '部门|kevindept|browse|';

//following is memuorder
$lang->kvr->menuOrder[5] = 'kevincom';
//$lang->kvr->menuOrder[50] = 'kevinremote';
$lang->kvr->menuOrder[15] = 'kevinuser';
$lang->kvr->menuOrder[20] = 'kevindept';

//------------ sub plugin menus
//kevincom
if(!isset($lang->kevincom)) $lang->kevincom = new stdclass();
if(!isset($lang->kevincom->menu)) $lang->kevincom->menu = new stdclass();
$lang->kevincom->menu->index       = '首页|kevincom|index|';

//kevinremote
if(!isset($lang->kevinremote)) $lang->kevinremote = new stdclass();
if(!isset($lang->kevinremote->menu)) $lang->kevinremote->menu = new stdclass();
$lang->kevinremote->menu->index		 = '首页|kevinremote|index|';


//kevinuser menu list
//kevinuser
if(!isset($lang->kevinuser)) $lang->kevinuser = new stdclass();
if(!isset($lang->kevinuser->menu)) $lang->kevinuser->menu = new stdclass();
$lang->kevinuser->menu->index		 = '首页|kevinuser|index|';
$lang->kevinuser->menu->kevinbrowse	 = '用户.K|kevinuser|browse|';
$lang->kevinuser->menu->deptlist	 = '部门列表|kevinuser|deptlist|';
//$lang->kevinuser->menu->deptset	 = '部门指定|kevinuser|deptset|';
$lang->kevinuser->menu->batchAddUser = array('link' => '<i class="icon-plus-sign"></i>&nbsp;批量添加|kevinuser|batchCreate|dept=%s', 'subModule' => 'kevinuser', 'float' => 'right');
$lang->kevinuser->menu->addUser      = array('link' => '<i class="icon-plus"></i>&nbsp;添加用户|kevinuser|create|dept=%s', 'subModule' => 'kevinuser', 'float' => 'right');

$lang->kevinuser->menuOrder[0]	 = 'index';
$lang->kevinuser->menuOrder[10]	 = 'kevinbrowse';
$lang->kevinuser->menuOrder[20]	 = 'classlist';
$lang->kevinuser->menuOrder[30]	 = 'recordlist';
$lang->kevinuser->menuOrder[40]	 = 'deptlist';
$lang->kevinuser->menuOrder[50]	 = 'deptset';

$lang->kevinuser->menuOrder[70]	 = 'defaultpwd';
$lang->kevinuser->menuOrder[75]	 = 'managepriv';
$lang->kevinuser->menuOrder[80]	 = 'domainaccount';

//kevinuser
if(!isset($lang->kevindept)) $lang->kevindept = new stdclass();
if(!isset($lang->kevindept->menu)) $lang->kevindept->menu = new stdclass();

$lang->kevindept->menu->index		 = '首页|kevindept|index|';
$lang->kevindept->menu->kevinbrowse	 = 'browse|kevindept|browse|';
$lang->kevindept->menu->deptlist	 = 'deptlist|kevindept|deptlist|';