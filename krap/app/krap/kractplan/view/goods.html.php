<?php 
/**
 * The browse view file of kractplan module of RanZhi.
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
<div id='menuActions'>
  <?php commonModel::printLink('kractplan', 'create', '', '<i class="icon-plus"></i> ' . $this->lang->kractplan->create, "id='createButton' class='btn btn-primary'");?>
</div>
<div class='panel' id='listMode'>
	  <table class='table table-hover table-striped tablesorter table-fixed table-data'>
    <tr class='text-center' >
      <td><?php echo $planItem->id;?></td>
      <td class='text-left'><?php echo $planItem->name;?></td>
      <td class='text-left'><?php echo '('.$planItem->project.')'. $planItem->projectName;?></td>
      <td><?php echo $planItem->manager;?></td>
      <td><?php echo '('.$planItem->member.')'. $planItem->member;?></td>
      <td></td>
      <td title='<?php echo strip_tags($planItem->desc);?>'><?php echo helper::substr(strip_tags($planItem->desc), 20, '...');?></td>
      <td>
        <?php commonModel::printLink('kractplan', 'edit', "id=$planItem->id", $lang->edit, "data-toggle='modal'");?>
      </td>
    </tr>

  </table>
	<br>
  <table class='table table-hover table-striped tablesorter table-fixed table-data'>
    <thead>
    <?php $vars = "";?>
    <tr class='text-center'>
      <th class='w-60px'>   <?php echo $lang->kractplan->id;?></th>
      <th class='text-right'><?php echo $lang->kractplan->plan;?></th>
      <th class='text-right'><?php echo $lang->kractplan->goods.' ID';?></th>
      <th class='text-right'><?php echo $lang->kractplan->name;?></th>
      <th class='text-right'><?php echo $lang->kractplan->amount;?></th>
      <th class='w-100px'>  <?php echo $lang->kractplan->begin;?></th>
      <th class='w-100px'>  <?php echo $lang->kractplan->end;?></th>
      <th class='w-100px'>  <?php echo $lang->kractplan->desc;?> </th>
      <th class='w-220px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <?php foreach($goods as $item):
		$browseLink = helper::createLink('krap.krgoods', 'view', "id=$item->goods");?>
    <tr class='text-center' data-url='<?php echo $browseLink;?>'>
      <td><?php echo $item->id;?></td>
      <td class='text-right'><?php echo '('.$item->plan.')';?></td>
      <td class='text-right'><?php echo $item->goods;?></td>
      <td class='text-right'><?php echo $item->GoodsName;?></td>
      <td class='text-right'><?php echo $item->amount;?></td>
      <td><?php echo $item->start;?></td>
      <td><?php echo $item->end;?></td>
      <td title='<?php echo strip_tags($item->desc);?>'><?php echo helper::substr(strip_tags($item->desc), 20, '...');?></td>
      <td>
        <?php commonModel::printLink('kractplan', 'edit', "id=$item->id", $lang->edit, "data-toggle='modal'");?>
       <?php if($item->status != 'finished') commonModel::printLink('kractplan','finish', "id=$item->id", $lang->finish, "data-toggle='modal'");?>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
