<?php
/**
 * The config file of kractplan module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
$config->kractplan = new stdclass();
$config->kractplan->require = new stdclass();
$config->kractplan->require->create = 'name, begin, end';
$config->kractplan->require->edit   = 'name, begin, end';

$config->kractplan->editor = new stdclass();
$config->kractplan->editor->create = array('id' => 'desc', 'tools' => 'simple');
$config->kractplan->editor->edit   = array('id' => 'desc', 'tools' => 'simple');
$config->kractplan->editor->finish = array('id' => 'comment', 'tools' => 'simple');

global $lang, $app;
$app->loadLang('kractplan', 'proj');
$config->kractplan->search['module'] = 'kractplan';

$config->kractplan->search['fields']['t1.name']        = $lang->kractplan->name;
$config->kractplan->search['fields']['t2.account']     = $lang->kractplan->member;
$config->kractplan->search['fields']['t1.begin']       = $lang->kractplan->begin;
$config->kractplan->search['fields']['t1.end']         = $lang->kractplan->end;
$config->kractplan->search['fields']['t1.status']      = $lang->kractplan->status;
$config->kractplan->search['fields']['t1.id']          = $lang->kractplan->id;
$config->kractplan->search['fields']['t1.createdBy']   = $lang->kractplan->createdBy;
$config->kractplan->search['fields']['t1.createdDate'] = $lang->kractplan->createdDate;

$config->kractplan->search['params']['t1.name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->kractplan->search['params']['t2.account']     = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->kractplan->search['params']['t1.begin']       = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->kractplan->search['params']['t1.end']         = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->kractplan->search['params']['t1.status']      = array('operator' => '=',  'control' => 'select', 'values' => $lang->kractplan->statusList);
$config->kractplan->search['params']['t1.id']          = array('operator' => '=',  'control' => 'input',  'values' => '');
$config->kractplan->search['params']['t1.createdBy']   = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->kractplan->search['params']['t1.createdDate'] = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
