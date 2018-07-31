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
?>
<?php
class kevindeptModel extends model
{
    /**
     * Get a department by id.
     * 
     * @param  int    $deptID 
     * @access public
     * @return object
     */
    public function getByID($deptID)
    {
        return $this->dao->findById($deptID)->from(TABLE_DEPT)->fetch();
    }

    /**
     * Build the query.
     * 
     * @param  int    $rootDeptID 
     * @access public
     * @return string
     */
    public function buildMenuQuery($rootDeptID)
    {
        $rootDept = $this->getByID($rootDeptID);
        if(!$rootDept)
        {
            $rootDept = new stdclass();
            $rootDept->path = '';
        }

        return $this->dao->select('*')->from(TABLE_DEPT)
            ->beginIF($rootDeptID > 0)->where('path')->like($rootDept->path . '%')->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Get option menu of departments.
     * 
     * @param  int    $rootDeptID 
     * @access public
     * @return array
     */
    public function getOptionMenu($rootDeptID = 0)
    {
        $deptMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($rootDeptID));
        $depts = array();
        while($kevindept = $stmt->fetch()) $depts[$kevindept->id] = $kevindept;

        foreach($depts as $kevindept)
        {
            $parentDepts = explode(',', $kevindept->path);
            $deptName = '/';
            foreach($parentDepts as $parentDeptID)
            {
                if(empty($parentDeptID)) continue;
                $deptName .= $depts[$parentDeptID]->name . '/';
            }
            $deptName = rtrim($deptName, '/');
            $deptName .= "|$kevindept->id\n";

            if(isset($deptMenu[$kevindept->id]) and !empty($deptMenu[$kevindept->id]))
            {
                if(isset($deptMenu[$kevindept->parent]))
                {
                    $deptMenu[$kevindept->parent] .= $deptName;
                }
                else
                {
                    $deptMenu[$kevindept->parent] = $deptName;;
                }
                $deptMenu[$kevindept->parent] .= $deptMenu[$kevindept->id];
            }
            else
            {
                if(isset($deptMenu[$kevindept->parent]) and !empty($deptMenu[$kevindept->parent]))
                {
                    $deptMenu[$kevindept->parent] .= $deptName;
                }
                else
                {
                    $deptMenu[$kevindept->parent] = $deptName;
                }    
            }
        }

        $topMenu = @array_pop($deptMenu);
        $topMenu = explode("\n", trim($topMenu));
        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $deptID) = explode('|', $menu);
            $lastMenu[$deptID] = $label;
        }
        return $lastMenu;
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
     * Update kevindept.
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function update($deptID)
    {
        $kevindept   = fixer::input('post')->get();
        $self   = $this->getById($deptID);
        $parent = $this->getById($this->post->parent);
        $childs = $this->getAllChildId($deptID);
        $kevindept->grade = $parent ? $parent->grade + 1 : 1;
        $kevindept->path  = $parent ? $parent->path . $deptID . ',' : ',' . $deptID . ',';
        $this->dao->update(TABLE_DEPT)->data($kevindept)->autoCheck()->check('name', 'notempty')->where('id')->eq($deptID)->exec();
        $this->dao->update(TABLE_DEPT)->set('grade = grade + 1')->where('id')->in($childs)->andWhere('id')->ne($deptID)->exec();
        $this->dao->update(TABLE_DEPT)->set('manager')->eq($this->post->manager)->where('id')->in($childs)->andWhere('manager')->eq('')->exec();
        $this->dao->update(TABLE_DEPT)->set('manager')->eq($this->post->manager)->where('id')->in($childs)->andWhere('manager')->eq($self->manager)->exec();
        $this->fixDeptPath();
    }

    /**
     * Create the manage link.
     * 
     * @param  int    $kevindept 
     * @access public
     * @return string
     */
    public function createManageLink($kevindept)
    {
        $linkHtml  = $kevindept->name;
        if(commonModel::hasPriv('kevindept', 'edit')) $linkHtml .= ' ' . html::a(helper::createLink('kevindept', 'edit', "deptid={$kevindept->id}"), $this->lang->edit, '', 'data-toggle="modal" data-type="ajax"');
        if(commonModel::hasPriv('kevindept', 'browse')) $linkHtml .= ' ' . html::a(helper::createLink('kevindept', 'browse', "deptid={$kevindept->id}"), $this->lang->kevindept->manageChild);
        if(commonModel::hasPriv('kevindept', 'delete')) $linkHtml .= ' ' . html::a(helper::createLink('kevindept', 'delete', "deptid={$kevindept->id}"), $this->lang->delete, 'hiddenwin');
        if(commonModel::hasPriv('kevindept', 'updateOrder')) $linkHtml .= ' ' . html::input("orders[$kevindept->id]", $kevindept->order, 'style="width:30px;text-align:center"');
        return $linkHtml;
    }

    /**
     * Create the member link.
     * 
     * @param  int    $kevindept 
     * @access public
     * @return string
     */
    public function createMemberLink($kevindept)
    {
        $linkHtml = html::a(helper::createLink('kevindept', 'browse', "kevindept={$kevindept->id}"), $kevindept->name, '_self', "id='kevindept{$kevindept->id}'");
        return $linkHtml;
    }

    /**
     * Create the group manage members link.
     * 
     * @param  int    $kevindept 
     * @param  int    $groupID
     * @access public
     * @return string 
     */
    public function createGroupManageMemberLink($kevindept, $groupID)
    {
        return html::a(helper::createLink('group', 'managemember', "groupID=$groupID&deptID={$kevindept->id}"), $kevindept->name, '_self', "id='kevindept{$kevindept->id}'");
    }

    /**
     * Get sons of a department.
     * 
     * @param  int    $deptID 
     * @access public
     * @return array
     */
    public function getSons($deptID)
    {
        return $this->dao->select('*')->from(TABLE_DEPT)->where('parent')->eq($deptID)->orderBy('`order`')->fetchAll();
    }
    
    /**
     * Get all childs.
     * 
     * @param  int    $deptID 
     * @access public
     * @return array
     */
    public function getAllChildId($deptID)
    {
        if($deptID == 0) return array();
        $kevindept = $this->getById($deptID);
        $childs = $this->dao->select('id')->from(TABLE_DEPT)->where('path')->like($kevindept->path . '%')->fetchPairs();
        return array_keys($childs);
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
     * Update order.
     * 
     * @param  int    $orders 
     * @access public
     * @return void
     */
    public function updateOrder($orders)
    {
        foreach($orders as $deptID => $order) $this->dao->update(TABLE_DEPT)->set('`order`')->eq($order)->where('id')->eq($deptID)->exec();
    }

    /**
     * Manage childs.
     * 
     * @param  int    $parentDeptID 
     * @param  string $childs 
     * @access public
     * @return void
     */
    public function manageChild($parentDeptID, $childs)
    {
        $parentDept = $this->getByID($parentDeptID);
        if($parentDept)
        {
            $grade      = $parentDept->grade + 1;
            $parentPath = $parentDept->path;
        }
        else
        {
            $grade      = 1;
            $parentPath = ',';
        }

        $i = 1;
        foreach($childs as $deptID => $deptName)
        {
            if(empty($deptName)) continue;
            if(is_numeric($deptID))
            {
                $kevindept->name   = strip_tags($deptName);
                $kevindept->parent = $parentDeptID;
                $kevindept->grade  = $grade;
                $kevindept->order  = $this->post->maxOrder + $i * 10;
                $this->dao->insert(TABLE_DEPT)->data($kevindept)->exec();
                $deptID = $this->dao->lastInsertID();
                $childPath = $parentPath . "$deptID,";
                $this->dao->update(TABLE_DEPT)->set('path')->eq($childPath)->where('id')->eq($deptID)->exec();
                $i++;
            }
            else
            {
                $deptID = str_replace('id', '', $deptID);
                $this->dao->update(TABLE_DEPT)->set('name')->eq(strip_tags($deptName))->where('id')->eq($deptID)->exec();
            }
        }
    }

    /**
     * Get users of a deparment.
     * 
     * @param  int    $deptID 
     * @access public
     * @return array
     */
    public function getUsers($deptID, $pager = null, $orderBy = 'id')
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($deptID)->andWhere('dept')->in($deptID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get user pairs of a department.
     *
     * @param  int    $deptID
     * @access public
     * @return array
     */
    public function getDeptUserPairs($deptID = 0)
    {
        $childDepts = $this->getAllChildID($deptID);
        return $this->dao->select('account, realname')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($deptID)->andWhere('kevindept')->in($childDepts)->fi()
            ->orderBy('account')
            ->fetchPairs();
    }
    
    /**
     * Delete a department.
     * 
     * @param  int    $deptID 
     * @param  null   $null      compatible with that of model::delete()
     * @access public
     * @return void
     */
    public function delete($deptID, $null = null)
    {
        $this->dao->delete()->from(TABLE_DEPT)->where('id')->eq($deptID)->exec();
    }

    /**
     * Fix kevindept path.
     * 
     * @access public
     * @return void
     */
    public function fixDeptPath()
    {
        /* Get all depts grouped by parent. */
        $groupDepts = $this->dao->select('id, parent')->from(TABLE_DEPT)->fetchGroup('parent', 'id');
        $depts      = array();

        /* Cycle the groupDepts until it has no item any more. */
        while(count($groupDepts) > 0)
        {
            $oldCounts = count($groupDepts);    // Record the counts before processing.
            foreach($groupDepts as $parentDeptID => $childDepts)
            {
                /* If the parentDept doesn't exsit in the depts, skip it. If exists, compute it's child depts. */
                if(!isset($depts[$parentDeptID]) and $parentDeptID != 0) continue;
                if($parentDeptID == 0)
                {
                    $parentDept = new stdclass();
                    $parentDept->grade = 0;
                    $parentDept->path  = ',';
                }
                else
                {
                    $parentDept = $depts[$parentDeptID];
                }

                /* Compute it's child depts. */
                foreach($childDepts as $childDeptID => $childDept)
                {
                    $childDept->grade = $parentDept->grade + 1;
                    $childDept->path  = $parentDept->path . $childDept->id . ',';
                    $depts[$childDeptID] = $childDept;    // Save child kevindept to depts, thus the child of child can compute it's grade and path.
                }
                unset($groupDepts[$parentDeptID]);    // Remove it from the groupDepts.
            }
            if(count($groupDepts) == $oldCounts) break;   // If after processing, no kevindept processed, break the cycle.
        }

        /* Save depts to database. */
        foreach($depts as $kevindept)
        {
            $this->dao->update(TABLE_DEPT)->data($kevindept)->where('id')->eq($kevindept->id)->exec();
        }
    }

    /**
     * Get data structure
     * @param  integer $rootDeptID
     * @access public
     * @return object
     */
    public function getDataStructure($rootDeptID = 0) 
    {
        $tree = array_values($this->getSons($rootDeptID));
        $users = $this->loadModel('user')->getPairs('nodeleted|noletter|noclosed');
        if(count($tree))
        {
            foreach ($tree as $node)
            {
                $node->managerName = $users[$node->manager];
                $children = $this->getDataStructure($node->id);
                if(count($children))
                {
                    $node->children = $children;
                    $node->actions = array('delete' => false);
                }
            }
        }
        return $tree; 
    }
}
