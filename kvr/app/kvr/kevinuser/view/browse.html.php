<?php
/**
 * The browse view file of product dept of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     kevinefine company browse
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
$this->moduleName = "kevinuser";
include '../../common/view/header.html.php';
include '../../../sys/common/view/treeview.html.php';
js::set('deptID', $deptID);
js::set('from', 'admin');
js::set('deptID', $deptID);
js::set('confirmDelete', $lang->kevinuser->confirmDelete);
$lockedSeconds = $this->config->kevinuser->lockMinutes * 60;
$nowTime       = time();
$longLockTime  = '2030-01-01 00:00:00';

?>
<div id='querybox'  class='show' style="margin: -20px -20px 20px;"><?php echo $searchForm ;?></div>

<div class="with-side <?php echo $this->cookie->todoCalendarSide == 'hide' ? 'hide-side' : '' ?>">
	<div class='side' style="position: absolute;top: auto;">
		<?php echo html::a('###', "<i class='icon-caret-left'></i>", "class='side-btn side-handle'") ?>
		<div class='side-body'>
			<div class='panel panel-sm'>
				<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']); ?>
					<strong><?php echo $lang->dept->common; ?></strong></div>
				<div class='panel-body'>
					<div id='treeMenuBox'><?php echo $deptTree;?></div>
					<div class='text-right'><?php commonModel::printLink('kevindept', 'browse', '', $lang->kevindept->manage); ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class='main'>
		<div class='panel'>
			<form action='<?php echo $this->createLink('kevinuser', 'userbatchedit', "deptID=$deptID") ?>' method='post' id='userListForm'>
				<table class='table table-condensed  table-hover table-striped tablesorter' id='userList'>
					<thead>
					<tr class='text-center'>
						<?php $vars = "param=$param&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
						<th class='text-center w-auto'><?php commonModel::printorderlink('id', $orderBy, $vars, $lang->kevinuser->idAB); ?></th>
						<th class='text-center w-auto'><?php commonModel::printorderlink('realname', $orderBy, $vars, $lang->kevinuser->realname); ?></th>
<!--						<th class='text-center w-auto'>--><?php //echo $lang->kevinuser->calendar; ?><!--</th>-->
						<th class='text-center w-auto'><?php commonModel::printOrderLink('account', $orderBy, $vars, $lang->kevinuser->account); ?></th>
						<th class='text-center w-auto'><?php commonModel::printOrderLink('code', $orderBy, $vars, $lang->kevinuser->code); ?></th>
						<th class='text-center w-auto'><?php commonModel::printOrderLink('dept', $orderBy, $vars, $lang->kevinuser->dept); ?></th>
						<th class='text-center w-auto'><?php commonModel::printOrderLink('deptdispatch', $orderBy, $vars, $lang->kevinuser->deptdispatch); ?></th>
						<th class='text-center w-auto'><?php commonModel::printOrderLink('email', $orderBy, $vars, $lang->kevinuser->email); ?></th>
						<th class='text-center w-auto'><?php commonModel::printOrderLink('gender', $orderBy, $vars, $lang->kevinuser->gender); ?></th>
						<th class='text-center w-auto'><?php commonModel::printOrderLink('last', $orderBy, $vars, $lang->kevinuser->last); ?></th>
						<th class='text-center w-auto'><?php commonModel::printOrderLink('visits', $orderBy, $vars, $lang->kevinuser->visits); ?></th>
						<th class='text-center w-auto'><?php echo $lang->actions; ?></th>
					</tr>
					</thead>
					<tbody>

					<?php
					$canBatchEdit      = commonModel::hasPriv('kevinuser', 'batchEdit');
					$canManageContacts = commonModel::hasPriv('kevinuser', 'manageContacts');
					?>
					<?php foreach ($users as $user):
						$isLock = (strtotime(date('Y-m-d H:i:s')) - strtotime($user->locked)) < $this->config->kevinuser->lockMinutes * 60;
						if ($isLock) $lockbackground = "style='background:yellow'";
						else $lockbackground = ""; ?>
						<tr class='text-center'>
							<td <?php echo $lockbackground; ?> >
								<?php
								if ($canBatchEdit or $canManageContacts)
									echo "<input type='checkbox' name='users[]' value='$user->account'> ";
								printf('%03d', $user->id);
								?>
							</td>
							<td <?php echo $lockbackground; ?>><?php echo $user->realname ?>
							</td>
							<td><?php echo $user->account; ?></td>
							<td><?php if ($user->code) echo $user->code; ?></td>
							<td><?php
								if (isset($depts[$user->dept]))
									echo $depts[$user->dept];
								else
									echo '&nbsp;';
								?></td>
							<td><?php
								if (isset($depts[$user->deptdispatch]))
									echo $depts[$user->deptdispatch];
								else
									echo '&nbsp;';
								?></td>
							<td><?php echo html::mailto($user->email); ?></td>
							<td><?php if (isset($lang->kevinuser->genderList[$user->gender])) echo $lang->kevinuser->genderList[$user->gender]; ?></td>
							<td><?php if ($user->last) echo date('Y-m-d', $user->last); ?></td>
							<td><?php echo $user->visits; ?></td>
							<td class='text-left'>
								<?php echo html::a($this->createLink('kevinuser', 'edit', "userID=$user->id&from=kevinuser"), $lang->kevinuser->edit, "data-toggle='modal' data-id='profile'"); ?>

								<?php

								$isNotAdmin = strpos($this->app->company->admins, ",{$user->account},") === false;

								if ($isNotAdmin) {
									$titlelockedNone = $lang->kevinuser->lockedNone.$lang->kevinuser->userLock."?";
									$titlelockedTemp = $lang->kevinuser->lockedTemp.$lang->kevinuser->unlock."?";
									$titlelockedLong = $lang->kevinuser->lockedLong.$lang->kevinuser->unlock."?";
									if ((int)$user->locked == 0) {
										commonModel::printLink('kevinuser', 'userLock', "userID=$user->account", "<i class='icon-unlock-alt text-muted'></i>", "data-type='iframe' data-toggle='modal'");
									} else if ($user->locked < $longLockTime) {
										commonModel::printLink('kevinuser', 'unlock', "userID=$user->account", "<i class='icon-lock'></i>", "data-type='iframe' data-toggle='modal'");
									} else {
										commonModel::printLink('kevinuser', 'unlock', "userID=$user->account", "<i class='icon-lock text-muted'></i>", "data-type='iframe' data-toggle='modal'");
									}
								}
								?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
					<tfoot>
					<tr>
						<td colspan='12'>
							<div class='table-actions clearfix'>
								<?php
								if ($canBatchEdit or $canManageContacts)
									echo html::selectButton();
								if ($canBatchEdit)
									echo html::submitButton($lang->edit, 'btn btn-default', 'onclick=batchEdit()');
								if ($canManageContacts)
									echo html::submitButton($lang->kevinuser->contacts->manage, 'btn btn-default', 'onclick=manageContacts()');
								?>
							</div>
							<?php echo $pager->show(); ?>
						</td>
					</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<script lanugage='javascript'>
	$('#dept<?php echo $deptID; ?>').addClass('active');

	function batchEdit() {
		$('#userListForm').attr('action', createLink('kevinuser', 'userbatchedit', 'dept=' + deptID));
	}

	function manageContacts() {
		$('#userListForm').attr('action', createLink('kevinuser', 'manageContacts'));
	}
</script>
<?php include '../../common/view/footer.html.php'; ?>
