<?php
/**
 * The kevindept module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     kevindept
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->kevindept->common      = '部门结构';
$lang->kevindept->manageChild = "下级部门";
$lang->kevindept->edit        = "编辑部门";
$lang->kevindept->parent      = "上级部门";
$lang->kevindept->manager     = "负责人";
$lang->kevindept->name        = "部门名称";
$lang->kevindept->browse      = "部门维护";
$lang->kevindept->manage      = "维护部门结构";
$lang->kevindept->updateOrder = "更新排序";
$lang->kevindept->add         = "添加部门";
$lang->kevindept->dragAndSort = "拖动排序";
$lang->kevindept->sync        = "同步";

$lang->kevindept->index           = "首页";
$lang->kevindept->deptlist        = "部门列表";
$lang->kevindept->deptcreate      = "新增部门";
$lang->kevindept->deptedit        = "编辑部门";
$lang->kevindept->deptdelete      = "删除部门";
$lang->kevindept->deptview        = "查看部门";
$lang->kevindept->deptBatchEdit   = "批量编辑部门";
$lang->kevindept->deptBatchDelete = "批量删除部门";
$lang->kevindept->deptset         = "部门指定";
$lang->kevindept->deletedeptuser  = "删除部门用户";
$lang->kevindept->synccategory    = "同步部门分类";

$lang->kevindept->code   = "代码";
$lang->kevindept->depart = "部门";
$lang->kevindept->search = "部门模糊搜索";

$lang->kevindept->confirmDelete = " 您确定删除该部门吗？";
$lang->kevindept->successSave   = " 修改成功。";

$lang->kevindept->error            = new stdclass();
$lang->kevindept->error->hasSons   = '该部门有子部门，不能删除！';
$lang->kevindept->error->hasUsers  = '该部门有职员，不能删除！';
$lang->kevindept->error->maxDeptId = "部门ID超过9999";