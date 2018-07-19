<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinplan
 * @link       
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix'><?php echo html::icon($lang->icons['project']);?></span>
		<strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $title;?></strong>
		<div class='actions'>
			<button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
		</div>
	</div>
</div>
<?php
$visibleFields = array();
foreach (explode(',', $showFields) as $field) {
	if ($field) $visibleFields[$field] = '';
}
$minWidth = (count($visibleFields) > 5) ? 'w-150px' : '';
?>
<form class='form-condensed' method='post' target='hiddenwin' action='<?php echo inLink('batchEdit');?>'>
	<table class='table table-form'>
		<thead>
			<tr class='text-center'>
				<th class='w-auto'><?php echo $lang->kevinremote->id;?></th>
				<th class='w-auto'><?php echo $lang->kevinremote->type1;?> </th>
				<th class='w-auto'><?php echo $lang->kevinremote->type2;?></th>
				<th class='w-auto'><?php echo $lang->kevinremote->realname;?></th>
				<th class='w-auto'><?php echo $lang->kevinremote->macname;?></th>
				<th class='w-auto'><?php echo $lang->kevinremote->ip;?></th>
				<th class='w-auto'><?php echo $lang->kevinremote->mactype;?></th>
				<th class='w-auto'><?php echo $lang->kevinremote->macaddress;?></th>
				<th class='w-auto'><?php echo $lang->kevinremote->order;?></th>
			</tr>
		</thead>
		<?php foreach ($IDList as $remoteID):?>
			<tr class='text-center'>
				<td><?php echo sprintf('%03d', $remoteID) . html::hidden("IDList[$remoteID]", $remoteID);?></td>
				<td class='<?php echo zget($visibleFields, 'type1', 'hidden')?>'>
					<?php echo html::select("type1[$remoteID]", $lang->kevinremote->type1List, $remotes[$remoteID]->type1, "class='form-control chosen'");?>
				</td>
				<td class='<?php echo zget($visibleFields, 'type2', 'hidden')?>'>
					<?php echo html::select("type2[$remoteID]", $lang->kevinremote->type2List, $remotes[$remoteID]->type2, "class='form-control chosen'");?>
				</td>
				<td class='<?php echo zget($visibleFields, 'realname', 'hidden')?>'><?php echo html::input("realname[$remoteID]", $remotes[$remoteID]->realname, "class='form-control' autocomplete='off'");?></td>
				<td class='<?php echo zget($visibleFields, 'macname', 'hidden')?>'><?php echo html::input("macname[$remoteID]", $remotes[$remoteID]->macname, "class='form-control' autocomplete='off'");?></td>
				<td class='<?php echo zget($visibleFields, 'ip', 'hidden')?>'><?php echo html::input("ip[$remoteID]", $remotes[$remoteID]->ip, "class='form-control' autocomplete='off'");?></td>
				<td class='<?php echo zget($visibleFields, 'mactype', 'hidden')?>'><?php echo html::input("mactype[$remoteID]", $remotes[$remoteID]->mactype, "class='form-control' autocomplete='off'");?></td>
				<td class='<?php echo zget($visibleFields, 'macaddress', 'hidden')?>'><?php echo html::input("macaddress[$remoteID]", $remotes[$remoteID]->macaddress, "class='form-control' autocomplete='off'");?></td>
				<td class='<?php echo zget($visibleFields, 'order', 'hidden')?>'><?php echo html::input("order[$remoteID]", $remotes[$remoteID]->order, "class='form-control' autocomplete='off'");?></td>
			</tr>
		<?php endforeach;?>
		<tr><td colspan='<?php echo count($visibleFields) + 6?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
	</table>
</form>
<?php include '../../common/view/footer.html.php';?>
