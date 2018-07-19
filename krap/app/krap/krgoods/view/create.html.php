<?php
/**
 * The create view file of krgoods module of RanZhi.
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
  <table class='table-form'>
    <tr>
      <th class='w-70px'><?php echo $lang->krgoods->name;?></th>
      <td class='w-p60'>
        <div class='required required-wrapper'></div>
        <?php echo html::input('name', '', "class='form-control'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->manager;?></th>
      <td><?php echo html::select('manager', $users, $this->app->user->account, "class='form-control user-chosen'");?></td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->member;?></th>
      <td><?php echo html::select('member[]', $users, $this->app->user->account, "class='form-control user-chosen' multiple");?></td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->begin;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::input('begin', '', "class='form-control form-date'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->end;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::input('end', '', "class='form-control form-date'");?>
      </td><td></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->desc;?></th>
      <td colspan='2'><?php echo html::textarea('desc', '', "class='form-control' rows='5'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->krgoods->whitelist . ' ' . html::a('javascript:void(0)', "<i class='icon-question-sign'></i>", "data-original-title='{$lang->krgoods->whitelistTip}' data-toggle='tooltip'");?></th>
      <td colspan='2'><?php echo html::checkbox('whitelist', $groups, '');?></td>
    </tr>
    <tr><th></th><td><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php js::set('id', '0')?>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
