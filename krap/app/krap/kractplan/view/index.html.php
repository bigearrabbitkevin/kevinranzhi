<?php 
/**
 * The index view file of kractplan module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('status', $status);?>
<?php js::set('mode', $status);?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div id='menuActions'>
  <?php commonModel::printLink('kractplan', 'create', '', '<i class="icon-plus"></i> ' . $this->lang->kractplan->create, "id='createButton' class='btn btn-primary'");?>
</div>

<div class='row' id='maindiv'>
  <table class='table table-hover table-striped tablesorter table-fixed table-data'>
    <thead>
    <?php $vars = "status={$status}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
    <tr class='text-center'>
      <th class='w-60px'>   <?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->kractplan->id);?></th>
      <th class='text-left'><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->kractplan->name);?></th>
      <th class='w-user'>  <?php commonModel::printOrderLink('manager', $orderBy, $vars, $lang->kractplan->manager);?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->kractplan->status);?></th>
      <th class='text-left'><?php commonModel::printOrderLink('project', $orderBy, $vars, $lang->kractplan->project);?></th>
      <th class='w-auto'>  <?php commonModel::printOrderLink('member', $orderBy, $vars, $lang->kractplan->member);?></th>
      <th><?php echo $lang->kractplan->desc;?></th>
      <th class='w-220px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <?php foreach($plans as $planItem):?>
    <?php $browseLink = helper::createLink('krap.kractplan', 'goods', "id=$planItem->id");?>
    <tr class='text-center' data-url='<?php echo $browseLink;?>'>
      <td><?php echo $planItem->id;?></td>
      <td class='text-left'><?php echo $planItem->name;?></td>
      <td><?php echo zget($users, $planItem->manager);?></td>
      <td><?php echo zget($lang->kractplan->statusList,$planItem->status);?></td>
      <td class='text-left'><?php echo '('.$planItem->project.')'. $planItem->projectName;?></td>
      <td><?php echo $planItem->member;?></td>
      <td title='<?php echo strip_tags($planItem->desc);?>'><?php echo helper::substr(strip_tags($planItem->desc), 20, '...');?></td>
      <td>
        <?php commonModel::printLink('kractplan', 'view', "id=$planItem->id", $lang->view, "data-toggle='modal'");?>
        <?php commonModel::printLink('kractplan', 'edit', "id=$planItem->id", $lang->edit, "data-toggle='modal'");?>
        <?php commonModel::printLink('kractplan', 'goods', "id=$planItem->id", $lang->kractplan->goods);?>
        <?php if($planItem->status != 'finished') commonModel::printLink('kractplan','finish', "id=$planItem->id", $lang->finish, "data-toggle='modal'");?>
        <?php commonModel::printLink('kractplan', 'delete', "id=$planItem->id", $lang->delete, "class='deleter'");?>
      </td>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='8'><?php echo $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
