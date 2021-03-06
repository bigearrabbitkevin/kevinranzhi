<?php
/**
 * The admin browse view file of book module of chanzhiEPS.
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
<?php
$path = explode(',', $node->path);
js::set('path', $path);
js::set('confirmDelete', $lang->book->confirmDelete);
?>
<?php include './side.html.php';?>
<div class='col-md-10'>
  <div class='panel'>
    <div class='panel-heading'>
      <strong><i class='icon-book'></i> <?php echo $book->title;?></strong>
      <div class='panel-actions' style="float: right;margin-top: -6px;margin-right: -12px;">
        <form method='get' class='form-inline form-search ve-form' style="width: 300px;float: left;">
          <?php echo html::hidden('m', 'book');?>
          <?php echo html::hidden('f', 'search');?>
          <?php echo html::hidden('recTotal', isset($this->get->recTotal) ? $this->get->recTotal : 0);?>
          <?php echo html::hidden('recPerPage', isset($this->get->recPerPage) ? $this->get->recPerPage : 20);?>
          <?php echo html::hidden('pageID', isset($this->get->pageID) ? $this->get->pageID :  1);?>
          <div class='input-group'>
            <?php echo html::input('searchWord', $this->get->searchWord, "class='form-control search-query' placeholder='{$lang->book->inputArticleTitle}'");?>
            <span class='input-group-btn'>
              <?php echo html::submitButton($lang->book->search, 'btn btn-primary');?>
            </span>
          </div>
        </form>
        <?php commonModel::printLink('book', 'create', '', '<i class="icon-plus"></i> ' . $lang->book->createBook, "class='btn btn-primary' style='margin-left: 10px;'");?>
      </div>
    </div>
    <div class='panel-body'><div class='books'><?php echo $catalog;?></div></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
