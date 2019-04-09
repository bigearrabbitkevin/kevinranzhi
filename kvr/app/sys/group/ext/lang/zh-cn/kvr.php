<?php

/* kvr */
$lang->appModule->kvr   = array();
$lang->appModule->kvr[] = 'kevincom';
$lang->appModule->kvr[] = 'kevinuser';
$lang->appModule->kvr[] = 'kevindept';

$lang->moduleOrder[] = 'kevincom';
$lang->moduleOrder[] = 'kevinuser';
$lang->moduleOrder[] = 'kevindept';

/* kevincom */
$lang->resource->kevincom        = new stdclass();
$lang->resource->kevincom->index = 'index';

$lang->kevincom->methodOrder[5] = 'index';

//item
$lang->resource->kevinuser                 = new stdclass();
$lang->resource->kevinuser->index          = 'index';
$lang->resource->kevinuser->browse         = 'browse';
$lang->resource->kevinuser->batchCreate    = 'batchCreate';
$lang->resource->kevinuser->create         = 'create';
$lang->resource->kevinuser->deletedeptuser = 'deletedeptuser';
$lang->resource->kevinuser->domainaccount  = 'domainaccount';
$lang->resource->kevinuser->edit           = 'edit';
$lang->resource->kevinuser->manageContacts = 'manageContacts';
$lang->resource->kevinuser->userbatchedit  = 'userbatchedit';
$lang->resource->kevinuser->unlock         = 'unlock';
$lang->resource->kevinuser->userLock       = 'userLock';

$lang->kevinuser->methodOrder[9]  = 'index';
$lang->kevinuser->methodOrder[31] = 'browse';
$lang->kevinuser->methodOrder[32] = 'deptlist';
$lang->kevinuser->methodOrder[40] = 'create';

//kevindept
$lang->resource->kevindept                   = new stdclass();
$lang->resource->kevindept->index            = 'index';
$lang->resource->kevindept->browse           = 'browse';
$lang->resource->kevindept->deptlist         = 'deptlist';
$lang->resource->kevindept->deptcreate       = 'deptcreate';
$lang->resource->kevindept->deptdelete       = 'deptdelete';
$lang->resource->kevindept->deptedit         = 'deptedit';
$lang->resource->kevindept->deptview         = 'deptview';
$lang->resource->kevindept->manageChild      = 'manageChild';
$lang->resource->kevindept->synccategory     = 'synccategory';
$lang->resource->kevindept->updateOrder      = 'updateOrder';


$lang->kevindept->methodOrder[5]  = 'index';
$lang->kevindept->methodOrder[10] = 'browse';
$lang->kevindept->methodOrder[15] = 'deptlist';
$lang->kevindept->methodOrder[20] = 'synccategory';
