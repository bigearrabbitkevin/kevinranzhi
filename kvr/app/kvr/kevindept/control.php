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
class kevindept extends control
{
    const NEW_CHILD_COUNT = 10;

    /**
     * Construct function, set menu. 
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
    }

    /**
     * Browse a department.
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function browse($deptID = 1)
    {
        $parentDepts = $this->kevindept->getParents($deptID);
        $this->view->title       = $this->lang->kevindept->manage . $this->lang->colon . $this->app->company->name;
        $this->view->position[]  = $this->lang->kevindept->manage;
        $this->view->deptID      = $deptID;
        $this->view->depts       = $this->kevindept->getTreeMenu($rootDeptID = 0, array('deptmodel', 'createManageLink'));
        $this->view->parentDepts = $parentDepts;
        $this->view->sons        = $this->kevindept->getSons($deptID);
        $this->view->tree        = $this->kevindept->getDataStructure(0);
 	    $this->view->treeMenu    = $this->loadModel('tree')->getTreeMenu('dept', 0, array('kevindeptModel', 'createMemberLink'));
	    //$this->view->treeMenu    = $this->kevindept->getTreeMenu( 0,  'createManageLink');
        $this->display();
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
		$deptList				 = $this->loadModel('kevinuser')->getDeptList($orderBy, $pager, $filter);
		$dept = $this->loadModel('kevinuser')->getDept($path);
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
		//$this->view->classpairs	 = $this->loadModel('kevinuser')->getAllClassPairs();
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
     * Update the departments order.
     * 
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        if(!empty($_POST))
        {
            $this->kevindept->updateOrder($_POST['orders']);
            die(js::reload('parent'));
        }
    }

    /**
     * Manage childs.
     * 
     * @access public
     * @return void
     */
    public function manageChild()
    {
        if(!empty($_POST))
        {
            $this->kevindept->manageChild($_POST['parentDeptID'], $_POST['depts']);
            die(js::locate(inLink('browse')));
        }
    }

    /**
     * Edit kevindept. 
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function edit($deptID)
    {
        if(!empty($_POST))
        {
            $this->kevindept->update($deptID);
            die(js::alert($this->lang->kevindept->successSave) . js::reload('parent'));
        }

        $kevindept  = $this->kevindept->getById($deptID);
        $users = $this->loadModel('user')->getPairs('nodeleted|noletter|noclosed');

        $this->view->optionMenu = $this->kevindept->getOptionMenu();

        $this->view->kevindept  = $kevindept;
        $this->view->users = $users;

        /* Remove self and childs from the $optionMenu. Because it's parent can't be self or childs. */
        $childs = $this->kevindept->getAllChildId($deptID);
        foreach($childs as $childModuleID) unset($this->view->optionMenu[$childModuleID]);

        die($this->display());
    }

    /**
     * Delete a department.
     * 
     * @param  int    $deptID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($deptID, $confirm = 'no')
    {
        /* Check this kevindept when delete. */
        $sons  = $this->kevindept->getSons($deptID);
        $users = $this->kevindept->getUsers($deptID);
        if($sons)  die(js::alert($this->lang->kevindept->error->hasSons));
        if($users) die(js::alert($this->lang->kevindept->error->hasUsers));

        if($confirm == 'no')
        {
            die(js::confirm($this->lang->kevindept->confirmDelete, $this->createLink('kevindept', 'delete', "deptID=$deptID&confirm=yes")));
        }
        else
        {
            $this->kevindept->delete($deptID);
            die(js::reload('parent'));
        }
    }

    /**
     * Ajax get users 
     * 
     * @param  int    $kevindept 
     * @param  string $user 
     * @access public
     * @return void
     */
    public function ajaxGetUsers($kevindept, $user = '')
    {
        $users = array('' => '') + $this->kevindept->getDeptUserPairs($kevindept);
        die(html::select('user', $users, $user, "class='form-control chosen'"));
    }

	/**
	 * Get parents.
	 *
	 * @param  int    $deptID
	 * @access public
	 * @return array
	 */
	public function getParents($deptID)
	{
		if($deptID == 0) return array();
		$path = $this->dao->select('path')->from(TABLE_DEPT)->where('id')->eq($deptID)->fetch('path');
		$path = substr($path, 1, -1);
		if(empty($path)) return array();
		return $this->dao->select('*')->from(TABLE_DEPT)->where('id')->in($path)->orderBy('grade')->fetchAll();
	}
	/**
	 * Get the treemenu of departments.
	 *
	 * @param  int        $rootDeptID
	 * @param  string     $userFunc
	 * @param  int        $param
	 * @access public
	 * @return string
	 */
	public function getTreeMenu($rootDeptID = 0, $userFunc, $param = 0)
	{
		$deptMenu = array();
		$stmt = $this->dbh->query($this->buildMenuQuery($rootDeptID));
		while($kevindept = $stmt->fetch())
		{
			$linkHtml = call_user_func($userFunc, $kevindept, $param);

			if(isset($deptMenu[$kevindept->id]) and !empty($deptMenu[$kevindept->id]))
			{
				if(!isset($deptMenu[$kevindept->parent])) $deptMenu[$kevindept->parent] = '';
				$deptMenu[$kevindept->parent] .= "<li>$linkHtml";
				$deptMenu[$kevindept->parent] .= "<ul>".$deptMenu[$kevindept->id]."</ul>\n";
			}
			else
			{
				if(isset($deptMenu[$kevindept->parent]) and !empty($deptMenu[$kevindept->parent]))
				{
					$deptMenu[$kevindept->parent] .= "<li>$linkHtml\n";
				}
				else
				{
					$deptMenu[$kevindept->parent] = "<li>$linkHtml\n";
				}
			}
			$deptMenu[$kevindept->parent] .= "</li>\n";
		}

		$lastMenu = "<ul class='tree tree-lines'>" . @array_pop($deptMenu) . "</ul>\n";
		return $lastMenu;
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
}
