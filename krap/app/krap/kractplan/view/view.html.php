<?php
/**
 * The detail view file of kractplan module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<table class='table table-bordered'>
  <tr>
    <th class='w-100px'><?php echo $lang->kractplan->status;?></th>
    <td><?php echo zget($lang->kractplan->statusList, $kractplan->status);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->kractplan->begin;?></th>
    <td><?php echo $kractplan->begin;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->kractplan->end;?></th>
    <td><?php echo $kractplan->end;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->kractplan->manager;?></th>
    <td><?php echo zget($users, $kractplan->manager);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->kractplan->member;?></th>
    <td><?php echo $kractplan->member;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->kractplan->desc;?></th>
    <td><?php echo $kractplan->desc;?></td>
  </tr>
</table>
<?php echo $this->fetch('action', 'history', "objectType=kractplan&objectID=$kractplan->id");?>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
