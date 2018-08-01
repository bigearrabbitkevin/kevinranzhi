<?php
/**
 * The edit view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<div class='container mw-800px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix' title='USER'><?php echo html::icon($lang->icons['user']);?> <strong><?php echo $user->id;?></strong></span>
      <strong><?php echo $user->realname;?> (<small><?php echo $user->account;?></small>)</strong>
      <small class='text-muted'> <?php echo $lang->kevinuser->edit;?> <?php echo html::icon($lang->icons['edit']);?></small>

    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='userform'>
	  <span style="position: absolute;right: 25px;top: 25px;"  ><?php echo html::submitButton($lang->save, 'btn btn-default', 'onclick=editUser()');?></span>
   <table align='center' class='table table-form'>
      <caption class='text-left text-muted'><?php echo $lang->kevinuser->basicInfo;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->kevinuser->realname;?></th>
        <td class='w-p40'><?php echo html::input('realname', $user->realname, "class='form-control'");?></td>
	      <th><?php echo $lang->kevinuser->role;?></th>
	      <td><?php echo html::select('role', $lang->kevinuser->roleList, $user->role, "class='form-control'");?></td>
      </tr>
      <tr>
        <th class='w-90px'><?php echo $lang->kevinuser->dept;?></th>
        <td class='w-p40' colspan="3"><?php echo html::select('dept', $depts, $user->dept, "class='form-control chosen'");?></td>

       </tr>
      <tr>
        <th class='w-90px'><?php echo $lang->kevinuser->deptdispatch;?></th>
		    <td class='w-p40' colspan="3"><?php echo html::select('deptdispatch', $depts, $user->deptdispatch, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->group->priv;?></th>
        <td colspan='3'><?php echo html::select('groups[]', $groups, $userGroups, "class='form-control chosen' multiple");?>
	        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kevinuser->join;?></th>
        <td><?php echo html::input('join', $user->join, "class='form-control form-date'");?></td>
        <th><?php echo $lang->kevinuser->gender;?></th>
        <td><?php echo html::radio('gender', (array)$lang->kevinuser->genderList, $user->gender);?></td>
      </tr>
    </table>
    <table align='center' class='table table-form'>
      <caption class='text-left text-muted'><?php echo $lang->kevinuser->accountInfo;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->kevinuser->account;?></th>
        <td class='w-p40'><?php echo html::input('account', $user->account, "class='form-control' autocomplete='off' disabled");?></td>
        <th class='w-90px'><?php echo $lang->kevinuser->email;?></th>
        <td>
          <?php echo html::input('email', $user->email, "class='form-control'");?>
          <input type='text' style="display:none"> <!-- Disable input account by browser automatically. -->
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kevinuser->password;?></th>
        <td>
          <input type='password' style="display:none"> <!-- Disable input password by browser automatically. -->
          <span class='input-group'>
            <?php echo html::password('password1', '', "class='form-control disabled-ie-placeholder' autocomplete='off' onmouseup='checkPassword(this.value)' onkeyup='checkPassword(this.value)' placeholder='" . (!empty($config->safe->mode) ? $lang->user->placeholder->passwordStrength[$config->safe->mode] : '') . "'");?>
            <span class='input-group-addon' id='passwordStrength'></span>
          </span>
        </td>
        <th><?php echo $lang->kevinuser->password2;?></th>
        <td><?php echo html::password('password2', '', "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kevinuser->commiter;?></th>
        <td><?php echo html::input('commiter', $user->commiter, "class='form-control'");?></td>
      </tr>
    </table>
    <table align='center' class='table table-form'>
      <caption class='text-left text-muted'><?php echo $lang->kevinuser->contactInfo;?></caption>
      <tr>
        <th class='w-90px'><?php echo $lang->kevinuser->skype;?></th>
        <td class='w-p40'><?php echo html::input('skype', $user->skype, "class='form-control'");?></td>
        <th class='w-90px'><?php echo $lang->kevinuser->qq;?></th>
        <td><?php echo html::input('qq', $user->qq, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->kevinuser->yahoo;?></th>
        <td><?php echo html::input('yahoo', $user->yahoo, "class='form-control'");?></td>
        <th><?php echo $lang->kevinuser->gtalk;?></th>
        <td><?php echo html::input('gtalk', $user->gtalk, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->kevinuser->wangwang;?></th>
        <td><?php echo html::input('wangwang', $user->wangwang, "class='form-control'");?></td>
        <th><?php echo $lang->kevinuser->mobile;?></th>
        <td><?php echo html::input('mobile', $user->mobile, "class='form-control'");?></td>
      </tr>
       <tr>
        <th><?php echo $lang->kevinuser->phone;?></th>
        <td><?php echo html::input('phone', $user->phone, "class='form-control'");?></td>
        <th><?php echo $lang->kevinuser->address;?></th>
        <td><?php echo html::input('address', $user->address, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->kevinuser->zipcode;?></th>
        <td><?php echo html::input('zipcode', $user->zipcode, "class='form-control'");?></td>
      </tr>
    </table>

  </form>
</div>
<script lanugage='javascript'>

	function editUser() {
		var userID = "<?php echo $user->id;?>";
		$('#userform').attr('action', createLink('kevinuser', 'edit', 'userID=' + userID));
	}
</script>
<?php js::set('passwordStrengthList', $lang->kevinuser->passwordStrengthList)?>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
