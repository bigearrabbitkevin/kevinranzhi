<?php

/**
 * The control file of kevindept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     kevindept
 * @version     $Id: control.php 4157 2013-01-20 07:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
class kevindept extends control {
	const NEW_CHILD_COUNT = 10;

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
	 * Ajax get users
	 *
	 * @param  int    $kevindept
	 * @param  string $user
	 * @access public
	 * @return void
	 */
	public function ajaxGetUsers($kevindept, $user = '') {
		$users = array('' => '') + $this->kevindept->getDeptUserPairs($kevindept);
		die(html::select('user', $users, $user, "class='form-control chosen'"));
	}

	/**
	 * Browse a department.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return void
	 */
	public function browse($deptID = 1) {
		$parentDepts            = $this->kevindept->getParents($deptID);
		$this->view->title      = $this->lang->kevindept->manage.$this->lang->colon.$this->app->company->name;
		$this->view->position[] = $this->lang->kevindept->manage;
		$this->view->deptID     = $deptID;
//		$this->view->depts       = $this->kevindept->getTreeMenu($rootDeptID = 0, array('deptmodel', 'createManageLink'));
		$this->view->parentDepts = $parentDepts;
		$this->view->sons        = $this->kevindept->getSons($deptID);
		$this->view->tree        = $this->kevindept->getDataStructure(0);
		$this->view->treeMenu    = $this->kevindept->getTreeMenu('dept', 0, array('kevindeptModel', 'createMemberLink'));
		//$this->view->treeMenu    = $this->kevindept->getTreeMenu( 0,  'createManageLink');
		$this->display();
	}

	/**
	 * Delete a department.
	 *
	 * @param  int    $deptID
	 * @param  string $confirm yes|no
	 * @access public
	 * @return void
	 */
	public function delete($deptID, $confirm = 'no') {
		/* Check this kevindept when delete. */
		$sons  = $this->kevindept->getSons($deptID);
		$users = $this->kevindept->getUsers($deptID);
		if ($sons) die(js::alert($this->lang->kevindept->error->hasSons));
		if ($users) die(js::alert($this->lang->kevindept->error->hasUsers));

		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevindept->confirmDelete, $this->createLink('kevindept', 'delete', "deptID=$deptID&confirm=yes")));
		} else {
			$this->kevindept->delete($deptID);
			die(js::reload('parent'));
		}
	}

	/**
	 * dept list.
	 *
	 * @param  int $recTotal ,$recPerPage,$pageID
	 * @access public
	 * @return void
	 */
	public function deptlist($path = '', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static = true);
		$pager  = pager::init($recTotal, $recPerPage, $pageID);
		$filter = [];

		$manager = $filter['manager'] = '';
		$group   = $filter['group'] = '';
		$deleted = $filter['deleted'] = '';

		if ($path)
			$this->session->set('deptpath', $path);
		if (!empty($_POST)) {
			if (isset($_POST['manager'])) $this->session->set('manager', $_POST['manager']);
			if (isset($_POST['group'])) {
				$this->session->set('group', $_POST['group']);
			} else {
				$this->session->set('group', '');
			}
			if (isset($_POST['deleted'])) {
				$this->session->set('deptdeleted', $_POST['deleted']);
			} else {
				$this->session->set('deptdeleted', '');
			}
			if (isset($_POST['path'])) $this->session->set('deptpath', $_POST['path']);
			if (isset($_POST['name'])) $this->session->set('name', $_POST['name']);

		}

		if (empty($orderBy)) {
			if ($this->session->deptOrderBy) {
				$orderBy = $this->session->deptOrderBy;
			} else {
				$orderBy = 'id_asc';
			}
		} else {
			$this->session->set('deptOrderBy', $orderBy);
		}

		if ($this->session->deptpath) {
			$path = $filter['path'] = $this->session->deptpath;
		} else {
			$path = $filter['path'] = '';
		}

		if ($this->session->manager) {
			$manager = $filter['manager'] = $this->session->manager;
		}

		if ($this->session->name) {
			$name = $filter['name'] = $this->session->name;
		}

		if ($this->session->group) {
			$group = $filter['group'] = $this->session->group;
		}

		if ($this->session->deptdeleted) {
			$deleted = $filter['deleted'] = $this->session->deptdeleted;
		}

		$this->view->manager = $manager;
		$this->view->group   = $group;

		$this->view->optionMenu  = $this->kevindept->getOptionMenu();
		$this->view->deptParents = $this->kevindept->getdeptParents();
		$this->view->userPairs   = $this->kevindept->getUserPairs();
		$this->view->title       = $this->lang->kevinuser->manage.$this->lang->colon.$this->lang->kevinuser->deptlist;
		$this->view->position[]  = $this->lang->kevinuser->manage;
		$deptList                = $this->kevindept->getDeptList($orderBy, $pager, $filter);
		$dept                    = $this->kevindept->getDept($path);
		$groups                  = $this->loadModel('group')->getPairs();
		if ($dept) {
			$this->session->set('deptID', $dept->id);
			$this->session->set('deptName', $dept->name);
			$this->session->set('deptParent', !empty($dept->parentName) ? $dept->parentName : $this->lang->kevinuser->topParent);
			$groupitem = '';
			if (!empty($dept->group)) {
				$groupArray = explode(',', trim($dept->group, ','));
				foreach ($groupArray as $item)
					if (isset ($groups[$item]))
						$groupitem .= $groups[$item].',';
			}
			$this->session->set('deptGroup', $groupitem);
		}
		$this->view->deptID     = $this->session->deptID;
		$this->view->deptName   = $this->session->deptName;
		$this->view->deptParent = $this->session->deptParent;
		$this->view->deptGroup  = $this->session->deptGroup;
		$this->view->deptList   = $deptList;
		$this->view->deptTitle  = $this->kevindept->getDept($this->session->deptID);

		$this->view->path       = $path;
		$this->view->grade      = isset($grade) ? $grade : '';
		$this->view->manager    = isset($manager) ? $manager : '';
		$this->view->name       = isset($name) ? $name : '';
		$this->view->group      = isset($group) ? $group : '';
		$this->view->deleted    = isset($deleted) ? $deleted : '';
		$this->view->pager      = $pager;
		$this->view->orderBy    = $orderBy;
		$this->view->groups     = $groups;
		$this->view->recTotal   = $recTotal;
		$this->view->recPerPage = $recPerPage;
		$this->view->pageID     = $pageID;
		$this->display();
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
			if ($data->deleted) {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(0)->where('id')->in($this->session->deptIDList)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete).js::locate($this->createLink('kevindept', 'deptlist'), 'parent'));
			} else {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->in($this->session->deptIDList)->exec();
				die(js::alert($this->lang->kevinuser->successDelete).js::locate($this->createLink('kevindept', 'deptlist'), 'parent'));
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
		if ($this->post->parent) {
			$allChanges = $this->kevinuser->deptBatchUpdate();
			if (!empty($allChanges)) {
				foreach ($allChanges as $deptID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevindept', $deptID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			die(js::alert($this->lang->kevinuser->successBatchEdit).js::locate($this->createLink('kevindept', 'deptlist'), 'parent'));
		}

		$users = $this->kevindept->getPairs('noletter|noclosed');

		$deptIDList = $this->post->deptIDList ? $this->post->deptIDList : die(js::locate($this->createLink('kevindept', 'deptlist'), 'parent'));
		if (count($deptIDList) > 5) {
			die(js::alert($this->lang->kevinuser->batchEditMsg).js::locate($this->createLink('kevindept', 'deptlist'), 'parent'));
		}
		$depts = $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($deptIDList)->fetchAll('id');

		$this->view->showFields = $this->config->kevinuser->deptBatchEditFields;
		$this->view->optionMenu = $this->kevindept->getOptionMenu();
		$this->view->users      = $users;
		$this->view->groups     = $this->loadModel('group')->getPairs();
		$this->view->title      = $this->lang->kevinuser->manage.$this->lang->colon.$this->lang->kevinuser->deptBatchEdit;
		$this->view->position[] = $this->lang->kevinuser->deptBatchEdit;
		$this->view->deptIDList = $deptIDList;
		$this->view->depts      = $depts;


		$this->display();
	}

	/**
	 * create dept.
	 *
	 * @access public
	 * @return void
	 */
	public function deptcreate($id = '', $createNew = false) {
		if (!empty($_POST)) {
			$deptID = $this->kevindept->deptCreate();
			if (dao::isError()) die(js::error(dao::getError()));
			$this->loadModel('action')->create('kevindept', $deptID, 'Created');
			die(js::alert($this->lang->kevinuser->successCreate).js::locate($this->createLink('kevindept', 'deptlist'), 'parent.parent'));
		}

		if (!empty($id)) $this->view->dept = $this->kevindept->getById($id);
		$users                  = $this->kevindept->getPairs('noletter|noclosed');
		$this->view->optionMenu = $this->kevindept->getOptionMenu();
		$this->view->modelName  = 'deptcreate';
		if ($createNew) $this->view->createNew = true;
		$this->view->users      = $users;
		$this->view->groups     = $this->loadModel('group')->getPairs();
		$this->view->func       = 'create';
		$this->view->title      = $this->lang->kevinuser->manage.$this->lang->colon.$this->lang->kevinuser->deptcreate;
		$this->view->position[] = $this->lang->kevinuser->manage;
		$this->display('kevindept', 'deptedit');
	}

	/**
	 * Delete a dept.
	 *
	 * @param  int    $id
	 * @param  string $confirm
	 * @access public
	 * @return void
	 */
	public function deptdelete($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinuser->confirmDelete, inlink('deptdelete', "id=$id&confirm=yes"), inLink('deptlist')));
		} else {
			$data = $this->dao->select('deleted')->from(TABLE_DEPT)->where('id')->eq($id)->fetch();
			if ($data->deleted) {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(0)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successUnDelete).js::locate($this->createLink('kevindept', 'deptlist'), 'parent.parent'));
			} else {
				$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
				die(js::alert($this->lang->kevinuser->successDelete).js::locate($this->createLink('kevindept', 'deptlist'), 'parent.parent'));
			}
		}
	}

	/**
	 * Edit dept.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return void
	 */
	public function deptedit($deptID) {
		if (!empty($_POST)) {
			$allChanges = $this->kevindept->deptUpdate($deptID);
			if (!empty($allChanges)) {
				foreach ($allChanges as $classID => $changes) {
					if (empty($changes)) continue;
					$actionID = $this->loadModel('action')->create('kevindept', $deptID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			if (dao::isError()) die($this->send(array('result' => 'fail', 'message' => dao::getError())));
			die(js::alert($this->lang->kevinuser->successSave).js::locate(inLink('deptlist')));
		}
		$dept                   = $this->kevindept->getById($deptID);
		$users                  = $this->kevindept->getPairs('noletter|noclosed');
		$this->view->optionMenu = $this->kevindept->getOptionMenu();
		$this->view->dept       = $dept;
		$this->view->users      = $users;
		$this->view->groups     = $this->loadModel('group')->getPairs();
		$this->view->func       = 'edit';
		$this->view->title      = $this->lang->kevinuser->manage.$this->lang->colon.$this->lang->kevinuser->deptedit;
		$this->view->position[] = $this->lang->kevinuser->manage;
		$this->view->actions    = $this->loadModel('action')->getList('kevindept', $deptID);
		$this->view->modelName  = 'deptedit';
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
		$dept                   = $this->kevindept->getDept($id);
		$this->view->optionMenu = $this->kevindept->getOptionMenu();
		$this->view->title      = $this->lang->kevinuser->manage.$this->lang->colon.$this->lang->kevinuser->deptview;
		$this->view->position[] = $this->lang->kevinuser->manage;
		$this->view->actions    = $this->loadModel('action')->getList('kevindept', $id);
		$this->view->accounts   = $this->kevindept->getPairs();
		$this->view->groups     = $this->loadModel('group')->getPairs();
		$this->view->dept       = $dept;
		$this->display();
	}

	/**
	 * Get user pairs of a department.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return array
	 */
	public function getDeptUserPairs($deptID = 0) {
		$childDepts = $this->getAllChildID($deptID);
		return $this->dao->select('account, realname')->from(TABLE_USER)
			->where('deleted')->eq(0)
			->beginIF($deptID)->andWhere('dept')->in($childDepts)->fi()
			->orderBy('account')
			->fetchPairs();
	}

	/**
	 * Get parents.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return array
	 */
	public function getParents($deptID) {
		if ($deptID == 0) return array();
		$path = $this->dao->select('path')->from(TABLE_DEPT)->where('id')->eq($deptID)->fetch('path');
		$path = substr($path, 1, -1);
		if (empty($path)) return array();
		return $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($path)->orderBy('grade')->fetchAll();
	}

	/**
	 * Get the treemenu of departments.
	 *
	 * @param  int    $rootDeptID
	 * @param  string $userFunc
	 * @param  int    $param
	 * @access public
	 * @return string
	 */
	public function getTreeMenu($rootDeptID = 0, $userFunc, $param = 0) {
		$deptMenu = array();
		$stmt     = $this->dbh->query($this->buildMenuQuery($rootDeptID));
		while ($kevindept = $stmt->fetch()) {
			$linkHtml = call_user_func($userFunc, $kevindept, $param);

			if (isset($deptMenu[$kevindept->id]) and !empty($deptMenu[$kevindept->id])) {
				if (!isset($deptMenu[$kevindept->parent])) $deptMenu[$kevindept->parent] = '';
				$deptMenu[$kevindept->parent] .= "<li>$linkHtml";
				$deptMenu[$kevindept->parent] .= "<ul>".$deptMenu[$kevindept->id]."</ul>\n";
			} else {
				if (isset($deptMenu[$kevindept->parent]) and !empty($deptMenu[$kevindept->parent])) {
					$deptMenu[$kevindept->parent] .= "<li>$linkHtml\n";
				} else {
					$deptMenu[$kevindept->parent] = "<li>$linkHtml\n";
				}
			}
			$deptMenu[$kevindept->parent] .= "</li>\n";
		}

		$lastMenu = "<ul class='tree tree-lines'>".@array_pop($deptMenu)."</ul>\n";
		return $lastMenu;
	}

	/**
	 * Manage childs.
	 *
	 * @access public
	 * @return void
	 */
	public function manageChild() {
		if (!empty($_POST)) {
			$this->kevindept->manageChild($_POST['parentDeptID'], $_POST['depts']);
			die(js::locate(inLink('browse')));
		}
	}


	/**
	 * Ajax Sync Categpry
	 *
	 * @access public
	 * @return void
	 */
	public function synccategory() {
		$msg = $this->kevindept->syncDeptCategory();
		echo $msg;
	}

	/**
	 * Update the departments order.
	 *
	 * @access public
	 * @return void
	 */
	public function updateOrder() {
		if (!empty($_POST)) {
			$this->kevindept->updateOrder($_POST['orders']);
			die(js::reload('parent'));
		}
	}
}
