<?php include '../../common/view/header.html.php';?>
<?php
   js::set('objectType', 'book');
   js::set('objectID', $article->id);
   ?>
<!--  <div class='row blocks' data-region='book_read-top'>--><?php //$control->block->printRegion($layouts, 'book_read', 'top', true) ?><!--</div>-->
<!--  --><?php //$common->printPositionBar($article->origins); ?>
  <div class='row'>
    <div class='col-md-3'>
      <div class='panel book-catalog bookScrollListsBox'>
        <?php if(!empty($book) && $book->title){ ?>
          <div class='panel-heading clearfix'>
            <div class='dropdown pull-left'>
            <a href='javascript:;' data-toggle='dropdown' class='dropdown-toggle'><i class="icon icon-book"></i><strong><?php echo $book->title ?></strong> <span><?php echo $lang->book->more ?><i class='icon icon-caret-down'></i></span></a>
              <ul role='menu' class='dropdown-menu'>
                <?php foreach($books as $bookMenu) { ?>
                  <li><?php echo html::a(inlink("browse", "id=$bookMenu->id", "book=$bookMenu->alias") . ($control->get->fullScreen ? "?fullScreen=$control->get->fullScreen" : ''), $bookMenu->title); ?></li>
                <?php } ?>
              </ul>
            </div>
            <div class='pull-right home hide'><a href='/' title='<?php echo $lang->book->goHome ?>'><i class='icon-home'></i></a></div>
          </div>
        <?php } ?>
        <div class='panel-body'>
          <div class='books'>
            <?php if(!empty($bookInfoLink) and !empty($book->content)){
            	echo "<span id='bookInfoLink'>" . $bookInfoLink . "</span>";
            }
            if(!empty($allCatalog)){
            	echo $allCatalog;
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class='col-md-9'>

  <div class='article book-content' id='book' data-id='{$article->id}'>
    <header>
      <h2><?php echo $article->title; ?></h2>
      <dl class='dl-inline'>
        <dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblAddedDate, formatTime($article->addedDate))?>'><i class='icon-time icon-large'></i> <?php echo formatTime($article->addedDate) ?></dd>
        <dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblAuthor, $article->author) ?>'><i class='icon-user icon-large'></i> <?php echo $article->author ?></dd>
        <dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->book->lblViews, $article->views) ?>'><i class='icon-eye-open'></i> <?php echo $article->views ?></dd>
        <?php if($article->editor) { ?>
        <dd data-toggle='tooltip' data-placement='top' ><i class='icon-edit icon-large'></i><?php printf($lang->book->lblEditor, $editor, formatTime($article->editedDate)) ?></dd>
        <?php } ?>
      </dl>
      <?php if($article->summary and $article->type != 'book'){ ?>
        <section class='abstract'><strong><?php echo $lang->book->summary; ?></strong><?php echo $lang->colon . $article->summary; ?></section>
      <?php } ?>
    </header>
    <section class='article-content'>
      <?php if(isset($content) and $article->type != 'book') {
      	echo $content;
      }
      if($article->type == 'book') {
      	echo $article->content;
      }
      ?>
  
    </section>
    <section><?php echo $files; ?></section>
    <footer>
      <?php if($article->keywords){ ?>
        <p class='small'><strong class='text-muted'><?php echo $lang->book->keywords ?></strong><span class='article-keywords'><?php echo $lang->colon . $article->keywords ?></span></p>
      <?php }
      if(isset($prevAndNext)){
        @extract($prevAndNext);
        ?>
        <ul class='pager pager-justify'>
          <?php if($prev){?>
            <li class='previous' title='<?php echo $prev->title ?>'><?php html::a(inlink('read', "articleID=$prev->id", "book=$book->alias&node=$prev->alias") . ($control->get->fullScreen ? "?fullScreen=$control->get->fullScreen" : ''), "<i class='icon-arrow-left'></i> <span>" . $prev->title . '</span>')?></li>
          <?php }else {?>
            <li class='previous disabled'><a href='###'><i class='icon-arrow-left'></i> <?php print($lang->book->none)?></a></li>
          <?php } ?>
          <?php if($control->config->book->chapter == 'home' or !$control->get->fullScreen) ?>
            <li class='back'> <?php html::a(inlink('browse', "bookID=$parent->id", "book=$book->alias&title=$parent->alias") . ($control->get->fullScreen ? "?fullScreen=$control->get->fullScreen" : ''), "<i class='icon-list-ul'></i> " . $lang->book->chapter) ?> </li>
          <?php }
          if($next){ ?>
            <li class='next' title='<?php echo $next->title?>'><?php html::a(inlink('read', "articleID=$next->id", "book=$book->alias&node=$next->alias") . ($control->get->fullScreen ? "?fullScreen=$control->get->fullScreen" : ''), '<span>' . $next->title . "</span> <i class='icon-arrow-right'></i>") ?></li>
          <?php }else{ ?>
            <li class='next disabled'><a href='###'> <?php print($lang->book->none)?><i class='icon-arrow-right'></i></a></li>
          <?php } ?>
        </ul>
      <?php  ?>
    </footer>
  </div>
<!--  --><?php //if(commonModel::isAvailable('message')){
//  	echo "<div id='commentBox'>" . $control->fetch('message', 'comment', "objectType=book&objectID=$article->id") . "</div>";
//  }?>
<!--  <div class='blocks' data-region='book_read-bottom'>--><?php //$control->block->printRegion($layouts, 'book_read', 'bottom') ?><!--</div>-->
  <?php if($control->config->book->chapter == 'left'){ ?>
    </div>
  </div>
  <?php }
//  include TPL_ROOT . 'common/video.html.php';
//  include $control->loadModel('ui')->getEffectViewFile('default', 'common', 'footer');
   ?>

<?php include '../../common/view/footer.html.php';?>