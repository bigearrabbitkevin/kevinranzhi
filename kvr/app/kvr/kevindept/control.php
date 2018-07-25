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
    public function browse($deptID = 0)
    {
        $parentDepts = $this->kevindept->getParents($deptID);
        $this->view->title       = $this->lang->kevindept->manage . $this->lang->colon . $this->app->company->name;
        $this->view->position[]  = $this->lang->kevindept->manage;
        $this->view->deptID      = $deptID;
        $this->view->depts       = $this->kevindept->getTreeMenu($rootDeptID = 0, array('deptmodel', 'createManageLink'));
        $this->view->parentDepts = $parentDepts;
        $this->view->sons        = $this->kevindept->getSons($deptID);
        $this->view->tree        = $this->kevindept->getDataStructure(0);
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
            die(js::reload('parent'));
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
}
