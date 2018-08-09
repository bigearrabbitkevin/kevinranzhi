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
$this->moduleName = "kevinuser";//第一级标签

?>
<?php include '../../common/view/header.html.php'; ?>
	<div class='main'>
		<div class="text-left">
			<div class='mw-800px text-left'>
				<form id='searchform' method="post" class='form-condensed' style="margin: 20px 0px;">
					<table width = 100%>
						<tr>
							<th class="nobr w-20px"><?php echo $lang->user->realname;?></th>
							<td style="max-width:100px;line-height: 40px;"><?php echo html::input("realname", isset($filter['realname']) ? $filter['realname'] : '', "class='form-control pull-right' ");?></td>
							<th class="nobr w-20px"><?php echo $lang->kevinuser->localname;?></th>
							<td style="max-width:100px;line-height: 40px;"><?php echo html::input("localname",  isset($filter['localname']) ? $filter['localname'] : '', "class='form-control pull-right' ");?></td>
							<th class="nobr w-20px"><?php echo $lang->kevinuser->remotename;?></th>
							<td style="max-width:100px;line-height: 40px;"><?php echo html::input("remotename",  isset($filter['remotename']) ? $filter['remotename'] : '', "class='form-control  pull-right' ");?></td>
							<td  class="text-center" colspan="2"><?php echo html::submitButton('搜索');?></td>
						</tr>
					</table>
				</form>
				<form class='form-condensed' method='post' target='hiddenwin'>
					<table align='center' class='table table-form' id='pwdList'>
						<thead>
						<tr class='text-center'>
							<th class='w-50px'><?php echo $lang->kevinuser->serialNum; ?></th>
							<th class='w-150px'><?php echo $lang->user->realname; ?></th>
							<th class='w-150px'><?php echo $lang->kevinuser->localname; ?></th>
							<th class='w-300px'><?php echo $lang->kevinuser->remotename; ?></th>
							<th class='w-80px'><?php echo $lang->actions; ?></th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i         = -10;
						$index     = 0;
						$pagid     = $pager->pageID;
						$indexRec  = ($pager->pageID - 1) * $pager->recPerPage + 1;
						foreach ($domainaccounts as $tempId => $user):$i += 1;
							$index += 1;
							?>
							<tr class='text-center'>
								<td class=' w-id'><?php
									echo $indexRec;
									$indexRec++;
									?></td>
								<td class='w-id'><?php echo $user->realname; ?></td>
								<td class='w-id'><?php echo $user->account.html::hidden("accountList[$user->id]", $user->account); ?></td>
								<td class=' w-id'>
									<?php echo html::input("domainFullAccount[$user->id]", $user->domainFullAccount, "class='form-control'").html::hidden("userIdList[$user->id]", $user->id); ?>
								</td>
								<td class='w-id'>
									<?php commonModel::printLink('kevinuser', 'deleteldapuser', "id=$user->id", $lang->delete, "data-type='iframe' data-toggle='modal' ");?>
								</td>
							</tr>
						<?php endforeach; ?>
						<?php if (count($domainaccounts)): ?>
							<tr>
								<td colspan='8' align='left'>
									<?php $pager->show(); ?>
								</td>
							</tr>
						<?php endif; ?>
						<?php
						for ($j = -10; $j < -6; $j++) {
							$index += 1;
							?>
							<tr class='text-center'>
								<td class='text-center'><i class="icon-plus-sign"></i></td>
								<td class=' w-id'>&nbsp;</td>
								<td class=' w-id'>
									<?php echo html::input("accountList[$j]", '', "class='form-control'"); ?>
								</td>
								<td class=' w-id'>
									<?php echo html::input("domainFullAccount[$j]", '', "class='form-control'").html::hidden("userIdList[$j]", $j); ?>
								</td>
							</tr>
						<?php } ?>
						<tr class='text-center'>
							<td colspan="8"><?php echo html::submitButton('',  'btn btn-default').html::backButton(); ?></td>
						</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
<?php include '../../common/view/footer.html.php'; ?>