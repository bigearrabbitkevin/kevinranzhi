<?php

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
