<?php
/**
 * The control file of krgoods module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
class krgoods extends control
{
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
    }

    /**
     * index page of krgoods module.
     * 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function index($status = 'involved', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $users = $this->loadModel('user')->getPairs('noclosed');
        $this->config->krgoods->search['actionURL'] = $this->createLink('krgoods', 'index', "status=bysearch");
        $this->config->krgoods->search['params']['t1.createdBy']['values'] = $users;
        $this->config->krgoods->search['params']['t2.account']['values']   = $users;
        $this->search->setSearchParams($this->config->krgoods->search);

        /* Save session. */
        $this->app->session->set('projectList',  $this->app->getURI(true));

        $this->view->title    = $this->lang->krgoods->common;
        $this->view->status   = $status;
			
		$this->view->goodsItems = $this->krgoods->getList($status, $orderBy, $pager);
        $this->view->users    = $users;
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
        $this->display();
    }

    /**
     * create a krgoods.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $id = $this->krgoods->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('krgoods', $id, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('task', 'browse', "id={$id}")));
        }

        $this->view->title  = $this->lang->krgoods->create;
        $this->view->users  = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * Edit krgoods. 
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $this->checkPriv($id);

        if($_POST)
        {
            $changes  = $this->krgoods->update($id);
            $actionID = $this->loadModel('action')->create('krgoods', $id, 'Edited');
            if($changes) $this->action->logHistory($actionID, $changes);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title   = $this->lang->krgoods->edit;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->view->krgoods = $this->krgoods->getByID($id);
        $this->view->groups  = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * View a krgoods.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function view($id = 0)
    {
        $krgoods = $this->krgoods->getByID($id);

        $this->view->title   = $this->lang->krgoods->view . $this->lang->colon . $krgoods->name;
        $this->view->groups  = $this->loadModel('group')->getPairs();
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->view->krgoods = $krgoods;
        $this->display();
    }

    /**
     * Finish krgoods.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function finish($id) 
    {
        $this->checkPriv($id);

        if($_POST)
        {
            $changes = $this->krgoods->finish($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('krgoods', $id, 'Finished', $this->post->comment);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $krgoods = $this->krgoods->getByID($id);

        $this->view->title     = $krgoods->name;
        $this->view->id = $id;
        $this->view->krgoods   = $krgoods;
        $this->display();
    }

    /**
     * Delete a krgoods.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->checkPriv($id, '', 'json');
        $this->krgoods->delete(TABLE_PROJECT, $id);
        $this->krgoods->deleteTasks($id);
        $this->krgoods->deleteDoclib($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }


    /**
     * Check krgoods privilege and locate index if no privilege. 
     * 
     * @param  int    $projectID 
     * @param  string $action 
     * @param  string $errorType   html|json
     * @access private
     * @return void
     */
    public function checkPriv($projectID, $action = '', $errorType = '')
    {
        if(!$this->krgoods->checkPriv($projectID))
        {
            if($errorType == '') $errorType = empty($_POST) ? 'html' : 'json';
            if($errorType == 'json')
            {
                $this->app->loadLang('notice');
                $this->send(array('result' => 'fail', 'message' => $this->lang->notice->typeList['accessLimited']));
            }
            else
            {
                $locate = helper::safe64Encode($this->server->http_referer);
                $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
                $this->locate($noticeLink);
            }
        }
        return true;
    }
}
