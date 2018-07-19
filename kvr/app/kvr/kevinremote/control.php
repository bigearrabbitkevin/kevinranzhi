<?php
/**
 * The view file
 *
 * @copyright   Kevin
 * @charge: free
 * @license: ZPL (http://zpl.pub/v1)
 * @author      Kevin <3301647@qq.com>
 * @package     kevinplan
 * @link       
 */
?>
<?php

class kevinremote extends control {

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
	 * Batch delete class.
	 *
	 * @access public
	 * @return void
	 */
	public function batchdelete($confirm = 'no') {
		if ($confirm == 'no') {
			$this->session->set('IDList', $this->post->IDList);
			die(js::confirm($this->lang->kevinremote->confirmDelete, inlink('batchdelete', "confirm=yes")));
		} else {
			$allChanges = $this->kevinremote->batchDelete($this->session->IDList);
			die(js::alert($this->lang->kevinremote->successBatchDelete) . js::locate($this->createLink('kevinremote', 'index'), 'parent'));
		}
	}

	/**
	 * Batch edit class.
	 *
	 * @access public
	 * @return void
	 */
	public function batchedit() {
		if ($this->post->type1) {
			$allChanges = $this->kevinremote->batchUpdate();
			if (!empty($allChanges)) {
				foreach ($allChanges as $remoteID => $changes) {
					if (empty($changes)) continue;
					$actionID = $this->loadModel('action')->create('kevinremote', $remoteID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			die(js::alert($this->lang->kevinremote->successBatchEdit) . js::locate($this->createLink('kevinremote', 'index'), 'parent'));
		}

		$IDList = $this->post->IDList ? $this->post->IDList : die(js::locate($this->createLink('kevinremote', 'index'), 'parent'));
		$remotes	 = $this->dao->select('*')->from(TABLE_KEVIN_REMOTE)->where('id')->in($IDList)->fetchAll('id');

		$this->view->showFields		 = $this->config->kevinuser->remoteBatchEditFields;
		$this->view->title			 = $this->lang->kevinremote->manage . $this->lang->colon . $this->lang->kevinremote->batchEdit;
		$this->view->position[]		 = $this->lang->kevinremote->batchEdit;
		$this->view->IDList	 = $IDList;
		$this->view->remotes		 = $remotes;

		$this->display();
	}

	/**
	 * Create class.
	 *
	 * @access public
	 * @return void
	 */
	public function create($id = '') {
		if (!empty($_POST)) {
			$remoteID = $this->kevinremote->create();
			if (dao::isError()) die(js::error(dao::getError()));
			$this->loadModel('action')->create('kevinremote', $remoteID, 'Created');
			$this->send(array('result' => 'success', 'message' => $this->lang->kevinremote->successCreate, 'locate' => $this->createLink('kevinremote', 'index')));
		}
		
		$this->view->remote			 = !empty($id)?$this->kevinremote->getRemote($id):null;
		$this->view->id				 = $id;

		$this->view->title			 = $this->lang->kevinremote->manage . $this->lang->colon . $this->lang->kevinremote->create;
		$this->view->position[]		 = $this->lang->kevinremote->manage;
		$this->view->func			 = "create";

		$this->display('kevinremote', 'edit');//用edit来显示create
	}

	/**
	 * Delete a class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function delete($id, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinremote->confirmDelete, inlink('delete', "id=$id&confirm=yes")));
		} else {
			$this->kevinremote->kevindelete($id);
			if (dao::isError()) die(js::error(dao::getError()));
			$this->send(array('result' => 'success', 'message' => $this->lang->kevinremote->successDelete, 'locate' => $this->createLink('kevinremote', 'index')));
		}
	}

	/**
	 * Update the class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function edit($id) {
		if (!empty($_POST)) {
			$allChanges = $this->kevinremote->update($id);
			if (!empty($allChanges)) {
				foreach ($allChanges as $remoteID => $changes) {
					if (empty($changes)) continue;

					$actionID = $this->loadModel('action')->create('kevinremote', $remoteID, 'Edited');
					$this->action->logHistory($actionID, $changes);
				}
			}
			if (dao::isError()) die(js::error(dao::getError()));
			die(js::alert($this->lang->kevinremote->successSave) . js::locate($this->createLink('kevinremote', 'index'), 'parent.parent'));
			$this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('kevinremote', 'index')));
		}

		$this->view->id				 = $id;

		$this->view->title			 = $this->lang->kevinremote->manage . $this->lang->colon . $this->lang->kevinremote->edit;
		$this->view->position[]		 = $this->lang->kevinremote->manage;
		$this->view->actions		 = $this->loadModel('action')->getList('kevinremote', $id);
		$this->view->remote			 = $this->kevinremote->getRemote($id);
		$this->view->func			 = "edit";
		$this->display();
	}

	/**
	 *  Index.
	 * 
	 * @access public
	 * @return void
	 */
	public function index($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);

		$this->view->title			 = $this->lang->kevinremote->manage . $this->lang->colon . $this->lang->kevinremote->remotelist;
		$this->view->position[]		 = $this->lang->kevinremote->manage;
		$this->view->remoteList		 = $this->kevinremote->getRemoteList($orderBy, $pager);;
		$this->view->orderBy		 = $orderBy;
		$this->view->pager			 = $pager;
		$this->view->recTotal		 = $recTotal;
		$this->view->recPerPage		 = $recPerPage;
		$this->view->pageID		 = $pageID;
		$this->display();
	}

	/**
	 *  Index.
	 * 
	 * @access public
	 * @return void
	 */
	public function wakeup() {
//		$IDList = $this->post->IDList ? $this->post->IDList : die(js::locate($this->createLink('kevinremote', 'index'), 'parent'));
		$ret = $this->kevinremote->wakeOnLan('B4-B5-2F-C3-84-69', '255.255.255.255');
//		die(js::alert($this->lang->kevinremote->successWakeup) . js::locate($this->createLink('kevinremote', 'index'), 'parent'));
	}
}
