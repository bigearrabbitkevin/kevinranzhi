<?php

/* krap */
$lang->appModule->krap	 = array();
$lang->appModule->krap[] = 'kractplan';
$lang->appModule->krap[] = 'krgoods';
//$lang->appModule->krap[] = 'krcash';
//$lang->appModule->krap[] = 'krstore';

$lang->moduleOrder[910]	 = 'kractplan';
$lang->moduleOrder[920]	 = 'krgoods';
//$lang->moduleOrder[930]	 = 'krcash';
//$lang->moduleOrder[940]	 = 'krstore';

/* kractplan */
$lang->resource->kractplan			 = new stdclass();
$lang->resource->kractplan->index	 = 'index';
$lang->resource->kractplan->view	 = 'view';
$lang->resource->kractplan->goods	 = 'goods';
$lang->resource->kractplan->create	 = 'create';
$lang->resource->kractplan->edit	 = 'edit';
$lang->resource->kractplan->finish	 = 'finish';
$lang->resource->kractplan->delete	 = 'delete';

$lang->kractplan->methodOrder[5]	 = 'index';
$lang->kractplan->methodOrder[10]	 = 'view';
$lang->kractplan->methodOrder[15]	 = 'goods';
$lang->kractplan->methodOrder[20]	 = 'create';
$lang->kractplan->methodOrder[25]	 = 'edit';
$lang->kractplan->methodOrder[30]	 = 'finish';
$lang->kractplan->methodOrder[35]	 = 'delete';


/* krgoods */
$lang->resource->krgoods		 = new stdclass();
$lang->resource->krgoods->index	 = 'index';
$lang->resource->krgoods->view	 = 'view';
//$lang->resource->krgoods->goods	 = 'goods';
$lang->resource->krgoods->create = 'create';
$lang->resource->krgoods->edit	 = 'edit';
$lang->resource->krgoods->finish = 'finish';
$lang->resource->krgoods->delete = 'delete';

$lang->krgoods->methodOrder[5]	 = 'index';
$lang->krgoods->methodOrder[10]	 = 'view';
//$lang->krgoods->methodOrder[15]	 = 'goods';
$lang->krgoods->methodOrder[20]	 = 'create';
$lang->krgoods->methodOrder[25]	 = 'edit';
$lang->krgoods->methodOrder[30]	 = 'finish';
$lang->krgoods->methodOrder[35]	 = 'delete';
