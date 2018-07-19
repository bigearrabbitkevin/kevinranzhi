<?php
/**
 * The model file of krgoods module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
class krgoodsModel extends model
{
    /**
     * Get krgoods by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getByID($id)
    {
	    $krgoods = $this->dao->select('*')->from(TABLE_KR_GOODS)->where('id')->eq($id)->fetch();
        if(!$krgoods) return false;

        $members = $this->getMembers($id); 
        $krgoods->members = $members;
        $krgoods->PM      = '';
        foreach($members as $member) if($member->role == 'manager') $krgoods->PM = $member->account;

        $krgoods = $this->loadModel('file')->replaceImgURL($krgoods, 'desc');
        return $krgoods;
    }

    /**
     * Get members of a krgoods.
     * 
     * @param  int    $krgoods 
     * @access public
     * @return void
     */
    public function getMembers($krgoods)
    {
        return $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('krgoods')->andWhere('id')->eq($krgoods)->fetchAll('account');
    }

    /**
     * Get member pairs.
     * 
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getMemberPairs($id)
    {
        $members = $this->dao->select('account')->from(TABLE_TEAM)->where('type')->eq('krgoods')->andWhere('id')->eq($id)->fetchPairs('account');
        $users   = $this->dao->select('account, realname')->from(TABLE_USER)->where('account')->in($members)->orderBy('id_asc')->fetchPairs();
        foreach($users as $account => $realname) if($realname == '') $users[$account] = $account; 
        return array('' => '') + $users;
    }

    /**
     * Get krgoods list.
     * 
     * @param  string $status 
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($status = null, $orderBy = 'id_desc', $pager = null)
    {
         $projects = $this->dao->select('*')->from(TABLE_KR_GOODS)
                ->where('deleted')->eq(0)
               // ->beginIF($status)->andWhere('status')->eq($status)->fi()
                //->orderBy($orderBy)
                ->fetchAll();

        return $projects;
    }

    /**
     * Get  krgoods pairs.
     * 
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairs($orderBy = 'id_desc')
    {
        return $this->dao->select('id,name')->from(TABLE_KR_GOODS)->where('deleted')->eq(0)->orderBy($orderBy)->fetchPairs();
    }

    /**
     * create 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $members = array_unique(array_merge(array($this->post->manager), (array)$this->post->member));
        $krgoods = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('whitelist', ',')
            ->remove('member,manager,master')
            ->stripTags('desc', $this->config->allowedTags)
            ->get();

        $krgoods = $this->loadModel('file')->processImgURL($krgoods, $this->config->krgoods->editor->create['id']);
        $this->dao->insert(TABLE_KR_GOODS)
            ->data($krgoods, $skip = 'uid')
            ->autoCheck()
            ->batchCheck($this->config->krgoods->require->create, 'notempty')
            ->checkIF($krgoods->end, 'end', 'ge', $krgoods->begin)
            ->exec();

        if(dao::isError()) return false;
        $id = $this->dao->lastInsertId();

        return $id;
    }

    /**
     * Update krgoods 
     * 
     * @param  int    $id 
     * @param  mix    $krgoods
     * @access public
     * @return bool
     */
    public function update($id, $krgoods = null)
    {
        $oldProject = $this->getByID($id);
		$krgoods = fixer::input('post')
			->add('editedBy', $this->app->user->account)
			->add('editedDate', helper::now())
			->join('whitelist', ',')
			->setDefault('whitelist', '')
			->stripTags('desc', $this->config->allowedTags)
			->remove('member,manager,master')
			->get();

        $this->dao->update(TABLE_KR_GOODS)
            ->data($krgoods, $skip = 'uid')
            ->autoCheck()
            ->batchCheck($this->config->krgoods->require->create, 'notempty')
            ->checkIF($krgoods->end, 'end', 'ge', $krgoods->begin)
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;

        /* Update manager. */
        return commonModel::createChanges($oldProject, $krgoods);
    }


    /**
     * Active krgoods.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function activate($id)
    {
        $krgoods = new stdclass();
        $krgoods->status     = 'doing';
        $krgoods->editedBy   = $this->app->user->account;
        $krgoods->editedDate = helper::now();

        $this->dao->update(TABLE_KR_GOODS)->data($krgoods)->where('id')->eq((int)$id)->exec();
        return !dao::isError();
    }

    /**
     * Suspend krgoods.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function suspend($id)
    {
        $krgoods = new stdclass();
        $krgoods->status     = 'suspend';
        $krgoods->editedBy   = $this->app->user->account;
        $krgoods->editedDate = helper::now();

        $this->dao->update(TABLE_KR_GOODS)->data($krgoods)->where('id')->eq((int)$id)->exec();
        return !dao::isError();
    }

    /**
     * Finish krgoods.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function finish($id)
    {
        $krgoods = new stdclass();
        $krgoods->status     = 'finished';
        $krgoods->editedBy   = $this->app->user->account;
        $krgoods->editedDate = helper::now();

        $this->dao->update(TABLE_KR_GOODS)->data($krgoods)->where('id')->eq((int)$id)->exec();
        return !dao::isError();
    }

    /**
     * Delete tasks of krgoods.
     * 
     * @param  int    $id 
     * @access public
     * @return bool 
     */
    public function deleteTasks($id)
    {
        $this->dao->update(TABLE_TASK)->set('deleted')->eq('1')->where('krgoods')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * Delete doclib of krgoods.
     * 
     * @param  int    $id 
     * @access public
     * @return bool 
     */
    public function deleteDoclib($id)
    {
        $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq('1')->where('krgoods')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * Import tasks.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function importTask($id)
    {
        $this->loadModel('task');

        /* Update tasks. */
        $tasks = $this->dao->select('id, krgoods, assignedTo, consumed, status')->from(TABLE_TASK)->where('id')->in($this->post->tasks)->fetchAll('id');
        foreach($tasks as $task)
        {
            /* Save the assignedToes, should linked to krgoods. */
            $assignedToes[$task->assignedTo] = $task->krgoods;

            $data = new stdclass();
            $data->krgoods = $id;

            if($task->status == 'cancel')
            {
                $data->canceledBy = '';
                $data->canceledDate = NULL;
            }

            $data->status = $task->consumed > 0 ? 'doing' : 'wait';
            $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();

            if(dao::isError()) return false;

            $this->loadModel('action')->create('task', $task->id, 'moved', '', $task->krgoods);
        }

        /* Add members to krgoods team. */
        $members = $this->getMemberPairs($id);
        foreach($assignedToes as $account => $preProjectID)
        {
            if(!isset($members[$account]))
            {
                $role = $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('krgoods')->andWhere('id')->eq($preProjectID)->andWhere('account')->eq($account)->fetch();
                if(!isset($role))
                {
                    $role = new stdclass();
                    $role->type    = 'krgoods';
                    $role->account = $account;
                }
                $role->id   = $id;
                $role->join = helper::today();
                $this->dao->insert(TABLE_TEAM)->data($role)->exec();
                
                return !dao::isError();
            }
        }

        return true;
    }

    /**
     * Save the krgoods id user last visited to session.
     * 
     * @param  int   $id 
     * @param  array $projects 
     * @access public
     * @return int
     */
    public function saveState($id, $projects)
    {
        if($id > 0) $this->session->set('krgoods', (int)$id);
        if($id == 0 and $this->cookie->lastProject)    $this->session->set('krgoods', (int)$this->cookie->lastProject);
        if($id == 0 and $this->session->krgoods == '') $this->session->set('krgoods', $projects[0]);
        if(!in_array($this->session->krgoods, $projects)) $this->session->set('krgoods', $projects[0]);
        return $this->session->krgoods;
    }

    /**
     * Set menu.
     * 
     * @param  array  $projects 
     * @param  int    $id 
     * @param  string $extra
     * @access public
     * @return void
     */
    public function setMenu($projects, $id, $extra = '')
    {
        /* Check the privilege. */
        $krgoods = $this->getById($id);

        if($projects and !isset($projects[$id]))
        {
            die(js::locate('back'));
        }

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();

        if($this->cookie->projectMode == 'noclosed' and $krgoods->status == 'finished') 
        {
            setcookie('projectMode', 'all');
            $this->cookie->projectMode = 'all';
        }

        $selectHtml = $this->select($projects, $id, $moduleName, $methodName, $extra);

        echo  $selectHtml;
    }

    /**
     * Create the select code of projects. 
     * 
     * @param  array     $projects 
     * @param  int       $id 
     * @param  string    $currentModule 
     * @param  string    $currentMethod 
     * @param  string    $extra
     * @access public
     * @return string
     */
    public function select($projects, $id, $currentModule, $currentMethod, $extra = '')
    {
        if(!$id) return;
        $krgoods = $this->getByID($id);

        setCookie("lastProject", $id, $this->config->cookieLife, $this->config->webRoot);
        $currentProject = $this->getById($id);

        $methodName = $this->app->getMethodName();
        $moduleName = $this->app->getModuleName();
            
        $this->app->loadLang('task', 'sys');

        $menu  = "<nav id='menu'><ul class='nav'>";
        $menu .= "<li><a id='currentItem' href=\"javascript:showDropMenu('krgoods', '$id', '$currentModule', '$currentMethod', '$extra')\"><i class='icon-folder-open-alt'></i> <strong>{$currentProject->name}</strong> <span class='icon-caret-down'></span></a><div id='dropMenu'></div></li>";

        $viewIcons = array('browse' => 'list-ul', 'kanban' => 'columns', 'outline' => 'list-alt');
        $this->lang->task->browse = $this->lang->task->list;

        if($methodName ==  'browse' or $methodName == 'importtask' or $moduleName == 'doc')
        {
            $menu .= '<li class="divider angle"></li>';
            $menu .= commonModel::printLink('task', 'browse', "id=$id&mode=all", $this->lang->task->all, '', false, '', 'li');
            if(isset($currentProject->members[$this->app->user->account])) $menu .= commonModel::printLink('task', 'browse', "id=$id&mode=assignedTo", $this->lang->task->assignedToMe, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "id=$id&mode=createdBy",  $this->lang->task->createdByMe, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "id=$id&mode=finishedBy", $this->lang->task->finishedByMe, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "id=$id&mode=untilToday", $this->lang->task->untilToday, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "id=$id&mode=expired",    $this->lang->task->expired, '', false, '', 'li');
            $menu .= commonModel::printLink('proj.doc', 'projectLibs', "id=$id", $this->lang->krgoods->doc, '', false, '', 'li');

            if($this->app->user->admin == 'super' || $this->app->user->account == $krgoods->createdBy || $this->app->user->account == $krgoods->PM)
            {
                $menu .= "<li>";
                $menu .= "<a data-toggle='dropdown' class='dropdown-toggle' href='#'>" . $this->lang->krgoods->krgoods. " <i class='icon-caret-down'></i></a>";
                $menu .= "<ul class='dropdown-menu'>";
                $menu .= commonModel::printLink('krgoods', 'view', "id=$id", $this->lang->view, "data-toggle='modal'", false, '', 'li');
                $menu .= commonModel::printLink('krgoods', 'edit', "id=$id", $this->lang->edit, "data-toggle='modal'", false, '', 'li');
                $menu .= commonModel::printLink('krgoods', 'member', "id=$id", $this->lang->krgoods->member, "data-toggle='modal''", false, '', 'li');
                if($krgoods->status != 'finished') $menu .= commonModel::printLink('krgoods','finish', "id=$id", $this->lang->finish, "data-toggle='modal'", false, '', 'li');
                if($krgoods->status != 'doing') $menu .= commonModel::printLink('krgoods', 'activate', "id=$id", $this->lang->activate, "class='switcher' data-confirm='{$this->lang->krgoods->confirm->activate}'", false, '', 'li');
                if($krgoods->status != 'suspend') $menu .= commonModel::printLink('krgoods', 'suspend', "id=$id", $this->lang->krgoods->suspend, "class='switcher' data-confirm='{$this->lang->krgoods->confirm->suspend}'", false, '', 'li');
                $menu .= commonModel::printLink('krgoods', 'delete', "id=$id", $this->lang->delete, "class='deleter'", false, '', 'li');
                $menu .= "</ul></li>";
            }
            else
            {
                $menu .= "<li>" . html::a(helper::createLink('krgoods', 'view', "id=$id"), $this->lang->krgoods->krgoods, "data-toggle='modal' data-width='500'") . "</li>";
            }
        }
        else if($methodName == 'kanban' || $methodName == 'outline')
        {
            $menu .= '<li class="divider angle"></li>';
            foreach($this->lang->task->groups as $key => $value)
            {
                if(empty($key)) continue;
                $menu .= "<li data-group='{$key}'>" . commonModel::printLink('task', $methodName, "id=$id&groupBy=$key", $value, '', false) . "</li>";
            }

            if($this->app->user->admin == 'super' or $this->app->user->account == $krgoods->createdBy or $this->app->user->account == $krgoods->PM)
            {
                $menu .= "<li>";
                $menu .= "<a data-toggle='dropdown' class='dropdown-toggle' href='#'>" . $this->lang->krgoods->krgoods. " <i class='icon-caret-down'></i></a>";
                $menu .= "<ul class='dropdown-menu'>";
                $menu .= commonModel::printLink('krgoods', 'edit', "id=$id", $this->lang->edit, "data-toggle='modal'", false, '', 'li');
                $menu .= commonModel::printLink('krgoods', 'member', "id=$id", $this->lang->krgoods->member, "data-toggle='modal''", false, '', 'li');
                if($krgoods->status != 'finished') $menu .= commonModel::printLink('krgoods','finish', "id=$id", $this->lang->finish, "data-toggle='modal'", false, '', 'li');
                if($krgoods->status != 'doing') $menu .= commonModel::printLink('krgoods', 'activate', "id=$id", $this->lang->activate, "class='switcher' data-confirm='{$this->lang->krgoods->confirm->activate}'", false, '', 'li');
                if($krgoods->status != 'suspend') $menu .= commonModel::printLink('krgoods', 'suspend', "id=$id", $this->lang->krgoods->suspend, "class='switcher' data-confirm='{$this->lang->krgoods->confirm->suspend}'", false, '', 'li');
                $menu .= commonModel::printLink('krgoods', 'delete', "id=$id", $this->lang->delete, "class='deleter'", false, '', 'li');
                $menu .= "</ul></li>";
            }
        }
        else if(strpos('view,create,edit', $methodName) !== false)
        {
            $menu .= '<li class="divider angle"></li>';
            $menu .= commonModel::printLink('task', 'browse', "id=$id", $this->lang->task->browse, '', false, '', 'li');
            $menu .= '<li class="divider angle"></li>';
            $menu .= '<li class="title">' . $this->lang->{$moduleName}->{$methodName} . '</li>';
        }
        else if($methodName == 'batchcreate')
        {
            $menu .= '<li class="divider angle"></li>';
            $menu .= commonModel::printLink('task', 'browse', "id=$id", $this->lang->task->browse, '', false, '', 'li');
            $menu .= '<li class="divider angle"></li>';
            $menu .= '<li class="title">' . $this->lang->{$moduleName}->batchCreate . '</li>';
        }

        $menu .= "</ul>";

        $menu .= "<div class='btn-group pull-right'>";
        $menu .= "<button data-toggle='dropdown' class='btn btn-primary dropdown-toggle' type='button'><i class='icon-plus'> </i>" . $this->lang->create . " <span class='caret'></span></button>";
        $menu .= "<ul id='exportActionMenu' class='dropdown-menu w-100px'>";
        $menu .= "<li>" . commonModel::printLink('task', 'create', "id=$id", $this->lang->task->create, '', false) . "</li>";
        $menu .= "<li>" . commonModel::printLink('task', 'batchCreate', "id=$id", $this->lang->task->batchCreate, '', false) . "</li>";
        $menu .= "</ul>";
        $menu .= "</div>";

        if(commonModel::hasPriv('task', 'export'))
        {
            $menu .= "<div class='btn-group pull-right'>";
            $menu .= "<button data-toggle='dropdown' class='btn btn-primary dropdown-toggle' type='button'>" . $this->lang->exportIcon . $this->lang->export . " <span class='caret'></span></button>";
            $menu .= "<ul id='exportActionMenu' class='dropdown-menu w-100px'>";
            $menu .= "<li>" . commonModel::printLink('task', 'export', "mode=all&id=$id&orderBy={$extra}", $this->lang->exportAll, "class='iframe' data-width='700'", false) . "</li>";
            $menu .= "<li>" . commonModel::printLink('task', 'export', "mode=thisPage&id={$id}&orderBy={$extra}", $this->lang->exportThisPage, "class='iframe' data-width='700'", false) . "</li>";
            $menu .= "</ul>";
            $menu .= "</div>";
        }
        $menu .= "<div class='pull-right'>" . commonModel::printLink('krgoods', 'importTask', "id=$id", $this->lang->importIcon . $this->lang->krgoods->import, 'class="btn btn-primary"', false) . "</div>";

        if($methodName == 'browse' || $methodName == 'kanban' || $methodName == 'outline')
        {
            $taskListType = $methodName;
            $viewName = $this->lang->task->{$methodName};
            $menu .= "<ul class='nav pull-right'>";
            $menu .= "<li id='viewBar' class='dropdown'><a href='javascript:;' id='groupButton' data-toggle='dropdown' class='dropdown-toggle'><i class='icon-" . $viewIcons[$methodName] . "'></i> {$viewName} <i class='icon-caret-down'></i></a><ul class='dropdown-menu'>";
            $menu .= "<li" . ($methodName == 'browse' ? " class='active'" : '') . ">" . commonModel::printLink('task', 'browse', "id=$id", "<i class='icon-list-ul icon'></i> " . $this->lang->task->list, '', false) . "</li>";
            $menu .= "<li" . ($methodName == 'kanban' ? " class='active'" : '') . ">" . commonModel::printLink('task', 'kanban', "id=$id", "<i class='icon-columns icon'></i> " . $this->lang->task->kanban, '', false) . "</li>";
            $menu .= "<li" . ($methodName == 'outline' ? " class='active'" : '') . ">" . commonModel::printLink('task', 'outline', "id=$id", "<i class='icon-list-alt icon'></i> " . $this->lang->task->outline, '', false) . "</li>";
            $menu .= '</ul></li>';

            if($methodName == 'outline')
            {
                $menu .= '<li><a href="javascript:;" id="toggleAll"><i class="icon-plus"></i></a></li>';
            }
            $menu .= "</ul>";
        }

        $menu .= '</nav>';

        return $menu;
    }

    /**
     * Create the link from module,method,extra
     * 
     * @param  string  $module 
     * @param  string  $method 
     * @param  mix     $extra 
     * @access public
     * @return void
     */
    public function getProjectLink($module, $method, $extra)
    {
        $link = '';
        if($module == 'task' && ($method == 'view' || $method == 'edit' || $method == 'batchedit'))
        {   
            $module = 'task';
            $method = 'browse';
        }   

        if($module == 'krgoods' && $method == 'create') return;

        $link = helper::createLink($module, $method, "id=%s");
        if($extra != '') $link = helper::createLink($module, $method, "id=%s&type=$extra");
        return $link;
    }
    
    /**
     * check krgoods's Priv. 
     * 
     * @param  int    $krgoods 
     * @access public
     * @return bool
     */
    public function checkPriv($id)
    {
        if($this->app->user->admin == 'super') return true;
        if($this->app->getModuleName() == 'krgoods' and strpos('edit, member, finish, suspend,delete', $this->app->getMethodName()) !== false)
        {
            $krgoods = $this->getByID($id);
            if(!$this->hasActionPriv($krgoods)) return false;
        }

        if(!empty($this->app->user->rights['task']['viewall']))   return true;
        if(!empty($this->app->user->rights['task']['editall']))   return true;
        if(!empty($this->app->user->rights['task']['deleteall'])) return true;

        static $projects, $members, $groups, $groupUsers = array();
        if(empty($groups)) 
        {
            $groups = $this->loadModel('group')->getList(0);
            foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

            $members  = $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('krgoods')->fetchGroup('id', 'account');
            $projects = $this->dao->select('*')->from(TABLE_KR_GOODS)->fetchAll('id');
            foreach($projects as $krgoods)
            {
                $krgoods->members = isset($members[$krgoods->id]) ? $members[$krgoods->id] : array();
                $accountList = empty($krgoods->members) ? array() : array_keys($krgoods->members);
                $whitelist   = trim($krgoods->whitelist, ',');
                $whitelist   = empty($whitelist) ? array() : explode(',', $whitelist);
                foreach($whitelist as $groupID) foreach($groupUsers[$groupID] as $account => $realname) $accountList[] = $account;

                $krgoods->accountList = $accountList;
            }
        }

        return in_array($this->app->user->account, $projects[$id]->accountList);
    }

    /**
     * Check current user has action privilege or not. 
     * 
     * @param  int    $krgoods 
     * @access public
     * @return void
     */
    public function hasActionPriv($krgoods)
    {
        return (($this->app->user->admin == 'super') or (isset($krgoods->members[$this->app->user->account]) and $krgoods->members[$this->app->user->account]->role == 'senior') or ($this->app->user->account == $krgoods->createdBy) or ($this->app->user->account == $krgoods->PM));
    }
}
