<?php
/**
 * The header view of wf common module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL
 * @author      ranzhi.org
 * @package     krdef
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
$kk = $this->app->getBasePath() . 'app/sys/common/view/header.html.php';//S
include $this->app->getBasePath() . 'app/sys/common/view/header.html.php';
