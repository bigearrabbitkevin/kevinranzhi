<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinplan
 * @link       
 */

 include '../../../sys/common/view/header.modal.html.php';
 //没有$id等的情况的默认值设定
if(!$remote ){
	if($id) die('Please input correct id.');
	$remote = new stdclass();
	$remote->id = 0;
	$remote->type1 = '0';
	$remote->type2 = '0';
	$remote->realname = '';
	$remote->macname = '';
	$remote->ip = '';
	$remote->mactype = '';
	$remote->macaddress = '';
	$remote->order = '';
 }
 ?>

<div class='container mw-700px'>
    <div class="main">  
        <form class='form-condensed mw-700px' method='post' target='hiddenwin' id='dataform'>
            <table align='center' class='table table-form'> 
                <tr>
                    <th colspan="1"><?php echo $lang->kevinremote->type1;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::select('type1', $lang->kevinremote->type1List,$remote->type1, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                    <th colspan="1"><?php echo $lang->kevinremote->type2;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::select('type2', $lang->kevinremote->type2List,$remote->type2, "class='form-control chosen'");?></td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinremote->realname;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::input('realname',$remote->realname, "class='form-control' placeholder='" . $lang->kevinremote->realnamePlaceholder . "'");?></td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinremote->macname;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::input('macname',$remote->macname, "class='form-control' placeholder='" . $lang->kevinremote->macnamePlaceholder . "'");?></td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinremote->ip;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::input('ip',$remote->ip, "class='form-control' placeholder='" . $lang->kevinremote->ipPlaceholder . "'");?></td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinremote->mactype;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::input('mactype',$remote->mactype, "class='form-control' placeholder='" . $lang->kevinremote->mactypePlaceholder . "'");?></td>
                </tr>
				<tr>
                    <th colspan="1"><?php echo $lang->kevinremote->macaddress;?></th>
                    <td colspan="3"><div class="required-wrapper"></div><?php echo html::input('macaddress',$remote->macaddress , "class='form-control' placeholder='" . $lang->kevinremote->macaddressPlaceholder . "'");?></td>
                </tr>
                <tr>
                    <th colspan="1"><?php echo $lang->kevinremote->order;?></th>
                    <td colspan="3"><?php echo html::input('order',$remote->order, "class='form-control' placeholder='" . $lang->kevinremote->orderPlaceholder . "'");?></td>
                </tr>

                <tr><th colspan="2"></th><td><?php echo html::submitButton() . html::backButton();?></td></tr>
            </table>
        </form>

      <?php if ($func == "edit" & $remote->id)echo $this->fetch('action', 'history', "objectType=kevinremote&objectID={$remote->id}");?>   
    </div>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
