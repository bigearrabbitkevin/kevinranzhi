<?php
/**
 * The read view file of book module of chanzhiEPS.
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

$fullScreen = $this->book->fullScreen;
if (!empty($this->config->book->fullScreen) or $this->get->fullScreen):
	?>
	<?php js::set('objectType', 'book'); ?>
	<?php js::set('objectID', $article->id); ?>
	<?php js::set('fullScreen', 1); ?>
	<div class='fullScreen-book'>
		<div class='fullScreen-content panel'>
			<div class='fullScreen-inner'>
				<?php $this->book->printPositionBar($article->origins); ?>
				<header>
					<h2><?php echo html::a(inlink('read', "articleID=$article->id") . "?fullScreen=0", "<i class='icon icon-resize-small icon-2x'></i> "); ?>
						<?php echo $article->title; ?></h2>
					<dl class='dl-inline'>
						<dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblAddedDate, formatTime($article->addedDate)); ?>'><i class='icon-time icon-large'></i> <?php echo formatTime($article->addedDate); ?></dd>
						<dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblAuthor, $article->author); ?>'><i class='icon-user icon-large'></i> <?php echo $article->author; ?></dd>
						<dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblViews, $article->views); ?>'><i class='icon-eye-open'></i> <?php echo $article->views; ?></dd>
						<?php if ($article->editor): ?>
							<dd data-toggle='tooltip' data-placement='top' ><i class='icon-edit icon-large'></i><?php printf($lang->book->lblEditor, $this->loadModel('user')->getByAccount($article->editor)->realname, formatTime($article->editedDate)); ?></dd>
						<?php endif; ?>
					</dl>
					<?php if ($article->summary): ?>
						<section class='abstract'><strong><?php echo $lang->book->summary; ?></strong><?php echo $lang->colon . $article->summary; ?></section>
					<?php endif; ?>
				</header>
				<section class='article-content'>
					<?php
					echo $content;
					?>
				</section>
				<section><?php $this->loadModel('file')->printFiles($article->files); ?></section>
				<section>
					<?php if ($article->keywords): ?>
						<p class='small'><strong class='text-muted'><?php echo $lang->book->keywords; ?></strong><span class='article-keywords'><?php echo $lang->colon . $article->keywords; ?></span></p>
					<?php endif; ?>
					<?php extract($prevAndNext); ?>
					<ul class='pager pager-justify'>
						<?php if ($prev): ?>
							<li class='previous'><?php echo html::a(inlink('read', "articleID=$prev->id") . $fullScreen, "<i class='icon-arrow-left'></i> " . $prev->title); ?></li>
						<?php else: ?>
							<li class='previous disabled'><a href='###'><i class='icon-arrow-left'></i> <?php print($lang->book->none); ?></a></li>
						<?php endif; ?>
						<li class='back'><?php echo html::a(inlink('browse', "bookID={$parent->id}") . $fullScreen, "<i class='icon-list-ul'></i> " . $lang->book->chapter); ?></li>
						<?php if ($next): ?>
							<li class='next'><?php echo html::a(inlink('read', "articleID=$next->id") . $fullScreen, $next->title . " <i class='icon-arrow-right'></i>"); ?></li>
						<?php else: ?>
							<li class='next disabled'><a href='###'> <?php print($lang->book->none); ?><i class='icon-arrow-right'></i></a></li>
						<?php endif; ?>
					</ul>
					<br>
				</section>
				<?php if (commonModel::isAvailable('message')): ?>
					<div id='commentBox'></div>
				<?php endif; ?>
				<div class='blocks' data-region='book_read-bottom'><?php //$this->block->printRegion($layouts, 'book_read', 'bottom'); ?></div>
			</div>
		</div>
	</div>
	<?php include TPL_ROOT . 'common/jplayer.html.php'; ?>
	<?php if ($config->debug) js::import($jsRoot . 'jquery/form/min.js'); ?>
	<?php if (isset($pageJS)) js::execute($pageJS); ?>
	</body>
	</html>
<?php else: ?>
	<?php js::set('objectType', 'book'); ?>
	<?php js::set('objectID', $article->id); ?>
	<div class='row blocks' data-region='book_read-top'><?php //$this->block->printRegion($layouts, 'book_read', 'top', true); ?></div>
	<?php $this->book->printPositionBar($article->origins); ?>
	<?php if ($this->config->book->chapter == 'left'): ?>
		<div class='row'>
			<?php include 'bookList.html.php'; ?>
			<div class='col-md-9'>
			<?php endif; ?>
			<div class='article book-content' id='book' data-id='<?php echo $article->id ?>'>
				<header>
					<h2><?php echo html::a(inlink('read', "articleID=$article->id") . "?fullScreen=1", "<i class='icon icon-resize-full icon-2x'></i> "); ?>
						<?php echo $article->title; ?></h2>
					<dl class='dl-inline'>
						<dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblAddedDate, formatTime($article->addedDate)); ?>'><i class='icon-time icon-large'></i> <?php echo formatTime($article->addedDate); ?></dd>
						<dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblAuthor, $article->author); ?>'><i class='icon-user icon-large'></i> <?php echo $article->author; ?></dd>
						<dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblViews, $article->views); ?>'><i class='icon-eye-open'></i> <?php echo $article->views; ?></dd>
						<?php if ($article->editor): ?>
							<dd data-toggle='tooltip' data-placement='top' ><i class='icon-edit icon-large'></i><?php printf($lang->book->lblEditor, $this->loadModel('user')->getByAccount($article->editor)->realname, formatTime($article->editedDate)); ?></dd>
						<?php endif; ?>
					</dl>
					<?php if ($article->summary): ?>
						<section class='abstract'><strong><?php echo $lang->book->summary; ?></strong><?php echo $lang->colon . $article->summary; ?></section>
					<?php endif; ?>
				</header>
				<section class='article-content'>
					<?php	echo $content;	?>
				</section>
				<section><?php $this->loadModel('file')->printFiles($article->files); ?></section>
				<footer>
					<?php if ($article->keywords): ?>
						<p class='small'><strong class='text-muted'><?php echo $lang->book->keywords; ?></strong><span class='article-keywords'><?php echo $lang->colon . $article->keywords; ?></span></p>
					<?php endif; ?>
					<?php extract($prevAndNext); ?>
					<ul class='pager pager-justify'>
						<?php if ($prev): ?>
							<li class='previous'><?php echo html::a(inlink('read', "articleID=$prev->id") . $fullScreen, "<i class='icon-arrow-left'></i> " . $prev->title); ?></li>
						<?php else: ?>
							<li class='previous disabled'><a href='###'><i class='icon-arrow-left'></i> <?php print($lang->book->none); ?></a></li>
						<?php endif; ?>
						<li class='back text-center'><?php echo html::a(inlink('browse', "nodeID=$article->parent") . $fullScreen, "<i class='icon-arrow-left'></i> " . $lang->book->chapter); ?></li>
						<?php if ($next): ?>
							<li class='next'><?php echo html::a(inlink('read', "articleID=$next->id") . $fullScreen, $next->title . " <i class='icon-arrow-right'></i>"); ?></li>
						<?php else: ?>
							<li class='next disabled'><a href='###'> <?php print($lang->book->none); ?><i class='icon-arrow-right'></i></a></li>
						<?php endif; ?>
					</ul>
				</footer>
				<?php if (commonModel::isAvailable('message')): ?>
					<div id='commentBox'></div>
				<?php endif; ?>
			</div>
			<div class='blocks' data-region='book_read-bottom'><?php //$this->block->printRegion($layouts, 'book_read', 'bottom'); ?></div>
			<?php if ($this->config->book->chapter == 'left'): ?>
			</div>
		</div>
	<?php endif; ?>
	<?php include TPL_ROOT . 'common/jplayer.html.php'; ?>
<?php endif; ?>

<?php include '../../common/view/footer.html.php';?>