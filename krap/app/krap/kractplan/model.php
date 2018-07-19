<?php
/**
 * The model file of kractplan module of RanZhi.
 *
 * @copyright   Kevin Yang <3301647@qq.com>
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Kevin Yang <3301647@qq.com>
 * @package     kractplan 
 * @version     
 * @link       
 */
class kractplanModel extends model
{
    /**
     * Get kractplan by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getByID($id)
    {
	   $kractplan = $this->dao->select('t1.*,t3.name as projectName')->from(TABLE_KR_ACTPLAN)->alias('t1')
				->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
                ->where('t1.id')->eq($id)->fetch();
        if(!$kractplan) return false;
        return $kractplan;
    }

    /**
     * Get kractplan list.
     * 
     * @param  string $status 
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($status = null, $orderBy = 'id_desc', $pager = null)
    {
         $projects = $this->dao->select('t1.*,t3.name as projectName')->from(TABLE_KR_ACTPLAN)->alias('t1')
				->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
                ->where('t1.deleted')->eq(0)
				->beginIF($status)->andWhere('status')->eq($status)->fi()
                ->orderBy($orderBy)
				->page($pager)
                ->fetchAll();

        return $projects;
    }

    /**
     * Get kractplan list.
     * 
     * @param  string $status 
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getGoods($id)
    {
		if(!$id)return null;
         $projects = $this->dao->select('t1.*,t2.name as GoodsName')->from('kr_act_plangoods')->alias('t1')
				->leftJoin(TABLE_KR_GOODS)->alias('t2')->on('t1.goods=t2.id')
				->where('t1.plan')->eq($id)
                ->fetchAll();
        return $projects;
    }

    /**
     * Get  kractplan pairs.
     * 
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairs($orderBy = 'id_desc')
    {
        return $this->dao->select('id,name')->from(TABLE_KR_ACTPLAN)->where('deleted')->eq(0)->orderBy($orderBy)->fetchPairs();
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
        $kractplan = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('member', ',')
            ->remove('uid,id,master')            //->remove('member,manager,master')
            ->stripTags('desc', $this->config->allowedTags)
            ->get();

        $kractplan = $this->loadModel('file')->processImgURL($kractplan, $this->config->kractplan->editor->create['id']);
        $this->dao->insert(TABLE_KR_ACTPLAN)
            ->data($kractplan, $skip = 'uid')
            ->autoCheck()
            ->batchCheck($this->config->kractplan->require->create, 'notempty')
            ->checkIF($kractplan->end, 'end', 'ge', $kractplan->begin)
            ->exec();

        if(dao::isError()) return false;
        $id = $this->dao->lastInsertId();

        return $id;
    }

    /**
     * Update kractplan 
     * 
     * @param  int    $id 
     * @param  mix    $kractplan
     * @access public
     * @return bool
     */
    public function update($id, $kractplan = null)
    {
        $oldProject = $this->getByID($id);
		if(!$oldProject) return false;
		$kractplan = fixer::input('post')
			->add('editedBy', $this->app->user->account)
			->add('editedDate', helper::now())
			->join('member', ',')
			->setDefault('member', '')
			->stripTags('desc', $this->config->allowedTags)
			->remove('master,id,whitelist')
			->get();

        $this->dao->update(TABLE_KR_ACTPLAN)
            ->data($kractplan, $skip = 'uid')
            ->autoCheck()
            ->batchCheck($this->config->kractplan->require->edit, 'notempty')
            ->checkIF($kractplan->end, 'end', 'ge', $kractplan->begin)
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;

        return commonModel::createChanges($oldProject, $kractplan);
    }

    /**
     * Active kractplan.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function activate($id)
    {
        $kractplan = new stdclass();
        $kractplan->status     = 'doing';
        $kractplan->editedBy   = $this->app->user->account;
        $kractplan->editedDate = helper::now();

        $this->dao->update(TABLE_KR_ACTPLAN)->data($kractplan)->where('id')->eq((int)$id)->exec();
        return !dao::isError();
    }

    /**
     * Suspend kractplan.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function suspend($id)
    {
        $kractplan = new stdclass();
        $kractplan->status     = 'suspend';
        $kractplan->editedBy   = $this->app->user->account;
        $kractplan->editedDate = helper::now();

        $this->dao->update(TABLE_KR_ACTPLAN)->data($kractplan)->where('id')->eq((int)$id)->exec();
        return !dao::isError();
    }

    /**
     * Finish kractplan.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function finish($id)
    {
        $kractplan = new stdclass();
        $kractplan->status     = 'finished';
        $kractplan->editedBy   = $this->app->user->account;
        $kractplan->editedDate = helper::now();

        $this->dao->update(TABLE_KR_ACTPLAN)->data($kractplan)->where('id')->eq((int)$id)->exec();
        return !dao::isError();
    }

    /**
     * Save the kractplan id user last visited to session.
     * 
     * @param  int   $id 
     * @param  array $projects 
     * @access public
     * @return int
     */
    public function saveState($id, $projects)
    {
        if($id > 0) $this->session->set('kractplan', (int)$id);
        if($id == 0 and $this->cookie->lastProject)    $this->session->set('kractplan', (int)$this->cookie->lastProject);
        if($id == 0 and $this->session->kractplan == '') $this->session->set('kractplan', $projects[0]);
        if(!in_array($this->session->kractplan, $projects)) $this->session->set('kractplan', $projects[0]);
        return $this->session->kractplan;
    }


    
    /**
     * check kractplan's Priv. 
     * 
     * @param  int    $kractplan 
     * @access public
     * @return bool
     */
    public function checkPriv($id)
    {
        if($this->app->user->admin == 'super') return true;
        return true;
    }

    /**
     * Check current user has action privilege or not. 
     * 
     * @param  int    $kractplan 
     * @access public
     * @return void
     */
    public function hasActionPriv($kractplan)
    {
        return (($this->app->user->admin == 'super') or (isset($kractplan->members[$this->app->user->account]) and $kractplan->members[$this->app->user->account]->role == 'senior') or ($this->app->user->account == $kractplan->createdBy) or ($this->app->user->account == $kractplan->manager));
    }
}
