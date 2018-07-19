<?php
/**
 * The configure xuanxuan view file of setting module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
?>
<?php include '../../../common/view/header.html.php';?>
<br><br><br><br><br><br><br><br><br><br>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->kractplan->settings;?></strong>
  </div>
  <form id='ajaxForm' method='post'>
    <table class='table table-form w-p40'>
      <tr>
        <th class='w-80px'><?php echo $lang->kractplan->version;?></th>
        <td><?php echo $config->krap->version;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kractplan->key;?></th>
        <td><?php echo html::input('key', $config->krap->key, "class='form-control'");?></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../../common/view/footer.html.php';?>
