<?php

global $lang, $app;
$app->loadLang('user');
$app->loadLang('action');

$config->kevinuser			 = new stdclass();
$config->kevinuser->create	 = new stdclass();
$config->kevinuser->edit	 = new stdclass();

$config->kevinuser->create->requiredFields	 = 'role,worktype,classify1,classify2,classify3,payrate,hourFee,conversionFee,start,end,monthFee';
$config->kevinuser->edit->requiredFields	 = 'role,worktype,classify1,classify2,classify3,payrate,hourFee,conversionFee,start,end,monthFee';

$config->kevinuser->classBatchEditFields	 = 'role,worktype,classify1,classify2,classify3,classify4,payrate,hourFee,start,end,jobRequirements,remarks';
$config->kevinuser->recordBatchEditFields	 = 'account,realname,class,worktype,start,end';
$config->kevinuser->deptBatchEditFields		 = 'name,parent,path,group,grade,order,manager,email,code';

$config->kevinuser->customBatchCreateFields = 'dept,deptdispatch,email,gender,commiter,join,skype,qq,yahoo,gtalk,wangwang,mobile,phone,address,zipcode';

$config->kevinuser->custom = new stdclass();
$config->kevinuser->custom->batchCreateFields = 'dept,deptdispatch,email,gender';
$config->kevinuser->custom->batchEditFields   = 'dept,deptdispatch,join,email,commiter';

$config->kevinuser->lockMinutes	 = 10;
$config->kevinuser->batchEditNum	 = 5;
$config->kevinuser->batchCreate	 = 10;
$config->kevinuser->endDate	 = '2030-01-01';


$config->kevinuser->browse = new stdClass();
$config->kevinuser->browse->search['module']             = 'user';
$config->kevinuser->browse->search['fields']['realname'] = $lang->user->realname;
$config->kevinuser->browse->search['fields']['email']    = $lang->user->email;
$config->kevinuser->browse->search['fields']['dept']     = $lang->user->dept;
$config->kevinuser->browse->search['fields']['account']  = $lang->user->account;
$config->kevinuser->browse->search['fields']['role']     = $lang->user->role;
$config->kevinuser->browse->search['fields']['phone']    = $lang->user->phone;
$config->kevinuser->browse->search['fields']['join']     = $lang->user->join;
$config->kevinuser->browse->search['fields']['id']       = $lang->user->id;
$config->kevinuser->browse->search['fields']['gender']   = $lang->user->gender;
$config->kevinuser->browse->search['fields']['qq']       = $lang->user->qq;
$config->kevinuser->browse->search['fields']['yahoo']    = $lang->user->yahoo;
$config->kevinuser->browse->search['fields']['gtalk']    = $lang->user->gtalk;
$config->kevinuser->browse->search['fields']['wangwang'] = $lang->user->wangwang;
$config->kevinuser->browse->search['fields']['address']  = $lang->user->address;
$config->kevinuser->browse->search['fields']['zipcode']  = $lang->user->zipcode;

$config->kevinuser->browse->search['params']['realname'] = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['email']    = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['dept']     = array('operator' => 'belong',   'control' => 'select', 'values' => '');
$config->kevinuser->browse->search['params']['account']  = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['role']     = array('operator' => '=',        'control' => 'select', 'values' => $lang->user->roleList);
$config->kevinuser->browse->search['params']['phone']    = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['join']     = array('operator' => '=',        'control' => 'input',  'values' => '', 'class' => 'date');
$config->kevinuser->browse->search['params']['id']       = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['gender']   = array('operator' => '=',        'control' => 'select', 'values' => $lang->user->genderList);
$config->kevinuser->browse->search['params']['qq']       = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['yahoo']    = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['gtalk']    = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['wangwang'] = array('operator' => '=',        'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['address']  = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->kevinuser->browse->search['params']['zipcode']  = array('operator' => '=',        'control' => 'input',  'values' => '');