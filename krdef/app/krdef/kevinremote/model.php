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

class kevinremoteModel extends model {

	/**
	 * Batch delete class. 
	 * 
	 * @access public
	 * @return void
	 */
	public function batchDelete($IDList) {
		$this->dao->update(TABLE_KEVIN_REMOTE)->set('deleted')->eq(1)->where('id')->in($IDList)->exec();
	}

	/**
	 * Batch update class. 
	 * 
	 * @access public
	 * @return array
	 */
	public function batchUpdate() {
		$remotes	 = array();
		$allChanges	 = array();
		$data		 = fixer::input('post')->get();
		$oldRemotes	 = $this->getRemoteByIdList($this->post->IDList);
		foreach ($data->IDList as $remoteID) {
			$data->payrate[$remoteID] /= 100;
			$remotes[$remoteID]		 = new stdClass();

			$remotes[$remoteID]->type1		 = $data->type1[$remoteID];
			$remotes[$remoteID]->type2			 = $data->type2[$remoteID];
			$remotes[$remoteID]->realname			 = $data->realname[$remoteID];
			$remotes[$remoteID]->macname	 = $data->macname[$remoteID];
			$remotes[$remoteID]->ip			 = $data->ip[$remoteID];
			$remotes[$remoteID]->mactype				 = $data->mactype[$remoteID];
			$remotes[$remoteID]->macaddress	 = $data->macaddress[$remoteID];
			$remotes[$remoteID]->order			 = $data->order[$remoteID];
		}

		foreach ($remotes as $remoteID => $remote) {
			$oldRemote = $oldRemotes[$remoteID];

			$this->dao->update(TABLE_KEVIN_REMOTE)->data($remote)
				->autoCheck()
				->where('id')->eq($remoteID)
				->limit(1)
				->exec();

			if (dao::isError()) die(js::error('kevinremote#' . $remoteID . dao::getError(true)));
			$allChanges[$remoteID] = commonModel::createChanges($oldRemote, $remote);
		}
		return $allChanges;
	}

	/**
	 * create class.
	 * 
	 * @access public
	 * @return int
	 */
	public function create() {
		$remote				 = fixer::input('post')->get()->remove('id');

		$this->dao->insert(TABLE_KEVIN_REMOTE)->data($remote)
			->autoCheck()
			->exec();
		$id = $this->dbh->lastInsertID();
		return $id;
	}

	/**
	 * Delete a class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return void
	 */
	public function kevindelete($id) {
		$this->dao->update(TABLE_KEVIN_REMOTE)
			->set('deleted')
			->eq(1)
			->where('id')
			->eq($id)
			->exec();
	}


	/**
	 * Update class.
	 * 
	 * @param  int    $id 
	 * @access public
	 * @return array
	 */
	public function update($id) {
		$allChanges			 = array();
		$oldRemote			 = $this->getRemote($id);
		$remote				 = fixer::input('post')->get();
		$this->dao->update(TABLE_KEVIN_REMOTE)
			->data($remote)
			->autoCheck()
			->where('id')
			->eq($id)
			->exec();
		$allChanges[$id]		 = commonModel::createChanges($oldRemote, $remote);
		return $allChanges;
	}


	/**
	 * Get class by id.
	 * 
	 * @param  int $id 
	 * @access public
	 * @return array
	 */
	public function getRemote($id) {
		$remote = $this->dao->select('*')
			->from(TABLE_KEVIN_REMOTE)
			->where('id')->eq($id)
			->fetch();
		return $remote;
	}

	/**
	 * Get by idList.
	 * 
	 * @param  array    $remoteIDList 
	 * @access public
	 * @return array
	 */
	public function getRemoteByIdList($remoteIDList) {
		return $this->dao->select('*')->from(TABLE_KEVIN_REMOTE)->where('id')->in($remoteIDList)->fetchAll('id');
	}

	/**
	 * Get classList.
	 * 
	 * @param  object $pager
	 * @param  array $filter 
	 * @access public
	 * @return array
	 */
	public function getRemoteList($orderBy, $pager) {
		$remotelist = $this->dao->select('*')
				->from(TABLE_KEVIN_REMOTE)
				->where('deleted')->eq(0)
				->orderBy($orderBy)
				->page($pager)
				->fetchAll();

		return $remotelist;
	}

	public function wakeOnLan($mac, $addr = false, $port = 7) {
		//Usage
		//    wakeOnLan($mac_str,'255.255.255.255');
		//    $addr:
		//    You will send and broadcast tho this addres.
		//    Normaly you need to use the 255.255.255.255 adres, so i made it as default. So you don't need
		//    to do anything with this.
		//    Since 255.255.255.255 have permission denied problems you can use addr=false to get all broadcast address from ifconfig command
		//    addr can be array with broadcast IP values
		//    $mac:
		//    You will WAKE-UP this WOL-enabled computer, you need to add the MAC-addres here.
		//    Mac can be array too    
		//
    //Return
		//    TRUE:    When socked was created succesvolly and the message has been send.
		//    FALSE:    Something went wrong
		//
    //Example 1
		//    When the message has been send you will see the message "Done...."
		//    if ( wake_on_lan('00:00:00:00:00:00'))
		//        echo 'Done...';
		//    else
		//        echo 'Error while sending';
		//
    if ($addr === false) {
			exec("ifconfig | grep Bcast | cut -d \":\" -f 3 | cut -d \" \" -f 1", $addr);
			$addr = array_flip(array_flip($addr));
		}
		if (is_array($addr)) {
			$last_ret	 = false;
			for ($i = 0; $i < count($ret); $i++)
				if ($ret[$i] !== false) $last_ret	 = wakeOnLan($mac, $ret[$i], $port);
			return($last_ret);
		}
		if (is_array($mac)) {
			$ret	 = array();
			foreach ($mac as $k => $v)
				$ret[$k] = wakeOnLan($v, $addr, $port);
			return($ret);
		}
		//Check if it's an real MAC-addres and split it into an array
		$mac		 = strtoupper($mac);
		if (!preg_match("/([A-F0-9]{1,2}[-:]){5}[A-F0-9]{1,2}/", $mac, $maccheck)) return (false); //MAC地址格式错误
		$addr_byte	 = preg_split("/[-:]/", $maccheck[0]);

		//Creating hardware adress
		$hw_addr = '';
		for ($a = 0; $a < 6; $a++)//Changing mac adres from HEXEDECIMAL to DECIMAL
			$hw_addr .= chr(hexdec($addr_byte[$a]));

		//Create package data
		$msg = str_repeat(chr(255), 6);
		for ($a = 1; $a <= 16; $a++)
			$msg .= $hw_addr;
		//Sending data
		if (function_exists('socket_create')) {
			//socket_create exists
			$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);	//Can create the socket
			if ($sock) {
				$sock_data = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1); //Set
				if ($sock_data) {
					$sock_data = socket_sendto($sock, $msg, strlen($msg), 0, $addr, $port); //Send data
					if ($sock_data) {
						socket_close($sock); //Close socket
						unset($sock);
						return(true);
					}
				}
			}
			@socket_close($sock);
			unset($sock);
		}
		$sock = fsockopen("udp://" . $addr, $port);
		if ($sock) {
			$ret = fwrite($sock, $msg);
			fclose($sock);
		}
		return ($ret);
	}

}
