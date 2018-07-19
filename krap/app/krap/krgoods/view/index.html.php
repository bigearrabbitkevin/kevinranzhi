<?php 
/**
 * The browse view file of krgoods module of RanZhi.
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
  <?php echo html::a("javascript:;", "<i class='icon icon-th-large'></i>", "data-mode='card' class='mode-toggle btn'");?>
  <?php echo html::a("javascript:;", "<i class='icon icon-list'></i>", "data-mode='list' class='mode-toggle btn'");?>
  <?php commonModel::printLink('krgoods', 'create', '', '<i class="icon-plus"></i> ' . $this->lang->krgoods->create, "id='createButton' class='btn btn-primary'");?>
</div>
<div class='row' id='cardMode'>
  <?php foreach($goodsItems as $item):?>
  <div class='col-md-4 col-sm-6'>
    <div class='panel krgoods-block'>
      <div class='panel-heading'>
        <strong><?php echo $item->name;?></strong>
        <?php if($this->krgoods->hasActionPriv($item)):?>
        <div class="panel-actions pull-right">
          <div class="dropdown">
            <button class="btn btn-mini" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu pull-right">
              <?php commonModel::printLink('krgoods', 'edit', "id=$item->id", $lang->edit, "data-toggle='modal'", '', '', 'li');?>
              <?php if($item->status != 'finished') commonModel::printLink('krgoods','finish', "id=$item->id", $lang->finish, "data-toggle='modal'", '', '', 'li');?>
               <?php commonModel::printLink('krgoods', 'delete', "id=$item->id", $lang->delete, "class='deleter'", '', '', 'li');?>
            </ul>
          </div>
        </div>
        <?php endif;?>
      </div>
      <div class='panel-body'>
        <div class='info'><?php echo $item->desc;?></div>
        <div class='footerbar text-important'>
           <?php $browseLink = helper::createLink('krap.krgoods', 'view', "id=$item->id");
			echo html::a($browseLink, $lang->krgoods->enter, "class='btn btn-primary entry'");?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  <div class='col-sm-6 col-md-12'><?php echo $pager->show();?></div>
</div>
<div class='panel hide' id='listMode'>
  <table class='table table-hover table-striped tablesorter table-fixed table-data'>
    <thead>
    <?php $vars = "status={$status}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
    <tr class='text-center'>
      <th class='w-60px'>   <?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->krgoods->id);?></th>
      <th class='text-left'><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->krgoods->name);?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('code', $orderBy, $vars, $lang->krgoods->code);?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->krgoods->type);?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('price', $orderBy, $vars, $lang->krgoods->price);?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('amount', $orderBy, $vars, $lang->krgoods->amount);?></th>
      <th><?php echo $lang->krgoods->desc;?></th>
      <th class='w-2000px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <?php foreach($goodsItems as $item):?>
    <?php $browseLink = helper::createLink('krap.krgoods', 'view', "id=$item->id");?>
    <tr class='text-center' data-url='<?php echo $browseLink;?>'>
      <td><?php echo $item->id;?></td>
      <td class='text-left'><?php echo $item->name;?></td>
     <td><?php echo $item->code;?></td>
     <td><?php echo $item->type;?></td>
      <td><?php echo $item->price;?></td>
      <td><?php echo $item->amount;?></td>
      <td title='<?php echo strip_tags($item->desc);?>'><?php echo helper::substr(strip_tags($item->desc), 20, '...');?></td>
      <td>
        <?php commonModel::printLink('krgoods', 'edit', "id=$item->id", $lang->edit, "data-toggle='modal'");?>
        <?php if($item->status != 'finished') commonModel::printLink('krgoods','finish', "id=$item->id", $lang->finish, "data-toggle='modal'");?>
        <?php commonModel::printLink('krgoods', 'delete', "id=$item->id", $lang->delete, "class='deleter'");?>
      </td>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='8'><?php echo $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
