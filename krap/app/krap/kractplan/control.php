<?php
/**
 * The control file of kractplan module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
class kractplan extends control
{
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
    }

    /**
     * index page of kractplan module.
     * 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function index($status = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $users = $this->loadModel('user')->getPairs('noclosed');
        $this->config->kractplan->search['actionURL'] = $this->createLink('kractplan', 'index', "status=bysearch");
        $this->config->kractplan->search['params']['t1.createdBy']['values'] = $users;
        $this->config->kractplan->search['params']['t2.account']['values']   = $users;
        $this->search->setSearchParams($this->config->kractplan->search);

        $this->view->title    = $this->lang->kractplan->common;
        $this->view->status   = $status;
			
		$this->view->plans	  = $this->kractplan->getList($status, $orderBy, $pager);
        $this->view->users    = $users;
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
        $this->display();
    }

    /**
     * create a kractplan.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $id = $this->kractplan->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('kractplan', $id, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('kractplan', 'view', "id={$id}")));
        }

        $this->view->title  = $this->lang->kractplan->common . ' : ' . $this->lang->kractplan->create;
        $this->view->users  = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * Edit kractplan. 
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
            $changes  = $this->kractplan->update($id);
            $actionID = $this->loadModel('action')->create('kractplan', $id, 'Edited');
            if($changes) $this->action->logHistory($actionID, $changes);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title   = $this->lang->kractplan->common . ' : ' . $this->lang->kractplan->edit;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->view->kractplan = $this->kractplan->getByID($id);
        $this->display();
    }

    /**
     * View a kractplan.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function view($id = 0)
    {
        $kractplan = $this->kractplan->getByID($id);

        $this->view->title   = $this->lang->kractplan->view . $this->lang->colon . $kractplan->name;
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->view->kractplan = $kractplan;
        $this->display();
    }

    /**
     * Finish kractplan.
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
            $changes = $this->kractplan->finish($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('kractplan', $id, 'Finished', $this->post->comment);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $kractplan = $this->kractplan->getByID($id);

        $this->view->title     = $this->lang->kractplan->common . ' : ' . $kractplan->name;
        $this->view->id = $id;
        $this->view->kractplan   = $kractplan;
        $this->display();
    }

    /**
     * Delete a kractplan.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->checkPriv($id, '', 'json');
        $this->kractplan->delete(TABLE_KR_ACTPLAN, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }
	
    /**
     * index page of kractplan module.
     * 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function goods($plan = '0', $orderBy = 'id_desc')
    {
		$plan = abs((int)$plan);//to int
		if(!$plan && $this->session->kractplan_ID)	$plan = $this->session->kractplan_ID;//get 
        $this->view->title    = $this->lang->kractplan->common;
		$this->view->goods =  $this->kractplan->getGoods($plan);
		$this->view->planItem =		$this->kractplan->getByID($plan);
		/* Save session. */
        if($this->view->planItem)$this->app->session->set('kractplan_ID',  $plan);
       $this->display();
    }
	
    /**
     * Check kractplan privilege and locate index if no privilege. 
     * 
     * @param  int    $id 
     * @param  string $action 
     * @param  string $errorType   html|json
     * @access private
     * @return void
     */
    public function checkPriv($id, $action = '', $errorType = '')
    {
        if(!$this->kractplan->checkPriv($id))
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
