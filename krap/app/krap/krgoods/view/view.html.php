<?php
/**
 * The detail view file of krgoods module of RanZhi.
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
    <th class='w-100px'><?php echo $lang->krgoods->status;?></th>
    <td><?php echo zget($lang->krgoods->statusList, $krgoods->status);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->krgoods->begin;?></th>
    <td><?php echo $krgoods->begin;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->krgoods->end;?></th>
    <td><?php echo $krgoods->end;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->krgoods->createdBy;?></th>
    <td><?php echo zget($users, $krgoods->createdBy);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->krgoods->desc;?></th>
    <td><?php echo $krgoods->desc;?></td>
  </tr>
</table>
<?php echo $this->fetch('action', 'history', "objectType=krgoods&objectID=$krgoods->id");?>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
