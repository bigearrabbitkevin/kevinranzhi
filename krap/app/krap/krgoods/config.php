<?php
/**
 * The config file of krgoods module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
$config->krgoods = new stdclass();
$config->krgoods->require = new stdclass();
$config->krgoods->require->create = 'name, begin, end';
$config->krgoods->require->edit   = 'name, begin, end';

$config->krgoods->editor = new stdclass();
$config->krgoods->editor->create = array('id' => 'desc', 'tools' => 'simple');
$config->krgoods->editor->edit   = array('id' => 'desc', 'tools' => 'simple');
$config->krgoods->editor->finish = array('id' => 'comment', 'tools' => 'simple');

global $lang, $app;
$app->loadLang('krgoods', 'proj');
$config->krgoods->search['module'] = 'krgoods';

$config->krgoods->search['fields']['t1.name']        = $lang->krgoods->name;
$config->krgoods->search['fields']['t2.account']     = $lang->krgoods->member;
$config->krgoods->search['fields']['t1.begin']       = $lang->krgoods->begin;
$config->krgoods->search['fields']['t1.end']         = $lang->krgoods->end;
$config->krgoods->search['fields']['t1.status']      = $lang->krgoods->status;
$config->krgoods->search['fields']['t1.id']          = $lang->krgoods->id;
$config->krgoods->search['fields']['t1.createdBy']   = $lang->krgoods->createdBy;
$config->krgoods->search['fields']['t1.createdDate'] = $lang->krgoods->createdDate;

$config->krgoods->search['params']['t1.name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->krgoods->search['params']['t2.account']     = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->krgoods->search['params']['t1.begin']       = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->krgoods->search['params']['t1.end']         = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->krgoods->search['params']['t1.status']      = array('operator' => '=',  'control' => 'select', 'values' => $lang->krgoods->statusList);
$config->krgoods->search['params']['t1.id']          = array('operator' => '=',  'control' => 'input',  'values' => '');
$config->krgoods->search['params']['t1.createdBy']   = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->krgoods->search['params']['t1.createdDate'] = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
