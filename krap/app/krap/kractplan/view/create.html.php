<?php
/**
 * The create view file of kractplan module of RanZhi.
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
<form method='post' id='ajaxForm' action='<?php echo inlink('create')?>'>
  <table class='table-form' >
    <tr>
      <th class='w-70px'><?php echo $lang->kractplan->name;?></th>
      <td class='w-auto'><?php echo html::input('name', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->manager;?></th>
      <td><?php echo html::select('manager', $users, $this->app->user->account, "class='form-control user-chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->member;?></th>
      <td><?php echo html::select('member[]', $users, $this->app->user->account, "class='form-control user-chosen' multiple");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->begin;?></th>
      <td><?php echo html::input('begin', helper::now(), "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->end;?></th>
      <td><?php echo html::input('end', helper::now(), "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->kractplan->desc;?></th>
      <td ><?php echo html::textarea('desc', '', "class='form-control' rows='5'");?></td>
    </tr>
    <tr>
	<th></th><td><?php echo html::submitButton();?></td>
	</tr>
  </table>
</form>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
