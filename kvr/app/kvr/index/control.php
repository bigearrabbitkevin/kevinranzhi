<?php
/**
 * The control file of index module of Kevin.
 *
 * @copyright   Kevin
 * @license     Kevin
 * @author      Kevin
 * @package     index 
 * @version     $Id: control.php 
 * @link        Kevin
 */
class index extends control
{
    /**
     * The construct method.
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
    }

    public function index()
    {
        $this->locate($this->createLink('kevincom', 'index'));
    }
}
