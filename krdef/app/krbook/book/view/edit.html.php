<?php
/**
 * The edit book view file of book of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV1.2 (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai<daitingting@xirangit.com>
 * @package     book
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php include '../../../sys/common/view/kindeditor.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php 
$path = explode(',', $node->path);
js::set('path', $path);
js::set('nodeID', $node->id);
js::set('nodeParent', $node->parent);

$linkChecked = $node->link ? checked : '';
?>
<?php include './side.html.php';?>
<div class='col-md-10'>
  <div class='panel'>
    <div class='panel-heading'>
      <strong><i class='icon-edit'></i> <?php echo $lang->edit . $lang->book->typeList[$node->type];?></strong>
    </div>
    <div class='panel-body'>
      <form id='ajaxForm' method='post' action='<?php echo inlink('edit', "nodeID=$node->id")?>'>
        <table class='table table-form'>
          <tr id='isLinked'>
            <th class='col-xs-1 w-100px' style="float: right;line-height: 30px;"><?php echo $lang->book->author;?></th>
            <td colspan='2'><?php echo html::input('author', $node->author, "class='form-control'");?></td>
          </tr>
          <?php if($node->type != 'book'):?>
          <tr>
            <th class='col-xs-1'><?php echo $lang->book->common;?></th>
            <td class='w-p40'><?php echo html::select('book', $bookList, $node->book->id, "class='chosen form-control'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->book->parent;?></th>
            <td id='parentBox'><?php echo html::select('parent', $optionMenu, $node->parent, "class='chosen form-control'");?></td>
          </tr>
          <?php endif; ?>
          <tr>
            <th><?php echo $lang->book->title;?></th>
            <td colspan='2'>
              <div class='required required-wrapper'></div>  
              <div class='row order'>
                <div class="col-sm-<?php echo $node->type == 'book' ? '9' : '12';?>">
                  <div class="input-group">
                    <?php echo html::input('title', $node->title, 'class="form-control"');?>
                    <?php if($node->type != 'book'):?>
                    <span class="input-group-addon">              
                      <label class='checkbox-inline'>
                      <input type="checkbox" name="isLink" id="isLink" autocomplete="off" <?php echo $linkChecked; ?>><span><?php echo $lang->book->isLink;?></span>
                      </label>
                    </span>
                    <?php endif;?>
                  </div>
                </div>
                <?php if($node->type == 'book'):?>
                <div class='col-sm-3 order'>
                  <div class='input-group'>
                    <span class="input-group-addon"><?php echo $lang->book->order;?></span>
                    <?php echo html::input('order', $node->order, "class='form-control'");?>
                  </div>
                </div>
                <?php endif;?>
              </div>
            </td>
          </tr>
          <tr class= 'trlink hide' id='trlink'>
            <th><?php echo $lang->book->link;?></th>
            <td colspan='2'>
              <div class='required required-wrapper'></div>
              <?php echo html::input('link', $node->link, "class='form-control' placeholder='{$lang->book->note->link}'");?>
            </td>
          </tr>
          <tr id='isLinked'>
            <th><?php echo $lang->book->alias;?></th>
            <td colspan='2'>
              <?php if($node->type == 'book'):?>
              <div class='required required-wrapper'></div>
              <?php endif;?>
              <div class='input-group text-1'>
                <span class='input-group-addon'>http://<?php echo $this->server->http_host . $config->webRoot?>book/id@</span>
                <?php echo html::input('alias', $node->alias, "class='form-control' placeholder='{$lang->book->alias}'");?>
                <span class='input-group-addon'>.html</span>
              </div>
            </td>
          </tr>
          <tr id='isLinked'>
            <th><?php echo $lang->book->keywords;?></th>
            <td colspan='2'><?php echo html::input('keywords', $node->keywords, "class='form-control'");?></td>
          </tr>
          <tr id='isLinked'>
            <th><?php echo $lang->book->summary;?></th>
            <td colspan='2'><?php echo html::textarea('summary', $node->summary, "class='form-control' rows='2'");?></td>
          </tr>
          <tr id='isLinked'>
            <th><?php echo $lang->book->content;?></th>
            <td colspan='2' valign='middle'><?php echo html::textarea('content', htmlspecialchars($node->content), "rows='15' class=''");?></td>
          </tr>
          <?php if($node->type == 'article'):?>
          <tr id='isLinked'>
            <th><?php echo $lang->book->addedDate;?></th>
            <td>
              <div class="input-append date">
                <?php echo html::input('addedDate', formatTime($node->addedDate), "class='form-control'");?>
                <span class='add-on'><button class="btn btn-default" type="button"><i class="icon-calendar"></i></button></span>
              </div>
            </td>
            <td><span class="help-inline"><?php echo $lang->book->note->addedDate;?></span></td>
          </tr>
          <?php endif;?>
          <?php if($node->type != 'book'):?>
          <tr id='isLinked'>
            <th><?php echo $lang->book->status;?></th>
            <td><?php echo html::radio('status', $lang->book->statusList, $node->status);?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th></th>
            <td><?php echo html::submitButton() . html::hidden('referer', $this->server->http_referer);?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
