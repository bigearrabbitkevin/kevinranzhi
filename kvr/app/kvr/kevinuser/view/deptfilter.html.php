<form id='searchform' method="post" class='form-condensed'>
    <table width = 100%>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->dept;?></th>
            <td style="max-width:160px"><?php echo html::input("path", $path, "class='form-control'");?></td>
        </tr>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->manager;?></th>
            <td style="max-width:160px"><?php echo html::input("manager", $manager, "class='form-control'");?></td>
        </tr>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->deptgroup;?></th>
            <td style="max-width:120px"><label class="checkbox-inline"><input name="group" value="1" id="group" type="checkbox" <?php if(!empty($group)):?> checked="checked"<?php endif;?>><?php echo $lang->kevinuser->hasdeptgroup;?></label></td>
        </tr>
        <tr>
            <th class="nobr w-20px"><?php echo $lang->kevinuser->delete;?></th>
            <td style="max-width:120px"><label class="checkbox-inline"><input name="deleted" value="1" id="deleted" type="checkbox" <?php if(!empty($deleted)):?> checked="checked"<?php endif;?>><?php echo $lang->kevinuser->deleted;?></label></td>
        </tr>
        <tr>
            <td  class="text-center" colspan="2"><?php echo html::submitButton('搜索');?></td>
        </tr>
    </table>	
</form>