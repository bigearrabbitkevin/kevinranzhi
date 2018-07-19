<?php
/**
 * The edit view file of krgoods module of RanZhi.
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
<form method='post' id='editForm' action='<?php echo inlink('edit', "id={$krgoods->id}")?>' class='form'>
  <table class='table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->krgoods->name;?></th>
      <td class='w-p50'>
        <div class='required required-wrapper'></div>
        <?php echo html::input('name', $krgoods->name, "class='form-control'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->manager;?></th>
      <td><?php echo html::select('manager', $users, $krgoods->PM, "class='form-control user-chosen'");?></td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->begin;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::input('begin', $krgoods->begin, "class='form-control form-date'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->end;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::input('end', $krgoods->end, "class='form-control form-date'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->desc;?></th>
      <td colspan='2'><?php echo html::textarea('desc', $krgoods->desc, "class='form-control' rows='5'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->whitelist . ' ' . html::a('javascript:void(0)', "<i class='icon-question-sign'></i>", "data-original-title='{$lang->krgoods->whitelistTip}' data-toggle='tooltip'");?></th>
      <td colspan='2'><?php echo html::checkbox('whitelist', $groups, $krgoods->whitelist);?></td>
    </tr>
    <tr><th></th><td><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
