<?php
/**
 * The edit view file of kractplan module of RanZhi.
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
<?php include '../../../sys/common/view/kindeditor.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<form method='post' id='editForm' action='<?php echo inlink('edit', "id={$kractplan->id}")?>' class='form'>
  <table class='table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->kractplan->name;?></th>
      <td class='w-p50'>
        <div class='required required-wrapper'></div>
        <?php echo html::input('name', $kractplan->name, "class='form-control'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->manager;?></th>
      <td><?php echo html::select('manager', $users, $kractplan->manager, "class='form-control user-chosen'");?></td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->begin;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::input('begin', $kractplan->begin, "class='form-control form-date'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->end;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::input('end', $kractplan->end, "class='form-control form-date'");?>
      </td><td></td>
    </tr>
	<tr>
      <th><?php echo $lang->kractplan->member;?></th>
      <td><?php echo html::select('member[]', $users, $kractplan->member, "class='form-control user-chosen' multiple");?></td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->desc;?></th>
      <td colspan='2'><?php echo html::textarea('desc', $kractplan->desc, "class='form-control' rows='5'");?></td>
    </tr>
    <tr><th></th><td><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
