<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix'><?php echo html::icon($lang->icons['project']);?></span>
		<strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $title;?></strong>
	</div>
</div>
<?php
$visibleFields = array();
foreach (explode(',', $showFields) as $field) {
	if ($field) $visibleFields[$field] = '';
}
$minWidth = (count($visibleFields) > 5) ? 'w-150px' : '';
?>
<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo inLink('deptBatchEdit');?>'>
	<table class='table table-form'>
		<thead>
			<tr class='text-center'>
				<th class='w-p5'><?php echo $lang->kevinuser->id;?></th>
				<th class='w-p20'><?php echo $lang->kevinuser->deptParent;?> <span class='required'></span></th>
				<th class='w-p15'><?php echo $lang->kevinuser->deptName;?> <span class='required'></span></th>
				<th class='w-p10' style="min-width:100px;"><?php echo $lang->kevinuser->manager;?> </th>
				<th class='w-p20'><?php echo $lang->kevinuser->deptgroup;?> </th>
				<th class='w-p10'><?php echo $lang->kevinuser->email;?> </th>
				<th class='w-p10'><?php echo $lang->kevinuser->code;?> </th>
				<th class='w-p10'><?php echo $lang->kevinuser->order;?> <span class='required'></span></th>
			</tr>
		</thead>
		<?php foreach ($deptIDList as $deptID):?>
			<tr class='text-center'>
				<td><?php echo sprintf('%03d', $deptID) . html::hidden("deptIDList[$deptID]", $deptID);?></td>
				<td ><?php echo html::select("parent[$deptID]", $optionMenu, $depts[$deptID]->parent, "class='form-control chosen pull-right'");?></td>
				<td ><?php echo html::input("name[$deptID]", $depts[$deptID]->name, "class='form-control' autocomplete='off'");?></td>
				<td ><?php echo html::select("manager[$deptID]", $users, $depts[$deptID]->manager, "class='form-control chosen'");?></td>
				<td ><?php echo html::select("group[$deptID][]", $groups, isset($depts[$deptID]->group)?$depts[$deptID]->group:'', "size=3 multiple=multiple class='form-control chosen'");?></td>
				<td ><?php echo html::input("email[$deptID]", $depts[$deptID]->email, "class='form-control' autocomplete='off'");?></td>
				<td ><?php echo html::input("code[$deptID]", $depts[$deptID]->code, "class='form-control' autocomplete='off'");?></td>
				<td ><?php echo html::input("order[$deptID]", $depts[$deptID]->order, "class='form-control' autocomplete='off'");?></td>
			</tr>
		<?php endforeach;?>
		<tr><td colspan='<?php echo count($visibleFields) + 6?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
	</table>
</form>
<?php include '../../common/view/footer.html.php';?>
<script>
	$("li[data-id='kevindept']").addClass('active');
	$("li[data-id='deptlist']").addClass('active');
</script>
<?php include '../../common/view/footer.html.php';?>
<?php include '../../common/view/footer.html.php';?>
