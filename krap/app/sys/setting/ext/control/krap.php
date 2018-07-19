<?php
class setting extends control
{
    /**
     * Configuration of xuanxuan. 
     * 
     * @access public
     * @return void
     */
    public function krap()
    {
        if($this->app->user->admin != 'super') die(js::locate('back'));

        $this->app->loadLang('kractplan', 'krap');
        if($_POST)
        {
            if(strlen($this->post->key) != 32 or !validater::checkREG($this->post->key, '|^[A-Za-z0-9]+$|')) $this->send(array('result' => 'fail', 'message' => array('key' => $this->lang->kractplan->errorKey)));
			//保存到表格sys_config内，会覆盖手写的config/ext/krap.php/$config->krap->key
            if($this->post->key) $this->loadModel('setting')->setItem('system.sys.krap..key', $this->post->key);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $this->lang->menuGroups->setting = 'system';
        $this->lang->setting->menu       = $this->lang->system->menu;
        $this->lang->setting->menuOrder  = $this->lang->system->menuOrder;

        $this->view->title = $this->lang->kractplan->settings;
        $this->display();
    }
}
