<?php

/**
 * The control file of dept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: control.php 4157 2013-01-20 07:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
class kevinuser extends control {

	/**
	 * Construct function, set menu. 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($moduleName = '', $methodName = '') {
		parent::__construct($moduleName, $methodName);
	}
	
	
	/**
	 * Browse departments and users of a company.
	 *
	 * @param  int    $param
	 * @param  string $type
	 * @param  string $orderBy
	 * @param  int    $recTotal
	 * @param  int    $recPerPage
	 * @param  int    $pageID
	 * @access public
	 * @return void
	 */
	public function browse($param = 0, $type = 'bydept', $orderBy = 'locked,id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		$this->loadModel('search');
		$this->loadModel('kevindept');
		
		$deptID = $type == 'bydept' ? (int) $param : 0;
		
		/* Save session. */
		$this->session->set('userList', $this->app->getURI(true));
		
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		
		/* Append id for secend sort. */
		$sort = null;//$this->loadModel('common')->appendOrder($orderBy);
		
		/* Build the search form. */
		$queryID															 = $type == 'bydept' ? 0 : (int) $param;
		$this->config->kevinuser->browse->search['actionURL']					 = $this->createLink('kevinuser', 'browse', "param=myQueryID&type=bysearch");
		$this->config->kevinuser->browse->search['queryID']					 = $queryID;
		$this->config->kevinuser->browse->search['params']['dept']['values']	 = array('' => '') + $this->kevindept->getOptionMenu();
		
		if ($type == 'bydept') {
			$childDeptIds	 = $this->kevindept->getAllChildID($deptID);
			$users			 = $this->kevindept->getUsers($childDeptIds, $pager, $orderBy);
		} else {
			if ($queryID) {
				$query = $this->search->getQuery($queryID);
				if ($query) {
					$this->session->set('userQuery', $query->sql);
					$this->session->set('userForm', $query->form);
				} else {
					$this->session->set('userQuery', ' 1 = 1');
				}
			}
			$users = $this->loadModel('kevinuser')->getByQuery($this->session->userQuery, $pager, $sort);
		}
		$this->view->title		= $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevindept->common;
		
		$this->view->position[]	 = $this->lang->kevindept->common;
		$this->view->users		 = $users;
		$this->view->searchForm	 = $this->fetch('search', 'buildForm', $this->config->kevinuser->browse->search,$this->createLink('search', 'buildQuery'));
		$this->view->deptTree    = $this->loadModel('tree')->getTreeMenu('dept', 0, array('kevinuserModel', 'createMemberLinkOfBrowse'));

		$this->view->parentDepts = $this->kevindept->getParents($deptID);
		$this->view->depts		 = $this->kevinuser->getDeptArray();
		$this->view->orderBy	 = $orderBy;
		$this->view->deptID		 = $deptID;
		$this->view->pager		 = $pager;
		$this->view->param		 = $param;
		$this->view->type		 = $type;
		
		$this->display();
	}
	
	/**
	 * Batch create users.
	 *
	 * @param  int    $deptID
	 * @access public
	 * @return void
	 */
	public function batchCreate($deptID = 0)
	{
		$this->loadModel('kevindept');
		$groups    = $this->dao->select('*')->from(TABLE_GROUP)->fetchAll();
		$groupList = array('' => '');
		$roleGroup = array();
		foreach($groups as $group)
		{
			$groupList[$group->id] = $group->name;
			if($group->role) $roleGroup[$group->role] = $group->id;
		}

		if(!empty($_POST))
		{
			$this->kevinuser->batchCreate();
			die(js::locate($this->createLink('kevinuser', 'browse'), 'parent'));
		}
		
		/* Set custom. */
		foreach(explode(',', $this->config->kevinuser->customBatchCreateFields) as $field) $customFields[$field] = $this->lang->kevinuser->$field;
		$this->view->customFields = $customFields;
		$this->view->showFields   = $this->config->kevinuser->custom->batchCreateFields;
		
		$title      = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->batchCreate;
		$position[] = $this->lang->kevinuser->batchCreate;
		$this->view->title     = $title;
		$this->view->position  = $position;
		$this->view->depts     = $this->kevindept->getOptionMenu();
		$this->view->deptID    = $deptID;
		$this->view->groupList = $groupList;
		$this->view->roleGroup = $roleGroup;
		
		$this->display();
	}
	
	/**
	 * Create a suer.
	 *
	 * @param  int    $deptID
	 * @access public
	 * @return void
	 */
	public function create($deptID = 0)
	{
		$this->loadModel('kevindept');
		if(!empty($_POST))
		{
			$this->kevinuser->create();
			if(dao::isError()) die(js::error(dao::getError()));
			die(js::locate($this->createLink('kevinuser', 'browse'), 'parent'));
		}
		$groups    = $this->dao->select('*')->from(TABLE_GROUP)->fetchAll();
		$groupList = array('' => '');
		$roleGroup = array();
		foreach($groups as $group)
		{
			$groupList[$group->id] = $group->name;
			if($group->role) $roleGroup[$group->role] = $group->id;
		}

		$title      = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->create;
		$position[] = $this->lang->kevinuser->create;
		$this->view->title     = $title;
		$this->view->position  = $position;
		$this->view->depts     = $this->kevindept->getOptionMenu();
		$this->view->groupList = $groupList;
		$this->view->roleGroup = $roleGroup;
		$this->view->deptID    = $deptID;
		
		$this->display();
	}
	
	/**
	 * Delete hours deptset.
	 *
	 * @param  int $id
	 * @access public
	 * @return void
	 */
	public function deletedeptuser($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('deletedeptuser', "id=$id&confirm=yes")). js::reload('parent'));
		} else {
		$this->dao->delete()->from(TABLE_KEVIN_DEPTSET)
				->where('id')->eq($id)
				->exec();
			if (dao::isError()) {
				$this->send(array('result' => 'fail', 'message' => dao::getError()));
			}
			die(js::reload('parent'));
		}
	}


	public function domainaccount($recTotal = 0, $recPerPage = 10, $pageID = 1) {
		$filter = [];
		if (!empty($_POST)) {
			$post = $_POST;
			if(isset($post['realname']) || isset($post['localname']) || isset($post['remotename'])) {
				if(isset($post['realname'])) $filter['realname'] = $post['realname'];
				if(isset($post['localname'])) $filter['localname'] = $post['localname'];
				if(isset($post['remotename'])) $filter['remotename'] = $post['remotename'];
				$this->session->set('domainaccount_filter', $filter);
			}else{
				$this->kevinuser->updateDefaultLdapusers();
				if (dao::isError()) die(js::error(dao::getError()));
				$vars = array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'pageID' => $pageID);
				$link = $this->createLink('kevinuser', 'domainaccount', $vars);
				die(js::locate($this->createLink('kevinuser', 'domainaccount', $vars), 'parent.parent'));
			}
		}
		if(empty($filter) && $_SESSION['domainaccount_filter']) $filter = $_SESSION['domainaccount_filter'];
		$this->domainaccountCorrect();
		/* Load pager. */
		$this->app->loadClass('pager', $static			 = true);
		if ($this->app->getViewType() == 'mhtml') $recPerPage		 = 10;
		$pager			 = pager::init($recTotal, $recPerPage, $pageID);
		$pager->recTotal = 0;

		$this->view->title			 = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->domainaccount;
		$this->view->position[]		 = $this->lang->kevinuser->domainaccount;
		$this->view->domainaccounts	 = $this->kevinuser->getDomainAccounts($pager, $filter);
		$this->view->pager			 = $pager;
		$this->view->controlType	 = 'domainaccount';
		$this->view->filter = $filter;
		$this->display();
	}

	/**
	 * Delete hours deptset.
	 *
	 * @param  int $id
	 * @access public
	 * @return void
	 */
	public function deleteldapuser($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->contacts->confirmDelete, inlink('deleteldapuser', "id=$id&confirm=yes")). js::reload('parent'));
		} else {
			$this->dao->update(TABLE_USER)
				->set("domainFullAccount")->eq("")
				->where('id')->eq($id)
				->exec();
			if (dao::isError()) {
				$this->send(array('result' => 'fail', 'message' => dao::getError()));
			}
			die(js::reload('parent'));
		}
	}

	/**
	 * Edit a user.
	 *
	 * @param  string|int $userID   the int user id or account
	 * @access public
	 * @return void
	 */
	public function edit($userID)
	{
		
		$this->loadModel('kevindept');
//		$this->lang->set('menugroup.user', 'company');
//		$this->lang->user->menu      = $this->lang->company->menu;
//		$this->lang->user->menuOrder = $this->lang->company->menuOrder;
		if(!empty($_POST))
		{
			$this->kevinuser->update($userID);
			if(dao::isError()) die(js::error(dao::getError()));
			die(js::locate($this->session->userList ? $this->session->userList : $this->createLink('kevinuser', 'browse'), 'parent'));
		}
		
		$user       = $this->kevinuser->getById($userID, 'id');
		$userGroups = $this->loadModel('group')->getByAccount($user->account);
		
		$title      = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->edit;
		$position[] = $this->lang->kevinuser->edit;
		$this->view->title      = $title;
		$this->view->position   = $position;
		$this->view->user       = $user;
		$this->view->depts      = $this->loadModel('kevindept')->getOptionMenu();
		$this->view->userGroups = implode(',', array_keys($userGroups));
		$this->view->groups     = $this->loadModel('group')->getPairs();
		
		$this->display();
	}
	
	/**
	 * Manage contacts.
	 *
	 * @param  int    $listID
	 * @access public
	 * @return void
	 */
	public function manageContacts($listID = 0) {
		$this->loadModel('kevinuser');
		$lists = $this->kevinuser->getContactLists($this->app->user->account);
		
		/* If set $mode, need to update database. */
		if ($this->post->mode) {
			/* The mode is new: append or new a list. */
			if ($this->post->mode == 'new') {
				if ($this->post->list2Append) {
					$this->kevinuser->append2ContactList($this->post->list2Append, $this->post->users);
					die(js::locate(inlink('manageContacts', "listID={$this->post->list2Append}"), 'parent'));
				} elseif ($this->post->newList) {
					$listID = $this->kevinuser->createContactList($this->post->newList, $this->post->users);
					die(js::locate(inlink('manageContacts', "listID=$listID"), 'parent'));
				}
			} elseif ($this->post->mode == 'edit') {
				$this->kevinuser->updateContactList($this->post->listID, $this->post->listName, $this->post->users);
				die(js::locate(inlink('manageContacts', "listID={$this->post->listID}"), 'parent'));
			}
		}
		if ($this->post->users) {
			$mode	 = 'new';
			$users	 = $this->kevinuser->getContactUserPairs($this->post->users);
		} else {
			$mode	 = 'edit';
			$listID	 = $listID ? $listID : key($lists);
			if (!$listID)
				die(js::alert($this->lang->user->contacts->noListYet) . js::locate($this->createLink('kevinuser', 'browse'), 'parent'));
			
			$list				 = $this->kevinuser->getContactListByID($listID);
			$users				 = explode(',', $list->userList);
			$users				 = $this->kevinuser->getContactUserPairs($users);
			$this->view->list	 = $list;
		}
		
		$this->view->title		 = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->manageContacts;
		$this->view->position[]	 = $this->lang->kevinuser->common;
		$this->view->position[]	 = $this->lang->kevinuser->manageContacts;
		$this->view->lists		 = $this->kevinuser->getContactLists($this->app->user->account);
		$this->view->users		 = $users;
		$this->view->listID		 = $listID;
		$this->view->mode		 = $mode;
		$this->display();
	}

	/**
	 *  Index.
	 * 
	 * @access public
	 * @return void
	 */
	public function index() {
		$this->display();
	}

	/**
	 * The profile of a user.
	 *
	 * @param  string $account
	 * @access public
	 * @return void
	 */
	public function profile($account)
	{
		/* Set menu. */
		$this->view->userList = $this->kevinuser->setUserList($this->kevinuser->getPairs('noempty|noclose|nodeleted'), $account);

		$user = $this->kevinuser->getById($account);

		$this->view->title      = "USER #$user->id $user->account/" . $this->lang->user->profile;
		$this->view->position[] = $this->lang->user->common;
		$this->view->position[] = $this->lang->user->profile;
		$this->view->account    = $account;
		$this->view->user       = $user;
		$this->view->groups     = $this->loadModel('group')->getByAccount($account);
		$this->view->deptPath   = $this->loadModel('kevindept')->getParents($user->dept);

		$this->display();
	}

	/**
	 * Batch delete records.
	 *
	 * @param string $confirm
	 * @access public
	 * @return void
	 */
	public function recordbatchdelete($confirm = 'no') {
		if ($confirm == 'no') {
			$this->session->set('recordIDList', $this->post->recordIDList);
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('recordbatchdelete', "confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_KEVIN_USER_RECORD)->where('id')->eq($this->session->recordIDList[0])->fetch();
			if($data->deleted) {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(0)->where('id')->in($this->session->recordIDList)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
			} else {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(1)->where('id')->in($this->session->recordIDList)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
			}
		}
	}

	/**
	 * Batch edit records.
	 *
	 * @access public
	 * @return void
	 */
	public function recordbatchedit() {
		if ($this->post->account) {
			$allChanges = $this->kevinuser->recordBatchUpdate();
			if (!empty($allChanges)) {
				foreach ($allChanges as $recordID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinuserrecord', $recordID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			die(js::alert($this->lang->kevinuser->successBatchEdit) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		}

		$recordIDList	 = $this->post->recordIDList ? $this->post->recordIDList : die(js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		if (count($recordIDList) > $this->config->kevinuser->batchEditNum) {
			die(js::alert($this->lang->kevinuser->batchEditMsg) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		}
		$records		 = $this->dao->select('a.*')
				->from(TABLE_KEVIN_USER_RECORD)->alias('a')
				->on('a.class=b.id')->where('a.id')->in($recordIDList)->fetchAll('id');

		$this->view->showFields		 = $this->config->kevinuser->recordBatchEditFields;
		$this->view->accounts		 = $this->loadModel('kevinuser')->getPairs();
		$this->view->classpairs		 = $this->kevinuser->getAllClassPairs();
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordBatchEdit;
		$this->view->position[]		 = $this->lang->kevinuser->recordBatchEdit;
		$this->view->recordIDList	 = $recordIDList;
		$this->view->records		 = $records;
		$this->display();
	}

	/**
	 * Create a record.
	 * 
	 * @access public
	 * @return void
	 */
	public function recordcreate($id = '') {
		if (!empty($_POST)) {
			$id = $this->kevinuser->recordcreate();
			if($id == 'startDataError')
				die(js::alert($this->lang->kevinuser->startDataError));
			if (dao::isError()) die(js::error(dao::getError()));
			$this->loadModel('action')->create('kevinuserrecord', $id, 'Created');
			die(js::alert($this->lang->kevinuser->successCreate) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
		}
		
		if (!empty($id)){
			$record	 = $this->kevinuser->getRecord($id);
			$this->view->record	= $record;
		}
		$this->view->accounts	 = $this->loadModel('kevinuser')->getPairs();
		$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordcreate;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->func		 = "create";
		$this->display('kevinuser', 'recordedit');
	}

	/**
	 * Delete a record.
	 * 
	 * @param  int    $id 
	 * @param  string $confirm
	 * @access public
	 * @return void
	 */
	public function recorddelete($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('recorddelete', "id=$id&confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_KEVIN_USER_RECORD)->where('id')->eq($id)->fetch();
			if ($data->deleted) {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(0)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
			} else {
				$this->dao->update(TABLE_KEVIN_USER_RECORD)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
			}			
			
		}
	}

	/**
	 * Update the record .
	 * 
	 * @param  int    $id  
	 * @access public
	 * @return void
	 */
	public function recordedit($id) {
		if (!empty($_POST)) {
			$allChanges = $this->kevinuser->recordUpdate($id);
			if ($allChanges == 'lock') {
				die(js::alert($this->lang->kevinuser->lockData) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
			}
			if (!empty($allChanges)) {
				foreach ($allChanges as $classID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinuserrecord', $classID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::alert($this->lang->kevinuser->successSave) . js::locate($this->createLink('kevinuser', 'recordlist'), 'parent.parent'));
		}
		$record					 = $this->kevinuser->getRecord($id);
		$this->view->accounts	 = $this->loadModel('kevinuser')->getPairs();
		$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordedit;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserrecord', $id);
		$this->view->record		 = $record;
		$this->view->func		 = "edit";
		$this->display();
	}

	/**
	 * record list.
	 * 
	 * @param  int    $recTotal,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function recordlist($account = '', $dept = '', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$filter	 = [];
		
		$class				 = $filter['class']		 = '';
		$deleted				 = $filter['deleted']		 = '';
		if($dept)	$this->session->set('dept', $dept);
		if($account)	$this->session->set('account', $account);

		if (!empty($_POST)) {
			if(isset($_POST['account'])) $this->session->set('account', $_POST['account']);
			if(isset($_POST['dept'])) $this->session->set('dept', $_POST['dept']);
			if(isset($_POST['class'])) $this->session->set('class', $_POST['class']);
			if(isset($_POST['deleted'])){
				$this->session->set('recorddeleted', $_POST['deleted']);
			}else{
				$this->session->set('recorddeleted', '');
			}
			die(js::locate($this->createLink('kevinuser', 'recordlist'), 'parent'));
		}
		
		if(empty($orderBy)) {
			if($this->session->recordOrderBy){
				$orderBy = $this->session->recordOrderBy;
			}else{
				$orderBy = 'id_desc';
			}
		}else{
			$this->session->set('recordOrderBy', $orderBy);
		}
		
		if($this->session->account) {
			$account = $filter['account'] = $this->session->account;
		}else{
			$account = $filter['account'] = '';
		}
		
		if($this->session->dept) {
			$dept = $filter['dept'] = $this->session->dept;
		}else{
			$dept = $filter['dept'] = '';
		}
		
		if($this->session->class) {
			$class = $filter['class'] = $this->session->class;
		}	
		
		if($this->session->recorddeleted) {
			$deleted = $filter['deleted'] = $this->session->recorddeleted;
		}
		
		$recordList = $this->kevinuser->getRecordList($orderBy, $pager, $filter);
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordlist;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->recordList	 = $recordList;
		$this->view->deleted	 = isset($deleted)?$deleted:'';
		$this->view->account	 = $account;
		$this->view->dept		 = $dept;
		$this->view->class		 = isset($class)?$class:'';
		$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->pager		 = $pager;
		$this->view->orderBy	 = $orderBy;
		$this->view->recTotal		 = $recTotal;
		$this->view->recPerPage		 = $recPerPage;
		$this->view->pageID	 = $pageID;
		$this->display();
	}

	/**
	 * View a record.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function recordview($id) {
		$record					 = $this->kevinuser->getRecord($id);
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->recordview;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->record		 = $record;
		$this->view->accounts	 = $this->loadModel('kevinuser')->getPairs();
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserrecord', $id);
		$this->display();
	}
	
	public function userbatchedit($deptID = 0) {
		if (isset($_POST['users'])) {
			$this->view->users = $this->dao->select('*')->from(TABLE_USER)->where('account')->in($this->post->users)->orderBy('id')->fetchAll('id');
		} elseif ($_POST) {
			if ($this->post->account)
				$this->kevinuser->userbatchedit();
			die(js::locate($this->createLink('kevinuser', 'browse', "deptID=$deptID"), 'parent'));
		}
		$this->lang->set('menugroup.user', 'kevinuser');
		$this->lang->user->menu		 = $this->lang->kevinuser->menu;
		$this->lang->user->menuOrder = $this->lang->kevinuser->menuOrder;
		
		$this->view->title		 = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->userbatchedit;
		$this->view->position[]	 = $this->lang->kevinuser->browse;
		$this->view->position[]	 = $this->lang->kevinuser->userbatchedit;
		$this->view->depts		 = $this->loadModel('kevindept')->getOptionMenu();
		$this->display();
	}

	/**
	 * Unlock a user.
	 *
	 * @param  int    $account
	 * @param  string $confirm
	 * @access public
	 * @return void
	 */
	public function unlock($account, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmUnlock, inlink('unlock', "account=$account&confirm=yes")). js::reload('parent'));
		} else {
			$this->loadModel('kevinuser')->cleanLocked($account);
			if (dao::isError()) {
				$this->send(array('result' => 'fail', 'message' => dao::getError()));
		}
			die(js::reload('parent'));
	}
	}


	public function userLock($account, $confirm = 'no') {
		if (strpos($this->app->kevinuser->admins, ",$account,") === false) {
			if ($confirm == 'no') {
				die(js::confirm($this->lang->kevinuser->confirmLock, inlink('userLock', "account=$account&confirm=yes")). js::reload('parent'));
			} else {
				$this->kevinuser->lockUser($account);
				if (dao::isError()) {
					$this->send(array('result' => 'fail', 'message' => dao::getError()));
				}
				die(js::reload('parent'));
			}
		} else {
			die(js::reload('parent'));
		}
	}

	private function domainaccountCorrect() {
		$ldapusers = $this->dao->select('*')->from(TABLE_KEVIN_LDAPUSER)
			->where('domain')->ne('')
			->fetchAll();
		if (!$ldapusers) return true;

		foreach ($ldapusers as $user) {
			if (!$user->domain) continue;
			$this->dao->update(TABLE_USER)
				->set('domainFullAccount')->eq($user->remote . "@" . $user->domain)
				->autoCheck()
				->where('account')->eq($user->local)
				->exec();
		}

		$this->dao->delete("*")->from(TABLE_KEVIN_LDAPUSER)->exec(); //empty
		return true;
	}

}
