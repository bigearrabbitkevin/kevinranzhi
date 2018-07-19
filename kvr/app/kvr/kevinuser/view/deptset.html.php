<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Kevin
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div class="text-left">
	<div class='mw-800px text-left'>
		<form class='form-condensed' method='post' target='hiddenwin'>
			<table align='center' class='table table-form' id='pwdList'>
				<thead>
					<tr class='text-center'>
						<th class='w-50px'><?php echo $lang->kevinuser->id; ?></th>
						<th class='w-50px'><?php echo $lang->kevinuser->realname; ?></th>
						<th class='w-150px'><?php echo $lang->kevinuser->account; ?></th>
						<th class='w-150px'><?php echo $lang->kevinuser->dept; ?></th>
						<th class='w-150px'><?php echo $lang->kevinuser->deptPrefer; ?></th>
						<th class='w-id'><?php echo $lang->actions; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i			 = -10;
					$index		 = 0;
					$pagid		 = $pager->pageID;
					$indexRec	 = ($pager->pageID - 1) * $pager->recPerPage + 1;
					foreach ($deptaccounts as $tempId => $user):$i+=1;
						$index+=1;
						?>
						<tr class='text-center'>
							<td class=' w-id'><?php
								echo $indexRec;
								$indexRec++;
								?></td>
							<td class='w-id'><?php echo $user->realname; ?></td>
							<td class='w-id'><?php echo $user->account . html::hidden("accountList[$user->id]", $user->account); ?></td>
							<td class='w-id'><?php echo $user->name. html::hidden("idList[$user->id]", $user->id);?></td>
							<td class='w-id'><?php echo html::input("deptPreferList[$user->id]", $user->deptPrefer, "class='form-control'"); ?></td>
							<td class='w-id'>
								deletedeptuser<?php //commonModel::printIcon('kevinuser', 'deletedeptuser', "id=$user->id", '', 'list', 'remove', 'hiddenwin'); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					<?php if (count($deptaccounts)): ?>			
						<tr>
							<td colspan='8' align='left'>
								<?php $pager->show(); ?>							
							</td>
						</tr>	
					<?php endif; ?>	
					<?php
					for ($j = -10; $j < -6; $j++) {
						$index+=1;
						?>
						<tr class='text-center'>
							<td class='text-center'><i class="icon-plus-sign"></i></td>
							<td class=' w-id'></td>
							<td class=' w-id'>
								<?php echo html::input("accountList[$j]", '', "class='form-control'"); ?>
							</td>
							<td class=' w-id'></td>
							<td class=' w-id'>
								<?php echo html::input("deptPreferList[$j]", '', "class='form-control'"). html::hidden("idList[$j]", $j); ?>
							</td>
						</tr>
					<?php } ?>
					<tr class='text-center'>
						<td colspan="8"><?php echo html::submitButton('', '', 'btn-primary') . html::backButton(); ?></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<?php include '../../common/view/footer.html.php'; ?>