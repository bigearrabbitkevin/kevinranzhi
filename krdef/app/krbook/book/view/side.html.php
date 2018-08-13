<div class='col-md-2'>
  <div class="leftmenu affix hiddden-xs hidden-sm" style="width: 15%;">
    <div class='panel book-list'>
      <div class='panel-body'>
        <ul class='tree tree-lines' data-idx='0'>
          <?php foreach($bookList as $bookID => $bookTitle):?> 
          <li data-idx='1' data-id="<?php echo $bookID;?>"><?php echo html::a($this->createLink('book', 'admin', "bookID=$bookID"), $bookTitle);?></li>
          <?php endforeach;?>
        </ul>
        <div class='text-right'>
          <?php commonModel::printLink('book', 'create', '', $lang->book->create);?>
          <?php commonModel::printLink('book', 'setting', '', $lang->book->setting);?>
        </div>
      </div>
    </div>
  </div>
</div>
