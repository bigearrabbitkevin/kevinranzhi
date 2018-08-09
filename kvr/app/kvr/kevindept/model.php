<?php
/**
 * The model file of kevindept module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     kevindept
 * @version     $Id: model.php 4210 2013-01-22 01:06:12Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */

class kevindeptModel extends model {
	/**
	 * Process categories.
	 *
	 * @param  array  $categories
	 * @param  string $type
	 * @access public
	 * @return array
	 */
	public function process($categories = array(), $type = '')
	{
		foreach($categories as $key => $category)
		{
			if(!$this->hasRight($category, $type, $categories))
			{
				unset($categories[$key]);
				continue;
			}
		}

		return $categories;
	}

	/**
	 * Check current user has Privilege for this category.
	 *
	 * @param  mixed  $category
	 * @param  string $type
	 * @param  array  $categories
	 * @access public
	 * @return bool
	 */
	public function hasRight($category = null, $type = '', $categories = array())
	{
		if($this->app->user->admin == 'super') return true;

		if(!is_object($category)) $category = $this->getByID($category, $type);
		if(!$category) return true;

		if(empty($category->users) && empty($category->rights))
		{
			$hasRight = true;
		}
		else
		{
			$hasRight = false;
			if(!empty($category->users))
			{
				$hasRight = strpos($category->users, ',' . $this->app->user->account . ',') !== false;
			}

			if(!$hasRight && !empty($category->rights))
			{
				$groups   = array_intersect($this->app->user->groups, explode(',', $category->rights));
				$hasRight = !empty($groups);
			}

			if(!$hasRight && !empty($category->moderators))
			{
				$hasRight = in_array($this->app->user->account, $category->moderators);
			}
		}

		if($hasRight && !empty($category->parent))
		{
			$category = zget($categories, $category->parent);
			$hasRight = $this->hasRight($category, $type, $categories);
		}

		return $hasRight;
	}

	/**
	 * Build the sql to execute.
	 *
	 * @param string $type              the tree type, for example, article|forum
	 * @param int    $startCategory     the start category id
	 * @param int    $root
	 * @access public
	 * @return string
	 */
	public function buildQuery($type, $startCategory = 0, $root = 0)
	{
		/* Get the start category path according the $startCategory. */
		$startPath = '';
		if($startCategory > 0)
		{
			$startCategory = $this->getById($startCategory);
			if($startCategory) $startPath = $startCategory->path . '%';
		}

		return $this->dao->select('*')->from(TABLE_DEPT)
			->where(1)
			->beginIF($root)->andWhere('root')->eq((int)$root)->fi()
			->beginIF($startPath)->andWhere('path')->like($startPath)->fi()
			->orderBy('grade desc, `order`')
			->get();
	}

	/**
     * Get the tree menu in <ul><ol> type.
     *
     * @param  string   $type           the tree type
     * @param  int      $startCategoryID  the start category
     * @param  string   $userFunc       which function to be called to create the link
     * @param  int      $root
     * @access public
     * @return string   the html code of the tree menu.
     */
    public function getTreeMenu($type = 'article', $startCategoryID = 0, $userFunc, $root = 0)
    {
        $treeMenu   = array();
        $categories = array();
        $stmt = $this->dbh->query($this->buildQuery($type, $startCategoryID, $root));
        while($category = $stmt->fetch())
        {
            $categories[$category->id] = $category;
        }
        $categories = $this->process($categories, $type);
        foreach($categories as $category)
        {
            $linkHtml = call_user_func($userFunc, $category);

            if(isset($treeMenu[$category->id]) and !empty($treeMenu[$category->id]))
            {
                if(!isset($treeMenu[$category->parent])) $treeMenu[$category->parent] = '';
                $treeMenu[$category->parent] .= "<li>$linkHtml";
                $treeMenu[$category->parent] .= "<ul>".$treeMenu[$category->id]."</ul>\n";
            }
            else
            {
                if(isset($treeMenu[$category->parent]) and !empty($treeMenu[$category->parent]))
                {
                    $treeMenu[$category->parent] .= "<li>$linkHtml\n";
                }
                else
                {
                    $treeMenu[$category->parent] = "<li>$linkHtml\n";
                }
            }
            $treeMenu[$category->parent] .= "</li>\n";
        }
        $lastMenu = "<ul class='tree'>" . @array_pop($treeMenu) . "</ul>\n";
        return $lastMenu;
    }

	/**
	 * Create the manage link.
	 *
	 * @param  int $kevindept
	 * @access public
	 * @return string
	 */
	public function createManageLink($kevindept) {
		$linkHtml = $kevindept->name;
		if (commonModel::hasPriv('kevindept', 'edit')) $linkHtml .= ' '.html::a(helper::createLink('kevindept', 'edit', "deptid={$kevindept->id}"), $this->lang->edit, '', 'data-toggle="modal" data-type="ajax"');
		if (commonModel::hasPriv('kevindept', 'browse')) $linkHtml .= ' '.html::a(helper::createLink('kevindept', 'browse', "deptid={$kevindept->id}"), $this->lang->kevindept->manageChild);
		if (commonModel::hasPriv('kevindept', 'delete')) $linkHtml .= ' '.html::a(helper::createLink('kevindept', 'delete', "deptid={$kevindept->id}"), $this->lang->delete, 'hiddenwin');
		if (commonModel::hasPriv('kevindept', 'updateOrder')) $linkHtml .= ' '.html::input("orders[$kevindept->id]", $kevindept->order, 'style="width:30px;text-align:center"');
		return $linkHtml;
	}

	/**
	 * Create the member link.
	 *
	 * @param  int $kevindept
	 * @access public
	 * @return string
	 */
	public function createMemberLink($kevindept) {
		$linkHtml = html::a(helper::createLink('kevindept', 'browse', "kevindept={$kevindept->id}"), $kevindept->name, '_self', "id='kevindept{$kevindept->id}'");
		return $linkHtml;
	}

	/**
	 * Create the group manage members link.
	 *
	 * @param  int $kevindept
	 * @param  int $groupID
	 * @access public
	 * @return string
	 */
	public function createGroupManageMemberLink($kevindept, $groupID) {
		return html::a(helper::createLink('group', 'managemember', "groupID=$groupID&deptID={$kevindept->id}"), $kevindept->name, '_self', "id='kevindept{$kevindept->id}'");
	}

	/**
	 * Delete a department.
	 *
	 * @param  int  $deptID
	 * @param  null $null compatible with that of model::delete()
	 * @access public
	 * @return void
	 */
	public function delete($deptID, $null = null) {
		$this->dao->delete()->from(TABLE_DEPT)->where('id')->eq($deptID)->exec();
	}

	/**
	 * Batch delete dept.
	 *
	 * @param  array    $deptIDList
	 * @access public
	 * @return void
	 */
	public function deptBatchDelete($deptIDList) {
		$this->dao->update(TABLE_DEPT)->set('deleted')->eq(1)->where('id')->in($deptIDList)->exec();
	}

	/**
	 * Batch update dept.
	 *
	 * @access public
	 * @return array
	 */
	public function deptBatchUpdate() {
		$depts		 = array();
		$allChanges	 = array();
		$data		 = fixer::input('post')->get();
		$oldDepts	 = $this->getDeptByIdList($this->post->deptIDList);
		$deptModel	 = $this->loadModel('kevindept');
		foreach ($data->deptIDList as $deptID) {
			$parent			 = $deptModel->getById($data->parent[$deptID]);
			$depts[$deptID]	 = new stdClass();

			$depts[$deptID]->parent	 = $data->parent[$deptID];
			$depts[$deptID]->name	 = $data->name[$deptID];
			$depts[$deptID]->manager = $data->manager[$deptID];
			$depts[$deptID]->email	 = $data->email[$deptID];
			$depts[$deptID]->code	 = $data->code[$deptID];
			$depts[$deptID]->grade	 = $parent ? $parent->grade + 1 : 1;
			$depts[$deptID]->path	 = $parent ? $parent->path . $deptID . ',' : ',' . $deptID . ',';
			$depts[$deptID]->order	 = $data->order[$deptID];
			if ($data->group[$deptID]) {
				foreach ($data->group[$deptID] as $groupID) {
					$depts[$deptID]->group .= "," . $groupID;
				}

				$depts[$deptID]->group .= ",";
			}
		}

		foreach ($depts as $deptID => $dept) {
			$oldDept = $oldDepts[$deptID];

			$this->dao->update(TABLE_DEPT)->data($dept)
				->autoCheck()
				->batchCheck('parent,name,order', 'notempty')
				->where('id')->eq($deptID)
				->limit(1)
				->exec();

			if (dao::isError()) die(js::error('deptBatchUpdate#' . $deptID . dao::getError(true)));
			$allChanges[$deptID] = commonModel::createChanges($oldDept, $dept);
		}
		return $allChanges;
	}

	/**
	 * create dept.
	 *
	 * @access public
	 * @return int
	 */
	public function deptCreate() {
		$postData		 = fixer::input('post')->get();
		$deptModel		 = $this->loadModel('kevindept');
		$parent			 = $deptModel->getById($this->post->parent);
		$dept			 = new stdClass();
		$dept->parent	 = $postData->parent;
		$dept->name		 = $postData->name;
		$dept->manager	 = $postData->manager;
		$dept->email	 = $postData->email;
		$dept->code		 = $postData->code;
		$dept->order	 = $postData->order;
		$dept->grade	 = $parent ? $parent->grade + 1 : 1;

		if ($this->post->group) {
			foreach ($this->post->group as $groupID) {
				$dept->group .= "," . $groupID;
			}

			$dept->group .= ",";
		}
		$this->dao->insert(TABLE_DEPT)->data($dept)
			->autoCheck()
			->batchCheck('name,order', 'notempty')
			->exec();
		$id = $this->dbh->lastInsertID();

		$path = $parent ? $parent->path . $id . ',' : ',' . $id . ',';
		$this->dao->update(TABLE_DEPT)
			->set('path')->eq($path)
			->autoCheck()
			->where('id')
			->eq($id)
			->exec();

		return $id;
	}

	/**
	 * Delete a dept.
	 *
	 * @param  int    $id
	 * @access public
	 * @return void
	 */
	public function deptDelete($id) {
		$this->dao->update(TABLE_DEPT)
			->set('deleted')
			->eq(1)
			->where('id')
			->eq($id)
			->exec();
	}

	/**
	 * Update dept.
	 *
	 * @param  int    $id
	 * @access public
	 * @return array
	 */
	public function deptUpdate($id) {
		$allChanges		 = array();
		$postData		 = fixer::input('post')->get();
		$deptModel		 = $this->loadModel('kevindept');
		$oldDept		 = $deptModel->getById($id);
		$parent			 = $deptModel->getById($this->post->parent);
		$dept			 = new stdClass();
		$dept->parent	 = $postData->parent;
		$dept->name		 = $postData->name;
		$dept->manager	 = $postData->manager;
		$dept->email	 = $postData->email;
		$dept->code		 = $postData->code;
		$dept->order	 = $postData->order;
		$dept->grade	 = $parent ? $parent->grade + 1 : 1;
		$dept->path		 = $parent ? $parent->path . $id . ',' : ',' . $id . ',';
		if ($this->post->group) {
			foreach ($this->post->group as $groupID) {
				$dept->group .= "," . $groupID;
			}

			$dept->group .= ",";
		}else{
			$dept->group = '';
		}
		$this->dao->update(TABLE_DEPT)
			->data($dept)
			->autocheck('order')
			->batchCheck('name', 'notempty')
			->where('id')
			->eq($id)
			->exec();
		$allChanges[$id] = commonModel::createChanges($oldDept, $dept);

		return $allChanges;
	}

	/**
	 * Fix kevindept path.
	 *
	 * @access public
	 * @return void
	 */
	public function fixDeptPath() {
		/* Get all depts grouped by parent. */
		$groupDepts = $this->dao->select('id, parent')->from(TABLE_DEPT)->fetchGroup('parent', 'id');
		$depts      = array();

		/* Cycle the groupDepts until it has no item any more. */
		while (count($groupDepts) > 0) {
			$oldCounts = count($groupDepts);    // Record the counts before processing.
			foreach ($groupDepts as $parentDeptID => $childDepts) {
				/* If the parentDept doesn't exsit in the depts, skip it. If exists, compute it's child depts. */
				if (!isset($depts[$parentDeptID]) and $parentDeptID != 0) continue;
				if ($parentDeptID == 0) {
					$parentDept        = new stdclass();
					$parentDept->grade = 0;
					$parentDept->path  = ',';
				} else {
					$parentDept = $depts[$parentDeptID];
				}

				/* Compute it's child depts. */
				foreach ($childDepts as $childDeptID => $childDept) {
					$childDept->grade    = $parentDept->grade + 1;
					$childDept->path     = $parentDept->path.$childDept->id.',';
					$depts[$childDeptID] = $childDept;    // Save child kevindept to depts, thus the child of child can compute it's grade and path.
				}
				unset($groupDepts[$parentDeptID]);    // Remove it from the groupDepts.
			}
			if (count($groupDepts) == $oldCounts) break;   // If after processing, no kevindept processed, break the cycle.
		}

		/* Save depts to database. */
		foreach ($depts as $kevindept) {
			$this->dao->update(TABLE_DEPT)->data($kevindept)->where('id')->eq($kevindept->id)->exec();
		}
	}

	/**
	 * Get by id.
	 *
	 * @param  array    $deptID
	 * @access public
	 * @return array
	 */
	public function getDept($deptID) {
		return $this->dao->select('a.*, b.name as parentName')
			->from(TABLE_DEPT)->alias('a')
			->leftJoin(TABLE_DEPT)->alias('b')->on('a.parent=b.id')
			->where('a.id')->eq($deptID)
			->fetch();
	}

	/**
	 * Get the account=>realname pairs.
	 *
	 * @param  string $params   noletter|noempty|noclosed|nodeleted|withguest|pofirst|devfirst|qafirst|pmfirst|realname, can be sets of theme
	 * @param  string $usersToAppended  account1,account2
	 * @access public
	 * @return array
	 */
	public function getPairs($params = '', $usersToAppended = '')
	{
		if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getUserPairs();
		/* Set the query fields and orderBy condition.
		 *
		 * If there's xxfirst in the params, use INSTR function to get the position of role fields in a order string,
		 * thus to make sure users of this role at first.
		 */
		$fields = 'account, realname, deleted';
		if(strpos($params, 'pofirst') !== false) $fields .= ", INSTR(',pd,po,', role) AS roleOrder";
		if(strpos($params, 'pdfirst') !== false) $fields .= ", INSTR(',po,pd,', role) AS roleOrder";
		if(strpos($params, 'qafirst') !== false) $fields .= ", INSTR(',qd,qa,', role) AS roleOrder";
		if(strpos($params, 'qdfirst') !== false) $fields .= ", INSTR(',qa,qd,', role) AS roleOrder";
		if(strpos($params, 'pmfirst') !== false) $fields .= ", INSTR(',td,pm,', role) AS roleOrder";
		if(strpos($params, 'devfirst')!== false) $fields .= ", INSTR(',td,pm,qd,qa,dev,', role) AS roleOrder";
		$orderBy = strpos($params, 'first') !== false ? 'roleOrder DESC, account' : 'account';

		/* Get raw records. */
		$users = $this->dao->select($fields)->from(TABLE_USER)
			->beginIF(strpos($params, 'nodeleted') !== false)->where('deleted')->eq(0)->fi()
			->orderBy($orderBy)
			->fetchAll('account');
		if($usersToAppended) $users += $this->dao->select($fields)->from(TABLE_USER)->where('account')->in($usersToAppended)->fetchAll('account');

		/* Cycle the user records to append the first letter of his account. */
		foreach($users as $account => $user)
		{
			$firstLetter = ucfirst(substr($account, 0, 1)) . ':';
			if(strpos($params, 'noletter') !== false){
				$users[$account] =  (($user->deleted and strpos($params, 'realname') === false) ? $account : ($user->realname ? $user->realname : $account));

			} else {
				$users[$account] =  $firstLetter . (($user->deleted and strpos($params, 'realname') === false) ? $account : ($user->realname ? $user->realname . "(".$account.")": $account));
			}
		}

		/* Append empty, closed, and guest users. */
		if(strpos($params, 'noempty')   === false) $users = array('' => '') + $users;
		if(strpos($params, 'noclosed')  === false) $users = $users + array('closed' => 'Closed');
		if(strpos($params, 'withguest') !== false) $users = $users + array('guest' => 'Guest');

		return $users;
	}

	/**
	 * Get classList.
	 *
	 * @param  object $pager
	 * @param  array $filter
	 * @access public
	 * @return array
	 */
	public function getDeptList($orderBy, $pager, $filter) {
		$deptlist = null;
		if (empty($filter)) {
			$deptlist = $this->dao->select('a.id,a.name,a.parent,a.path,  a.order, a.manager, a.email, a.code, b.name as parentName, c.realname')
				->from(TABLE_DEPT)->alias('a')
				->leftJoin(TABLE_DEPT)->alias('b')->on('a.parent=b.id')
				->leftJoin(TABLE_USER)->alias('c')
				->on('a.manager=c.account')
				->where('a.deleted')->eq(0)
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();
		} else {
			$path		 = !empty($filter['path']) ? "%," . $filter['path'] . ",%" : '';
			$manager = $filter['manager'];
			$deptlist	 = $this->dao->select('a.id,a.name,a.parent, a.path, a.order, a.manager, a.email, a.code,a.group, b.name as parentName, c.realname')
				->from(TABLE_DEPT)->alias('a')
				->leftJoin(TABLE_DEPT)->alias('b')->on('a.parent=b.id')
				->leftJoin(TABLE_USER)->alias('c')
				->on('a.manager=c.account')
				->where(true)
				->beginIF(empty($filter['deleted']))->andWhere('a.deleted')->eq(0)->FI()
				->beginIF(!empty($filter['deleted']))->andWhere('a.deleted')->eq($filter['deleted'])->FI()
				->beginIF(!empty($filter['manager']))->andWhere('a.manager')->like("%$manager%")->FI()
				->beginIF(!empty($filter['group']))->andWhere('a.group')->ne('')->FI()
				->beginIF(!empty($filter['path']))->andWhere('a.path')->like($path)->FI()
				->orderBy($orderBy)
				->page($pager)
				->fetchAll('id');
		}
		return $deptlist;
	}

	/**
	 * Get user account and realname pairs
	 *
	 * @access public
	 * @return array
	 */
	public function getUserPairs()
	{
		return array(""=>' ')+$this->dao->select('account, CONCAT(realname," (",account,")") realname')->from(TABLE_USER)->fetchPairs();
	}

	/**
	 * Get all childs.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return array
	 */
	public function getAllChildId($deptID) {
		if ($deptID == 0) return array();
		$kevindept = $this->getById($deptID);
		$childs    = $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like($kevindept->path.'%')->fetchPairs();
		return array_keys($childs);
	}

	/**
	 * Get a department by id.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return object
	 */
	public function getByID($deptID) {
		return $this->dao->findById($deptID)->from(TABLE_DEPT)->fetch();
	}

	/**
	 * Get data structure
	 * @param  integer $rootDeptID
	 * @access public
	 * @return object
	 */
	public function getDataStructure($rootDeptID = 0) {
		$tree  = array_values($this->getSons($rootDeptID));
		$users = $this->loadModel('user')->getPairs('nodeleted|noletter|noclosed');
		if (count($tree)) {
			foreach ($tree as $node) {
				$node->managerName = $users[$node->manager];
				$children          = $this->getDataStructure($node->id);
				if (count($children)) {
					$node->children = $children;
					$node->actions  = array('delete' => false);
				}
			}
		}
		return $tree;
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
			->beginIF($deptID)->andWhere('kevindept')->in($childDepts)->fi()
			->orderBy('account')
			->fetchPairs();
	}

	/**
	 * 获取上级部门列表
	 * @access public
	 * @return array
	 */
	public function getdeptParents() {
		$parentlist = $this->dao->select('DISTINCT parent')->from(TABLE_DEPT)
			->fetchPairs();
		$arr        = $this->dao->select('id,name')->from(TABLE_DEPT)
			->where('id')->in($parentlist)
			->orderBy('id')
			->fetchPairs();

		return array(0 => 'ALL') + $arr;

	}

	/**
	 * Get option menu of departments.
	 *
	 * @param  int $rootDeptID
	 * @access public
	 * @return array
	 */
	public function getOptionMenu($rootDeptID = 0) {
		$deptMenu = array();
		$stmt     = $this->dbh->query($this->buildMenuQuery($rootDeptID));
		$depts    = array();
		while ($kevindept = $stmt->fetch()) $depts[$kevindept->id] = $kevindept;

		foreach ($depts as $kevindept) {
			$parentDepts = explode(',', $kevindept->path);
			$deptName    = '/';
			foreach ($parentDepts as $parentDeptID) {
				if (empty($parentDeptID)) continue;
				$deptName .= $depts[$parentDeptID]->name.'/';
			}
			$deptName = rtrim($deptName, '/');
			$deptName .= "|$kevindept->id\n";

			if (isset($deptMenu[$kevindept->id]) and !empty($deptMenu[$kevindept->id])) {
				if (isset($deptMenu[$kevindept->parent])) {
					$deptMenu[$kevindept->parent] .= $deptName;
				} else {
					$deptMenu[$kevindept->parent] = $deptName;;
				}
				$deptMenu[$kevindept->parent] .= $deptMenu[$kevindept->id];
			} else {
				if (isset($deptMenu[$kevindept->parent]) and !empty($deptMenu[$kevindept->parent])) {
					$deptMenu[$kevindept->parent] .= $deptName;
				} else {
					$deptMenu[$kevindept->parent] = $deptName;
				}
			}
		}

		$topMenu    = @array_pop($deptMenu);
		$topMenu    = explode("\n", trim($topMenu));
		$lastMenu[] = '/';
		foreach ($topMenu as $menu) {
			if (!strpos($menu, '|')) continue;
			list($label, $deptID) = explode('|', $menu);
			$lastMenu[$deptID] = $label;
		}
		return $lastMenu;
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
	 * Get sons of a department.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return array
	 */
	public function getSons($deptID) {
		return $this->dao->select('*')->from(TABLE_DEPT)->where('parent')->eq($deptID)->orderBy('`order`')->fetchAll();
	}

	/**
	 * Build the query.
	 *
	 * @param  int $rootDeptID
	 * @access public
	 * @return string
	 */
	public function buildMenuQuery($rootDeptID) {
		$rootDept = $this->getByID($rootDeptID);
		if (!$rootDept) {
			$rootDept       = new stdclass();
			$rootDept->path = '';
		}

		return $this->dao->select('*')->from(TABLE_DEPT)
			->beginIF($rootDeptID > 0)->where('path')->like($rootDept->path.'%')->fi()
			->orderBy('grade desc, `order`')
			->get();
	}

	/**
	 * Get users of a deparment.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return array
	 */
	public function getUsers($deptID, $pager = null, $orderBy = 'id') {
		return $this->dao->select('*')->from(TABLE_USER)
			->where('deleted')->eq(0)
			->beginIF($deptID)->andWhere('dept')->in($deptID)->fi()
			->orderBy($orderBy)
			->page($pager)
			->fetchAll();
	}

	/**
	 * Manage childs.
	 *
	 * @param  int    $parentDeptID
	 * @param  string $childs
	 * @access public
	 * @return void
	 */
	public function manageChild($parentDeptID, $childs) {
		$parentDept = $this->getByID($parentDeptID);
		if ($parentDept) {
			$grade      = $parentDept->grade + 1;
			$parentPath = $parentDept->path;
		} else {
			$grade      = 1;
			$parentPath = ',';
		}

		$i = 1;
		foreach ($childs as $deptID => $deptName) {
			if (empty($deptName)) continue;
			if (is_numeric($deptID)) {

				$kevindept->name   = strip_tags($deptName);
				$kevindept->parent = $parentDeptID;
				$kevindept->grade  = $grade;
				$kevindept->order  = $this->post->maxOrder + $i * 10;
				$this->dao->insert(TABLE_DEPT)->data($kevindept)->exec();
				$deptID    = $this->dao->lastInsertID();
				$childPath = $parentPath."$deptID,";
				$this->dao->update(TABLE_DEPT)->set('path')->eq($childPath)->where('id')->eq($deptID)->exec();
				$i++;
			} else {
				$deptID = str_replace('id', '', $deptID);
				$this->dao->update(TABLE_DEPT)->set('name')->eq(strip_tags($deptName))->where('id')->eq($deptID)->exec();
			}
		}
	}

	/**
	 * Update kevindept.
	 *
	 * @param  int $deptID
	 * @access public
	 * @return void
	 */
	public function update($deptID) {
		$kevindept        = fixer::input('post')->get();
		$self             = $this->getById($deptID);
		$parent           = $this->getById($this->post->parent);
		$childs           = $this->getAllChildId($deptID);
		$kevindept->grade = $parent ? $parent->grade + 1 : 1;
		$kevindept->path  = $parent ? $parent->path.$deptID.',' : ','.$deptID.',';
		$this->dao->update(TABLE_DEPT)->data($kevindept)->autoCheck()->check('name', 'notempty')->where('id')->eq($deptID)->exec();
		$this->dao->update(TABLE_DEPT)->set('grade = grade + 1')->where('id')->in($childs)->andWhere('id')->ne($deptID)->exec();
		$this->dao->update(TABLE_DEPT)->set('manager')->eq($this->post->manager)->where('id')->in($childs)->andWhere('manager')->eq('')->exec();
		$this->dao->update(TABLE_DEPT)->set('manager')->eq($this->post->manager)->where('id')->in($childs)->andWhere('manager')->eq($self->manager)->exec();
		$this->fixDeptPath();
	}

	/**
	 * Update order.
	 *
	 * @param  int $orders
	 * @access public
	 * @return void
	 */
	public function updateOrder($orders) {
		foreach ($orders as $deptID => $order) $this->dao->update(TABLE_DEPT)->set('`order`')->eq($order)->where('id')->eq($deptID)->exec();
	}

	/**
	 * 将dept数据同步到category.
	 *
	 * @access public
	 * @return string
	 */
	public function syncDeptCategory() {
		$maxId = $this->dao->select('`id`')->from(TABLE_DEPT)->orderBy('id_desc')->limit(1)->fetch('id');
		//如果部门id超过9999就报错返回
		if($maxId > $this->config->kevindept->MaxDeptID){
			return $this->lang->kevindept->error->maxDeptId;
		}
		$categories = $this->dao->select('*')->from(TABLE_CATEGORY)->fetchAll('id');
		$depts = $this->dao->select('*')->from(TABLE_DEPT)->fetchAll('id');

		foreach ($depts as $dept) {
			$statusCount[] = 'TOTAL';
			$deptNew                    = new stdclass();
			$deptNew->id                = $dept->id;
			$deptNew->name              = $dept->name;
			$deptNew->parent            = $dept->parent;
			$deptNew->path              = $dept->path;
			$deptNew->grade             = $dept->grade;
			$deptNew->order             = $dept->order;
			$deptNew->type              = 'dept';
			//判断本地部门数据是否存在对应部门id
			if (array_key_exists($dept->id, $categories)) {
				//如果数据不相同,则更新
				if (!$this->isSameDept($categories[$dept->id], $depts[$dept->id])) {
					$this->dao->update(TABLE_CATEGORY)->data($deptNew)->where('id')->eq($dept->id)->exec();
					$statusCount[]=$this->config->kevindept->importStatus[1];
				}else{
					$statusCount[]=$this->config->kevindept->importStatus[3];
				}
			} else {
				//不存在部门id,则新增
				$this->dao->insert(TABLE_CATEGORY)->data($deptNew)->exec();
				$statusCount[]=$this->config->kevindept->importStatus[2];
			}
		}
		//本地多于Ucenter的部门数据标记删除
//		foreach ($categories as $item) {
//			if (!array_key_exists($item->id, $depts) && $item->deleted == 0) {
//				$this->dao->update(TABLE_CATEGORY)->set('deleted')->eq(1)->where('id')->eq($item->id)->exec();
//			}
//		}

		$statusCountArr = array_count_values($statusCount);

		//将TOTAL移到数组最后一位
		$tmp = $statusCountArr['TOTAL'];
		unset($statusCountArr['TOTAL']);
		$statusCountArr['TOTAL']=$tmp;

		$str ='Update Category Success!';
		//拼接提示信息
		foreach ($statusCountArr as $k=>$v) {
			$str .="\n". $k.':  '.$v;
		}
		return $str;

	}

	/*
    * 查询部门是否相同
    */
	private function isSameDept(& $deptSource, & $deptTarget) {
		if ($deptSource->name != $deptTarget->name) return false;
		if ($deptSource->parent != $deptTarget->parent) return false;
		if ($deptSource->path != $deptTarget->path) return false;
		if ($deptSource->grade != $deptTarget->grade) return false;
		if ($deptSource->order != $deptTarget->order) return false;

		return true; //没有发现不同的
	}
}
