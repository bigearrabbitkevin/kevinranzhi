<?php

/* krdef app */
$lang->appModule->krdef	 = array();
$lang->appModule->krdef[] = 'kevinremote';
$lang->moduleOrder[]	 = 'kevinremote';

//kevinremote
$lang->resource->kevinremote		 = new stdclass();
$lang->resource->kevinremote->index			 = 'index';
$lang->resource->kevinremote->create		 = 'create';
$lang->resource->kevinremote->edit			 = 'edit';
$lang->resource->kevinremote->delete		 = 'delete';
$lang->resource->kevinremote->batchEdit		 = 'batchEdit';
$lang->resource->kevinremote->batchDelete	 = 'batchDelete';
$lang->resource->kevinremote->wakeup		 = 'wakeup';

$lang->kevinremote->methodOrder[5] = 'index';
$lang->kevinremote->methodOrder[10] = 'create';
$lang->kevinremote->methodOrder[15] = 'edit';
$lang->kevinremote->methodOrder[20] = 'delete';
$lang->kevinremote->methodOrder[25] = 'batchEdit';
$lang->kevinremote->methodOrder[30] = 'batchDelete';
$lang->kevinremote->methodOrder[35] = 'wakeup';

/* krbook app */
$lang->appModule->krbook	 = array();
$lang->appModule->krbook[] = 'book';

$lang->moduleOrder[]	 = 'book';

/* book module. */
$lang->resource->book = new stdclass();
$lang->resource->book->index     = 'index';
$lang->resource->book->read     = 'read';
$lang->resource->book->browse     = 'browse';
$lang->resource->book->project     = 'project';
$lang->resource->book->search     = 'search';
$lang->resource->book->setting     = 'setting';
$lang->resource->book->admin     = 'admin';
$lang->resource->book->catalog   = 'catalog';
$lang->resource->book->create    = 'create';
$lang->resource->book->edit      = 'edit';
$lang->resource->book->sort      = 'sort';
$lang->resource->book->delete    = 'delete';

