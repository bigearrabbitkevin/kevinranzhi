<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$lang->kevinuser->common = 'kevin用户管理';
$lang->kevinuser->index  = '首页';

$lang->zt_dept = &$lang->kevinuser;

$lang->kevinuserclass  = &$lang->kevinuser;
$lang->kevinuserrecord = &$lang->kevinuser;
$lang->kevinuserdept   = &$lang->kevinuser;

$lang->kevinuser->idAB        = 'id';
$lang->kevinuser->classlist   = '级别列表';
$lang->kevinuser->classcreate = '添加级别';
$lang->kevinuser->classview   = '显示级别';
$lang->kevinuser->classdelete = '删除级别';
$lang->kevinuser->classedit   = '编辑级别';

$lang->kevinuser->recordlist   = '人员履历列表';
$lang->kevinuser->recordcreate = '添加履历';
$lang->kevinuser->recordview   = '显示履历';
$lang->kevinuser->recorddelete = '删除履历';
$lang->kevinuser->recordedit   = '编辑履历';

$lang->kevinuser->deptcreate = '添加部门';
$lang->kevinuser->deptdelete = '删除部门';
$lang->kevinuser->deptview   = '查看部门';
$lang->kevinuser->deptedit   = '编辑部门';
$lang->kevinuser->deptlist   = '部门列表';

$lang->kevinuser->id              = 'ID';
$lang->kevinuser->grade           = '级别';
$lang->kevinuser->role            = '职位';
$lang->kevinuser->classify1       = "分类1";
$lang->kevinuser->classify2       = "分类2";
$lang->kevinuser->classify3       = "分类3";
$lang->kevinuser->classify4       = "分类4";
$lang->kevinuser->payrate         = "付款率";
$lang->kevinuser->hourFee         = "小时费用";
$lang->kevinuser->conversionFee   = "折算费用";
$lang->kevinuser->start           = "开始日期";
$lang->kevinuser->end             = "结束日期";
$lang->kevinuser->jobRequirements = "岗位要求";
$lang->kevinuser->monthFee        = "月费用";
$lang->kevinuser->remarks         = "备注";
$lang->kevinuser->oprater         = "操作";
$lang->kevinuser->ditto           = "同上";

$lang->kevinuser->guid        = 'GUID';
$lang->kevinuser->account     = "账号";
$lang->kevinuser->realname    = "姓名";
$lang->kevinuser->deptPrefer  = "部门编号";
$lang->kevinuser->class       = "级别";
$lang->kevinuser->classname   = "级别名称";
$lang->kevinuser->manage      = "用户";
$lang->kevinuser->type        = "类别";
$lang->kevinuser->worktype    = "工作类别";
$lang->kevinuser->classFilter = "级别筛选";

$lang->kevinuser->dateError      = "请输入合法日期";
$lang->kevinuser->errorUnique    = "数据已经存在，请重新填写";
$lang->kevinuser->lockData       = "数据已经锁定，不能修改";
$lang->kevinuser->startDataError = "开始日期不能小于上一条数据的开始日期";

$lang->kevinuser->delete  = '删除';
$lang->kevinuser->deleted = '已删除';

$lang->kevinuser->verifyPassword = "请输入你的密码";
$lang->kevinuser->verify         = "需要输入你的密码加以验证";

$lang->kevinuser->batchdelete = "批量删除";
$lang->kevinuser->batchedit   = "批量编辑";

$lang->kevinuser->classBatchDelete  = "批量删除级别";
$lang->kevinuser->classBatchEdit    = "批量编辑级别";
$lang->kevinuser->recordBatchDelete = "批量删除员工履历";
$lang->kevinuser->recordBatchEdit   = "批量编辑员工履历";
$lang->kevinuser->dept              = "部门";
$lang->kevinuser->deptBatchDelete   = "批量删除部门";
$lang->kevinuser->deptBatchEdit     = "批量编辑部门";
$lang->kevinuser->deptgroup         = "默认权限";
$lang->kevinuser->hasdeptgroup      = "已设置默认权限";
$lang->kevinuser->deptName          = "部门名称";
$lang->kevinuser->deptParent        = "上级部门";
$lang->kevinuser->deptPath          = "路径";
$lang->kevinuser->order             = "排序";
$lang->kevinuser->manager           = "负责人";
$lang->kevinuser->email             = "公司email";
$lang->kevinuser->code              = "公司代码";
$lang->kevinuser->topParent         = "顶级";

$lang->kevinuser->confirmDelete   = " 您确定删除数据吗？";
$lang->kevinuser->successDelete   = "删除成功。";
$lang->kevinuser->successSave     = " 修改成功。";
$lang->kevinuser->successUnDelete = "还原成功。";
$lang->kevinuser->successCreate   = " 录入数据成功。";

$lang->kevinuser->batchEditMsg = "一次最多只能批量编辑5条记录";

$lang->kevinuser->successBatchEdit   = " 批量编辑成功。";
$lang->kevinuser->successBatchDelete = " 批量删除成功。";

$lang->kevinuser->worktypeList['']  = '';
$lang->kevinuser->worktypeList['0'] = "正式";
$lang->kevinuser->worktypeList['1'] = "外援";


$lang->kevinuser->error           = new stdclass();
$lang->kevinuser->error->hasSons  = '该部门有子部门，不能删除！';
$lang->kevinuser->error->hasUsers = '该部门有职员，不能删除！';

$lang->kevinuser->classFilter  = "过滤级别";
$lang->kevinuser->recordFilter = "过滤个人履历";
$lang->kevinuser->deptFilter   = "过滤部门";

$lang->kevinuser->classify4Placeholder       = "填写扣款百分比（eg：扣款10%，不扣款），可不填";
$lang->kevinuser->payratePlaceholder         = "填写付款率,整数（eg:90）";
$lang->kevinuser->hourFeePlaceholder         = "填写每小时费用，整数(eg:100)";
$lang->kevinuser->startPlaceholder           = "选择开始日期,格式(2018-01-01)";
$lang->kevinuser->endPlaceholder             = "选择结束日期,格式(2018-01-01)";
$lang->kevinuser->jobRequirementsPlaceholder = "填写岗位要求";
$lang->kevinuser->remarksPlaceholder         = "填写备注";
$lang->kevinuser->accountPlaceholder         = "选择账号";
$lang->kevinuser->classPlaceholder           = "选择级别";
$lang->kevinuser->deptNamePlaceholder        = "填写部门名称";
$lang->kevinuser->gradePlaceholder           = "填写级别";
$lang->kevinuser->emailPlaceholder           = "填写公司email";
$lang->kevinuser->codePlaceholder            = "填写公司代码";
$lang->kevinuser->orderPlaceholder           = "填写排序";

$lang->kevinuser->deptset        = "部门指定";
$lang->kevinuser->deletedeptuser = "删除部门用户";
$lang->kevinuser->notexist       = "不存在";
$lang->kevinuser->deptinfo       = "部门信息";
$lang->kevinuser->path           = "path";

$lang->kevinuser->roleChoice      = "结构工程师";
$lang->kevinuser->classify1Choice = "CAD";
$lang->kevinuser->classify2Choice = "初级";
$lang->kevinuser->classify3Choice = "乙方软硬件";


$lang->kevinuser->browse   = "用户";
$lang->kevinuser->calendar = '日历';

$lang->kevinuser->userbatchedit  = "批量编辑用户";
$lang->kevinuser->manageContacts = "维护联系人";

$lang->kevinuser->lockedTemp = "临时被锁定。";
$lang->kevinuser->lockedLong = "长期被锁定。";
$lang->kevinuser->lockedNone = "激活的用户。";
$lang->kevinuser->userLock   = '锁定用户';
$lang->kevinuser->unlock     = "解锁用户";

$lang->kevinuser->create       = '添加用户';
$lang->kevinuser->edit         = "编辑用户";
$lang->kevinuser->batchcreate  = "批量添加";
$lang->kevinuser->batchCreate  = "批量添加用户";
$lang->kevinuser->deptdispatch = "服务部门";

$lang->kevinuser->confirmLock   = "您确定锁定该用户吗？";
$lang->kevinuser->confirmUnlock = "您确定解除该用户的锁定状态吗？";


$lang->kevinuser->company          = '所属公司';
$lang->kevinuser->dept             = '所属部门';
$lang->kevinuser->account          = '用户名';
$lang->kevinuser->password         = '密码';
$lang->kevinuser->password2        = '请重复密码';
$lang->kevinuser->role             = '职位';
$lang->kevinuser->group            = '分组';
$lang->kevinuser->realname         = '真实姓名';
$lang->kevinuser->nickname         = '昵称';
$lang->kevinuser->commiter         = '源代码帐号';
$lang->kevinuser->birthyear        = '出生年';
$lang->kevinuser->gender           = '性别';
$lang->kevinuser->email            = '邮箱';
$lang->kevinuser->basicInfo        = '基本信息';
$lang->kevinuser->accountInfo      = '帐号信息';
$lang->kevinuser->verify           = '安全验证';
$lang->kevinuser->contactInfo      = '联系信息';
$lang->kevinuser->skype            = 'Skype';
$lang->kevinuser->qq               = 'QQ';
$lang->kevinuser->yahoo            = '雅虎通';
$lang->kevinuser->gtalk            = 'GTalk';
$lang->kevinuser->wangwang         = '旺旺';
$lang->kevinuser->mobile           = '手机';
$lang->kevinuser->phone            = '电话';
$lang->kevinuser->address          = '通讯地址';
$lang->kevinuser->zipcode          = '邮编';
$lang->kevinuser->join             = '入职日期';
$lang->kevinuser->visits           = '访问次数';
$lang->kevinuser->ip               = '最后IP';
$lang->kevinuser->last             = '最后登录';
$lang->kevinuser->ranzhi           = '然之帐号';
$lang->kevinuser->ditto            = '同上';
$lang->kevinuser->originalPassword = '原密码';
$lang->kevinuser->verifyPassword   = '请输入你的密码';
$lang->kevinuser->resetPassword    = '忘记密码';

$lang->kevinuser->genderList['m'] = '男';
$lang->kevinuser->genderList['f'] = '女';

$lang->kevinuser->placeholder            = new stdclass();
$lang->kevinuser->placeholder->account   = '英文、数字和下划线的组合，三位以上';
$lang->kevinuser->placeholder->password1 = '六位以上';
$lang->kevinuser->placeholder->role      = '职位影响内容和用户列表的顺序。';
$lang->kevinuser->placeholder->group     = '分组决定用户的权限列表。';
$lang->kevinuser->placeholder->commiter  = '版本控制系统(subversion)中的帐号';
$lang->kevinuser->placeholder->verify    = '需要输入你的密码加以验证';

$lang->kevinuser->placeholder->passwordStrength[1] = '强度必须为中，6位以上，包含大小写字母，数字。';
$lang->kevinuser->placeholder->passwordStrength[2] = '强度必须为强，10位以上，包含字母，数字，特殊字符。';

$lang->kevinuser->roleList['']       = '';
$lang->kevinuser->roleList['dev']    = '研发';
$lang->kevinuser->roleList['qa']     = '测试';
$lang->kevinuser->roleList['pm']     = '项目经理';
$lang->kevinuser->roleList['po']     = '产品经理';
$lang->kevinuser->roleList['td']     = '研发主管';
$lang->kevinuser->roleList['pd']     = '产品主管';
$lang->kevinuser->roleList['qd']     = '测试主管';
$lang->kevinuser->roleList['top']    = '高层管理';
$lang->kevinuser->roleList['others'] = '其他';

$lang->kevinuser->error              = new stdclass();
$lang->kevinuser->error->account     = "ID %s，英文、数字和下划线的组合，三位以上";
$lang->kevinuser->error->accountDupl = "ID %s，该用户名已经存在";
$lang->kevinuser->error->realname    = "ID %s，必须填写真实姓名";
$lang->kevinuser->error->password    = "ID %s，密码必须六位以上";
$lang->kevinuser->error->mail        = "ID %s，请填写正确的邮箱地址";
$lang->kevinuser->error->role        = "ID %s，职位不能为空";

$lang->kevinuser->error->verifyPassword   = "安全验证密码错误，请输入你的登录密码";
$lang->kevinuser->error->originalPassword = "原密码不正确";

$lang->kevinuser->contacts           = new stdclass();
$lang->kevinuser->contacts->common   = '联系人';
$lang->kevinuser->contacts->listName = '列表名称';

$lang->kevinuser->contacts->manage        = '维护列表';
$lang->kevinuser->contacts->contactsList  = '已有列表';
$lang->kevinuser->contacts->selectedUsers = '选择用户';
$lang->kevinuser->contacts->selectList    = '选择列表';
$lang->kevinuser->contacts->createList    = '创建新列表';
$lang->kevinuser->contacts->noListYet     = '还没有创建任何列表，请先创建联系人列表。';
$lang->kevinuser->contacts->confirmDelete = '您确定要删除这个列表吗？';
$lang->kevinuser->contacts->or            = ' 或者 ';