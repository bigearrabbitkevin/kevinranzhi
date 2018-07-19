<?php

/**
 * The kevin class file for ranzhi only
 * copyright:Kevin<3301647@qq.com>
 * http://kevincom.sourceforge.net/
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The kevin class.
 * 
 * @package   framework
 */
class kevin {

    /**
     * Append order by.
     * 
     * @param  string $orderBy 
     * @param  string $append 
     * @access public
     * @return string
     */
    public static function appendOrder($orderBy, $append = 'id')
    {
        list($firstOrder) = explode(',', $orderBy);
        $sort = strpos($firstOrder, '_') === false ? '_asc' : strstr($firstOrder, '_');
        return strpos($orderBy, $append) === false ? $orderBy . ',' . $append . $sort : $orderBy;
    }

	/**
	 * echo message Die
	 *  @param array $Errors
	 *  like $Err[][], 2D
	 */
	public static function dieError($Errors) {
		if (is_array($Errors)) {
			foreach ($Errors as $msgArr) {
				if (is_array($msgArr)) {
					foreach ($msgArr as $msg) {
						echo $msg . "<br>";
					}
				} else echo $msg . "<br>";
			}
		} else echo $Errors;
		die();
	}

	/**
	 *  echo message ifdao error
	 */
	public static function dieIfDaoError() {
		if (!dao::isError()) return;
		kevin::dieError(dao::getError());
		die();
	}

	/**
	 * if has message,echo and Die 
	 *  @param array $Errors
	 *  like $Err[][], 2D
	 */
	public static function dieIfError(&$Errors) {
		if (empty($Errors)) return;
		kevin::dieError($Errors);
	}

	/**
	 * Format time 0915 to 09:15
	 *  20150810 to 2015-08-10
	 * @param  string $time 
	 * @access public
	 * @return string
	 */
	public static function formatTime($time) {
		switch (strlen($time)) {
			case 4:
				return substr($time, 0, 2) . ':' . substr($time, 2, 2);
			case 8:
				return substr($time, 0, 4) . '-' . substr($time, 4, 2) . '-' . substr($time, 6, 2);
			default:
				return $time;
		}
	}

	/**
	 * The getBeginEndTime.
	 * 
	 * @access public
	 * @param  类型 $type 
	 * @param  int $delta 
	 * @return array{begin,end}.
	 */
	public static function getBeginEndTime($period) {
		if ('' == $period) {
			$type = 'thisMonth';
		}
		//$this->app->loadClass('date');
		$period		 = strtolower($period);
		$inputLength = strlen($period);
		$isNumeric	 = is_numeric($period);
		//此处组合为年+月 共6位数字
		if ($isNumeric && 6 == $inputLength) {
			$period .= '0';
			$inputLength = 7;
		}
		//此处判断为最近时间,小于等于4位数字
		if ($isNumeric && $inputLength < 4) {
			$begin	 = date('Y-m-d', strtotime('-' . $period . ' day'));
			$end	 = date('Y-m-d', strtotime('+1 day'));
		} else if ($isNumeric && 4 == $inputLength) {
			$begin	 = $period . '-01-01 00:00:00';
			$end	 = $period . '-12-31 23:59:59';
			;
		}
		//此处判断的7为数字组成为年+月+季度
		else if ($isNumeric && 7 == $inputLength) {
			//日期截取
			$year	 = substr($period, 0, 4);
			$month	 = substr($period, 4, 2);
			if ('00' == $month) {
				$month = '';
			}
			$season = substr($period, 6, 1);
			if ('0' == $season) {
				$season = '';
			}
			$monthArray = kevin::getMonthBySeason($month, $season);
			if (1 == count($monthArray)) {
				$currentMonth	 = current($monthArray);
				$begin			 = $year . '-' . $currentMonth . '-01';
				return kevin::getMonthBeginEnd($begin);
			} else {
				$begin			 = $year . '-' . current($monthArray) . '-01 00:00:00';
				$last			 = $year . '-' . end($monthArray) . '-' . '01 00:00:00';
				$Array			 = kevin::getMonthBeginEnd($last);
				$Array['begin']	 = $begin;
				return $Array;
			}
		} else {
			switch ($period) {
				case 'today':
					$begin	 = kevin::formatTime(date::today());
					$end	 = $begin;
					break;
				case 'yesterday':
					$begin	 = kevin::formatTime(date::yesterday());
					$end	 = $begin;
					break;
				case 'thisweek':
					return(date::getThisWeek());
					break;
				case 'lastweek':
					return(date::getLastWeek());
					break;
				case 'nextmonth':
					return(kevin::getNextMonth());
					break;
				case 'thismonth':
					return(date::getThisMonth());
					break;
				case 'lastmonth':
					return(date::getLastMonth());
					break;

				case 'thisseason':
					return(date::getThisSeason());
					break;
				case 'thisyear':
					return(date::getThisYear());
					break;
				case 'lastyear':
					return(date::getLastYear());
					break;
				case 'future':
					$begin	 = kevin::formatTime(date::tomorrow());
					$end	 = '2109-01-01'; //end furture
					break;
				case 'all':
					$begin	 = '1970-01-01';
					$end	 = '2109-01-01'; //end furture
					break;
				case 'before':
					$begin	 = '1970-01-01';
					$end	 = kevin::formatTime(date::yesterday());
					break;
				default:
					$begin	 = kevin::formatTime($period);
					$end	 = $begin;
			}
			if (10 == strlen($end)) $end = $end . ' 23:59:59';
		}
		return array('begin' => $begin, 'end' => $end);
	}

	/**
	 * The get year month
	 * 
	 * @access public
	 * @param  string $period 
	 * @return array of year month.
	 */
	public static function getFirstYearMonth($period) {
		$arrayK	 = kevin::getBeginEndTime($period);
		$begin	 = $arrayK['begin'];
		$year	 = substr($begin, 0, 4);
		$month	 = substr($begin, 5, 2);
		return array('year' => $year, 'month' => $month);
	}

	/**
	 * The getMonth.
	 * 
	 * @access public
	 * @param  date $now 
	 * @param  int $delta 
	 * @return int of month[1 to 12].
	 */
	public static function getMonth($delta, $now = 'time()') {
		$delta = (int) $delta;
		if ($now == 'time()') {
			$now = time();
		}
		$m	 = (int) date('m', $now);
		$m	 = $m + $delta;
		$m	 = $m % 12;
		if ($m <= 0) {
			$m+=12;
		}
		return $m;
	}

	/**
	 * The strtotime.
	 * 
	 * @access public
	 * @param  string $time 
	 * @return new date.
	 */
	public static function getMonthBeginEnd($time) {
		$newTime	 = strtotime($time);
		$begin		 = date('Y-m-01 00:00:00', $newTime); //to first day
		$tempTime	 = strtotime($begin);
		$end		 = date('Y-m-d', strtotime("+1 month -1 day", $tempTime)) . ' 23:59:59'; //to last day
		return array('begin' => $begin, 'end' => $end);
	}

	/**
	 * The get month by Season.
	 * 
	 * @access public
	 * @param  int $month 
	 * @param  int $season 
	 * @return array of month.
	 */
	public static function getMonthBySeason($month = '', $season = '') {
		$monthArray = array();
		if ('' == $month && $season == '') {
			return $monthArray;
		}
		if ('' == $season) {
			$monthArray[] = $month;
			return $monthArray;
		}
		for ($i = 1; $i < 4; $i++) {
			$currentMonth	 = sprintf("%02d", 3 * ($season - 1) + $i);
			$monthArray[]	 = $currentMonth;
		}
		return $monthArray;
	}

	/**
	 * Get begin and end time of this month.
	 * 
	 * @access public
	 * @return array
	 */
	public static function getNextMonth() {
		$begin	 = date('Y-m', strtotime('next month')) . '-01 00:00:00';
		$year	 = substr($begin, 0, 4);
		$month	 = substr($begin, 5, 2);
		$days	 = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end	 = $year . '-' . $month . '-' . $days . ' 23:59:59';
		return array('begin' => $begin, 'end' => $end);
	}

	/**
	 * The get month list.
	 * 
	 * @access public
	 * @param  int $month 
	 * @param  int $season 
	 * @return array of month.
	 */
	public static function getMonthList($begin, $end, & $monthArray) {
		$monthArray	 = array();
		$month		 = date('Y-m', strtotime($begin)); //to last day
		$monthEnd	 = date('Y-m', strtotime($end)); //to last day
		if ($monthEnd < $month) return; //error

		$monthArray[$month] = $month;
		if ($monthEnd == $month) return; //get one
		while (true) {
			$month = date('Y-m', strtotime("$month +1 month")); //to next Month

			if ($month > $monthEnd) return;
			$monthArray[$month] = $month;
		}
	}

	/**
     * Print link icon.
     * 
     * @param  string $module 
     * @param  string $method 
     * @param  string $vars 
     * @param  object $object 
     * @param  string $type button|list 
     * @param  string $icon 
     * @param  string $target 
     * @param  string $extraClass 
     * @param  bool   $onlyBody 
     * @param  string $misc 
     * @static
     * @access public
     * @return void
     */
    public static function printIcon($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '')
    {
        echo kevin::buildIconButton($module, $method, $vars, $object, $type, $icon, $target, $extraClass, $onlyBody, $misc, $title);
    }

    /**
     * Build icon button.
     * 
     * @param  string $module 
     * @param  string $method 
     * @param  string $vars 
     * @param  object $object 
     * @param  string $type button|list 
     * @param  string $icon 
     * @param  string $target 
     * @param  string $extraClass 
     * @param  bool   $onlyBody 
     * @param  string $misc 
     * @static
     * @access public
     * @return void
     */
    public static function buildIconButton($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '')
    {
        if(isonlybody() and strpos($extraClass, 'showinonlybody') === false) return false;

        global $app, $lang;

        /* Judge the $method of $module clickable or not, default is clickable. */
        $clickable = true;
        if(is_object($object))
        {
            if($app->getModuleName() != $module) $app->control->loadModel($module);
            $modelClass = class_exists("ext{$module}Model") ? "ext{$module}Model" : $module . "Model";
            if(class_exists($modelClass) and is_callable(array($modelClass, 'isClickable')))
            {
                $clickable = call_user_func_array(array($modelClass, 'isClickable'), array('object' => $object, 'method' => $method));
            }
        }

        /* Set module and method, then create link to it. */
        if(strtolower($module) == 'story'    and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'tostory')    ($module = 'story') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        if(!commonModel::hasPriv($module, $method)) return false;
        $link = helper::createLink($module, $method, $vars, '', $onlyBody);

        /* Set the icon title, try search the $method defination in $module's lang or $common's lang. */
        if(empty($title))
        {
            $title = $method;
            if($method == 'create' and $icon == 'copy') $method = 'copy';
            if(isset($lang->$method) and is_string($lang->$method)) $title = $lang->$method;
            if((isset($lang->$module->$method) or $app->loadLang($module)) and isset($lang->$module->$method)) 
            {
                $title = $method == 'report' ? $lang->$module->$method->common : $lang->$module->$method;
            }
            if($icon == 'toStory')   $title  = $lang->bug->toStory;
            if($icon == 'createBug') $title  = $lang->testtask->createBug;
        }

        /* set the class. */
        if(!$icon)
        {
            $icon = $lang->icons[$method] ? $lang->icons[$method] : $method;
        }
        if(strpos(',edit,copy,report,export,delete,', ",$method,") !== false) $module = 'common';
        $class = "icon-$module-$method";
        if(!$clickable) $class .= ' disabled';
        if($icon) $class       .= ' icon-' . $icon;


        /* Create the icon link. */
        if($clickable)
        {
            if($app->getViewType() == 'mhtml')
            {
                return html::a($link, $title, $target, "class='$extraClass' data-role='button' data-mini='true' data-inline='true' data-theme='b'", true);
            }
            if($type == 'button')
            {
                if($method != 'edit' and $method != 'copy' and $method != 'delete')
                {
                    return html::a($link, "<i class='$class'></i> " . $title, $target, "class='btn $extraClass' $misc", true);
                }
                else
                {
                    return html::a($link, "<i class='$class'></i>", $target, "class='btn $extraClass' title='$title' $misc", false);
                }
            }
            else
            {
                return html::a($link, "<i class='$class'></i>", $target, "class='btn-icon $extraClass' title='$title' $misc", false);
            }
        }
        else
        {
            if($type == 'list')
            {
                return "<button type='button' class='disabled btn-icon $extraClass'><i class='$class' title='$title' $misc></i></button>";
            }
        }
    }
	
	/* show hours like 3.5 for 210 minutes */

	public static function showHours($iMinutes, $i = 1) {
		$workhour	 = ((float) $iMinutes / 60);
		$workhour	 = number_format($workhour, $i);
		return $workhour;
	}

	/* show hours like 3:30 for 210 minutes */

	public static function showWorkHours($iMinutes) {
		if ($iMinutes > 60) {
			if (0 == (int) ($iMinutes % 60)) return ((int) ($iMinutes / 60)) . ':00';
			return ((int) ($iMinutes / 60)) . ':' . ((int) ($iMinutes % 60));
		}
		else if ($iMinutes == 60) {
			return((int) $iMinutes / 60) . ':00';
		}
		return '0:' . ((int) $iMinutes % 60);
	}

	/**
	 * The strtotime.
	 * 
	 * @access public
	 * @param  string $time 
	 * @param  date $now 
	 * @return new date.
	 */
	public static function strtotimeMonth($time, $now = 'time()') {
		if ($now == 'time()') {
			$now = time();
		}
		$newTime		 = strtotime($time, $now);
		$firstday		 = date('Y-m-01', $now); //to first day
		$newFirstday	 = strtotime("$firstday " . $time);
		$newFirstday_s	 = date('Y-m-d', $newFirstday);
		$newLastday_s	 = date('Y-m-d', strtotime("$newFirstday_s +1 month -1 day")); //to last day
		$newLastday		 = strtotime($newLastday_s);

		if ($newTime > $newLastday) {
			return $newLastday;
		} elseif ($newTime < $newFirstday) {
			return $newFirstday;
		}
		return $newTime;
	}

}
