<?php

class kevinbook extends control
{
	public function __construct() {
		parent::__construct();
		$this->loadModel('action');
	}
    /**
     * The default catalog counts when create. 
     */
    const NEW_CATALOG_COUNT = 5;

    /**
     * Index page, locate to browse default.
     * 
     * @access public
     * @return void
     */
    public function index($pageID = 1)
    {
        $recPerPage = !empty($this->config->site->bookRec) ? $this->config->site->bookRec : $this->config->kevinbook->recPerPage;
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal = 0, $recPerPage, $pageID);

        if(isset($this->config->kevinbook->index) and $this->config->kevinbook->index == 'list')
        {
            $this->view->title = $this->lang->kevinbook->list;
            $this->view->books = $this->kevinbook->getBookList($pager);
            $this->view->pager = $pager;
            $this->display();
        }
        else
        {
            if(isset($this->config->kevinbook->index) and $this->config->kevinbook->index != 'list')
            {
                $book = $this->kevinbook->getBookByID($this->config->kevinbook->index);
            }
            else
            {
                $book = $this->kevinbook->getFirstBook();
            }
            $this->locate(inlink('browse', "nodeID=$book->id", "kevinbook=$book->alias") . ($this->get->fullScreen ? "?fullScreen={$this->get->fullScreen}" : ''));
        }
    }

    /**
     * Browse a node of a kevinbook.
     * 
     * @param  int    $nodeID 
     * @access public
     * @return void
     */
    public function browse($nodeID)
    {
        $node = $this->kevinbook->getNodeByID($nodeID);
        if($node)
        {
            $nodeID = $node->id;
            $book = $this->kevinbook->getBookByNode($node);
            if(($this->config->kevinbook->chapter == 'left' or $this->config->kevinbook->fullScreen or $this->get->fullScreen) and $this->app->clientDevice == 'desktop') 
            {
                $families = $this->dao->select('id,parent,type,`order`')->from(TABLE_KEVIN_BOOK)
                    ->where('path')->like(",{$nodeID},%")
                    ->andWhere('addedDate')->le(helper::now())
                    ->andWhere('status')->eq('normal')
                    ->orderBy('`order`')
                    ->fetchGroup('parent', 'id');
                
                $allNodes = $this->dao->select('*')->from(TABLE_KEVIN_BOOK)
                    ->where('path')->like("%,{$nodeID},%")
                    ->andWhere('addedDate')->le(helper::now())
                    ->andWhere('status')->eq('normal')
                    ->fetchAll('id');
                $articles = $this->kevinbook->getArticleIdList($nodeID, $families, $allNodes);
                
                if($articles)
                {
                    $articles  = explode(',', $articles);
                    $articleID = current($articles);
                    $article   = zget($allNodes, $articleID);
                    $this->locate(inlink('read', "articleID=$articleID", "kevinbook=$book->alias&node=$article->alias") . ($this->get->fullScreen ? "?fullScreen={$this->get->fullScreen}" : ''));
                }
            }

            $serials = $this->kevinbook->computeSN($book->id);

            $this->view->title      = $book->title;
            $this->view->keywords   = trim(trim($node->keywords . ' - ' . $book->keywords), '-');
            $this->view->node       = $node;
            $this->view->kevinbook       = $book;
            $this->view->serials    = $serials;
            $this->view->books      = $this->kevinbook->getBookList();
            $this->view->catalog    = $this->kevinbook->getFrontCatalog($node->id, $serials);
            $this->view->allCatalog = $this->kevinbook->getFrontCatalog($book->id, $serials);
            $this->view->mobileURL  = helper::createLink('kevinbook', 'browse', "nodeID=$node->id", $book->id == $node->id ? "kevinbook=$book->alias" : "kevinbook=$book->alias&node=$node->alias", 'mhtml');
            $this->view->desktopURL = helper::createLink('kevinbook', 'browse', "nodeID=$node->id", $book->id == $node->id ? "kevinbook=$book->alias" : "kevinbook=$book->alias&node=$node->alias", 'html');
        }
        $this->display();
    }

    /**
     * Read an article.
     * 
     * @param  int    $articleID 
     * @access public
     * @return void
     */
    public function read($articleID = 1)
    {
    	if(empty($articleID) && isset($_GET['articleID'])) $articleID = $_GET['articleID'];
        $article = $this->kevinbook->getNodeByID($articleID);
        if(!$article) die($this->fetch('errors', 'index'));
        $book    = $article->kevinbook;
        $serials = $this->kevinbook->computeSN($book->id);
        $content = $this->kevinbook->addMenu($article->content);
        
        if($article->type != 'kevinbook')
        {        
            $parent  = $article->origins[$article->parent];
            $this->view->parent      = $parent;
            $this->view->prevAndNext = $this->kevinbook->getPrevAndNext($article);
        }
        $activeInfoLink = $article->type == 'kevinbook' ? 'activeBookInfo' : '';
        $this->view->bookInfoLink = html::a(inLink('read', "articleID=$book->id", "kevinbook=$book->alias&node=$article->alias"), $book->title . $this->lang->kevinbook->info, "class = $activeInfoLink");
        
        $this->view->title       = $article->title . ' - ' . $book->title;;
        $this->view->keywords    = trim(trim($article->keywords . ' - ' . $book->keywords), '-');
        $this->view->desc        = $article->summary;
        $this->view->article     = $article;
        $this->view->content     = $content;
	    $this->view->editor = $this->loadModel('user')->getByAccount($article->editor)->realname;
	    $this->view->files = $this->loadModel('file')->printFiles($article->files);
        $this->view->kevinbook            = $book;
        $this->view->allCatalog      = $this->kevinbook->getFrontCatalog($book->id, $serials);
        $this->view->mobileURL       = helper::createLink('kevinbook', 'read', "articleID=$article->id", "kevinbook=$book->alias&node=$article->alias", 'mhtml');
        $this->view->desktopURL      = helper::createLink('kevinbook', 'read', "articleID=$article->id", "kevinbook=$book->alias&node=$article->alias", 'html');
        $this->view->books           = $this->kevinbook->getBookList();

        $this->display();
    }

    /**
     * Admin a kevinbook or a chapter.
     * 
     * @params int    $nodeID
     * @access public
     * @return void
     */
    public function admin($nodeID = '')
    {
        if($nodeID) ($node = $this->kevinbook->getNodeByID($nodeID)) && $book = $node->kevinbook;
        if(!$nodeID or !$node) ($node = $book = $this->kevinbook->getFirstBook()) && $nodeID = $node->id;
        if(!$node) $this->locate(inlink('create'));
        $this->view->title    = $this->lang->kevinbook->common;
        $this->view->bookList = $this->kevinbook->getBookPairs();
        $this->view->kevinbook     = $book;
        $this->view->node     = $node;
        $this->view->catalog  = $this->kevinbook->getAdminCatalog($nodeID, $this->kevinbook->computeSN($book->id));
        
        $this->display();
    }

    /**
     * Create a kevinbook.
     *
     * @access public 
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $bookID = $this->kevinbook->createBook();
            if($bookID)  $this->send(array('result' => 'success', 'message'=>$this->lang->saveSuccess, 'locate' => inlink('admin', "bookID=$bookID")));
            if(!$bookID) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $this->view->title    = $this->lang->kevinbook->createBook;
        $this->view->bookList = $this->kevinbook->getBookPairs();
        $this->display(); 
    }

    /**
     * Manage catalog of a kevinbook or a chapter.
     *
     * @param  int    $node   the node to manage.
     * @access public
     * @return void
     */
    public function catalog($node)
    {
        if($_POST)
        {
            /* First I need to check alias. */
            $return = $this->kevinbook->checkAlias();
            if(!$return['result']) 
            {
                $message =  sprintf($this->lang->kevinbook->aliasRepeat, join(',', array_unique($return['alias'])));
                $this->send(array('result' => 'fail', 'message' => $message));
            }

            /* No error, save to database. */
            $result = $this->kevinbook->manageCatalog($node);
            if($result) $this->send(array('result' => 'success', 'message'=>$this->lang->saveSuccess, 'locate' => $this->post->referer . "#node" . $node));
            $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        unset($this->lang->kevinbook->typeList['kevinbook']);

        $this->view->title    = $this->lang->kevinbook->catalog;
        $this->view->node     = $this->kevinbook->getNodeByID($node);
        $this->view->children = $this->kevinbook->getChildren($node);
        $this->view->bookList = $this->kevinbook->getBookPairs();

        $this->display(); 
    }

    /**
     * Edit a kevinbook, a chapter or an article.
     *
     * @param int $nodeID
     * @access public
     * @return void
     */
    public function edit($nodeID)
    {
        if($_POST)
        {
            $result = $this->kevinbook->update($nodeID);
            if($result) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->post->referer . "#node" . $nodeID));
            $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $node = $this->kevinbook->getNodeByID($nodeID, false);
        $book = $node->kevinbook;

        $bookList   = $this->kevinbook->getBookPairs();
        $optionMenu = $this->kevinbook->getOptionMenu($book->id, $removeRoot = true);
        $families   = $this->kevinbook->getFamilies($node);
        foreach($families as $member) unset($optionMenu[$member->id]);

        $this->view->title      = $this->lang->edit . $this->lang->kevinbook->typeList[$node->type];
        $this->view->node       = $node;
        $this->view->optionMenu = $optionMenu;
        $this->view->bookList   = $bookList;
        $this->display();
    }

    /**
     * Delete a node.
     *
     * @param int $nodeID
     * @retturn void
     */
    public function delete($nodeID)
    {
        if($this->kevinbook->delete($nodeID)) $this->send(array('result' => 'success'));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }

    /**
     * sort books 
     * 
     * @access public
     * @return void
     */
    public function sort()
    {
        if($this->kevinbook->sort()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }


    /**
     * search articles of kevinbook
     *
     * @access protect
     * @return void
     */
    public function search($recTotal = 0, $recPerPage = 10, $pageID = 1, $searchWord = '')
    {
        $this->app->loadClass('pager');
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $articles = $this->dao->select('*')->from(TABLE_KEVIN_BOOK)
            ->where(1)
            ->beginIf($searchWord)
            ->andwhere('type', true)->eq('article')
            ->andWhere('title')->like("%{$searchWord}%")
            ->orWhere('keywords')->like("%{$searchWord}%")
            ->orWhere('content')->like("%{$searchWord}%")
            ->orWhere('summary')->like("%{$searchWord}%")
            ->markRight(1)
            ->fi()
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id'); 

        $this->view->title    = $this->lang->kevinbook->searchResults;
        $this->view->articles = $articles;
        $this->view->pager    = $pager;
        $this->view->bookList = $this->kevinbook->getBookPairs();

        $this->display();
    }

    /**
     * Setting.
     * 
     * @access public
     * @return void
     */
    public function setting()
    {
        if($_POST)
        {
            $data = new stdclass();
            $data->index      = $this->post->index;
            $data->fullScreen = $this->post->fullScreen;
            $data->chapter    = $this->post->fullScreen ? 'left' : $this->post->chapter;
            $this->loadModel('setting')->setItems('system.kevinbook', $data);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $books = $this->kevinbook->getBookPairs();

        $this->view->title     = $this->lang->kevinbook->setting; 
        $this->view->books     = array('list' => $this->lang->kevinbook->list) + $books;
        $this->view->firstBook = key($books);
        $this->view->bookList  = $this->kevinbook->getBookPairs();
        $this->display();
    }

    /**
     * Ajax get modules.
     * 
     * @param  int    $bookID 
     * @param  int    $nodeID 
     * @access public
     * @return void
     */
    public function ajaxGetModules($bookID, $nodeID = 0)
    {
        $node = '';
        if($nodeID) $node = $this->kevinbook->getNodeByID($nodeID, false);

        $optionMenu = $this->kevinbook->getOptionMenu($bookID, $removeRoot = true);
        if($node and $bookID == $node->kevinbook->id)
        {
            $families   = $this->kevinbook->getFamilies($node);
            foreach($families as $member) unset($optionMenu[$member->id]);
        }
        die(html::select('parent', $optionMenu, '', "class='form-control'"));
    }
}
