<?php
/**
 * The create book view file of book of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV1.2 (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai<daitingting@xirangit.com>
 * @package     book
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php include '../../common/view/header.admin.html.php'; ?>
<?php
if(isset($node)) $common->printPositionBar($node->origins)} {/if}

{!js::set('fullScreen', (!empty($control->config->book->fullScreen) or $control->get->fullScreen) ? 1 : 0)}
?>
<div class='row blocks' data-region='book_browse-topBanner'>{$control->block->printRegion($layouts, 'book_browse', 'topBanner', true)}</div>
<div class='panel' id='bookCatalog' data-id='{if(isset($node))}{$node->id}{/if}'>
  {if(!empty($book) && $book->title)}
  <div class='panel-heading clearfix'>
    <div class='dropdown'>
      <a data-toggle='dropdown' class='dropdown-toggle' href='javascript:;'><strong>{$book->title}</strong> <span class='caret'></span></a>
      <ul role='menu' class='dropdown-menu'>
        {foreach($books as $bookMenu)}
        <li>{!html::a(inlink("browse", "id=$bookMenu->id", "book=$bookMenu->alias") . ($control->get->fullScreen ? "?fullScreen={{$control->get->fullScreen}}" : ''), $bookMenu->title)}</li>
        {/foreach}
      </ul>
    </div>
  </div>
  {/if}
  <div class='panel-body'>
    <div class='books'>{if(!empty($catalog))} {$catalog} {/if}</div>
  </div>
</div>
<div class='row blocks' data-region='book_browse-bottomBanner'>{$control->block->printRegion($layouts, 'book_browse', 'bottomBanner', true)}</div>

<?php include '../../common/view/footer.html.php';?>
