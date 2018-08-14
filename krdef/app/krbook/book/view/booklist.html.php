<div class='col-md-3'>
	<div class='panel book-catalog'>
		<?php if (!empty($book) && $book->title): ?>
			<div class='panel-heading clearfix'>
				<div class='dropdown pull-left'>
					<a href='javascript:;' data-toggle='dropdown' class='dropdown-toggle'><strong><?php echo $book->title; ?></strong> <span class='caret'></span></a>
					<ul role='menu' class='dropdown-menu'>
						<?php foreach ($books as $bookMenu): ?>
							<li><?php echo html::a(inlink("browse", "id=$bookMenu->id", "book=$bookMenu->alias") . ($this->get->fullScreen ? "?fullScreen={$this->get->fullScreen}" : ''), $bookMenu->title); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class='pull-right home hide'><a href='/' title='<?php echo $lang->book->goHome; ?>'><i class='icon-home'></i></a></div>
			</div>
		<?php endif; ?>
		<div class='books'>
			<dl class='nav nav-primary nav-stacked'>
				<?php
				$title = $lang->book->list;
				if (null != $parent) {
					$title = html::a(helper::createLink('book', 'browse', "nodeID=$parent->id") . $fullScreen, $parent->title) . '－>' . $this->lang->book->chapterList;
				}
				?>
				<li ><strong><?php echo $title; ?></strong></li>
				<?php
				if (!empty($MenuItems)) {
					$iconType	 = '';
					$index		 = 1;
					foreach ($MenuItems as $menu) {
						if ('book' == $menu->type) {
							$iconType = "<i class='icon-book'></i> &nbsp";
							echo '<li' . (($menu->title == $book->title) ? " class='active'" : '') . '>'
							. html::a(inlink('browse', "nodeID=$menu->id") . $fullScreen
									, $index . " " . $iconType
									. $menu->title . '<i class="icon-chevron-right"></i>') . '</li>';
						} else if ('chapter' == $menu->type) {
							$iconType = "<i class='icon-list-ul'></i> &nbsp";
							echo '<li' . (($menu->title == $node->title) ? " class='active'" : '') . '>'
							. html::a(inlink('browse', "nodeID=$menu->id") . $fullScreen
									, $index . " " . $iconType
									. $menu->title . '<i class="icon-chevron-right"></i>') . '</li>';
						} else {
							$iconType = "<i class='icon-file-text'></i> &nbsp";
							echo '<li' . (($menu->title == $node->title) ? " class='active'" : '') . '>'
							. html::a(inlink('read', "articleID=$menu->id") . $fullScreen
									, $index . " " . $iconType
									. $menu->title . '<i class="icon-chevron-right"></i>') . '</li>';
						}
						$index++;
					}
				}
				?>
			</dl>
		</div>
	</div>
</div>