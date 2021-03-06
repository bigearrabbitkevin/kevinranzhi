<?php
/**
 * The control file of group module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     group
 * @version     $Id: control.php 4648 2013-04-15 02:45:49Z chencongzhi520@gmail.com $
 * @link        http://www.ranzhi.org
 */
class group extends control
{
    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->loadModel('user');
    }

    /**
     * Browse groups.
     * 
     * @param  int    $companyID 
     * @access public
     * @return void
     */
    public function browse($companyID = 0)
    {
        $groups = $this->group->getList($companyID);
        $groupUsers = array();
        foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

        $this->view->title      = $this->lang->group->browse;
        $this->view->groups     = $groups;
        $this->view->groupUsers = $groupUsers;

        $this->display();
    }

    /**
     * Create a group.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $this->group->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->group->create;
        $this->view->position[] = $this->lang->group->create;
        $this->display();
    }

    /**
     * Edit a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function edit($groupID)
    {
       if(!empty($_POST))
        {
            $this->group->update($groupID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->group->edit;
        $this->view->position[] = $this->lang->group->edit;
        $this->view->group      = $this->group->getById($groupID);

        $this->display();
    }

    /**
     * Copy a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function copy($groupID)
    {
       if(!empty($_POST))
        {
            $this->group->copy($groupID);
            if(dao::isError()) die(js::error(dao::getError()));
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
            die(js::locate($this->createLink('group', 'browse'), 'parent'));
        }

        $this->view->title      = $this->lang->group->copy;
        $this->view->position[] = $this->lang->group->copy;
        $this->view->group      = $this->group->getById($groupID);
        $this->display();
    }

    /**
     * Manage privleges of a group. 
     * 
     * @param  string $type 
     * @param  mix    $param 
     * @param  string $menu 
     * @param  string $version 
     * @param  string $app 
     * @access public
     * @return void
     */
    public function managePriv($type = 'byGroup', $param = 0, $menu = '', $version = '', $app = '')
    {
        if($type == 'byGroup') $groupID = $param;
        $this->view->type = $type;
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->group->checkMenuModule($menu, $moduleName) or $type != 'byGroup') $this->app->loadLang($moduleName);
        }

        if(!empty($_POST))
        {
            if($type == 'byGroup')  $result = $this->group->updatePrivByGroup($groupID, $menu, $version);
            if($type == 'byModule') $result = $this->group->updatePrivByModule();
            if($result)
            {
                if($type == 'byGroup') $this->group->updateAccounts($groupID);
                $this->send(array('result' => 'success', 'message' => $this->lang->group->successSaved, 'locate'=>inlink('browse')));
            }
            $this->send(array('result' => 'fail', 'message' => $this->lang->group->errorNotSaved));
        }

        if($type == 'byGroup')
        {
            $this->group->sortResource();
            $group      = $this->group->getById($groupID);
            $groupPrivs = $this->group->getPrivs($groupID);

            $this->view->title      = $group->name . $this->lang->group->managePriv;
            $this->view->position[] = $group->name;
            $this->view->position[] = $this->lang->group->managePriv;

            /* Join changelog when be equal or greater than this version.*/
            $realVersion = str_replace('_', '.', $version);
            $changelog = array();
            foreach($this->lang->changelog as $currentVersion => $currentChangeLog)
            {
                if(version_compare($currentVersion, $realVersion, '>=')) $changelog[] = join($currentChangeLog, ',');
            }

            $this->view->group      = $group;
            $this->view->changelogs = ',' . join($changelog, ',') . ',';
            $this->view->groupPrivs = $groupPrivs;
            $this->view->groupID    = $groupID;
            $this->view->menu       = $menu;
            $this->view->version    = $version;
        }
        elseif($type == 'byModule')
        {
            $this->group->sortResource();
            $this->view->title      = $this->lang->group->managePriv;
            $this->view->position[] = $this->lang->group->managePriv;

            foreach($this->lang->resource as $module => $moduleActions)
            {
                $modules[$module] = $this->lang->$module->common;
                foreach($moduleActions as $action)
                {
                    $actions[$module][$action] = $this->lang->$module->$action;
                }
            }
            $this->view->groups  = $this->group->getPairs();
            $this->view->modules = $modules;
            $this->view->actions = $actions;
        }
        $this->display();
    }

    /**
     * Manage app privleges of a group. 
     * 
     * @param  string $type 
     * @param  mix    $param 
     * @access public
     * @return void
     */
    public function manageAppPriv($type = 'byGroup', $param = 0)
    {
        if($type == 'byGroup')
        {
            $groupID = $param;
            if($_POST)
            {
                $this->group->updateAppPrivByGroup($groupID, $this->post->apps);
                if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $this->group->updateAccounts($groupID);
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
            }

            $apps = $this->loadModel('entry')->getEntries();

            $app = new stdclass();
            $app->id = 'superadmin';
            $app->code = 'superadmin';
            $app->abbr = $this->lang->apps->superadmin;
            $app->name = $this->lang->apps->superadmin;
            $app->logo = '';

            array_push($apps, $app);

            $privs = $this->group->getPrivs($groupID);
            foreach($apps as $app)
            {
                $rights[$app->code]['id']    = $app->id;
                $rights[$app->code]['right'] = isset($privs['apppriv'][$app->code]) ? 1 : 0;
                $rights[$app->code]['abbr']  = $app->abbr;
                $rights[$app->code]['name']  = $app->name;
                $rights[$app->code]['icon']  = $app->logo == '' ? '' : $app->logo;
            }
        }

        if($type == 'byApp')
        {
            $appCode = $param;
            if($_POST)
            {
                $this->group->updateAppPrivByApp($appCode, $this->post->groups);
                if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('entry', 'admin')));
            }

            $groups = $this->group->getPairs();
            $privs  = $this->group->getAppPriv($appCode);
            foreach($groups as $code => $name)
            {
                $rights[$code]['right'] = isset($privs[$code]) ? 1 : 0;
                $rights[$code]['name']  = $name;
            }
        }

        $this->view->type   = $type;
        $this->view->rights = $rights;
        $this->display();
    }

    /**
     * Manage privleges of out. 
     * 
     * @access public
     * @return void
     */
    public function manageTradePriv()
    {
        $this->lang->group->menu = $this->lang->setting->menu;
        $this->lang->menuGroups->group = 'setting';

        if($_POST)
        {
            $this->group->updateTradePriv($this->post->groups);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $groups = $this->group->getPairs();
        $privs  = $this->group->getTradePriv();
        foreach($groups as $code => $name)
        {
            $rights[$code]['right'] = isset($privs[$code]) ? 1 : 0;
            $rights[$code]['name']  = $name;
        }

        $this->view->rights = $rights;
        $this->display();
    }

    /**
     * Manage members of a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */

	public function manageMember($groupID, $deptID = 0) {
		if (!empty($_POST)) {
			$this->group->updateUser($groupID);
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			//die(js::alert('Save successfully!'));
			die(js::locate($this->createLink('group', 'browse'), 'parent'));
		}
		$group        = $this->group->getById($groupID);         //获取组信息
		$deptAllUsers = $this->group->getDeptUserPairs($deptID); //获取所有包含子部门的用户
		$groupUsers   = $this->group->getUserPairs($groupID);    //组内用户
		$deptusers    = $this->group->getUserByDept($deptID);    //部门内用户
		$allUsers     = $this->group->getDeptUserPairs(0);       //所有用户

		$groupNotInUsers = array_diff_assoc($deptAllUsers, $groupUsers);
		$otherUsers = array_diff_assoc($allUsers, $groupUsers, $groupNotInUsers);

		$title      = $this->lang->company->common.$this->lang->colon.$group->name.$this->lang->colon.$this->lang->group->manageMember;
		$position[] = $group->name;
		$position[] = $this->lang->group->manageMember;

		$this->view->title           = $title;
		$this->view->position        = $position;
		$this->view->group           = $group;
		$this->view->treemenu        = $this->group->getTreeMenu($rooteDeptID = 0, array('groupModel', 'createGroupManageMemberLink'), $groupID);
		$this->view->deptusers       = $deptusers;
		$this->view->groupNotInUsers = $groupNotInUsers;
		$this->view->groupUsers      = $groupUsers;
		$this->view->otherUsers      = $otherUsers;

		$this->display();
	}


    /**
     * Delete a group.
     * 
     * @param  int    $groupID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($groupID)
    {
        $this->group->updateAccounts($groupID);

        $this->group->delete($groupID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }
}
