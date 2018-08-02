<?php

global $lang, $app;
$app->loadLang('kevinuser');
$app->loadLang('action');

$config->kevindept			 = new stdclass();
$config->kevindept->MaxDeptID = 9999;
$config->kevindept->wideSize      = 1400;
//导入状态提示
$config->kevindept->importStatus = array(0 => 'NG', 1 => 'UPDATE', 2 => 'INSERT', 3 => 'SAME');
