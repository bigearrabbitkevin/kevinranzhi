<?php
/**
 * The index view file of book module of chanzhiEPS.
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
$fullScreen		 = $this->book->fullScreen;
?>
<div class='row'>
  <?php foreach($books as $book) :?>
  <div class='col-xs-6 col-sm-4 col-md-3'>
    <div class='card'>
      <div class='card-heading text-center'>
        <?php echo html::a(helper::createLink('book', 'browse', "nodeID=$book->id", "book=$book->alias") . ($fullScreen ? "?fullScreen={$fullScreen}" : ''), $book->title);?>
      </div>
      <div class='card-content text-muted'><?php echo $book->summary;?></div>
      <div class='card-actions'>
        <span class='text-muted'><i class='icon-user'></i> <?php echo $book->author;?></span>
        <span class='text-muted'><i class='icon-time'></i> <?php echo $book->addedDate;?></span>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  <?php if($pager->pageTotal > 1):?>
  <div class='col-xs-12 col-sm-12 col-md-12 pull-left'><?php $pager->show('right', 'short');?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
