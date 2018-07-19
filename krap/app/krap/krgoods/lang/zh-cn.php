<?php
/**
 * The krgoods module zh-cn file of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
if(!isset($lang->krgoods)) $lang->krgoods = new stdclass();
$lang->krgoods->common     = '物品';
$lang->krgoods->browse     = '列表';
$lang->krgoods->index      = '首页';
$lang->krgoods->create     = "创建";
$lang->krgoods->edit       = '修改';
$lang->krgoods->view       = '详情';
$lang->krgoods->finish     = '完成';
$lang->krgoods->delete     = '删除';
$lang->krgoods->enter      = '进入';
$lang->krgoods->suspend    = '挂起';
$lang->krgoods->activate   = '激活';
$lang->krgoods->mine       = '我负责:';
$lang->krgoods->other      = '其他：';
$lang->krgoods->deleted    = '已删除';
$lang->krgoods->finished   = '已结束';
$lang->krgoods->suspended  = '已挂起';
$lang->krgoods->noMatched  = '找不到包含"%s"的条目';
$lang->krgoods->search     = '搜索';
$lang->krgoods->import     = '导入';
$lang->krgoods->importTask = '导入任务';
$lang->krgoods->role       = '角色';
$lang->krgoods->krgoods    = '条目';
$lang->krgoods->dateRange  = '起止日期';

$lang->krgoods->id          = '编号';
$lang->krgoods->name        = '名称';
$lang->krgoods->status      = '状态';
$lang->krgoods->desc        = '描述';
$lang->krgoods->begin       = '开始日期';
$lang->krgoods->manager     = '负责人';
$lang->krgoods->member      = '团队';
$lang->krgoods->end         = '结束日期';
$lang->krgoods->createdBy   = '由谁创建';
$lang->krgoods->createdDate = '创建时间';
$lang->krgoods->fromproject = '所属项目';
$lang->krgoods->whitelist   = '参观者';
$lang->krgoods->doc         = '文档';
$lang->krgoods->code         = '代码';
$lang->krgoods->type         = '类型';
$lang->krgoods->unit         = '单位';
$lang->krgoods->amount         = '数量';
$lang->krgoods->price         = '价格';
$lang->krgoods->TotalPrice      = '总价';
$lang->krgoods->times         = '次数';
$lang->krgoods->goodslink1         = '文档';
$lang->krgoods->goodslink2         = '文档';

$lang->krgoods->confirm = new stdclass();
$lang->krgoods->confirm->activate = '确认激活此id条目？';
$lang->krgoods->confirm->suspend  = '确认挂起此项目？';

$lang->krgoods->activateSuccess = '激活操作成功';
$lang->krgoods->suspendSuccess  = '挂起操作成功';
$lang->krgoods->selectProject   = '请选择项目';

$lang->krgoods->note = new stdclass();
$lang->krgoods->note->rate = '按工时计算';
$lang->krgoods->note->task = '任务数';

$lang->krgoods->statusList['doing']    = '进行中';
$lang->krgoods->statusList['finished'] = '已完成';
$lang->krgoods->statusList['suspend']  = '已挂起';

$lang->krgoods->roleList['member']  = '默认';
$lang->krgoods->roleList['senior']  = '管理员';
$lang->krgoods->roleList['limited'] = '受限';

$lang->krgoods->whitelistTip        = '参观者可以查看项目和任务';
$lang->krgoods->roleTip             = "管理员拥有所有权限，默认成员不可删除与自己无关的任务，受限成员仅可操作自己相关任务。";
$lang->krgoods->roleTips['senior']  = "管理员：可以查看、编辑、删除所有任务。";
$lang->krgoods->roleTips['member']  = "默认：可以查看、编辑所有任务，删除与自己相关的任务。";
$lang->krgoods->roleTips['limited'] = "受限：只能查看、编辑与自己相关的任务。";
