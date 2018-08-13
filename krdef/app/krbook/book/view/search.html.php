<?php include '../../common/view/header.html.php';?>
<?php include './side.html.php';?>
<div class='col-md-10'>
  <div class='panel'>
    <div class='panel-heading'>
      <strong><i class='icon-book'></i> <?php echo $lang->book->searchResults;?></strong>
      <div class='panel-actions' style="float: right;margin-top: -6px;margin-right: -12px;">
        <form method='get' class='form-inline form-search'  style="width: 300px;float: left;">
          <?php echo html::hidden('m', 'book');?>
          <?php echo html::hidden('f', 'search');?>
          <?php echo html::hidden('recTotal', isset($this->get->recTotal) ? $this->get->recTotal : 0);?>
          <?php echo html::hidden('recPerPage', isset($this->get->recPerPage) ? $this->get->recPerPage : 10);?>
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
    <table class='table table-hover table-striped table-fixed tablesorter'>
      <thead>
        <tr class='text-center'>
          <th class='w-60px'><?php echo $lang->book->id;?></th>
          <th class='w-120px'><?php echo $lang->book->typeList['chapter'];?></th>
          <th class='w-200px'><?php echo $lang->book->title;?></th>
          <th class='w-120px'><?php echo $lang->book->keywords;?></th>
          <th class='w-60px'><?php echo $lang->book->author;?></th>
          <th class='w-60px'><?php echo $lang->book->views;?></th>
          <th class='w-150px'><?php echo $lang->book->addedDate;?></th>
          <th class='w-120px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($articles as $article):?>
        <tr class='text-center'>
          <td><?php echo $article->id;?></td>
          <td class='text-left'><?php echo $this->book->explodePath($article->path);?></td>
          <td class='text-left'><?php echo $article->title;?></td>
          <td><?php echo $article->keywords;?></td>
          <td><?php echo $article->author;?></td>
          <td><?php echo $article->views;?></td>
          <td><?php echo $article->addedDate;?></td>
          <td>
            <?php commonModel::printLink('book', 'edit', "nodeID=$article->id", $lang->edit);?>
            <?php commonModel::printLink('file', 'browse', "objectType=book&objectID=$article->id", $lang->book->files, "data-toggle=modal");?>
            <?php commonModel::printLink('book', 'delete', "nodeID=$article->id", $lang->delete, "class='deleter'");?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot><tr><td colspan='8'><?php $pager->show();?></td></tr></tfoot>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
