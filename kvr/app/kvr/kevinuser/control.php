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
			$users			 = $this->kevindept->getUsers($childDeptIds, $pager, $sort);
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

	/**
	 * Batch delete dept.
	 *
	 * @param  String $confirm
	 * @access public
	 * @return void
	 */
	public function deptbatchdelete($confirm = 'no') {
		if ($confirm == 'no') {
			$this->session->set('deptIDList', $this->post->deptIDList);
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('deptbatchdelete', "confirm=yes")));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_DEPT)->where('id')->eq($this->session->deptIDList[0])->fetch();
			if($data->deleted) {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(0)->where('id')->in($this->session->deptIDList)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
			} else {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->in($this->session->deptIDList)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
			}
		}
	}

	/**
	 * Batch edit dept.
	 *
	 * @access public
	 * @return void
	 */
	public function deptbatchedit() {
		$deptModel = $this->loadModel('kevindept');
		if ($this->post->parent) {
			$allChanges = $this->kevinuser->deptBatchUpdate();
			if (!empty($allChanges)) {
				foreach ($allChanges as $deptID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinuserdept', $deptID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			die(js::alert($this->lang->kevinuser->successBatchEdit) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		}

		$users = $this->loadModel('kevinuser')->getPairs('noletter|noclosed');

		$deptIDList	 = $this->post->deptIDList ? $this->post->deptIDList : die(js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		if (count($deptIDList) > $this->config->kevinuser->batchEditNum) {
			die(js::alert($this->lang->kevinuser->batchEditMsg) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		}
		$depts		 = $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($deptIDList)->fetchAll('id');

		$this->view->showFields		 = $this->config->kevinuser->deptBatchEditFields;
		$this->view->optionMenu		 = $deptModel->getOptionMenu();
		$this->view->users			 = $users;
		$this->view->groups		     = $this->loadModel('group')->getPairs();
		$this->view->title			 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptBatchEdit;
		$this->view->position[]		 = $this->lang->kevinuser->deptBatchEdit;
		$this->view->deptIDList		 = $deptIDList;
		$this->view->depts			 = $depts;

		$this->display();
	}

	/**
	 * create dept. 
	 * 
	 * @access public
	 * @return void
	 */
	public function deptcreate($id = '') {
		$deptModel = $this->loadModel('kevindept');
		if (!empty($_POST)) {
			$deptID = $this->kevinuser->deptCreate();
			if (dao::isError()) die(js::error(dao::getError()));
			$this->loadModel('action')->create('kevinuserdept', $deptID, 'Created');
			die(js::alert($this->lang->kevinuser->successCreate) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
		}
		
		if(!empty($id))	$this->view->dept		 = $deptModel->getById($id);
		$users					 = $this->loadModel('kevinuser')->getPairs('noletter|noclosed');
		$this->view->optionMenu	 = $deptModel->getOptionMenu();

		$this->view->users		 = $users;
		$this->view->groups		 = $this->loadModel('group')->getPairs();
		$this->view->func		 = 'create';
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptcreate;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->display('kevinuser', 'deptedit');
	}

	/**
	 * Delete a dept.
	 * 
	 * @param  int    $id 
	 * @param  string    $confirm 
	 * @access public
	 * @return void
	 */
	public function deptdelete($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('deptdelete', "id=$id&confirm=yes"),inLink('deptlist')));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_DEPT)->where('id')->eq($id)->fetch();
			if ($data->deleted) {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(0)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
			} else {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successDelete) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
			}
		}
	}

	/**
	 * Edit dept. 
	 * 
	 * @param  int    $deptID 
	 * @access public
	 * @return void
	 */
	public function deptedit($deptID) {
		$deptModel = $this->loadModel('kevindept');
		if (!empty($_POST)) {
			$allChanges = $this->kevinuser->deptUpdate($deptID);
			if (!empty($allChanges)) {
				foreach ($allChanges as $classID => $changes) {
					if (empty($changes)) continue;
					$actionID = $this->loadModel('action')->create('kevinuserdept', $deptID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::alert($this->lang->kevinuser->successSave) . js::locate($this->createLink('kevinuser', 'deptlist'), 'parent.parent'));
		}
		$dept					 = $deptModel->getById($deptID);
		$users					 = $this->loadModel('kevinuser')->getPairs('noletter|noclosed');
		$this->view->optionMenu	 = $deptModel->getOptionMenu();
		$this->view->dept		 = $dept;
		$this->view->users		 = $users;
		$this->view->groups		 = $this->loadModel('group')->getPairs();
		$this->view->func		 = 'edit';
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptedit;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserdept', $deptID);
		$this->display('kevinuser', 'deptedit');
	}

	/**
	 * dept list.
	 * 
	 * @param  int    $recTotal,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function deptlist($path='', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$deptModel = $this->loadModel('kevindept');
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$filter	 = [];
		
		$manager			 = $filter['manager']	 = '';
		$group				 = $filter['group']		 = '';
		$deleted				 = $filter['deleted']		 = '';
		
		if($path)
			$this->session->set('deptpath', $path);
		if (!empty($_POST)) {
			if(isset($_POST['manager'])) $this->session->set('manager', $_POST['manager']);
			if(isset($_POST['group'])){
				$this->session->set('group', $_POST['group']);
			}else{
				$this->session->set('group', '');
			}
			if(isset($_POST['deleted'])) {
				$this->session->set('deptdeleted', $_POST['deleted']);
			}else{
				$this->session->set('deptdeleted', '');
			}
			if(isset($_POST['path'])) $this->session->set('deptpath', $_POST['path']);
			die(js::locate($this->createLink('kevinuser', 'deptlist'), 'parent'));
		}
					
		if(empty($orderBy)) {
			if($this->session->deptOrderBy){
				$orderBy = $this->session->deptOrderBy;
			}else{
				$orderBy = 'id_asc';
			}
		}else{
			$this->session->set('deptOrderBy', $orderBy);
		}
		
		if($this->session->deptpath) {
			$path = $filter['path'] = $this->session->deptpath;
		}else{
			$path = $filter['path'] = '';
		}
		
		if($this->session->manager) {
			$manager = $filter['manager'] = $this->session->manager;
		}
		
		if($this->session->group) {
			$group = $filter['group'] = $this->session->group;
		}	
		
		if($this->session->deptdeleted) {
			$deleted = $filter['deleted'] = $this->session->deptdeleted;
		}
		
		$this->view->manager = $manager;
		$this->view->group		 = $group;
		
		$this->view->optionMenu	 = $this->kevindept->getOptionMenu();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptlist;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$deptList				 = $this->kevinuser->getDeptList($orderBy, $pager, $filter);
		$dept = $this->kevinuser->getDept($path);
		$groups = $this->loadModel('group')->getPairs();
		if ($dept) {
			$this->session->set('deptName', $dept->name);
			$this->session->set('deptParent', !empty($dept->parentName) ? $dept->parentName : $this->lang->kevinuser->topParent);
			$groupitem = '';
			if (!empty($dept->group)) {
				$groupArray = explode(',', trim($dept->group, ','));
				foreach ($groupArray as $item)
					if(isset ($groups[$item]))
						$groupitem .= $groups[$item] . ',';
			}
			$this->session->set('deptGroup', $groupitem);
		}
		$this->view->deptName = $this->session->deptName;
		$this->view->deptParent = $this->session->deptParent;
		$this->view->deptGroup = $this->session->deptGroup;
		$this->view->deptList	 = $deptList;
		//$this->view->classpairs	 = $this->kevinuser->getAllClassPairs();
		$this->view->path		 = $path;
		$this->view->grade		 = isset($grade) ? $grade : '';
		$this->view->manager	 = isset($manager) ? $manager : '';
		$this->view->group		 = isset($group) ? $group : '';
		$this->view->deleted	 = isset($deleted) ? $deleted : '';
		$this->view->pager		 = $pager;
		$this->view->orderBy	 = $orderBy;
		$this->view->groups		 = $groups;
		$this->view->recTotal	 = $recTotal;
		$this->view->recPerPage	 = $recPerPage;
		$this->view->pageID		 = $pageID;
		$this->display();
	}

	/**
	 *  View and update deptset.
	 * 
	 * @param  int $recTotal,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function deptset($recTotal = 0, $recPerPage = 10, $pageID = 1) {
		if (!empty($_POST)) {
			$messages = $this->kevinuser->updateDeptUsers();
			if (dao::isError()) die(js::error(dao::getError()));
			$vars = array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'pageID' => $pageID);
			if(!empty($messages)){
				$message = '';
				foreach ($messages as $key =>$item) {
					if($key == 0) {
						$message .= $item;
					}else{
						$message .= ','.$item;
					}
				}
				die(js::alert($message) .js::locate($this->createLink('kevinuser', 'deptset', $vars), 'parent.parent'));
			}else{
				die(js::locate($this->createLink('kevinuser', 'deptset', $vars), 'parent.parent'));
			}
		}

		/* Load pager. */
		$this->app->loadClass('pager', $static			 = true);
		if ($this->app->getViewType() == 'mhtml') $recPerPage		 = 10;
		$pager			 = pager::init($recTotal, $recPerPage, $pageID);
		$pager->recTotal = 0;

		$this->view->title			 = $this->lang->kevinuser->common . $this->lang->colon . $this->lang->kevinuser->deptset;
		$this->view->position[]		 = $this->lang->kevinuser->deptset;
		$this->view->deptaccounts	 = $this->kevinuser->getDeptset($pager);
		$this->view->pager			 = $pager;
		$this->display();
	}

	/**
	 *  View dept.
	 * 
	 * @param  int $id
	 * @access public
	 * @return void
	 */
	public function deptview($id) {
		$dept					 = $this->kevinuser->getDept($id);
		$this->view->optionMenu	 = $this->loadModel('kevindept')->getOptionMenu();
		$this->view->title		 = $this->lang->kevinuser->manage . $this->lang->colon . $this->lang->kevinuser->deptview;
		$this->view->position[]	 = $this->lang->kevinuser->manage;
		$this->view->actions	 = $this->loadModel('action')->getList('kevinuserdept', $id);
		$this->view->accounts	 = $this->loadModel('kevinuser')->getPairs();
		$this->view->groups		 = $this->loadModel('group')->getPairs();
		$this->view->dept		 = $dept;
		$this->display();
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

}
