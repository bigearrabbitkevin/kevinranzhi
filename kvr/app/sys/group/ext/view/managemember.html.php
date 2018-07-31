<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managemember.html.php 4627 2013-04-10 05:42:20Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../../common/view/header.html.php';?>
<?php include '../../../common/view/treeview.html.php';?>
<?php 
//kevin. 检出部门的用户
$deptID     = $this->get->deptID;
$deptName  = '';
$deptusers = null;
$deptTree = null;
if($deptID) {
    $dept = $this->loadModel('kevindept', 'kvr')->getByID($deptID);
    $deptName = $dept ? $dept->name : '';
    //  $deptTree =  $this->kevindept->getTreeMenu($deptID,  $groupID);
    $deptusers = $this->dao->select('account,account as code')->from(TABLE_USER)->where('dept')->eq($deptID)->fetchPairs('account', 'code');
}
?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='GROUP'>  <strong><?php echo $group->id;?></strong></span>
    <strong><?php echo $group->name;?></strong>
    <small class='text-muted'> <?php echo $lang->group->manageMember;?> </small>
	<?php //显示部门的信息 .kevin
	if($deptName)echo ' ('.$deptName.')';?>
  </div>
</div>
<div class='row-table row-table-swap'>
  <div class="col-side">
    <div class='side-body'>
      <div class='panel panel-sm' >
        <div class='panel-heading nobr'>
	        <?php echo $treemenu; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-main">
    <form class='form-condensed pdb-20' method='post' id='ajaxForm'>
      <table align='center' class='table table-form'> 
        <?php if($groupUsers):?>
        <tr>
		<!--Start: 区分组内和组外语句。 kevin.-->
          <th class='w-100px' ><?php echo $lang->group->inside;?><?php echo html::selectAll('group', 'checkbox', true);?> </th>
          <td id='group' class='form-control ' size='10' style='height:200px;overflow:auto;' ><?php $i = 1;?>
			  <div class='w-80px' >
				<strong><?php echo '部门内人员';//echo '科室人员';?></strong><?php echo html::selectAll('indept', 'checkbox', true);?>
			  </div>
            <?php foreach($groupUsers as $account => $realname):	
				if(!in_array($account, $deptusers))continue; //忽略不在组织内的
			?>
			  <div class='group-item' id='indept'><?php echo html::checkbox('members', array($account => $realname), $account);?></div>
            <?php endforeach;?>
		<!--end: 区分组内和组外语句。 kevin.--> 
			<!--Start:添加非组内人员循环语句。 kevin.-->
			<br>
			  <div class='w-80px ' >
				<strong><?php echo '部门外人员';//echo '非科室人员';?></strong><?php echo html::selectAll('extdept', 'checkbox', true);?>
			  </div>
			 <?php foreach($groupUsers as $account => $realname):
				if(in_array($account, $deptusers))continue; //忽略在组织内的
			 ?>
			   <div class='group-item extdept' id='extdept'><?php echo html::checkbox('members', array($account => $realname), $account);?></div>
			 <?php endforeach;?>
 			<!--end: 添加非组内人员循环语句。 kevin.-->
         </td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-100px'><?php echo $lang->group->outside;?><?php echo html::selectAll('other','checkbox');?> </th>
          <td id='other' class='form-control' size='10' style='height:200px;overflow:auto;'>
	          <div class='w-80px' ><strong><?php echo '部门内成员';//echo '科室人员';?></strong><?php echo html::selectAll('indept', 'checkbox', '');?></div>
	          <?php foreach($groupNotInUsers as $account => $realname):?>
		          <div class='group-item' id='indept'><?php echo html::checkbox('members', array($account => $realname), '');?></div>

	          <?php endforeach;?>
	          <div class='w-80px' ><strong><?php echo '部门外成员';//echo '非科室人员';?></strong><?php echo html::selectAll('extdept', 'checkbox', '');?></div>
	          <?php $i = 1;?>
            <?php foreach($otherUsers as $account => $realname):?>
            <div class='group-item'><?php echo html::checkbox('members', array($account => $realname), '');?></div>
            <?php endforeach;?>
          </td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center'>
            <?php 
            echo html::submitButton();
            echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
            echo html::hidden('foo'); // Just a var, to make sure $_POST is not empty.
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

<?php include '../../../common/view/footer.html.php';?>
