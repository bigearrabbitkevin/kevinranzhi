<?php
/**
 * The browse view file of book module of chanzhiEPS.
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
if (!empty($this->config->book->fullScreen) or $this->get->fullScreen) include TPL_ROOT . 'common/header.lite.html.php';
else  include TPL_ROOT . 'common/header.html.php';
	?>
<?php js::set('fullScreen', (!empty($this->config->book->fullScreen) or $this->get->fullScreen) ? 1 : 0);?>

<?php
$stateSubBook	 = ('true' == $this->book->isShowSubBook) ? " checked ='checked'" : "";
$stateContent		 = ('true' == $this->book->isShowContent) ? " checked ='checked'" : "";
?>
<div class='page-content' >
	<?php if ( ! $this->get->fullScreen) {
		if (isset($node)) $this->book->printPositionBar($node->origins);
		include 'booklist.html.php';
	}?>
	<form class='form-condensed' method='post' id='checkBoxForm'>
		<div id='content' class='col-md-9'>
			<div class='panel panel-block'>
				<?php if ($this->get->fullScreen && isset($node)) $this->book->printPositionBar($node->origins);?>
				<?php if (!empty($book) && $book->title): ?>
					<div class='panel-heading'>
						<?php
						if ($this->get->fullScreen)echo html::a(inlink('browse', "nodeID=$node->id"), "<i class='icon icon-resize-small icon-2x'></i> ");
						else echo html::a(inlink('browse', "nodeID=$node->id")."?fullScreen=1", "<i class='icon icon-resize-full icon-2x'></i> ");
						?>
						<strong class='title'><?php echo $book->title; ?></strong>
						<?php if ('chapter' == $node->type): ?>
							<div class='isShowSubBook pull-right'><?php
								echo html::checkbox('isShowSubBook', $this->lang->book->showQuote
										, 'checked', "$stateSubBook onchange='submitCheckBox();' 'class='form-control'");
								echo html::hidden('postSubBookCheckBox', '');
								?></div>
							<div class='isShowContent  pull-right'><?php
								echo html::checkbox('isShowContent', $this->lang->book->showContent
										, 'checked', "$stateContent onchange='submitCheckBox();' 'class='form-control'");
								echo html::hidden('postContentCheckBox', '');
								?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class='panel-body'>
					<div  class='books'><?php if (!empty($catalog)) echo $catalog; ?></div>
				</div>
			</div>
		</div>
	</form>
</div>
<?php include '../../common/view/footer.html.php';?>
<script>
	function submitCheckBox(switcher)
	{
		if (document.getElementById('isShowSubBook1').checked)
			document.getElementById("postSubBookCheckBox").value = 'true';
		else
			document.getElementById("postSubBookCheckBox").value = 'false';
		if (document.getElementById('isShowContent1').checked)
			document.getElementById("postContentCheckBox").value = 'true';
		else
			document.getElementById("postContentCheckBox").value = 'false';
		$("#checkBoxForm").attr('target', '_self');//不打开新的标签页 如果打开新的标签页则设置属性 $("#checkBoxForm").attr('target', '_blank');
		document.getElementById("checkBoxForm").submit();
	}
</script>
