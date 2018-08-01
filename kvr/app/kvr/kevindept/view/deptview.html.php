<?php
/**
 * The create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<style>
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        cursor: not-allowed;
        background-color: #fff;
    }
</style>
<div class='container mw-700px' style="border: none;">
    <div class="main">
        <fieldset>
            <legend>
                <h3><?php echo $lang->kevinuser->deptview;?></h3>
            </legend>
            <table align='center' class='table table-form'>
                <tr>
                    <th><?php echo $lang->kevinuser->id;?></th>
                    <td><?php echo $dept->id;?></td>
                    <th><?php echo $lang->kevinuser->guid;?></th>
                    <td><?php echo $dept->guid;?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->deptName;?></th>
                    <td><?php echo $dept->name;?></td>
                    <th><?php echo $lang->kevinuser->deptParent;?></th>
                    <td><?php echo!empty($dept->parentName) ? $dept->parentName : $lang->kevinuser->topParent;?>(<?php echo $dept->parent;?>)</td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->deptPath;?></th>
                    <td><?php echo $optionMenu[$dept->parent];?></td>
                    <th><?php echo $lang->kevinuser->manager;?></th>
                    <td><?php echo $accounts[$dept->manager];?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->email;?></th>
                    <td><?php echo $dept->email;?></td>
                    <th><?php echo $lang->kevinuser->code;?></th>
                    <td> <?php echo $dept->code;?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->kevinuser->deptgroup;?></th>
                    <td>
						<?php
						$groupArray = explode(',', trim($dept->group, ','));
						foreach ($groupArray as $group)
							if(isset ($groups[$group])) echo $groups[$group].',';
						?>
					</td>
                    <th><?php echo $lang->kevinuser->order;?></th>
                    <td><?php echo $dept->order;?></td>
                </tr>

                <tr><th></th><td><?php echo html::backButton();?></td></tr>
            </table>
        </fieldset>
<?php include '../../common/view/action.html.php';?>
    </div>

</div>
<?php include '../../common/view/footer.html.php';?>
