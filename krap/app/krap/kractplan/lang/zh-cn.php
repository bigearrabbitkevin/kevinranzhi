<?php
/**
 * The kractplan module zh-cn file of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
if(!isset($lang->kractplan)) $lang->kractplan = new stdclass();
$lang->kractplan->common     = '计划';
$lang->kractplan->browse     = '列表';
$lang->kractplan->index      = '首页';
$lang->kractplan->create     = "创建";
$lang->kractplan->edit       = '修改';
$lang->kractplan->view       = '详情';
$lang->kractplan->finish     = '完成';
$lang->kractplan->delete     = '删除';
$lang->kractplan->enter      = '进入';
$lang->kractplan->suspend    = '挂起';
$lang->kractplan->activate   = '激活';
$lang->kractplan->mine       = '我负责:';
$lang->kractplan->other      = '其他：';
$lang->kractplan->deleted    = '已删除';
$lang->kractplan->finished   = '已结束';
$lang->kractplan->suspended  = '已挂起';
$lang->kractplan->noMatched  = '找不到包含"%s"的条目';
$lang->kractplan->search     = '搜索';
$lang->kractplan->import     = '导入';
$lang->kractplan->importTask = '导入任务';
$lang->kractplan->role       = '角色';
$lang->kractplan->kractplan    = '项目';
$lang->kractplan->dateRange  = '起止日期';

$lang->kractplan->id          = '编号';
$lang->kractplan->name        = '名称';
$lang->kractplan->status      = '状态';
$lang->kractplan->desc        = '描述';
$lang->kractplan->begin       = '开始日期';
$lang->kractplan->manager     = '负责人';
$lang->kractplan->member      = '团队';
$lang->kractplan->end         = '结束日期';
$lang->kractplan->createdBy   = '由谁创建';
$lang->kractplan->createdDate = '创建时间';
$lang->kractplan->fromproject = '所属项目';
$lang->kractplan->whitelist   = '参观者';
$lang->kractplan->doc         = '文档';
$lang->kractplan->code         = '代码';
$lang->kractplan->type         = '类型';
$lang->kractplan->unit         = '单位';
$lang->kractplan->amount         = '数量';
$lang->kractplan->price         = '价格';
$lang->kractplan->TotalPrice      = '总价';

$lang->kractplan->customer         = '客户';
$lang->kractplan->project          = '项目';
$lang->kractplan->goods          = '物品';
$lang->kractplan->amount          = '数量';
$lang->kractplan->plan          = '计划';

$lang->kractplan->confirm = new stdclass();
$lang->kractplan->confirm->activate = '确认激活此项目？';
$lang->kractplan->confirm->suspend  = '确认挂起此项目？';

$lang->kractplan->activateSuccess = '激活操作成功';
$lang->kractplan->suspendSuccess  = '挂起操作成功';
$lang->kractplan->selectProject   = '请选择项目';

$lang->kractplan->note = new stdclass();
$lang->kractplan->note->rate = '按工时计算';
$lang->kractplan->note->task = '任务数';

$lang->kractplan->statusList['doing']    = '进行中';
$lang->kractplan->statusList['finished'] = '已完成';
$lang->kractplan->statusList['suspend']  = '已挂起';

$lang->kractplan->roleList['member']  = '默认';
$lang->kractplan->roleList['senior']  = '管理员';
$lang->kractplan->roleList['limited'] = '受限';

$lang->kractplan->whitelistTip        = '参观者可以查看项目和任务';
$lang->kractplan->roleTip             = "管理员拥有所有权限，默认成员不可删除与自己无关的任务，受限成员仅可操作自己相关任务。";
$lang->kractplan->roleTips['senior']  = "管理员：可以查看、编辑、删除所有任务。";
$lang->kractplan->roleTips['member']  = "默认：可以查看、编辑所有任务，删除与自己相关的任务。";
$lang->kractplan->roleTips['limited'] = "受限：只能查看、编辑与自己相关的任务。";

//版本密码相关的部分用于krap的版本
$lang->kractplan->settings = '活动计划设置';
$lang->kractplan->version  = '版本';
$lang->kractplan->key      = '密钥';
$lang->kractplan->errorKey = '<strong>密钥</strong> 应该为数字或字母的组合，长度为32位。';