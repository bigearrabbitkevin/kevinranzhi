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
<div id='menuActions'>
	<?php if (commonModel::hasPriv('kevinremote', 'create')) commonModel::printLink('kevinremote', 'create', '', '<i class="icon-plus"></i> ' . $this->lang->create,"data-toggle='modal' id='createButton' class='btn btn-primary' ");?>
</div>
<div class='col-md-12' id='kevinmaindiv'>
    <form method='post' id='classForm'>
        <table class='table table-condensed  table-hover table-striped tablesorter ' id='KevinValueList'>
            <thead>
                <tr class='text-center' height=35px>
					<?php $vars = "orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";?>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('id', $orderBy, $vars, 'ID');?></th>
                    <th class='text-center w-auto'><?php echo $lang->kevinremote->oprate;?></th>
					<th class='text-center w-auto'><?php echo $lang->kevinremote->status;?></th>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('type1', $orderBy, $vars, 'type1');?></th>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('type2', $orderBy, $vars, 'type2');?></th>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('realname', $orderBy, $vars, 'UserRealName');?></th>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('macname', $orderBy, $vars, 'MacName');?></th>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('ip', $orderBy, $vars, 'IP');?></th>

                    <th class='text-center w-auto'><?php commonModel::printOrderLink('mactype', $orderBy, $vars, 'MacType');?></th>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('macaddress', $orderBy, $vars, 'MacAddress');?></th>
                    <th class='text-center w-auto'><?php commonModel::printOrderLink('order', $orderBy, $vars, 'Order');?></th>
                </tr>
            </thead>
			<?php
			foreach ($remoteList as $item):
				?>
				<tr>
					<td class='text-center'><input name="IDList[]" value="<?php echo $item->id;?>" type="checkbox"> <?php printf('%03d', $item->id);?></td>
					<td class='text-center'>
						<?php
						if (commonModel::hasPriv('kevinremote', 'edit')) commonModel::printLink('kevinremote', 'edit',  "id=$item->id", $this->lang->edit,"data-toggle='modal' id='createButton' class='' ");
						if (commonModel::hasPriv('kevinremote', 'delete')) commonModel::printLink('kevinremote', 'delete',  "id=$item->id", $this->lang->delete,"data-toggle='modal' id='createButton' class='' ");
						if (commonModel::hasPriv('kevinremote', 'create')) commonModel::printLink('kevinremote', 'create',  "id=$item->id", $this->lang->copy,"data-toggle='modal' id='createButton' class='' ");
						?>
					</td>
					
					<td class='text-center'><?php echo $item->status;?></td>
					<td class='text-center'><?php echo $item->type1;?></td>
					<td class='text-center'><?php echo $item->type2;?></td>
					<td class='text-center'><?php echo $item->realname;?></td>
					<td class='text-center'><?php echo $item->macname;?></td>
					<td class='text-center'><?php echo $item->ip;?></td>
					<td class='text-center'><?php echo $item->mactype;?></td>
					<td class='text-center'><?php echo $item->macaddress;?></td>
					<td class='text-center'><?php echo $item->order;?></td>
				</tr>
			<?php endforeach;?>
            <tfoot>
                <tr>
					<?php if (!isset($columns)) $columns		 = 16;/*($this->cookie->windowWidth > $this->config->wideSize ? 16 : 16);*/?>
                    <td colspan='<?php echo $columns;?>'>
                        <div class='table-actions clearfix'>
							<?php
							$canBatchDelete	 = commonModel::hasPriv('kevinremote', 'batchDelete');
							$canBatchEdit	 = commonModel::hasPriv('kevinremote', 'batchEdit');
							$canWakeup	 = commonModel::hasPriv('kevinremote', 'wakeup');

							if (count($remoteList)) {
								echo html::selectButton();
								echo "<div class='btn-group'>";
								$actionLink	 = $this->createLink('kevinremote', 'batchDelete');
								$misc		 = $canBatchDelete ? "onclick=\"setFormAction('$actionLink','hiddenwin',this)\"" : "disabled='disabled'";
								echo html::commonButton($lang->kevinremote->batchDelete, $misc);
								$actionLink	 = $this->createLink('kevinremote', 'batchEdit');
								$misc		 = $canBatchEdit ? "onclick=\"setFormAction('$actionLink',null,this)\"" : "disabled='disabled'";
								echo html::commonButton($lang->kevinremote->batchEdit, $misc);
								$actionLink	 = $this->createLink('kevinremote', 'wakeup');
								$misc		 = $canWakeup ? "onclick=\"setFormAction('$actionLink',null,this)\"" : "disabled='disabled'";
								echo html::commonButton($lang->kevinremote->wakeup, $misc);
								echo "</div>";
							}
							?>
                        </div>
						<?php $pager->show();?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
<?php include '../../common/view/footer.html.php';?>