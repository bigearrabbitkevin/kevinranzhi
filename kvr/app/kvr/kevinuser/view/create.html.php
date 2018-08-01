<?php
/**
 * The create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php if(!empty($config->safe->mode)) $lang->kevinuser->placeholder->password1 = $lang->kevinuser->placeholder->passwordStrength[$config->safe->mode]?>
<?php js::set('holders', $lang->kevinuser->placeholder);?>
<?php js::set('roleGroup', $roleGroup);?>
<div class='container mw-700px'>
	<div id='titlebar'>
		<div class='heading'>
			<span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
			<strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->kevinuser->create;?></strong>
		</div>
	</div>
	<form class='form-condensed mw-700px' method='post' target='hiddenwin' id='dataform' action="<?php echo inLink('create'); ?>">
		<table align='center' class='table table-form'>
			<tr>
				<th class='w-120px'><?php echo $lang->kevinuser->dept;?></th>
				<td class='w-p50'><?php echo html::select('dept', $depts, $deptID, "class='form-control chosen'");?></td>
			</tr>
			<tr>
				<th class='w-120px'><?php echo $lang->kevinuser->deptdispatch;?></th>
				<td class='w-p50'><?php echo html::select('deptdispatch', $depts, $deptdispatch, "class='form-control chosen'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->account;?></th>
				<td><?php echo html::input('account', '', "class='form-control' autocomplete='off'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->realname;?></th>
				<td><?php echo html::input('realname', '', "class='form-control' autocomplete='off'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->password;?></th>
				<td>
					<input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
					<span class='input-group'>
            <?php echo html::password('password1', '', "class='form-control' autocomplete='off' onmouseup='checkPassword(this.value)' onkeyup='checkPassword(this.value)'");?>
						<span class='input-group-addon' id='passwordStrength'></span>
          </span>
				</td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->password2;?></th>
				<td><?php echo html::password('password2', '', "class='form-control' autocomplete='off'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->role;?></th>
				<td><?php echo html::select('role', $lang->kevinuser->roleList, '', "class='form-control' onchange='changeGroup(this.value)'");?></td>
				<td><?php echo $lang->kevinuser->placeholder->role?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->group;?></th>
				<td><?php echo html::select('group', $groupList, '', "class='form-control chosen'");?></td>
				<td><?php echo $lang->kevinuser->placeholder->group?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->email;?></th>
				<td><?php echo html::input('email', '', "class='form-control'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->commiter;?></th>
				<td><?php echo html::input('commiter', '', "class='form-control'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->join;?></th>
				<td><?php echo html::input('join', '', "class='form-control form-date'");?></td>
			</tr>
			<tr>
				<th><?php echo $lang->kevinuser->gender;?></th>
				<td><?php echo html::radio('gender', (array)$lang->kevinuser->genderList, 'm');?></td>
			</tr>

			<tr><th></th><td><?php echo html::submitButton() . html::backButton();?></td></tr>
		</table>
	</form>
</div>
<?php js::set('passwordStrengthList', $lang->kevinuser->passwordStrengthList)?>
<?php include '../../common/view/footer.modal.html.php';?>
